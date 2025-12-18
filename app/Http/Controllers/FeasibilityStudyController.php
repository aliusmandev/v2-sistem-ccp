<?php

namespace App\Http\Controllers;

use App\Models\FeasibilityStudy;
use App\Models\FeasibilityStudyDetail;
use Illuminate\Http\Request;

class FeasibilityStudyController extends Controller
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

        return view('feasibility-study.create', compact('idPengajuan', 'idPengajuanItem'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idPengajuan = $request->input('idPengajuan');
        $idPengajuanItem = $request->input('idPengajuanItem');
        // dd($request->all());
        // Ambil data langsung dari input tanpa validasi
        $parseRupiah = function ($value) {
            return (int) preg_replace('/[^\d]/', '', $value ?? 0);
        };

        $header = FeasibilityStudy::updateOrCreate(
            [
                'IdPengajuan' => $idPengajuan,
                'PengajuanItemId' => $idPengajuanItem,
            ],
            [
                'NamaBarang' => $request->input('NamaBarang'),
                'NilaiInvestasi' => $parseRupiah($request->input('NilaiInvestasi')),
                'Spesifikasi' => $request->input('Spesifikasi'),
                'BungaTetap' => preg_replace('/[^\d]/', '', $request->input('BungaTetap')),
                'Penyusutan' => preg_replace('/[^\d]/', '', $request->input('Penyusutan')),
                'Maintenance' => preg_replace('/[^\d]/', '', $request->input('Maintenance')),
                'Pegawai' => preg_replace('/[^\d]/', '', $request->input('Pegawai')),
                'SewaGedung' => preg_replace('/[^\d]/', '', $request->input('SewaGedung')),
                'TotalBiayaTetap' => preg_replace('/[^\d]/', '', $request->input('TotalBiayaTetap')),
                'Konsumable' => preg_replace('/[^\d]/', '', $request->input('Konsumable')),
                'Dokter' => preg_replace('/[^\d]/', '', $request->input('Dokter')),
                'TotalBiayaVariable' => preg_replace('/[^\d]/', '', $request->input('TotalBiayaVariable')),
                'Tarif' => preg_replace('/[^\d]/', '', $request->input('Tarif')),
                'UserCreate' => auth()->user()->id ?? null,
            ]
        );

        $rugiLaba = $request->input('rugi_laba', []);
        $tahunKeArr = isset($rugiLaba['TahunKe']) ? $rugiLaba['TahunKe'] : [];

        for ($i = 1; $i <= 7; $i++) {
            $detailData = [
                'IdFs' => $header->id,
                'TahunKe' => $tahunKeArr[$i] ?? $i,
                'JumlahPasien' => preg_replace('/[^\d]/', '', $rugiLaba['JumlahPasien'][$i] ?? 0),
                'TarifUmum' => preg_replace('/[^\d]/', '', $rugiLaba['TarifUmum'][$i] ?? 0),
                'TarifBpjs' => preg_replace('/[^\d]/', '', $rugiLaba['TarifBpjs'][$i] ?? 0),
                'Revenue' => preg_replace('/[^\d]/', '', $rugiLaba['Revenue'][$i] ?? 0),
                'BiayaTetap' => preg_replace('/[^\d]/', '', $rugiLaba['BiayaTetap'][$i] ?? 0),
                'BiayaVariable' => preg_replace('/[^\d]/', '', $rugiLaba['BiayaVariable'][$i] ?? 0),
                'NetProfit' => preg_replace('/[^\d]/', '', $rugiLaba['NetProfit'][$i] ?? 0),
                'Ebitda' => preg_replace('/[^\d]/', '', $rugiLaba['Ebitda'][$i] ?? 0),
                'AkumEbitda' => preg_replace('/[^\d]/', '', $rugiLaba['AkumEbitda'][$i] ?? 0),
                'RoiTahunKe' => preg_replace('/[^\d]/', '', $rugiLaba['RoiTahunKe'][$i] ?? 0),
                'UserCreate' => auth()->user()->id ?? null,
            ];
            FeasibilityStudyDetail::updateOrCreate(
                [
                    'IdFs' => $header->id,
                    'TahunKe' => $tahunKeArr[$i] ?? $i,
                ],
                $detailData
            );
        }

        return redirect()->back()->with('success', 'Feasibility Study berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show($idPengajuan, $idPengajuanItem)
    {
        // Fetch the FeasibilityStudy by idPengajuan and idPengajuanItem
        $data = FeasibilityStudy::with('getFsDetail')
            ->where('IdPengajuan', $idPengajuan)
            ->where('PengajuanItemId', $idPengajuanItem)
            ->firstOrFail();
        // dd($data);
        return view('feasibility-study.show', [
            'data' => $data,
            'idPengajuan' => $idPengajuan,
            'idPengajuanItem' => $idPengajuanItem
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeasibilityStudy $feasibilityStudy)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeasibilityStudy $feasibilityStudy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeasibilityStudy $feasibilityStudy)
    {
        //
    }
}
