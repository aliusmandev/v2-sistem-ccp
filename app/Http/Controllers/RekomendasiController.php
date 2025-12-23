<?php

namespace App\Http\Controllers;

use App\Models\DokumenApproval;
use App\Models\LembarDisposisi;
use App\Models\MasterBarang;
use App\Models\MasterJenisPengajuan;
use App\Models\MasterParameter;
use App\Models\MasterPerusahaan;
use App\Models\MasterVendor;
use App\Models\Negara;
use App\Models\PengajuanItem;
use App\Models\PengajuanPembelian;
use App\Models\PermintaanPembelian;
use App\Models\Rekomendasi;
use App\Models\RekomendasiDetail;
use App\Models\UsulanInvestasi;
use App\Models\UsulanInvestasiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PengajuanPembelian::with('getPerusahaan', 'getJenisPermintaan')
                ->when($request->jenis, function ($query) use ($request) {
                    $query->where('Jenis', $request->jenis);
                })
                ->when($request->perusahaan, function ($query) use ($request) {
                    $query->where('KodePerusahaan', $request->perusahaan);
                })
                ->when($request->Status, function ($query) use ($request) {
                    $query->where('Status', $request->status);
                }, function ($query) {
                    // Default: Tampilkan Diajukan atau Selesai jika tidak ada filter status
                    $query->where(function ($q) {
                        $q->where('Status', 'Diajukan')
                            ->orWhere('Status', 'Selesai');
                    });
                })
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('Jenis', function ($row) {
                    return optional($row->getJenisPermintaan)->Nama ?? '-';
                })
                ->editColumn('KodePerusahaan', function ($row) {
                    return $row->getPerusahaan->Nama ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $id = encrypt($row->id);
                    $buttonReview = '
                        <a href="' . route('rekomendasi.show', $id) . '" class="btn btn-sm btn-info" title="Detail">
                            <i class="fa fa-eye"></i> Review
                        </a>
                    ';

                    return $buttonReview;
                })
                ->addColumn('Status', function ($row) {
                    switch ($row->Status) {
                        case 'Draft':
                            return '<span class="badge" style="background-color:#6c757d;color:#fff;">
                                <i class="fa fa-pencil-alt"></i> Draft
                            </span>'; // abu
                        case 'Diajukan':
                            return '<span class="badge" style="background-color:#007bff;color:#fff;">
                                <i class="fa fa-paper-plane"></i> Diajukan
                            </span>'; // biru
                        case 'Dalam Review':
                            return '<span class="badge" style="background-color:#ffc107;color:#212529;">
                                <i class="fa fa-search"></i> Dalam Review
                            </span>'; // kuning
                        case 'Selesai':
                            return '<span class="badge" style="background-color:#198754;color:#fff;">
                                <i class="fa fa-check-circle"></i> Selesai
                            </span>'; // hijau tua
                        case 'Disetujui':
                            return '<span class="badge" style="background-color:#28a745;color:#fff;">
                                <i class="fa fa-thumbs-up"></i> Disetujui
                            </span>'; // hijau
                        case 'Ditolak':
                            return '<span class="badge" style="background-color:#dc3545;color:#fff;">
                                <i class="fa fa-times-circle"></i> Ditolak
                            </span>'; // merah
                        default:
                            return '<span class="badge" style="background-color:#f8f9fa;color:#212529;">
                                <i class="fa fa-question-circle"></i> ' . e($row->Status ?? '-') . '
                            </span>';
                    }
                })
                ->addColumn('DiajukanPada', function ($row) {
                    // format tanggal pengajuan menjadi format Indonesia: 01 Jan 2024 13:30
                    return $row->DiajukanPada
                        ? \Carbon\Carbon::parse($row->DiajukanPada)->translatedFormat('d M Y H:i')
                        : '-';
                })
                ->rawColumns(['action', 'Status', 'DiajukanPada'])
                ->make(true);
        }
        $jenis = MasterJenisPengajuan::get();
        $perusahaan = MasterPerusahaan::get();
        return view('rekomendasi-pembelian.index', compact('jenis', 'perusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idPengajuan, $idPengajuanItem)
    {
        $idPengajuan = decrypt($idPengajuan);
        $idPengajuanItem = decrypt($idPengajuanItem);
        $data = PengajuanPembelian::with([
            'getVendor.getVendorDetail',
            'getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getVendor.getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getVendor.getRekomendasi' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk');
            }
        ])->find($idPengajuan);
        // dd($data);
        $negara = Negara::get();
        $parameter = MasterParameter::get();
        return view('rekomendasi-pembelian.create', compact('data', 'parameter', 'negara'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $header = Rekomendasi::updateOrCreate(
            [
                'IdPengajuan' => $request->rekomendasi[0]['IdPengajuan'],
                'PengajuanItemId' => $request->rekomendasi[0]['PengajuanItemId'],
            ],
            [
                'IdPengajuan' => $request->rekomendasi[0]['IdPengajuan'],
                'PengajuanItemId' => $request->rekomendasi[0]['PengajuanItemId'],
                'UserNego' => auth()->user()->id,
            ]
        );

        if (isset($request->rekomendasi) && is_array($request->rekomendasi)) {
            foreach ($request->rekomendasi as $key => $value) {
                $isi = RekomendasiDetail::updateOrCreate(
                    [
                        'IdPengajuan' => $value['IdPengajuan'],
                        'PengajuanItemId' => $value['PengajuanItemId'],
                        'IdRekomendasi' => $header->id,
                        'IdVendor' => $value['IdVendor'] ?? null,
                    ],
                    [
                        'NamaPermintaan' => $value['NamaPermintaan'] ?? null,
                        'HargaAwal' => isset($value['HargaAwal']) ? preg_replace('/\D/', '', $value['HargaAwal']) : null,
                        'HargaNego' => isset($value['HargaNego']) ? preg_replace('/\D/', '', $value['HargaNego']) : null,
                        'Spesifikasi' => $value['Spesifikasi'] ?? null,
                        'NegaraProduksi' => $value['NegaraProduksi'] ?? null,
                        'Garansi' => $value['Garansi'] ?? null,
                        'Teknisi' => $value['Teknisi'] ?? null,
                        'Bmhp' => $value['Bmhp'] ?? null,
                        'SparePart' => $value['SparePart'] ?? null,
                        'BackupUnit' => $value['BackupUnit'] ?? null,
                        'Top' => $value['Top'] ?? null,
                        'Populasi' => $value['Populasi'] ?? null,
                        'UserNego' => auth()->user()->id,
                        // 'Rekomendasi' dihilangkan, karena tidak ada di input sesuai contoh yang diberikan
                        'Keterangan' => $value['Keterangan'] ?? null,
                        // 'Disetujui' dihilangkan, karena tidak ada di input sesuai contoh yang diberikan
                        'KodePerusahaan' => auth()->user()->kodeperusahaan ?? null,
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $data = PengajuanPembelian::with('getVendor.getVendorDetail', 'getJenisPermintaan', 'getPengajuanItem.getBarang', 'getPengajuanItem.getHtaGpa', 'getPengajuanItem.getRekomendasi', 'getPengajuanItem.getFui')->find($id);
        $vendor = MasterVendor::orderBy('Nama', 'asc')->get();
        $masterbarang = MasterBarang::get();
        return view('rekomendasi-pembelian.show', compact('data', 'vendor', 'masterbarang'));
    }
    public function rekap($idPengajuan, $idPengajuanItem)
    {
        $idPengajuan = decrypt($idPengajuan);
        $idPengajuanItem = decrypt($idPengajuanItem);
        $caripermintaan = PengajuanPembelian::find($idPengajuan);

        $rekomendasi = Rekomendasi::with('getRekomedasiDetail.getPerusahaan', 'getRekomedasiDetail.getBarang', 'getRekomedasiDetail.getNegara')->where('PengajuanItemId', $idPengajuanItem)->first();

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
        //fui
        $usulan = UsulanInvestasi::with('getFuiDetail', 'getBarang', 'getVendor', 'getAccDirektur', 'getAccKadiv', 'getDepartemen', 'getDepartemen2', 'getNamaForm')
            ->where('IdPengajuan', $idPengajuan)
            ->where('PengajuanItemId', $idPengajuanItem)
            ->first();
        $VendorAcc = UsulanInvestasiDetail::with('getVendorDipilih')->where('idUsulan', $usulan->id)->where('Vendor', $usulan->VendorDipilih)->first();
        $approval2 = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $usulan->JenisForm)
            ->where('DokumenId', $usulan->id)
            ->orderBy('Urutan', 'asc')
            ->get();

        $permintaan = PermintaanPembelian::with([
            'getDetail.getBarang.getMerk',
            'getDiajukanOleh',
            'getDetail.getBarang.getSatuan'
        ])->find($caripermintaan->IdPermintaan);
        // dd($permintaan);
        $approval3 = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $permintaan->JenisForm)
            ->where('DokumenId', $permintaan->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        $pdf = Pdf::loadView('rekomendasi-pembelian.rekap-pdf', [
            'rekomendasi' => $rekomendasi,
            'data' => $data,
            'usulan' => $usulan,
            'VendorAcc' => $VendorAcc,
            'approval2' => $approval2,
            'permintaan' => $permintaan,
            'approval3' => $approval3,
        ]);
        return $pdf->stream('rekap_pengajuan.pdf');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekomendasi $rekomendasi)
    {
        //
    }

    public function Cetak($idPengajuan, $idPengajuanItem)
    {
        $idPengajuan = decrypt($idPengajuan);
        $idPengajuanItem = decrypt($idPengajuanItem);

        $rekomendasi = Rekomendasi::with('getRekomedasiDetail.getPerusahaan', 'getRekomedasiDetail.getBarang', 'getRekomedasiDetail.getNegara')->where('PengajuanItemId', $idPengajuanItem)->first();
        $pdf = Pdf::loadView('rekomendasi-pembelian.cetak-review', [
            'rekomendasi' => $rekomendasi,
        ]);
        return $pdf->stream('cetak_rekomendasi_' . $idPengajuan . '_' . $idPengajuanItem . '.pdf');
    }

    public function detail($idPengajuan, $idPengajuanItem)
    {
        $idPengajuan = decrypt($idPengajuan);
        $idPengajuanItem = decrypt($idPengajuanItem);
        $data = PengajuanPembelian::with([
            'getVendor.getVendorDetail',
            'getVendor.getHtaGpa' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getVendor.getRekomendasi' => function ($query) use ($idPengajuanItem) {
                $query->where('PengajuanItemId', $idPengajuanItem);
            },
            'getJenisPermintaan.getForm',
            'getPengajuanItem' => function ($query) use ($idPengajuanItem) {
                $query->where('id', $idPengajuanItem)->with('getBarang.getMerk', 'getRekomendasi.getUserNego');
            }
        ])->find($idPengajuan);
        // dd($data);
        $negara = Negara::get();
        $parameter = MasterParameter::get();
        return view('rekomendasi-pembelian.acc-rekomendasi', compact('data', 'parameter', 'negara'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekomendasi $rekomendasi)
    {
        //
    }

    public function UpdateRekomendasi(Request $request)
    {
        $header = Rekomendasi::updateOrCreate(
            [
                'IdPengajuan' => $request->rekomendasi[0]['IdPengajuan'],
                'PengajuanItemId' => $request->rekomendasi[0]['PengajuanItemId'],
            ],
            [
                'IdPengajuan' => $request->rekomendasi[0]['IdPengajuan'],
                'PengajuanItemId' => $request->rekomendasi[0]['PengajuanItemId'],
                'VendorAcc' => $request->rekomendasi[0]['RekomendasiSelect'],
                'Presentasi' => $request->Presentasi ?? null,
                'TanggalPresentasi' => $request->TanggalPresentasi ?? null,
                'DisetujuiOleh' => auth()->user()->id,
                'DisetujuiPada' => now(),
                'KodePerusahaan' => auth()->user()->kodeperusahaan,
            ]
        );

        if (isset($request->rekomendasi) && is_array($request->rekomendasi)) {
            foreach ($request->rekomendasi as $key => $value) {
                $isi = RekomendasiDetail::updateOrCreate(
                    [
                        'IdPengajuan' => $value['IdPengajuan'],
                        'PengajuanItemId' => $value['PengajuanItemId'],
                        'IdRekomendasi' => $header->id,
                        'IdVendor' => $value['IdVendor'] ?? null,
                    ],
                    [
                        'NamaPermintaan' => $value['NamaPermintaan'] ?? null,
                        'HargaAwal' => $value['HargaAwal'] ?? null,
                        'HargaNego' => $value['HargaNego'] ?? null,
                        'Spesifikasi' => $value['Spesifikasi'] ?? null,
                        'NegaraProduksi' => $value['NegaraProduksi'] ?? null,
                        'Garansi' => $value['Garansi'] ?? null,
                        'Teknisi' => $value['Teknisi'] ?? null,
                        'Bmhp' => $value['Bmhp'] ?? null,
                        'SparePart' => $value['SparePart'] ?? null,
                        'BackupUnit' => $value['BackupUnit'] ?? null,
                        'Top' => $value['Top'] ?? null,
                        'DistujuiOleh' => auth()->user()->id,
                        'Rekomendasi' => $value['RekomendasiSelect'] ?? null,
                        'Keterangan' => $value['Keterangan'] ?? null,
                    ]
                );
                if (($value['RekomendasiSelect'] ?? null) == 1) {
                    $pengajuanItem = PengajuanItem::find($value['PengajuanItemId']);
                    if ($pengajuanItem) {
                        $pengajuanItem->VendorAcc = $request->rekomendasi[0]['RekomendasiSelect'];
                        $pengajuanItem->HargaNegoAcc = preg_replace('/\D/', '', $value['HargaNego']);
                        $pengajuanItem->save();
                    }
                }
            }
        }
        $pengajuan = PengajuanPembelian::find($request->rekomendasi[0]['IdPengajuan']);
        // dd($pengajuan);
        if ($pengajuan) {
            $pengajuan->Status = 'Selesai';
            $pengajuan->save();
        }
        // hanya isi jika rekomendasi 1

        return redirect()->back()->with('success', 'Anda sudah menentukan rekomendasi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekomendasi $rekomendasi)
    {
        //
    }
}
