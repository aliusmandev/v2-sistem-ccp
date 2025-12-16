<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\MasterParameter;
use App\Models\MasterVendor;
use App\Models\Negara;
use App\Models\PengajuanPembelian;
use App\Models\Rekomendasi;
use App\Models\RekomendasiDetail;
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
                ->where('Status', 'Diajukan')
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('Jenis', function ($row) {
                    return isset($row->Jenis) ? $row->Jenis : '-';
                })
                ->editColumn('KodePerusahaan', function ($row) {
                    return $row->getPerusahaan->Nama ?? '-';
                })

                ->addColumn('action', function ($row) {
                    $id = encrypt($row->id);
                    return '
                        <a href="' . route('rekomendasi.show', $id) . '" class="btn btn-sm btn-info" title="Detail">
                            <i class="fa fa-eye"></i> Review
                        </a>
                    ';
                })
                ->editColumn('Jenis', function ($row) {
                    return optional($row->getJenisPermintaan)->Nama ?? '-';
                })
                ->addColumn('Status', function ($row) {
                    switch ($row->Status) {
                        case 'Draft':
                            return '<span class="badge bg-secondary">Draft</span>';
                        case 'proses':
                            return '<span class="badge bg-warning text-dark">Dalam Proses</span>';
                        case 'ditolak':
                            return '<span class="badge bg-danger">Ditolak</span>';
                        case 'disetujui':
                            return '<span class="badge bg-success">Disetujui</span>';
                        case 'batal':
                            return '<span class="badge bg-dark">Dibatalkan</span>';
                        default:
                            return '<span class="badge bg-light text-dark">' . e($row->Status ?? '-') . '</span>';
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

        return view('rekomendasi-pembelian.index');
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
        $header = Rekomendasi::updateOrCreate(
            [
                'IdPengajuan' => $request->rekomendasi[0]['IdPengajuan'],
                'PengajuanItemId' => $request->rekomendasi[0]['PengajuanItemId'],
            ],
            [
                'IdPengajuan' => $request->rekomendasi[0]['IdPengajuan'],
                'PengajuanItemId' => $request->rekomendasi[0]['PengajuanItemId'],
                // 'VendorAcc' => $request->VendorAcc,
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

        $rekomendasi = Rekomendasi::with('getRekomedasiDetail.getPerusahaan', 'getRekomedasiDetail.getBarang')->where('PengajuanItemId', $idPengajuanItem)->first();
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
            }
        }
        $pengajuan = PengajuanPembelian::find($request->rekomendasi[0]['IdPengajuan']);
        // dd($pengajuan);
        if ($pengajuan) {
            $pengajuan->Status = 'Selesai';
            $pengajuan->save();
        }
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
