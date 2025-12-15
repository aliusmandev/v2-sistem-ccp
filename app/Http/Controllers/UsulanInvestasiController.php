<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\MasterDepartemen;
use App\Models\PengajuanItem;
use App\Models\PengajuanPembelian;
use App\Models\Rekomendasi;
use App\Models\User;
use App\Models\UsulanInvestasi;
use App\Models\UsulanInvestasiDetail;
use Illuminate\Http\Request;

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
        // dd($PengajuanItemId);
        $pengajuanItem = PengajuanItem::find($PengajuanItemId);
        if (!$pengajuanItem) {
            return redirect()->back()->withErrors(['Pengajuan item tidak ditemukan.']);
        }
        $IdBarang = $pengajuanItem->IdBarang;
        // dd($IdBarang);
        $data = PengajuanPembelian::with([
            'getVendor.getNamaVendor',
            'getVendor.getVendorDetail' => function ($query) use ($IdBarang) {
                $query->where('NamaBarang', $IdBarang);
            }
        ])->find($IdPengajuan);
        // dd($data);
        $CariPengajuanItem = PengajuanItem::with('getRekomendasi')->find($PengajuanItemId);
        // $vendorAcc = $CariPengajuanItem->getRekomendasi;
        $vendorAcc = Rekomendasi::with([
            'getRekomedasiDetail' => function ($query2) {
                $query2->where('Rekomendasi', 1);
            },
            'getRekomedasiDetail.getNamaVendor'
        ])
            ->where('PengajuanItemId', $PengajuanItemId)
            ->first();
        $Acc = $vendorAcc->getRekomedasiDetail[0]->IdVendor;
        $data2 = PengajuanPembelian::with([
            'getVendor' => function ($query2) use ($Acc) {
                $query2->where('NamaVendor', $Acc);
            },
            'getVendor.getVendorDetail' => function ($query) use ($IdBarang) {
                $query->where('NamaBarang', $IdBarang);
            }
        ])->find($IdPengajuan);
        // dd($data2);
        $departemen = MasterDepartemen::get();
        $user = User::get();
        $barang = MasterBarang::get();
        // $dataPengajuan = PengajuanPembelian::with('getVendor.getVendorDetail')->find($data->IdPengajuan);
        return view('form-usulan-investari.create', compact('barang', 'user', 'departemen', 'data', 'data2', 'PengajuanItemId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // try {
        $validatedData = $request->validate([
            'IdPengajuan' => 'required|integer',
            'PengajuanItemId' => 'required|integer',
            'Tanggal' => 'required|date',
            'Divisi' => 'required|integer',
            'NamaKadiv' => 'required|integer',
            'Kategori' => 'required|string',
            'Tanggal2' => 'required|date',
            'Divisi2' => 'required|integer',
            'NamaKadiv2' => 'required|integer',
            'Kategori2' => 'required|string',
            'Alasan' => 'required|string',
            'items' => 'required|array',
            'BiayaAkhir' => 'required|string',
            'VendorDipilih' => 'required|integer',
            'HargaDiskonPpn' => 'required|string',
            'Total' => 'required|string',
            'SudahRkap' => 'required|string|in:Y,N',
            'SisaBudget' => 'required|string',
            'SudahRkap2' => 'required|string|in:Y,N',
            'SisaBudget2' => 'required|string',
        ]);
        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     dd($e->validator->errors());
        // }
        // dd($request->all());
        $usulan = UsulanInvestasi::create([
            'IdPengajuan' => $request->IdPengajuan,
            'PengajuanItemId' => $request->PengajuanItemId,
            'IdVendor' => $request->VendorDipilih,
            'IdBarang' => $request->PengajuanItemId,
            'Tanggal' => $request->Tanggal,
            'NamaKadiv' => $request->NamaKadiv,
            'Divisi' => $request->Divisi,
            'Kategori' => $request->Kategori,
            'Tanggal2' => $request->Tanggal2,
            'NamaKadiv2' => $request->NamaKadiv2,
            'Divisi2' => $request->Divisi2,
            'Kategori2' => $request->Kategori2,
            'Alasan' => $request->Alasan,
            'BiayaAkhir' => preg_replace('/[^0-9]/', '', $request->BiayaAkhir),
            'VendorDipilih' => $request->VendorDipilih,
            'HargaDiskonPpn' => preg_replace('/[^0-9]/', '', $request->HargaDiskonPpn),
            'Total' => preg_replace('/[^0-9]/', '', $request->Total),
            'SudahRkap' => $request->SudahRkap,
            'SisaBudget' => preg_replace('/[^0-9]/', '', $request->SisaBudget),
            'SudahRkap2' => $request->SudahRkap2,
            'SisaBudget2' => preg_replace('/[^0-9]/', '', $request->SisaBudget2),
            'DiajukanOleh' => auth()->user()->id ?? null,
            'DiajukanPada' => now(),
        ]);

        if (is_array($request->items)) {
            foreach ($request->items as $item) {
                UsulanInvestasiDetail::create([
                    'IdUsulan' => $usulan->id,
                    'NamaBarang' => $item['NamaBarang'] ?? null,
                    'Vendor' => $item['Vendor'] ?? null,
                    'IdVendor' => $item['IdVendor'] ?? null,
                    'Jumlah' => $item['Jumlah'] ?? null,
                    'Harga' => isset($item['Harga']) ? preg_replace('/[^0-9]/', '', $item['Harga']) : null,
                    'Diskon' => isset($item['Diskon']) ? preg_replace('/[^0-9]/', '', $item['Diskon']) : null,
                    'Ppn' => $item['Ppn'] ?? null,
                    'Total' => preg_replace('/[^0-9]/', '', $request->Total),
                    'UserCreate' => auth()->user()->id ?? null,
                    'UserUpdate' => null,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Usulan Investasi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($IdPengajuan, $barang)
    {
        $usulan = UsulanInvestasi::with('getFuiDetail', 'getBarang', 'getVendor', 'getAccDirektur', 'getAccKadiv')
            ->where('IdPengajuan', $IdPengajuan)
            ->where('PengajuanItemId', $barang)
            ->first();

        return view('form-usulan-investari.show', compact('usulan'));
    }

    public function print($IdPengajuan, $barang)
    {
        $usulan = UsulanInvestasi::with('getFuiDetail', 'getBarang', 'getVendor', 'getAccDirektur', 'getAccKadiv', 'getDepartemen', 'getDepartemen2')
            ->where('IdPengajuan', $IdPengajuan)
            ->where('PengajuanItemId', $barang)
            ->first();
        $VendorAcc = UsulanInvestasiDetail::with('getVendorDipilih')->where('idUsulan', $usulan->id)->where('Vendor', $usulan->VendorDipilih)->first();
        // Load the dompdf wrapper with F4 (Folio) paper size
        $pdf = \PDF::loadView('form-usulan-investari.show-pdf', compact('usulan', 'VendorAcc'))
            ->setPaper([0, 0, 612, 936], 'portrait');
        return $pdf->stream('Usulan_Investasi_' . $IdPengajuan . '_' . $barang . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsulanInvestasi $UsulanInvestasi)
    {
        //
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
