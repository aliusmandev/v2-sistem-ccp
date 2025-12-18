<?php

namespace App\Http\Controllers;

use App\Models\LembarDisposisi;
use App\Models\LembarDisposisiApproval;
use App\Models\MasterBarang;
use App\Models\MasterDepartemen;
use App\Models\MasterJabatan;
use App\Models\PengajuanItem;
use App\Models\PengajuanPembelian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Mail\NotifikasiDisposisiMail;
use App\Models\MasterVendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        $user = User::where('kodeperusahaan', auth()->user()->kodeperusahaan)->get();
        $jabatan = MasterJabatan::get();
        $departemen = MasterDepartemen::get();
        // dd($data);
        return view('lembar-disposisi.create', compact('data', 'user', 'jabatan', 'departemen', 'idPengajuan', 'idPengajuanItem'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
                // Jika $idUser adalah string dengan koma, pecah menjadi array
                if (is_string($idUser)) {
                    $idUser = explode(',', $idUser);
                }
                if (!$idUser) {
                    continue;
                }
                // Tambahkan ApprovalToken (misal: uuid atau string random unik)
                $approvalToken = Str::uuid()->toString();
                $approval = LembarDisposisiApproval::create([
                    'IdLembarDisposisi' => $lembarDisposisi->id,
                    'IdUser' => $idUser[0],
                    'Nama' => $idUser[1] ?? null,
                    'Email' => $request->input('Email')[$i] ?? null,
                    'Jabatan' => $request->input('Jabatan')[$i] ?? null,
                    'Departemen' => $request->input('Departemen')[$i] ?? null,
                    'Justifikasi' => $request->input('Justifikasi')[$i] ?? null,
                    'Status' => 'N',
                    'UserCreate' => auth()->user()->id ?? null,
                    'ApprovalToken' => $approvalToken,
                ]);

                if (!empty($approval->Email)) {
                    Mail::to($approval->Email)
                        ->send(new NotifikasiDisposisiMail($lembarDisposisi, $approval, $MasterVendor, $MasterBarang));
                }
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
        // dd($data);
        return view('lembar-disposisi.show', compact('data'));
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

        // Ambil data penilaian/approval berdasarkan IdLembarDisposisi
        $penilaian = DB::table('lembar_disposisi_approvals') // sesuaikan nama tabel
            ->where('IdLembarDisposisi', $lembarDisposisi->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Grouping penilaian berdasarkan role/posisi
        $kadivYanmed = $penilaian->where('Jabatan', 'like', '%Kadiv%')->first();
        $kadivJangmed = $penilaian->where('Jabatan', 'like', '%Kepala Divisi%')->first();
        $direktur = $penilaian->where('Jabatan', 'like', '%Direktur%')->first();
        $ghProcurement = $penilaian->where('Jabatan', 'like', '%Group Head%')->first();
        $direkturRsabGroup = $penilaian->where('Jabatan', 'like', '%Direktur RSAB%')->first();
        $ceoRsabGroup = $penilaian->where('Jabatan', 'like', '%CEO%')->first();

        // Siapkan data untuk PDF
        $data = [
            'lembarDisposisi' => $lembarDisposisi,
            'namaBarang' => $lembarDisposisi->getBarang->Nama,
            'harga' => $lembarDisposisi->Harga,
            'rencanaVendor' => $lembarDisposisi->getVendor->Nama,
            'tujuanPenempatan' => $lembarDisposisi->TujuanPenempatan,
            'formPermintaan' => $lembarDisposisi->FormPermintaanUser,

            // Data approval/penilaian
            'kadivYanmed' => $kadivYanmed,
            'kadivJangmed' => $kadivJangmed,
            'direktur' => $direktur,
            'ghProcurement' => $ghProcurement,
            'direkturRsabGroup' => $direkturRsabGroup,
            'ceoRsabGroup' => $ceoRsabGroup,
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
        // dd($token);
        $penilai = LembarDisposisiApproval::where('ApprovalToken', $token)->firstOrFail();

        // if (!is_null($penilai->StatusAcc)) {
        //     return view('emails.setelah-approval', [
        //         'message' => 'Persetujuan sudah diproses sebelumnya.'
        //     ]);
        // }

        $penilai->update([
            'Status' => 'Y',
            'ApprovePada' => Carbon::now(),
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
