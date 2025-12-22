<?php

namespace App\Http\Controllers;

use App\Models\DokumenApproval;
use App\Models\MasterBarang;
use App\Models\MasterDepartemen;
use App\Models\MasterForm;
use App\Models\PengajuanItem;
use App\Models\PengajuanPembelian;
use App\Models\Rekomendasi;
use App\Models\User;
use App\Models\UsulanInvestasi;
use App\Models\UsulanInvestasiDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsulanInvestasiController extends Controller
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
    public function create($IdPengajuan, $PengajuanItemId)
    {
        $IdPengajuan = decrypt($IdPengajuan);
        $PengajuanItemId = decrypt($PengajuanItemId);
        $pengajuanItem = PengajuanItem::find($PengajuanItemId);
        if (!$pengajuanItem) {
            return redirect()->back()->withErrors(['Pengajuan item tidak ditemukan.']);
        }
        $IdBarang = $pengajuanItem->IdBarang;
        $data = PengajuanPembelian::with([
            'getVendor.getNamaVendor',
            'getVendor.getVendorDetail' => function ($query) use ($IdBarang) {
                $query->where('NamaBarang', $IdBarang);
            }
        ])->find($IdPengajuan);

        $CariPengajuanItem = PengajuanItem::with('getRekomendasi')->find($PengajuanItemId);
        $vendorAcc = Rekomendasi::with([
            'getRekomedasiDetail' => function ($query2) {
                $query2->where('Rekomendasi', 1);
            },
            'getRekomedasiDetail.getNamaVendor'
        ])
            ->where('PengajuanItemId', $PengajuanItemId)
            ->first();

        $namaUser = auth()->user()->name ?? 'User';
        if (is_null($vendorAcc) || is_null($vendorAcc->getRekomedasiDetail) || count($vendorAcc->getRekomedasiDetail) == 0) {
            return redirect()->back()->with('error', "Hai $namaUser, Maaf, CCP belum menentukan pilihan vendor.");
        }
        $Acc = $vendorAcc->getRekomedasiDetail[0]->IdVendor;

        $data2 = PengajuanPembelian::with([
            'getVendor' => function ($query2) use ($Acc) {
                $query2->where('NamaVendor', $Acc);
            },
            'getVendor.getVendorDetail' => function ($query) use ($IdBarang) {
                $query->where('NamaBarang', $IdBarang);
            }
        ])->find($IdPengajuan);
        $departemen = MasterDepartemen::get();
        $user = User::get();
        $barang = MasterBarang::get();

        $usulan = UsulanInvestasi::with('getFuiDetail', 'getBarang', 'getVendor', 'getAccDirektur', 'getAccKadiv')
            ->where('IdPengajuan', $IdPengajuan)
            ->where('PengajuanItemId', $PengajuanItemId)
            ->first();
        return view('form-usulan-investari.create', compact('barang', 'user', 'departemen', 'data', 'data2', 'PengajuanItemId', 'usulan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // try {
        $validatedData = $request->validate([
            'IdPengajuan' => 'required|integer',
            'PengajuanItemId' => 'required|integer',
            'Tanggal' => 'required|date',
            'Divisi' => 'nullable|integer',
            'NamaKadiv' => 'nullable|integer',
            'Kategori' => 'nullable|string',
            'Tanggal2' => 'nullable|date',
            'Divisi2' => 'nullable|integer',
            'NamaKadiv2' => 'nullable|integer',
            'Kategori2' => 'nullable|string',
            'Alasan' => 'nullable|string',
            'items' => 'required|array',
            'BiayaAkhir' => 'nullable|string',
            'VendorDipilih' => 'required|integer',
            'HargaDiskonPpn' => 'nullable|string',
            'Total' => 'nullable|string',
            'SudahRkap' => 'nullable|string|in:Y,N',
            'SisaBudget' => 'nullable|string',
            'SudahRkap2' => 'nullable|string|in:Y,N',
            'SisaBudget2' => 'nullable|string',
        ]);

        $totalNumeric = isset($request->Total) ? preg_replace('/[^0-9]/', '', $request->Total) : null;
        $jenisForm = null;
        if ($totalNumeric !== null && $totalNumeric !== '') {
            if ($totalNumeric < 50000000) {
                $jenisForm = '7';
            } elseif ($totalNumeric >= 50000000 && $totalNumeric <= 100000000) {
                $jenisForm = '11';
            } elseif ($totalNumeric > 100000000) {
                $jenisForm = '12';
            }
        }
        $usulan = UsulanInvestasi::updateOrCreate(
            [
                'IdPengajuan' => $request->IdPengajuan ?? null,
                'PengajuanItemId' => $request->PengajuanItemId ?? null,
            ],
            [
                'JenisForm' => $jenisForm,
                'IdVendor' => $request->VendorDipilih ?? null,
                'IdBarang' => $request->PengajuanItemId ?? null,
                'Tanggal' => $request->Tanggal ?? null,
                'NamaKadiv' => $request->NamaKadiv ?? null,
                'Divisi' => $request->Divisi ?? null,
                'Kategori' => $request->Kategori ?? null,
                'Tanggal2' => $request->Tanggal2 ?? null,
                'NamaKadiv2' => $request->NamaKadiv2 ?? null,
                'Divisi2' => $request->Divisi2 ?? null,
                'Kategori2' => $request->Kategori2 ?? null,
                'Alasan' => $request->Alasan ?? null,
                'BiayaAkhir' => isset($request->BiayaAkhir) ? preg_replace('/[^0-9]/', '', $request->BiayaAkhir) : null,
                'VendorDipilih' => $request->VendorDipilih ?? null,
                'HargaDiskonPpn' => isset($request->HargaDiskonPpn) ? preg_replace('/[^0-9]/', '', $request->HargaDiskonPpn) : null,
                'Total' => isset($request->Total) ? preg_replace('/[^0-9]/', '', $request->Total) : null,
                'SudahRkap' => $request->SudahRkap ?? null,
                'SisaBudget' => isset($request->SisaBudget) ? preg_replace('/[^0-9]/', '', $request->SisaBudget) : null,
                'SudahRkap2' => $request->SudahRkap2 ?? null,
                'SisaBudget2' => isset($request->SisaBudget2) ? preg_replace('/[^0-9]/', '', $request->SisaBudget2) : null,
                'DiajukanOleh' => auth()->user()->id ?? null,
                'KodePerusahaan' => auth()->user()->kodeperusahaan ?? null,
                'DiajukanPada' => now(),
            ]
        );

        if (!empty($request->items) && is_array($request->items)) {
            foreach ($request->items as $item) {
                UsulanInvestasiDetail::updateOrCreate(
                    [
                        'IdUsulan' => $usulan->id ?? null,
                        'NamaBarang' => $item['NamaBarang'] ?? null,
                        'Vendor' => $item['Vendor'] ?? null,
                    ],
                    [
                        'Jumlah' => $item['Jumlah'] ?? null,
                        'Harga' => isset($item['Harga']) ? preg_replace('/[^0-9]/', '', $item['Harga']) : null,
                        'Diskon' => isset($item['Diskon']) ? preg_replace('/[^0-9]/', '', $item['Diskon']) : null,
                        'Ppn' => $item['Ppn'] ?? null,
                        // If $request->Total is not set, this will be null (nullable)
                        'Total' => isset($request->Total) ? preg_replace('/[^0-9]/', '', $request->Total) : null,
                        'UserCreate' => auth()->user()->id ?? null,
                        'UserUpdate' => null,
                    ]
                );
            }
        }
        $Form = MasterForm::with([
            'getApproval' => function ($q) use ($usulan) {
                $q->where('KodePerusahaan', $usulan->KodePerusahaan);
            },
            'getApproval.getUser'
        ])
            ->where('id', $usulan->JenisForm)
            ->first();

        foreach ($Form->getApproval as $approvalSetting) {
            DokumenApproval::updateOrCreate(
                [
                    'JenisFormId' => $usulan->JenisForm,
                    'DokumenId' => $usulan->id,
                    'Urutan' => $approvalSetting->Urutan ?? null,
                ],
                [
                    'JenisUser' => $approvalSetting->JenisUser ?? 'Master',
                    'DepartemenId' => $approvalSetting->Departemen ?? null,
                    'PerusahaanId' => $approvalSetting->KodePerusahaan,
                    'JabatanId' => $approvalSetting->JabatanId ?? null,
                    'UserId' => $approvalSetting->UserId ?? null,
                    'Nama' => $approvalSetting->getUser->name ?? null,
                    'Status' => 'Pending',
                    'TanggalApprove' => null,
                    'ApprovalToken' => str_replace('-', '', Str::uuid()),
                    'Catatan' => null,
                    'Ttd' => null,
                    'UserCreate' => auth()->user()->name,
                ]
            );
        }
        return redirect()->back()->with('success', 'Usulan Investasi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($IdPengajuan, $barang)
    {
        // $IdPengajuan = decrypt($IdPengajuan);
        // $PengajuanItemId = decrypt($barang);

        $usulan = UsulanInvestasi::with('getFuiDetail', 'getBarang', 'getVendor', 'getAccDirektur', 'getAccKadiv')
            ->where('IdPengajuan', $IdPengajuan)
            ->where('PengajuanItemId', $barang)
            ->first();
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $usulan->JenisForm)
            ->where('DokumenId', $usulan->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        return view('form-usulan-investari.show', compact('usulan', 'approval'));
    }

    public function print($IdPengajuan, $barang)
    {
        $usulan = UsulanInvestasi::with('getFuiDetail', 'getBarang', 'getVendor', 'getAccDirektur', 'getAccKadiv', 'getDepartemen', 'getDepartemen2', 'getNamaForm')
            ->where('IdPengajuan', $IdPengajuan)
            ->where('PengajuanItemId', $barang)
            ->first();
        $VendorAcc = UsulanInvestasiDetail::with('getVendorDipilih')->where('idUsulan', $usulan->id)->where('Vendor', $usulan->VendorDipilih)->first();
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $usulan->JenisForm)
            ->where('DokumenId', $usulan->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        $pdf = \PDF::loadView('form-usulan-investari.show-pdf', compact('usulan', 'VendorAcc', 'approval'))
            ->setPaper([0, 0, 612, 936], 'portrait');
        return $pdf->stream('Usulan_Investasi_' . $IdPengajuan . '_' . $barang . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($IdPengajuan, $barang)
    {
        $IdPengajuan = decrypt($IdPengajuan);
        $PengajuanItemId = decrypt($barang);
        $usulan = UsulanInvestasi::with('getFuiDetail', 'getBarang', 'getVendor', 'getAccDirektur', 'getAccKadiv')
            ->where('IdPengajuan', $IdPengajuan)
            ->where('PengajuanItemId', $PengajuanItemId)
            ->first();

        $user = user::where('kodeperusahaan', auth()->user()->kodeperusahaan);
        $departemen = MasterDepartemen::get();
        return view('form-usulan-investari.edit', compact('IdPengajuan', 'user', 'departemen', 'PengajuanItemId', 'usulan'));
    }

    public function approveKadiv(Request $request)
    {
        $usulan = UsulanInvestasi::find($request->id);
        if (!$usulan) {
            return redirect()->back()->with('error', 'Usulan Investasi tidak ditemukan.');
        }
        // dd($usulan);
        $usulan->KadivJangMed = auth()->user()->id;
        $usulan->KadivJangMedPada = now();
        $usulan->save();

        return redirect()->back()->with('success', 'Usulan Investasi telah disetujui oleh Kadiv.');
    }

    public function approveDirektur(Request $request)
    {
        $usulan = UsulanInvestasi::find($request->id);
        if (!$usulan) {
            return redirect()->back()->with('error', 'Usulan Investasi tidak ditemukan.');
        }
        $usulan->Direktur = auth()->user()->id;
        $usulan->DirekturPada = now();
        $usulan->save();

        return redirect()->back()->with('success', 'Usulan Investasi telah disetujui oleh Direktur.');
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
        $penilai = DokumenApproval::where('ApprovalToken', $token)->firstOrFail();
        if (!is_null($penilai->StatusAcc)) {
            return view('emails.setelah-approval', [
                'message' => 'Persetujuan sudah diproses sebelumnya.'
            ]);
        }
        $penilai->update([
            'Status' => 'Rejected',
            'TanggalApprove' => Carbon::now(),
        ]);

        return view('emails.setelah-approval', [
            'message' => 'Penilaian telah ditolak.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsulanInvestasi $UsulanInvestasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsulanInvestasi $UsulanInvestasi)
    {
        //
    }
}
