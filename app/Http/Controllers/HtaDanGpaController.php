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
            'getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getHtaGpa.getPenilai1',
            'getHtaGpa.getPenilai2',
            'getHtaGpa.getPenilai3',
            'getHtaGpa.getPenilai4',
            'getHtaGpa.getPenilai5',
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

    public function accPenilai1(Request $request, $id)
    {
        $htaDanGpa = HtaDanGpa::findOrFail($id);
        // dd($htaDanGpa);
        $htaDanGpa->Penilai1_Oleh = auth()->user()->id ?? 'N/A';
        $htaDanGpa->Penilai1_Status = 'Y';
        $htaDanGpa->Penilai1_Pada = now();

        $htaDanGpa->save();

        return redirect()->back()->with('success', 'Penilaian Tahap 1 telah berhasil disetujui.');
    }

    public function accPenilai2(Request $request, $id)
    {
        $htaDanGpa = HtaDanGpa::findOrFail($id);
        // if (empty($htaDanGpa->Penilai1_Status) || $htaDanGpa->Penilai1_Status != 'Y') {
        //     return redirect()->back()->with('error', 'Penilaian Tahap 1 harus disetujui sebelum dapat melanjutkan ke Tahap 2.');
        // }

        $htaDanGpa->Penilai2_Oleh = auth()->user()->id ?? 'N/A';
        $htaDanGpa->Penilai2_Status = 'Y';
        $htaDanGpa->Penilai2_Pada = now();

        $htaDanGpa->save();

        return redirect()->back()->with('success', 'Penilaian Tahap 2 telah berhasil disetujui.');
    }

    public function accPenilai3(Request $request, $id)
    {
        $htaDanGpa = HtaDanGpa::findOrFail($id);

        // Cek apakah Penilai2 sudah diisi
        // if (empty($htaDanGpa->Penilai2_Status) || $htaDanGpa->Penilai2_Status != 'Y') {
        //     return redirect()->back()->with('error', 'Penilaian Tahap 2 harus disetujui sebelum dapat melanjutkan ke Tahap 3.');
        // }

        $htaDanGpa->Penilai3_Oleh = auth()->user()->id ?? 'N/A';
        $htaDanGpa->Penilai3_Status = 'Y';
        $htaDanGpa->Penilai3_Pada = now();

        $htaDanGpa->save();

        return redirect()->back()->with('success', 'Penilaian Tahap 3 telah berhasil disetujui.');
    }

    public function accPenilai4(Request $request, $id)
    {
        $htaDanGpa = HtaDanGpa::findOrFail($id);

        // Cek apakah Penilai3 sudah diisi
        // if (empty($htaDanGpa->Penilai3_Status) || $htaDanGpa->Penilai3_Status != 'Y') {
        //     return redirect()->back()->with('error', 'Penilaian Tahap 3 harus disetujui sebelum dapat melanjutkan ke Tahap 4.');
        // }

        $htaDanGpa->Penilai4_Oleh = auth()->user()->id ?? 'N/A';
        $htaDanGpa->Penilai4_Status = 'Y';
        $htaDanGpa->Penilai4_Pada = now();

        $htaDanGpa->save();

        return redirect()->back()->with('success', 'Penilaian Tahap 4 telah berhasil disetujui.');
    }

    public function accPenilai5(Request $request, $id)
    {
        $htaDanGpa = HtaDanGpa::findOrFail($id);

        // Cek apakah Penilai4 sudah diisi
        // if (empty($htaDanGpa->Penilai4_Status) || $htaDanGpa->Penilai4_Status != 'Y') {
        //     return redirect()->back()->with('error', 'Penilaian Tahap 4 harus disetujui sebelum dapat melanjutkan ke Tahap 5.');
        // }

        $htaDanGpa->Penilai5_Oleh = auth()->user()->id ?? 'N/A';
        $htaDanGpa->Penilai5_Status = 'Y';
        $htaDanGpa->Penilai5_Pada = now();

        $htaDanGpa->save();

        return redirect()->back()->with('success', 'Penilaian Tahap 5 telah berhasil disetujui.');
    }
}
