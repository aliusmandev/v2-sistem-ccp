<?php

namespace App\Http\Controllers;

use App\Models\MasterDepartemen;
use App\Models\PengajuanItem;
use App\Models\PengajuanPembelian;
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
    public function create($IdPengajuan, $barang)
    {
        $IdPengajuan = decrypt($IdPengajuan);
        $barang = decrypt($barang);
        $data = PengajuanItem::with('getRekomendasi.getRekomedasiDetail.getNamaVendor')->find($IdPengajuan);
        $VendorAcc = PengajuanItem::with('getRekomendasi')->find($IdPengajuan);
        // dd( $VendorAcc->VendorAcc);
        $data2 = PengajuanItem::with([
            'getRekomendasi.getRekomedasiDetail' => function ($query) use ($VendorAcc) {
                $query->where('IdVendor', $VendorAcc->getRekomendasi->VendorAcc);
            },
            'getRekomendasi.getRekomedasiDetail.getNamaVendor'
        ])->find($IdPengajuan);
        // dd($data2);
        $departemen = MasterDepartemen::get();
        $user = User::get();
        // $dataPengajuan = PengajuanPembelian::with('getVendor.getVendorDetail')->find($data->IdPengajuan);
        return view('form-usulan-investari.create', compact('user', 'departemen', 'data', 'data2'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'IdPengajuan' => 'required|integer',
            'PengjuanItemId' => 'required|integer',
            'Tanggal' => 'required|date',
            'Divisi' => 'required|integer',
            'NamaKadiv' => 'required|integer',
            'Kategori' => 'required|string',
            'Tanggal2' => 'required|date',
            'Divisi2' => 'required|integer',
            'NamaKadiv2' => 'required|integer',
            'Kategori2' => 'required|string',
            'Alasan' => 'required|string',
            'BiayaAkhir' => 'required|string',
            'VendorDipilih' => 'required|integer',
            'HargaDiskonPpn' => 'required|string',
            'Total' => 'required|string',
            'SudahRkap' => 'required|string|in:Y,N',
            'SisaBudget' => 'required|string',
            'SudahRkap2' => 'required|string|in:Y,N',
            'SisaBudget2' => 'required|string',
        ]);

        $usulan = UsulanInvestasi::create([
            'IdPengajuan' => $request->IdPengajuan,
            'PengajuanItemId' => $request->PengjuanItemId,
            'IdVendor' => $request->VendorDipilih,
            'IdBarang' => $request->PengjuanItemId,
            'Tanggal' => $request->Tanggal,
            'NamaKadiv' => $request->NamaKadiv,
            'Divisi' => $request->Divisi,
            'Kategori' => $request->Kategori,
            'Tanggal2' => $request->Tanggal2,
            'NamaKadiv2' => $request->NamaKadiv2,
            'Divisi2' => $request->Divisi2,
            'Kategori2' => $request->Kategori2,
            'Alasan' => $request->Alasan,
            'BiayaAkhir' => $request->BiayaAkhir,
            'VendorDipilih' => $request->VendorDipilih,
            'HargaDiskonPpn' => $request->HargaDiskonPpn,
            'Total' => $request->Total,
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
                    'NamaBarang' => $item['NamaPermintaan'] ?? null,
                    'IdVendor' => $item['IdVendor'] ?? null,
                    'Merek' => $item['Merek'] ?? null,
                    'HargaAwal' => $item['HargaAwal'] ?? null,
                    'HargaNego' => $item['HargaNego'] ?? null,
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
        $usulan = UsulanInvestasi::with('getFuiDetail')->where('IdPengajuan', $IdPengajuan)
            ->where('PengajuanItemId', $barang)
            ->firstOrFail();

        $pdf = \PDF::loadView('form-usulan-investari.show', ['data' => $usulan]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Usulan_Investasi_' . $IdPengajuan . '_' . $barang . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsulanInvestasi $UsulanInvestasi)
    {
        //
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
