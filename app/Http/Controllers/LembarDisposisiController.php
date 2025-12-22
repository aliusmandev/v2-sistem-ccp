<?php

namespace App\Http\Controllers;

use App\Mail\NotifikasiDisposisiMail;
use App\Models\DokumenApproval;
use App\Models\LembarDisposisi;
use App\Models\LembarDisposisiApproval;
use App\Models\MasterBarang;
use App\Models\MasterDepartemen;
use App\Models\MasterForm;
use App\Models\MasterJabatan;
use App\Models\MasterVendor;
use App\Models\PengajuanItem;
use App\Models\PengajuanPembelian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LembarDisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idPengajuan, $idPengajuanItem)
    {
        $idPengajuan = decrypt($idPengajuan);
        $idPengajuanItem = decrypt($idPengajuanItem);
        $getNamaBarang = PengajuanItem::with('getBarang')->where('id', $idPengajuanItem)->first();
        $data = PengajuanItem::with([
            'getRekomendasi.getRekomedasiDetail' => function ($query) {
                $query->where('Rekomendasi', 1)->with('getNamaVendor');
            },
            'getBarang',
            'getPengajuanPembelian.getPermintaan.getDetail' => function ($query) use ($getNamaBarang) {
                $query->where('NamaBarang', $getNamaBarang->IdBarang);
            },
            'getPengajuanPembelian.getPermintaan.getDiajukanOleh'
        ])->where('id', $idPengajuanItem)->first();
        // dd($data);
        $ttd = MasterForm::with([
            'getApproval' => function ($q) use ($data) {
                $q->where('KodePerusahaan', $data->KodePerusahaan);
            },
            'getApproval.getUser'
        ])
            ->where('id', 9)
            ->first();
        $user = User::get();
        $jabatan = MasterJabatan::get();
        $departemen = MasterDepartemen::get();
        // dd($data);
        return view('lembar-disposisi.create', compact('data', 'user', 'jabatan', 'departemen', 'idPengajuan', 'idPengajuanItem', 'ttd'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $idPengajuan = encrypt($request->IdPengajuan);
        // dd($idPengajuan);
        $idPengajuanItem = encrypt($request->PengajuanItemId);
        $validatedHeader = $request->validate([
            'NamaBarang' => 'required|string',
            'IdPengajuan' => 'required',
            'PengajuanItemId' => 'required',
            'Harga' => 'required|string',
            // 'Email' => 'required|string',
            'RencanaVendor' => 'required|string',
            'TujuanPenempatan' => 'required|string',
            'FormPermintaan' => 'required|string',
        ]);

        // Simpan Header (LembarDisposisi sebagai header)
        $lembarDisposisi = LembarDisposisi::updateOrCreate(
            [
                'IdPengajuan' => $validatedHeader['IdPengajuan'],
                'PengajuanItemId' => $validatedHeader['PengajuanItemId'],
                'JenisForm' => 9,
            ],
            [
                'NamaBarang' => $validatedHeader['NamaBarang'],
                'Harga' => preg_replace('/\D/', '', $validatedHeader['Harga']),
                'RencanaVendor' => $validatedHeader['RencanaVendor'],
                'TujuanPenempatan' => $validatedHeader['TujuanPenempatan'],
                'FormPermintaan' => $validatedHeader['FormPermintaan'],
            ]
        );

        // Hapus data approval lama terlebih dahulu jika ada
        if (LembarDisposisiApproval::where('IdLembarDisposisi', $lembarDisposisi->id)->exists()) {
            LembarDisposisiApproval::where('IdLembarDisposisi', $lembarDisposisi->id)->delete();
        }
        // Mendapatkan nama vendor untuk setiap vendor pada pengajuan item
        $MasterVendor = MasterVendor::find($lembarDisposisi->RencanaVendor);
        $MasterBarang = MasterBarang::find($lembarDisposisi->NamaBarang);

        if ($request->has('IdUser') && is_array($request->IdUser)) {
            foreach ($request->IdUser as $i => $idUser) {
                // Mendapatkan nama user berdasarkan IdUser
                $user = User::find($idUser);
                $namaUser = $user ? $user->name : null;
                $approvalToken = str_replace('-', '', Str::uuid()->toString());

                $approval = DokumenApproval::updateOrCreate(
                    [
                        'JenisFormId' => $lembarDisposisi->JenisForm,
                        'DokumenId' => $lembarDisposisi->id,
                        'Urutan' => $i + 1 ?? null,
                    ],
                    [
                        'JenisUser' => 'Master',
                        'PerusahaanId' => auth()->user()->kodeperusahaan,
                        'DepartemenId' => $request->Departemen[$i] ?? null,
                        'JabatanId' => $request->Jabatan[$i] ?? null,
                        'UserId' => $request->IdUser[$i] ?? null,
                        'Nama' => $namaUser ?? null,
                        'Status' => 'Pending',
                        'TanggalApprove' => null,
                        'ApprovalToken' => $approvalToken,
                        'Catatan' => null,
                        'Ttd' => null,
                        'UserCreate' => auth()->user()->name,
                    ]
                );

                Mail::to($request->Email[$i])
                    ->send(new NotifikasiDisposisiMail($lembarDisposisi, $approval, $MasterVendor, $MasterBarang));
            }
        }

        return redirect()->back()->with('success', 'Lembar Disposisi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($idPengajuan, $idPengajuanItem)
    {
        $data = LembarDisposisi::with('getDetail', 'getBarang')->where('IdPengajuan', $idPengajuan)->where('PengajuanItemId', $idPengajuanItem)->first();
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->JenisForm)
            ->where('DokumenId', $data->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        return view('lembar-disposisi.show', compact('data', 'approval'));
    }

    public function print($idPengajuan, $idPengajuanItem)
    {
        // Ambil data lembar disposisi dengan relasi
        $lembarDisposisi = LembarDisposisi::with(['getDetail', 'getBarang'])
            ->where('IdPengajuan', $idPengajuan)
            ->where('PengajuanItemId', $idPengajuanItem)
            ->first();

        if (!$lembarDisposisi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $lembarDisposisi->JenisForm)
            ->where('DokumenId', $lembarDisposisi->id)
            ->orderBy('Urutan', 'asc')
            ->get();

        // Siapkan data untuk PDF
        $data = [
            'lembarDisposisi' => $lembarDisposisi,
            'namaBarang' => $lembarDisposisi->getBarang->Nama,
            'harga' => $lembarDisposisi->Harga,
            'rencanaVendor' => $lembarDisposisi->getVendor->Nama,
            'tujuanPenempatan' => $lembarDisposisi->TujuanPenempatan,
            'formPermintaan' => $lembarDisposisi->FormPermintaanUser,
            'approval' => $approval,
        ];

        $pdf = \PDF::loadView('lembar-disposisi.cetak-pdf', compact('data'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('lembar-disposisi-' . $idPengajuan . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LembarDisposisi $lembarDisposisi)
    {
        //
    }

    public function approve($token)
    {
        $penilai = DokumenApproval::where('ApprovalToken', $token)->firstOrFail();
        // dd($penilai);

        $penilai->update([
            'Status' => 'Approved',
            'TanggalAppove' => Carbon::now(),
        ]);

        return view('emails.setelah-approval', [
            'message' => 'Terima kasih, persetujuan Anda berhasil dicatat.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LembarDisposisi $lembarDisposisi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LembarDisposisi $lembarDisposisi)
    {
        //
    }
}
