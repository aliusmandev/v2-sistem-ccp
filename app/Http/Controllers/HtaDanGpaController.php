<?php

namespace App\Http\Controllers;

use App\Models\HtaDanGpa;
use App\Models\HtaDanGpaDetail;
use App\Models\MasterParameter;
use App\Models\PengajuanPembelian;
use Illuminate\Http\Request;

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
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk');
            }
        ])->find($idPengajuan);
        // dd($data);
        $parameter = MasterParameter::get();
        return view('hta-gpa.index', compact('data', 'parameter'));
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
        // dd($request->vendor[0]['IdPengajuan']);
        $header = HtaDanGpa::updateOrCreate(
            [
                'JenisForm' => $request->JenisForm ?? null,
                'IdPengajuan' => $request->vendor[0]['IdPengajuan'],
                'PengajuanItemId' => $request->vendor[0]['PengajuanItemId'],
                'IdVendor' => $request->vendor[0]['IdVendor'],
                'IdBarang' => $request->vendor[0]['IdBarang'],
            ],
            [
                'JenisForm' => $request->JenisForm ?? null,
                'IdPengajuan' => $request->vendor[0]['IdPengajuan'],
                'PengajuanItemId' => $request->vendor[0]['PengajuanItemId'],
                'IdVendor' => $request->vendor[0]['IdVendor'],
                'IdBarang' => $request->vendor[0]['IdBarang'],
                'UserCreate' => auth()->user()->name,
                'DiajukanOleh' => auth()->user()->id,
                'DiajukanPada' => now(),
            ]
        );

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
                    'TarifDiusulkan' => $value['TarifDiusulkan'],
                    'TargetPemakaianBulanan' => $value['TargetPemakaianBulanan'],
                    'Keterangan' => $value['Keterangan'],
                ]
            );
        }
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($idPengajuan, $idPengajuanItem)
    {
        $data = PengajuanPembelian::with([
            'getVendor.getVendorDetail',
            'getVendor.getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk');
            }
        ])->find($idPengajuan);
        // dd($data);
        $parameter = MasterParameter::get();
        return view('hta-gpa.show', compact('data', 'parameter'));
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
}
