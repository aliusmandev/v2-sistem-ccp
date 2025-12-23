<?php

namespace App\Http\Controllers;

use App\Models\DokumenApproval;
use App\Models\FeasibilityStudy;
use App\Models\FeasibilityStudyDetail;
use App\Models\MasterBarang;
use App\Models\MasterForm;
use App\Models\PengajuanItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $data = PengajuanItem::with([
            'getFui',
            'getRekomendasi.getRekomedasiDetail' => function ($query) {
                $query->where('Rekomendasi', 1);
            },
            'getHtaGpa'
        ])->find($idPengajuanItem);
        // dd($data);
        $barang = MasterBarang::where('id', $data->IdBarang)->first();
        return view('feasibility-study.create', compact('data', 'idPengajuan', 'idPengajuanItem', 'barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $idPengajuan = $request->input('idPengajuan');
        $idPengajuanItem = $request->input('idPengajuanItem');
        $parseRupiah = function ($value) {
            return (int) preg_replace('/[^\d]/', '', $value ?? 0);
        };

        $header = FeasibilityStudy::updateOrCreate(
            [
                'IdPengajuan' => $idPengajuan,
                'PengajuanItemId' => $idPengajuanItem,
                'JenisForm' => 6,
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
                'Tarif' => preg_replace('/[^\d]/', '', $request->input('Tarif')) ?? 0,
                'UserCreate' => auth()->user()->id ?? null,
                'KodePerusahaan' => auth()->user()->kodeperusahaan ?? null,
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
                    'DepartemenId' => $approvalSetting->Departemen,
                    'PerusahaanId' => $approvalSetting->KodePerusahaan,
                    'JabatanId' => $approvalSetting->JabatanId ?? null,
                    'UserId' => $approvalSetting->UserId ?? null,
                    'Nama' => $approvalSetting->getUser->name ?? null,
                    'Status' => 'Pending',
                    'TanggalApprove' => null,
                    'ApprovalToken' => str_replace('-', '', Str::uuid()->toString()),
                    'Catatan' => null,
                    'Ttd' => null,
                    'UserCreate' => auth()->user()->name,
                ]
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
        $data = FeasibilityStudy::with('getFsDetail', 'getBarang')
            ->where('IdPengajuan', $idPengajuan)
            ->where('PengajuanItemId', $idPengajuanItem)
            ->firstOrFail();
        // dd($data);
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->JenisForm)
            ->where('DokumenId', $data->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        return view('feasibility-study.show', [
            'data' => $data,
            'approval' => $approval,
            'idPengajuan' => $idPengajuan,
            'idPengajuanItem' => $idPengajuanItem
        ]);
    }

    public function cetak($idPengajuan, $idPengajuanItem)
    {
        $data = FeasibilityStudy::with('getFsDetail', 'getBarang')
            ->where('IdPengajuan', $idPengajuan)
            ->where('PengajuanItemId', $idPengajuanItem)
            ->firstOrFail();

        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->JenisForm)
            ->where('DokumenId', $data->id)
            ->orderBy('Urutan', 'asc')
            ->get();

        // Render blade view to HTML
        $pdfView = view('feasibility-study.cetak', [
            'data' => $data,
            'approval' => $approval,
            'idPengajuan' => $idPengajuan,
            'idPengajuanItem' => $idPengajuanItem
        ])->render();

        // Generate PDF dari HTML view
        $pdf = \PDF::loadHTML($pdfView);
        return $pdf->stream('Feasibility-Study-' . $data->id . '.pdf');
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

        return redirect()->back()->with('success', 'Terima kasih, persetujuan Anda berhasil dicatat.');
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
