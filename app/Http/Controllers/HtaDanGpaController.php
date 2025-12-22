<?php

namespace App\Http\Controllers;

use App\Mail\NotifikasiPengajuanMail;
use App\Models\DokumenApproval;
use App\Models\HtaDanGpa;
use App\Models\HtaDanGpaDetail;
use App\Models\MasterDepartemen;
use App\Models\MasterForm;
use App\Models\MasterJabatan;
use App\Models\MasterParameter;
use App\Models\PengajuanPembelian;
use App\Models\PenilaiHtaGpa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HtaDanGpaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idPengajuan, $idPengajuanItem)
    {
        $data = PengajuanPembelian::with([
            'getVendor.getVendorDetail',
            'getVendor.getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem)->with('getPenilai');
            },
            'getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem)->with('getPenilai');
            },
            'getJenisPermintaan.getForm',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk');
            }
        ])->find($idPengajuan);
        // dd($data);
        $approval = null;
        if ($data->getHtaGpa) {
            $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
                ->where('JenisFormId', $data->getHtaGpa->JenisForm)
                ->where('DokumenId', $data->getHtaGpa->id)
                ->orderBy('Urutan', 'asc')
                ->get();
        }
        $parameter = MasterParameter::get();
        $user = User::get();
        $jabatan = MasterJabatan::get();
        $departemen = MasterDepartemen::get();
        return view('hta-gpa.index', compact('data', 'parameter', 'user', 'approval', 'jabatan', 'departemen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function ajukan(Request $request)
    {
        $htaDanGpa = HtaDanGpa::where('IdPengajuan', $request->IdPengajuan)
            ->where('PengajuanItemId', $request->PengajuanItemId)
            ->where('IdBarang', $request->IdBarang)
            ->first();
        if ($htaDanGpa) {
            $htaDanGpa->Status = 'Final';
            $htaDanGpa->save();
        }

        return redirect()->back()->with('success', 'Hai ' . auth()->user()->name . ', HTA Berhasil Diajukan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $header = HtaDanGpa::updateOrCreate(
            [
                'JenisForm' => '1',
                'IdPengajuan' => $request->vendor[0]['IdPengajuan'],
                'PengajuanItemId' => $request->vendor[0]['PengajuanItemId'],
                'IdVendor' => $request->vendor[0]['IdVendor'],
                'IdBarang' => $request->vendor[0]['IdBarang'],
            ],
            [
                'JenisForm' => '1',
                'IdPengajuan' => $request->vendor[0]['IdPengajuan'],
                'PengajuanItemId' => $request->vendor[0]['PengajuanItemId'],
                'IdVendor' => $request->vendor[0]['IdVendor'],
                'IdBarang' => $request->vendor[0]['IdBarang'],
                'UserCreate' => auth()->user()->name,
                'KodePerusahaan' => auth()->user()->kodeperusahaan,
                'DiajukanOleh' => auth()->user()->id,
                'DiajukanPada' => now(),
            ]
        );
        $Form = MasterForm::with([
            'getApproval' => function ($q) use ($header) {
                $q->where('KodePerusahaan', $header->KodePerusahaan);
            },
            'getApproval.getUser'
        ])
            ->where('id', $header->JenisForm)
            ->first();
        // dd($Form);
        foreach ($Form->getApproval as $approvalSetting) {
            DokumenApproval::updateOrCreate(
                [
                    'JenisFormId' => $header->JenisForm,
                    'DokumenId' => $header->id,
                    'Urutan' => $approvalSetting->Urutan ?? null,
                ],
                [
                    'JenisUser' => $approvalSetting->JenisUser ?? 'Master',
                    'DepartemenId' => $approvalSetting->DepartemenId ?? null,
                    'PerusahaanId' => $approvalSetting->KodePerusahaan,
                    'JabatanId' => $approvalSetting->JabatanId ?? null,
                    'UserId' => $approvalSetting->UserId ?? null,
                    'Nama' => $approvalSetting->getUser->name ?? null,
                    'Status' => 'Pending',
                    'TanggalApprove' => null,
                    'Catatan' => null,
                    'Ttd' => null,
                    'UserCreate' => auth()->user()->name,
                ]
            );
        }
        foreach ($request->vendor as $key => $value) {
            $Isi = HtaDanGpaDetail::updateOrCreate(
                [
                    'IdPengajuan' => $value['IdPengajuan'],
                    'PengajuanItemId' => $value['PengajuanItemId'],
                    'IdVendor' => $value['IdVendor'] ?? null,
                    'IdBarang' => $value['IdBarang'] ?? null,
                    'IdHtaGpa' => $header->id,
                ],
                [
                    'IdParameter' => $value['IdParameter'] ?? null,
                    'Parameter' => $value['Parameter'] ?? null,
                    'Deskripsi' => $value['Deskripsi'] ?? null,
                    'Nilai1' => $value['Nilai1'] ?? null,
                    'Nilai2' => $value['Nilai2'] ?? null,
                    'Nilai3' => $value['Nilai3'] ?? null,
                    'Nilai4' => $value['Nilai4'] ?? null,
                    'Nilai5' => $value['Nilai5'] ?? null,
                    'SubTotal' => $value['SubTotal'] ?? null,
                    'UmurEkonomis' => $value['UmurEkonomis'],
                    'BuybackPeriod' => $value['BuybackPeriod'],
                    'TarifDiusulkan' => preg_replace('/\D/', '', $value['TarifDiusulkan']),
                    'TargetPemakaianBulanan' => $value['TargetPemakaianBulanan'],
                    'Keterangan' => $value['Keterangan'],
                ]
            );
        }

        activity('hta')
            ->causedBy(auth()->user())
            ->performedOn($header)
            ->withProperties([
                'attributes' => $header->toArray(),
                'vendor_items' => $request->vendor,
            ])
            ->log('Memperbarui data HTA dengan kode ' . ($header->Nomor ?? $header->id));
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function SimpanPenilai(Request $request)
    {
        // dd($request->all());
        $cariHTA = HtaDanGpa::where('IdPengajuan', $request->IdPengajuan)
            ->where('PengajuanItemId', $request->PengajuanItemId)
            ->first();
        // dd($cariHTA);
        if (!$cariHTA) {
            return redirect()->back()->with('error', 'Data HTA tidak ditemukan.');
        }

        $approvalDocs = DokumenApproval::where([
            'JenisFormId' => $cariHTA->JenisForm,
            'DokumenId' => $cariHTA->id,
        ])->orderBy('Urutan', 'asc')->get();
        // dd($approvalDocs);
        foreach ($approvalDocs as $key => $approval) {
            // Handle the NamaPenilai entry, which may contain "id,name"
            $namaPenilai = $request->NamaPenilai[$key] ?? null;
            $userId = null;
            $userName = null;

            if ($namaPenilai && strpos($namaPenilai, ',') !== false) {
                [$userId, $userName] = explode(',', $namaPenilai, 2);
            } else {
                $userId = $namaPenilai;
                $userName = null;
            }

            $approval->update([
                'JenisUser' => $request->TipeInputPenilai[$key],
                'JabatanId' => $request->JabatanId[$key],
                'DepartemenId' => $request->DepartemenId[$key],
                'UserId' => $userId,
                'Nama' => $request->NamaPenilaiManual[$key] ?? $userName,
                'Email' => $request->EmailPenilai[$key],
                'Urutan' => $approval->Urutan,
                'ApprovalToken' => str_replace('-', '', Str::uuid()),
                'UserUpdate' => auth()->user()->name,
            ]);
        }
        $idPengajuan = $request->IdPengajuan;
        $idPengajuanItem = $request->PengajuanItemId;

        $pengajuan = PengajuanPembelian::with([
            'getVendor.getVendorDetail',
            'getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk');
            }
        ])->find($idPengajuan);

        $parameter = MasterParameter::get();
        foreach ($approvalDocs as $penilai) {
            if (empty($penilai->Email)) {
                continue;
            }

            Mail::to($penilai->Email)
                ->send(
                    new NotifikasiPengajuanMail(
                        $pengajuan,
                        $cariHTA,
                        $parameter,
                        $penilai
                    )
                );
        }
        return redirect()->back()->with('success', 'Data berhasil disimpan & email notifikasi terkirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show($idPengajuan, $idPengajuanItem)
    {
        $data = PengajuanPembelian::with([
            'getVendor.getVendorDetail',
            'getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk');
            }
        ])->find($idPengajuan);
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->getHtaGpa->JenisForm)
            ->where('DokumenId', $data->getHtaGpa->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        $parameter = MasterParameter::get();
        return view('hta-gpa.show', compact('data', 'parameter', 'approval'));
    }

    public function print($idPengajuan, $idPengajuanItem)
    {
        $data = PengajuanPembelian::with([
            'getVendor.getVendorDetail',
            'getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getHtaGpa.getPenilai1',
            'getHtaGpa.getPenilai2',
            'getHtaGpa.getPenilai3',
            'getHtaGpa.getPenilai4',
            'getHtaGpa.getPenilai5',
            'getHtaGpa.getPenilai',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk');
            }
        ])->find($idPengajuan);
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->getHtaGpa->JenisForm)
            ->where('DokumenId', $data->getHtaGpa->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        $parameter = MasterParameter::get();

        $pdf = \PDF::loadView('hta-gpa.cetak-hta-gpa', compact('data', 'parameter', 'approval'))
            ->setPaper('a4', 'landscape');  // Ubah ke A4 landscape

        return $pdf->stream('hta-gpa-' . $idPengajuan . '-' . $idPengajuanItem . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HtaDanGpa $htaDanGpa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HtaDanGpa $htaDanGpa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HtaDanGpa $htaDanGpa)
    {
        //
    }

    public function approve($token)
    {
        // dd($token);
        $penilai = DokumenApproval::where('ApprovalToken', $token)->firstOrFail();
        if ($penilai->Status !== 'Pending') {
            return view('emails.setelah-approval', [
                'message' => 'Persetujuan sudah diproses sebelumnya.'
            ]);
        }

        $penilai->update([
            'Status' => 'Approved',
            'TanggalApprove' => Carbon::now(),
        ]);

        return view('emails.setelah-approval', [
            'message' => 'Terima kasih, persetujuan Anda berhasil dicatat.'
        ]);
    }

    public function reject($token)
    {
        $penilai = PenilaiHtaGpa::where('ApprovalToken', $token)->firstOrFail();
        if (!is_null($penilai->StatusAcc)) {
            return view('emails.setelah-approval', [
                'message' => 'Persetujuan sudah diproses sebelumnya.'
            ]);
        }
        $penilai->update([
            'StatusAcc' => 'N',
            'AccPada' => Carbon::now(),
        ]);

        return view('emails.setelah-approval', [
            'message' => 'Penilaian telah ditolak.'
        ]);
    }
}
