<?php

namespace App\Http\Controllers;

use App\Models\DokumenApproval;
use App\Models\HtaDanGpa;
use App\Models\HtaMedis;
use App\Models\ListVendor;
use App\Models\ListVendorDetail;
use App\Models\MasterBarang;
use App\Models\MasterDepartemen;
use App\Models\MasterJenisPengajuan;
use App\Models\MasterVendor;
use App\Models\PengajuanItem;
use App\Models\PengajuanPembelian;
use App\Models\PermintaanPembelian;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PengajuanPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->hasRole('SMI')) {
                $data = PengajuanPembelian::with('getPerusahaan', 'getJenisPermintaan')
                    ->where('KodePerusahaan', $request->perusahaan)
                    ->where('Jenis', 1)
                    ->orderBy('id', 'desc');
            } elseif (auth()->user()->hasRole('LOGUM')) {
                $data = PengajuanPembelian::with('getPerusahaan', 'getJenisPermintaan')
                    ->where('KodePerusahaan', $request->perusahaan)
                    ->where('Jenis', '!=', 1)
                    ->orderBy('id', 'desc');
            } else {
                $data = PengajuanPembelian::with('getPerusahaan', 'getJenisPermintaan')
                    ->orderBy('id', 'desc');
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('Jenis', function ($row) {
                    return isset($row->Jenis) ? $row->Jenis : '-';
                })
                ->editColumn('KodePerusahaan', function ($row) {
                    return $row->getPerusahaan->Nama ?? '-';
                })
                ->addColumn('KodePengajuan', function ($row) {
                    $id = encrypt($row->id);
                    $kode = isset($row->KodePengajuan) ? $row->KodePengajuan : '-';
                    return '<a href="' . route('ajukan.show', $id) . '" style="color: #007bff; font-weight: bold;">' . e($kode) . '</a>';
                })
                ->addColumn('action', function ($row) {
                    $id = $row->id;
                    return '


                        <button class="btn btn-md btn-danger btn-delete" data-id="' . $id . '" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>
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
                ->rawColumns(['action', 'KodePengajuan', 'Status'])
                ->make(true);
        }
        $permintaan = PermintaanPembelian::with('getPerusahaan', 'getDepartemen', 'getDiajukanOleh', 'getJenisPermintaan')
            ->where('Status', 'Telah Disetujui')
            ->whereDoesntHave('getPengajuanPembelian')
            ->latest()
            ->get();
        return view('form.pengajuan-pembelian.index', compact('permintaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $vendor = MasterVendor::where('Status', 'Y')->orderBy('Nama', 'asc')->get();
        $masterbarang = MasterBarang::get();
        $permintaan = PermintaanPembelian::with('getPerusahaan', 'getDepartemen', 'getDiajukanOleh', 'getDetail', 'getPengajuanPembelian.getVendor.getVendorDetail')->find($id);
        $JenisPengajuan = MasterJenisPengajuan::get();
        $departemen = MasterDepartemen::get();
        return view('form.pengajuan-pembelian.create', compact('JenisPengajuan', 'masterbarang', 'permintaan', 'vendor', 'departemen'));
    }

    public function SimpanDraft($id) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'pengajuan.tanggal' => 'required|date',
            'pengajuan.id_permintaan' => 'required',
            'pengajuan.jenis' => 'required|string|max:255',
            'pengajuan.tujuan' => 'required|string|max:255',
            'pengajuan.perkiraan_utilitasi_bulanan' => 'required',
            'pengajuan.perkiraan_bep_pada_tahun' => 'required',
            'pengajuan.rkap' => 'required',
            'pengajuan.nominal_rkap' => 'required',
        ]);

        $nomor = $this->generateNomorPengajuan();

        $pengajuan = PengajuanPembelian::updateOrCreate(
            [
                'IdPermintaan' => $request->pengajuan['id_permintaan'],
            ],
            [
                'KodePengajuan' => $nomor,
                'Tanggal' => $request->pengajuan['tanggal'],
                'Jenis' => $request->pengajuan['jenis'],
                'Tujuan' => $request->pengajuan['tujuan'],
                'PerkiraanUtilitasiBulanan' => $request->pengajuan['perkiraan_utilitasi_bulanan'],
                'PerkiraanBepPadaTahun' => $request->pengajuan['perkiraan_bep_pada_tahun'],
                'Rkap' => $request->pengajuan['rkap'],
                'NominalRkap' => preg_replace('/\D/', '', $request->pengajuan['nominal_rkap']),
                'DepartemenId' => $request->pengajuan['departemen'],
                'KodePerusahaan' => auth()->user()->kodeperusahaan,
                'UserCreate' => auth()->user()->name,
            ]
        );

        foreach ($request->vendors as $key => $vendorData) {
            if (!isset($vendorData['vendor_id']) || $vendorData['vendor_id'] === null) {
                continue;
            }

            $filename = null;
            $ListVendorOld = ListVendor::where('IdPengajuan', $pengajuan->id)
                ->where('VendorKe', $key + 1)
                ->first();

            if (isset($vendorData['penawaran_file']) && is_array($vendorData['penawaran_file'])) {
                $fileArr = $vendorData['penawaran_file'];
                if (isset($fileArr[0]) && $fileArr[0] instanceof \Illuminate\Http\UploadedFile) {
                    $file = $fileArr[0];
                    $filename = 'penawaran_' . time() . '_' . ($key + 1) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('penawaran_vendor', $filename, 'public');
                } elseif (isset($fileArr[0]) && is_string($fileArr[0]) && $fileArr[0]) {
                    $filename = $fileArr[0];
                }
            } elseif (isset($vendorData['penawaran_file']) && is_string($vendorData['penawaran_file']) && $vendorData['penawaran_file'] !== null && $vendorData['penawaran_file'] !== '') {
                $filename = $vendorData['penawaran_file'];
            } elseif ($request->hasFile('penawaran_file_' . ($key + 1))) {
                $file = $request->file('penawaran_file_' . ($key + 1));
                if ($file) {
                    $filename = 'penawaran_' . time() . '_' . ($key + 1) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('penawaran_vendor', $filename, 'public');
                }
            } elseif ($ListVendorOld && $ListVendorOld->SuratPenawaranVendor) {
                $filename = $ListVendorOld->SuratPenawaranVendor;
            }

            $ListVendor = ListVendor::updateOrCreate(
                [
                    'IdPengajuan' => $pengajuan->id,
                    'VendorKe' => $key + 1,
                ],
                [
                    'NamaVendor' => $vendorData['vendor_id'],
                    'SuratPenawaranVendor' => $filename,
                    'HargaTanpaDiskon' => preg_replace('/\D/', '', $vendorData['total_harga_sebelum_diskon']),
                    'HargaDenganDiskon' => preg_replace('/\D/', '', $vendorData['total_harga_setelah_diskon']),
                    'TotalDiskon' => preg_replace('/\D/', '', $vendorData['total_diskon']),
                    'Ppn' => $vendorData['ppn_persen'],
                    'TotalPpn' => preg_replace('/\D/', '', $vendorData['total_ppn']),
                    'TotalHarga' => preg_replace('/\D/', '', $vendorData['grand_total']),
                    'KodePerusahaan' => auth()->user()->kodeperusahaan,
                    // Masukkan UserCreate atau UserUpdate sesuai kebutuhan
                    'UserCreate' => auth()->user()->name,
                    'UserUpdate' => auth()->user()->name,
                ]
            );

            if (isset($vendorData['details']) && is_array($vendorData['details'])) {
                foreach ($vendorData['details'] as $detailB) {
                    ListVendorDetail::updateOrCreate(
                        [
                            'IdPengajuan' => $pengajuan->id,
                            'IdListVendor' => $ListVendor->id,
                            'NamaBarang' => $detailB['barang_id'] ?? null,
                        ],
                        [
                            'NamaVendor' => null,
                            'Jumlah' => $detailB['jumlah'],
                            'HargaSatuan' => preg_replace('/\D/', '', $detailB['harga_satuan']),
                            'Diskon' => preg_replace('/\D/', '', $detailB['diskon_item']),
                            'JenisDiskon' => $detailB['jenis_diskon_item'],
                            'TotalDiskon' => preg_replace('/\D/', '', $detailB['total_diskon']),
                            'TotalHarga' => preg_replace('/\D/', '', $detailB['total_harga']),
                            'KodePerusahaan' => auth()->user()->kodeperusahaan,
                            'UserCreate' => auth()->user()->name,
                        ]
                    );
                }
            }
        }
        foreach ($request->vendors[0]['details'] as $key => $listalat) {
            PengajuanItem::updateOrCreate(
                [
                    'IdPengajuan' => $pengajuan->id,
                    'IdBarang' => $listalat['barang_id'],
                ],
                [
                    'RencanaPenempatan' => $listalat['rencana_penempatan'] ?? null,
                    'DiajukanOleh' => $listalat['diajukan_oleh'] ?? null,
                    'DiajukanDepartemen' => $request->pengajuan['departemen'] ?? null,
                    'Jumlah' => $listalat['jumlah'] ?? null,
                    'Satuan' => $listalat['satuan'] ?? null,
                    'VendorAcc' => $listalat['vendor_acc'] ?? null,
                    'HargaSatuanAcc' => isset($listalat['harga_satuan_acc']) ? preg_replace('/\D/', '', $listalat['harga_satuan_acc']) : null,
                    'HargaNegoAcc' => isset($listalat['harga_nego_acc']) ? preg_replace('/\D/', '', $listalat['harga_nego_acc']) : null,
                    'HargaAkhirFui' => isset($listalat['harga_akhir_fui']) ? preg_replace('/\D/', '', $listalat['harga_akhir_fui']) : null,
                    'KodePerusahaan' => auth()->user()->kodeperusahaan,
                    'UserCreate' => auth()->user()->name,
                ]
            );
        }

        activity('pengajuan_pembelian')
            ->causedBy(auth()->user())
            ->performedOn($pengajuan)
            ->withProperties([
                'attributes' => $pengajuan->toArray(),
                'vendors' => $request->vendors,
            ])
            ->log('Membuat pengajuan pembelian baru dengan kode ' . $pengajuan->KodePengajuan);
        return redirect()->back()->with('success', 'Pengisian vendor berhasil, silahkan lanjutkan isi vendor selanjutnya jika diperlukan');
    }

    private function generateNomorPengajuan()
    {
        $prefix = 'PJ';
        $tahun = date('y');  // 2 digit tahun
        $bulan = date('m');  // 2 digit bulan

        $maxNomor = PengajuanPembelian::where('KodePengajuan', 'like', "{$prefix}{$tahun}{$bulan}%")
            ->orderByDesc('KodePengajuan')
            ->value('KodePengajuan');

        if ($maxNomor) {
            $lastNumber = (int) substr($maxNomor, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nomorAkhir = $prefix . $tahun . $bulan . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        return $nomorAkhir;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $data = PengajuanPembelian::with('getVendor.getVendorDetail', 'getJenisPermintaan', 'getPengajuanItem.getBarang', 'getPengajuanItem.getHtaGpa', 'getPengajuanItem.getRekomendasi', 'getPengajuanItem.getFui', 'getPengajuanItem.getDisposisi', 'getDepartemen', 'getPengajuanItem.getFs')->find($id);
        // dd($data);
        $vendor = MasterVendor::orderBy('Nama', 'asc')->get();
        $masterbarang = MasterBarang::get();
        return view('form.pengajuan-pembelian.show', compact('data', 'vendor', 'masterbarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $vendor = MasterVendor::where('Status', 'Y')->orderBy('Nama', 'asc')->get();
        $masterbarang = MasterBarang::get();
        $permintaan = PermintaanPembelian::with('getPerusahaan', 'getDepartemen', 'getDiajukanOleh', 'getDetail', 'getPengajuanPembelian.getVendor.getVendorDetail')->find($id);
        $JenisPengajuan = MasterJenisPengajuan::get();
        $departemen = MasterDepartemen::get();
        $data = PengajuanPembelian::with('getVendor.getVendorDetail', 'getJenisPermintaan', 'getPengajuanItem.getBarang', 'getPengajuanItem.getHtaGpa', 'getPengajuanItem.getRekomendasi', 'getPengajuanItem.getFui', 'getDepartemen')->find($id);
        // dd($id);
        return view('form.pengajuan-pembelian.edit', compact('JenisPengajuan', 'masterbarang', 'permintaan', 'vendor', 'departemen', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'pengajuan.tanggal' => 'required|date',
            'pengajuan.id_permintaan' => 'required',
            'pengajuan.jenis' => 'required|string|max:255',
            'pengajuan.tujuan' => 'required|string|max:255',
            'pengajuan.perkiraan_utilitasi_bulanan' => 'required',
            'pengajuan.perkiraan_bep_pada_tahun' => 'required',
            'pengajuan.rkap' => 'required',
            'pengajuan.nominal_rkap' => 'required',
        ]);

        $pengajuan = PengajuanPembelian::findOrFail($id);

        $pengajuan->Tanggal = $request->pengajuan['tanggal'];
        $pengajuan->Jenis = $request->pengajuan['jenis'];
        $pengajuan->Tujuan = $request->pengajuan['tujuan'];
        $pengajuan->PerkiraanUtilitasiBulanan = $request->pengajuan['perkiraan_utilitasi_bulanan'];
        $pengajuan->PerkiraanBepPadaTahun = $request->pengajuan['perkiraan_bep_pada_tahun'];
        $pengajuan->Rkap = $request->pengajuan['rkap'];
        $pengajuan->NominalRkap = preg_replace('/\D/', '', $request->pengajuan['nominal_rkap']);
        $pengajuan->DepartemenId = $request->pengajuan['departemen'];
        $pengajuan->KodePerusahaan = auth()->user()->kodeperusahaan;
        $pengajuan->UserUpdate = auth()->user()->name;
        $pengajuan->save();

        ListVendor::where('IdPengajuan', $pengajuan->id)->delete();
        ListVendorDetail::where('IdPengajuan', $pengajuan->id)->delete();

        foreach ($request->vendors as $key => $vendorData) {
            if (!isset($vendorData['vendor_id']) || $vendorData['vendor_id'] === null) {
                continue;
            }

            $filename = null;
            $ListVendorOld = ListVendor::where('IdPengajuan', $pengajuan->id)
                ->where('VendorKe', $key + 1)
                ->first();

            if (isset($vendorData['penawaran_file']) && $vendorData['penawaran_file'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $vendorData['penawaran_file'];
                $filename = 'penawaran_' . time() . '_' . ($key + 1) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('penawaran_vendor', $filename, 'public');
            } elseif (isset($vendorData['penawaran_file']) && is_array($vendorData['penawaran_file'])) {
                $fileArr = $vendorData['penawaran_file'];
                if (isset($fileArr[0]) && $fileArr[0] instanceof \Illuminate\Http\UploadedFile) {
                    $file = $fileArr[0];
                    $filename = 'penawaran_' . time() . '_' . ($key + 1) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('penawaran_vendor', $filename, 'public');
                } elseif (isset($fileArr[0]) && is_string($fileArr[0]) && $fileArr[0]) {
                    $filename = $fileArr[0];
                }
            } elseif (isset($vendorData['penawaran_file']) && is_string($vendorData['penawaran_file']) && $vendorData['penawaran_file'] !== null && $vendorData['penawaran_file'] !== '') {
                $filename = $vendorData['penawaran_file'];
            } elseif ($request->hasFile('penawaran_file_' . ($key + 1))) {
                $file = $request->file('penawaran_file_' . ($key + 1));
                if ($file) {
                    $filename = 'penawaran_' . time() . '_' . ($key + 1) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('penawaran_vendor', $filename, 'public');
                }
            } elseif ($ListVendorOld && $ListVendorOld->SuratPenawaranVendor) {
                $filename = $ListVendorOld->SuratPenawaranVendor;
            }

            $ListVendor = ListVendor::create([
                'IdPengajuan' => $pengajuan->id,
                'VendorKe' => $key + 1,
                'NamaVendor' => $vendorData['vendor_id'],
                'SuratPenawaranVendor' => $filename,
                'HargaTanpaDiskon' => preg_replace('/\D/', '', $vendorData['total_harga_sebelum_diskon']),
                'HargaDenganDiskon' => preg_replace('/\D/', '', $vendorData['total_harga_setelah_diskon']),
                'TotalDiskon' => preg_replace('/\D/', '', $vendorData['total_diskon']),
                'Ppn' => $vendorData['ppn_persen'],
                'TotalPpn' => preg_replace('/\D/', '', $vendorData['total_ppn']),
                'TotalHarga' => preg_replace('/\D/', '', $vendorData['grand_total']),
                'KodePerusahaan' => auth()->user()->kodeperusahaan,
                'UserCreate' => auth()->user()->name,
                'UserUpdate' => auth()->user()->name,
            ]);

            if (isset($vendorData['details']) && is_array($vendorData['details'])) {
                foreach ($vendorData['details'] as $detailB) {
                    ListVendorDetail::create([
                        'IdPengajuan' => $pengajuan->id,
                        'IdListVendor' => $ListVendor->id,
                        'NamaBarang' => $detailB['barang_id'] ?? null,
                        'NamaVendor' => null,
                        'Jumlah' => $detailB['jumlah'],
                        'HargaSatuan' => preg_replace('/\D/', '', $detailB['harga_satuan']),
                        'Diskon' => preg_replace('/\D/', '', $detailB['diskon_item']),
                        'JenisDiskon' => $detailB['jenis_diskon_item'],
                        'TotalDiskon' => preg_replace('/\D/', '', $detailB['total_diskon']),
                        'TotalHarga' => preg_replace('/\D/', '', $detailB['total_harga']),
                        'KodePerusahaan' => auth()->user()->kodeperusahaan,
                        'UserCreate' => auth()->user()->name,
                    ]);
                }
            }
        }

        // foreach ($request->vendors[0]['details'] as $key => $listalat) {
        //     PengajuanItem::create([
        //         'IdPengajuan' => $pengajuan->id,
        //         'IdBarang' => $listalat['barang_id'],
        //         'Jumlah' => $listalat['jumlah'] ?? null,
        //         'Satuan' => $listalat['satuan'] ?? null,
        //         'HargaSatuan' => isset($listalat['harga_satuan']) ? preg_replace('/\D/', '', $listalat['harga_satuan']) : null,
        //         'HargaNego' => isset($listalat['harga_nego']) ? preg_replace('/\D/', '', $listalat['harga_nego']) : null,
        //         'UserCreate' => auth()->user()->name,
        //         'UserUpdate' => auth()->user()->name,
        //     ]);
        // }

        activity('pengajuan_pembelian')
            ->causedBy(auth()->user())
            ->performedOn($pengajuan)
            ->withProperties([
                'attributes' => $pengajuan->toArray(),
                'vendors' => $request->vendors,
            ])
            ->log('Memperbarui pengajuan pembelian dengan kode ' . $pengajuan->KodePengajuan);

        return back()->with('success', 'Pengajuan pembelian berhasil diperbarui');
    }

    public function UpdatePengajuan(Request $request, $id)
    {
        $data = PengajuanPembelian::with('getVendor.getVendorDetail', 'getJenisPermintaan', 'getPengajuanItem.getBarang', 'getPengajuanItem.getHtaGpa', 'getPengajuanItem.getRekomendasi', 'getPengajuanItem.getFui', 'getPengajuanItem.getDisposisi', 'getDepartemen', 'getPengajuanItem.getFs', 'getHtaGpa')->find($id);
        // dd($data);
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->getHtaGpa->JenisForm)
            ->where('DokumenId', $data->getHtaGpa->id)
            ->orderBy('Urutan', 'asc')
            ->get();

        $semuaApproved = $approval->every(function ($item) {
            return $item->Status === 'Approved';
        });

        if ($semuaApproved) {
            $countVendor = $data->getVendor;
            $namaUser = auth()->user()->name ?? 'User';
            if (count($countVendor) < 2) {
                return redirect()->back()->with('error', "Hai $namaUser, pengajuan tidak bisa diajukan ke CCP. Minimal harus ada 2 vendor dan maksimal 3 vendor ya.");
            }
            if (count($countVendor) > 3) {
                return redirect()->back()->with('error', "Hai $namaUser, pengajuan tidak bisa diajukan ke CCP. Maksimal hanya boleh 3 vendor ya.");
            }
            $pengajuan = PengajuanPembelian::findOrFail($id);
            $pengajuan->Status = $request->Status;
            $pengajuan->DiajukanOleh = auth()->user()->id;
            $pengajuan->DiajukanPada = now();
            $pengajuan->save();

            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user()->id)
                    ->withProperties(['ip' => request()->ip()])
                    ->log('Mengajukan pengajuan pembelian ke CCP: ' . $pengajuan->KodePengajuan);
            }

            $message = '';
            if ($request->Status == 'Diajukan') {
                $message = 'Terimakasih ' . auth()->user()->name . ', pengajuan Anda berhasil diajukan ke CCP.';
            } elseif ($request->Status == 'Draft') {
                $message = 'Pengajuan berhasil dikembalikan ke draft.';
            } else {
                $message = 'Status pengajuan berhasil diperbarui.';
            }
            return redirect()
                ->route('ajukan.show', encrypt($id))
                ->with('success', $message);
        } else {
            return back()->with('error', 'HTA / GPA, Belum Disetujui. Proses Tidak Dapat Diteruskan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $id = decrypt($id);
        $perusahaan = PengajuanPembelian::with('DetailPengajuan')->find($id);

        if (!$perusahaan) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user()->id)
                ->withProperties(['ip' => request()->ip()])
                ->log('Menghapus master perusahaan: ' . $perusahaan->Nama);
        }

        $perusahaan->delete();

        return response()->json(['status' => 200, 'message' => 'Master perusahaan berhasil dihapus']);
    }
}
