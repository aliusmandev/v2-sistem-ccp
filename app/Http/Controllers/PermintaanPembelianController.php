<?php

namespace App\Http\Controllers;

use App\Mail\NotifikasiApproval;
use App\Mail\NotifikasiPermintaanPembelian;
use App\Models\DokumenApproval;
use App\Models\MasterBarang;
use App\Models\MasterDepartemen;
use App\Models\MasterForm;
use App\Models\MasterJabatan;
use App\Models\MasterJenisPengajuan;
use App\Models\MasterPerusahaan;
use App\Models\MasterSatuan;
use App\Models\PermintaanPembelian;
use App\Models\PermintaanPembelianDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PermintaanPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd(Auth::user()->kodeperusahaan);
        if ($request->ajax()) {
            if (auth()->user()->hasRole('SMI')) {

                $query = PermintaanPembelian::with('getJenisPermintaan', 'getPerusahaan', 'getDepartemen', 'getDiajukanOleh')
                    ->where('Jenis', '1')
                    ->where('KodePerusahaan', Auth::user()->kodeperusahaan)
                    // ->where('Departemen', auth()->user()->departemen)
                    ->orderBy('id', 'desc');
            } elseif (auth()->user()->hasRole('LOGUM')) {
                $query = PermintaanPembelian::with('getJenisPermintaan', 'getPerusahaan', 'getDepartemen', 'getDiajukanOleh')
                    ->where('Jenis', '!=', 1)
                    ->where('KodePerusahaan', Auth::user()->kodeperusahaan)
                    // ->where('Departemen', auth()->user()->departemen)
                    ->orderBy('id', 'desc');
            } else {
                $query = PermintaanPembelian::with('getJenisPermintaan', 'getPerusahaan', 'getDepartemen', 'getDiajukanOleh')
                    ->orderBy('id', 'desc');
            }

            // if ($request->filled('perusahaan')) {
            //     $query->where('KodePerusahaan', $request->perusahaan);
            // }
            // if ($request->filled('jenis')) {
            //     $query->where('Jenis', $request->jenis);
            // }
            if ($request->filled('departemen')) {
                $query->where('Departemen', $request->departemen);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('Departemen', function ($row) {
                    return optional($row->getDepartemen)->Nama;
                })
                ->editColumn('DiajukanOleh', function ($row) {
                    return optional($row->getDiajukanOleh)->name;
                })
                ->addColumn('NomorPermintaan', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '<a href="' . route('pp.show', ['id' => $encryptedId]) . '" style="color: #007bff; font-weight: bold;">' . e($row->NomorPermintaan) . '</a>';
                })
                ->editColumn('KodePerusahaan', function ($row) {
                    return optional($row->getPerusahaan)->Nama;
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('pp.edit', $encryptedId) . '" class="btn btn-sm btn-warning me-1">
                            <i class="fa fa-paper-plane"></i> Kirim Permintaan
                        </a>
                        <button class="btn btn-sm btn-danger btn-delete me-1" data-id="' . $encryptedId . '">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                        <a href="' . route('pp.print', $encryptedId) . '" class="btn btn-sm btn-success" target="_blank">
                            <i class="fa fa-print"></i> Print
                        </a>
                    ';
                })
                ->editColumn('Jenis', function ($row) {
                    return optional($row->getJenisPermintaan)->Nama;
                })
                ->addColumn('Status', function ($row) {
                    $status = e($row->Status);
                    $statusUpdate = $row->StatusUpdate ? \Carbon\Carbon::parse($row->StatusUpdate)->format('d-m-Y H:i') : '-';
                    return '<div><span class="badge bg-info">' . $status . '</span><br><small class="text-muted">Update: ' . $statusUpdate . '</small></div>';
                })
                ->rawColumns(['action', 'NomorPermintaan', 'Status'])
                ->make(true);
        }
        $perusahaan = MasterPerusahaan::get();
        $jenisPermintaan = MasterJenisPengajuan::get();
        $departemen = MasterDepartemen::get();
        return view('form.permintaan-pembelian.index', compact('perusahaan', 'jenisPermintaan', 'departemen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = MasterBarang::with('getMerk', 'getSatuan', 'getJenis')->get();
        $departemen = MasterDepartemen::get();
        $satuan = MasterSatuan::get();
        $jenisPengajuan = MasterJenisPengajuan::get();
        return view('form.permintaan-pembelian.create', compact('jenisPengajuan', 'barang', 'departemen', 'satuan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Tanggal' => 'required|date',
            'Departemen' => 'required',
            'Jenis' => 'required',
            'Tujuan' => 'required',
            'NamaBarang' => 'required|array|min:1',
            'Jumlah' => 'required|array|min:1',
            'Satuan' => 'required|array|min:1',
            'RencanaPenempatan' => 'required|array|min:1',
            'Keterangan' => 'nullable|array',
            'NamaBarang.*' => 'required|string|max:255',
            'Jumlah.*' => 'required|numeric|min:1',
            'Satuan.*' => 'required|string|max:100',
            'RencanaPenempatan.*' => 'nullable|string|max:255',
            'Keterangan.*' => 'nullable|string|max:500',
        ]);
        // settingan jenis form yang dipakai
        if ($request->Jenis == 1) {
            $JenisForm = 5;
        } elseif ($request->Jenis == 2) {
            $JenisForm = 3;
        } else {
            $JenisForm = 4;
        }
        $nomorAkhir = $this->generateNomorPermintaan();
        $permintaan = PermintaanPembelian::create([
            'JenisForm' => $JenisForm,
            'NomorPermintaan' => $nomorAkhir,
            'Jenis' => $request->Jenis,
            'Tujuan' => $request->Tujuan,
            'Tanggal' => $request->Tanggal,
            'Departemen' => $request->Departemen,
            'Status' => 'Draft',
            'StatusUpdate' => now(),
            'KodePerusahaan' => auth()->user()->kodeperusahaan,
            'DiajukanOleh' => auth()->user()->id,
            'DiajukanPada' => now(),
            'UserCreate' => auth()->user()->name,
        ]);

        foreach ($request->NamaBarang as $key => $item) {
            PermintaanPembelianDetail::create([
                'IdPermintaan' => $permintaan->id,
                'NamaBarang' => $item,
                'Jumlah' => $request->Jumlah[$key],
                'Satuan' => $request->Jumlah[$key],
                'RencanaPenempatan' => $request->RencanaPenempatan[$key],
                'Keterangan' => $request->Keterangan[$key],
                'KodePerusahaan' => auth()->user()->kodeperusahaan,
            ]);
        }
        $Form = MasterForm::with([
            'getApproval' => function ($q) use ($permintaan) {
                $q->where('KodePerusahaan', $permintaan->KodePerusahaan);
            },
            'getApproval.getUser'
        ])
            ->where('id', $permintaan->JenisForm)
            ->first();
        // dd($Form);
        foreach ($Form->getApproval as $approvalSetting) {
            DokumenApproval::updateOrCreate(
                [
                    'JenisFormId' => $permintaan->JenisForm,
                    'DokumenId' => $permintaan->id,
                    'Urutan' => $approvalSetting->Urutan ?? null,
                ],
                [
                    'JenisUser' => $approvalSetting->JenisUser ?? 'Master',
                    'DepartemenId' => $permintaan->Departemen,
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
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user()->id)
                ->performedOn($permintaan)
                ->withProperties(['ip' => request()->ip()])
                ->log('Permintaan Pembelian baru dibuat: ' . $nomorAkhir);
        }

        return redirect()->route('pp.index')->with('success', 'Permintaan pembelian berhasil disimpan.');
    }

    private function generateNomorPermintaan()
    {
        $prefix = 'PP-';
        $bulan = date('m');
        $tahun = date('y');
        $kodePerusahaan = auth()->user()->kodeperusahaan;
        $jumlah = PermintaanPembelian::where('KodePerusahaan', $kodePerusahaan)
            ->whereMonth('Tanggal', $bulan)
            ->whereYear('Tanggal', '20' . $tahun)
            ->count();

        $nextNumber = $jumlah + 1;

        $nomorAkhir = $prefix . $tahun . $bulan . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        return $nomorAkhir;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $data = PermintaanPembelian::with('getJenisPermintaan', 'getDetail.getBarang', 'getDiajukanOleh')->find($id);
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->JenisForm)
            ->where('DokumenId', $data->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        return view('form.permintaan-pembelian.show', compact('data', 'approval'));
    }

    /**
     * Handle approval action for Permintaan Pembelian.
     */
    public function approve(Request $request)
    {
        $userId = $request->input('UserId');
        $dokumenId = $request->input('DokumenId');
        $jenisFormId = $request->input('JenisForm');

        if (!$userId || !$dokumenId || !$jenisFormId) {
            return back()->with('error', 'Parameter approval tidak lengkap.');
        }

        $data = PermintaanPembelian::find($dokumenId);
        if (!$data) {
            return back()->with('error', 'Data Permintaan Pembelian tidak ditemukan.');
        }
        $approvalList = DokumenApproval::where('DokumenId', $dokumenId)
            ->where('JenisFormId', $jenisFormId)
            ->orderBy('Urutan', 'asc')
            ->get();
        $dokumenApproval = $approvalList->where('UserId', $userId)->first();

        if (!$dokumenApproval) {
            return back()->with('error', 'Persetujuan tidak tersedia untuk pengguna yang dipilih.');
        }

        if (auth()->id() != $dokumenApproval->UserId) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menyetujui dokumen ini.');
        }

        $myUrutan = $dokumenApproval->Urutan;
        $cekApproveSebelumnya = $approvalList->where('Urutan', '<', $myUrutan)->where('Status', '!=', 'Approved')->count();
        if ($cekApproveSebelumnya > 0) {
            return back()->with('error', 'Anda belum bisa menyetujui dokumen ini. Approval pada urutan sebelumnya harus dilakukan terlebih dahulu.');
        }
        $user = User::find($dokumenApproval->UserId);
        if ($user && !empty($user->tandatangan)) {
            $dokumenApproval->Ttd = $user->tandatangan;
        }
        $dokumenApproval->Status = 'Approved';
        $dokumenApproval->TanggalApprove = now();
        $dokumenApproval->save();

        $nextApproval = $approvalList->where('Urutan', '>', $myUrutan)->sortBy('Urutan')->first();
        if ($nextApproval) {
            if (!empty($nextApproval->Email)) {
                Mail::to($nextApproval->Email)
                    ->send(new NotifikasiApproval($data, $nextApproval));
            }
        } else {

            $data->Status = 'Telah Disetujui';
            $data->save();
        }

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user()->id)
                ->performedOn($data)
                ->withProperties(['ip' => request()->ip()])
                ->log('Menyetujui permintaan pembelian: ' . ($data->NomorPengajuan ?? $data->id));
        }

        return redirect()->route('pp.show', encrypt($data->id))->with('success', 'Permintaan pembelian berhasil disetujui.');
    }

    public function print($id)
    {
        $id = decrypt($id);
        $data = PermintaanPembelian::with([
            'getDetail.getBarang.getMerk',
            'getDiajukanOleh',
            'getDetail.getBarang.getSatuan'
        ])->find($id);
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->JenisForm)
            ->where('DokumenId', $data->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        // dd($data);
        $pdf = Pdf::loadView('form.permintaan-pembelian.cetak-permintaan', compact('data', 'approval'));
        // If you need to enable remote, use setOption if supported:
        if (method_exists($pdf, 'setOption')) {
            $pdf->setOption('enable_remote', true);
        }
        return $pdf->stream('permintaan-pembelian-' . $data->NomorPengajuan . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $barang = MasterBarang::with('getMerk', 'getSatuan')->get();
        $departemen = MasterDepartemen::get();
        $jabatan = MasterJabatan::get();
        $satuan = MasterSatuan::get();
        $data = PermintaanPembelian::with('getDetail.getBarang')->find($id);
        $approval = DokumenApproval::with('getUser', 'getJabatan', 'getDepartemen')
            ->where('JenisFormId', $data->JenisForm)
            ->where('DokumenId', $data->id)
            ->orderBy('Urutan', 'asc')
            ->get();
        $jenisPengajuan = MasterJenisPengajuan::get();
        $user = User::with('getJabatan', 'getDepartemen')->get();
        return view('form.permintaan-pembelian.edit', compact('barang', 'departemen', 'satuan', 'data', 'jenisPengajuan', 'approval', 'user', 'jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'Tanggal' => 'required|date',
            'Departemen' => 'required',
            'Jenis' => 'required',
            'Tujuan' => 'required',
            'NamaBarang' => 'required|array|min:1',
            'Jumlah' => 'required|array|min:1',
            'Satuan' => 'required|array|min:1',
            'RencanaPenempatan' => 'required|array|min:1',
            'Keterangan' => 'nullable|array',
            'NamaBarang.*' => 'required|string|max:255',
            'Jumlah.*' => 'required|numeric|min:1',
            'Satuan.*' => 'required|string|max:100',
            'RencanaPenempatan.*' => 'nullable|string|max:255',
            'Keterangan.*' => 'nullable|string|max:500',
        ]);

        $permintaan = PermintaanPembelian::findOrFail($id);

        $permintaan->update([
            'Tanggal' => $request->Tanggal,
            'Departemen' => $request->Departemen,
            'Jenis' => $request->Jenis,
            'Tujuan' => $request->Tujuan,
            'Status' => 'Sudah Diajukan',
            'UserUpdate' => auth()->user()->name ?? null,
        ]);

        PermintaanPembelianDetail::where('IdPermintaan', $permintaan->id)->delete();

        foreach ($request->NamaBarang as $key => $item) {
            PermintaanPembelianDetail::create([
                'IdPermintaan' => $permintaan->id,
                'Jenis' => $request->Jenis,
                'NamaBarang' => $item,
                'Jumlah' => $request->Jumlah[$key],
                'Satuan' => $request->Satuan[$key],
                'RencanaPenempatan' => $request->RencanaPenempatan[$key],
                'Keterangan' => $request->Keterangan[$key] ?? null,
                'KodePerusahaan' => auth()->user()->kodeperusahaan,
            ]);
        }

        $approvalDocs = DokumenApproval::where([
            'JenisFormId' => $permintaan->JenisForm,
            'DokumenId' => $permintaan->id,
        ])->orderBy('Urutan', 'asc')->get();

        foreach ($approvalDocs as $key => $approval) {
            $userIdRaw = $request->UserId[$key] ?? null;
            $userIdParts = explode('|', $userIdRaw, 2);
            $userId = trim($userIdParts[0] ?? '');
            $namaUser = trim($userIdParts[1] ?? '');

            if ($userId === (string) (auth()->user()->id)) {
                $status = 'Approved';
                $tanggalApprove = now();
            } else {
                $status = 'Pending';
                $tanggalApprove = now();
            }

            $approval->update([
                'JabatanId' => $request->JabatanId[$key],
                'DepartemenId' => $request->DepartemenId[$key],
                'UserId' => $userId,
                'Nama' => $namaUser,
                'Email' => $request->Email[$key],
                'Urutan' => $approval->Urutan,
                'Status' => $status,
                'TanggalApprove' => $tanggalApprove,
                'ApprovalToken' => str_replace('-', '', Str::uuid()->toString()),
                'UserUpdate' => auth()->user()->name,
            ]);
            Mail::to($request->Email[$key])->send(
                new NotifikasiPermintaanPembelian(
                    $permintaan,
                    $approval
                )
            );
        }
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($permintaan)
                ->withProperties(['ip' => request()->ip()])
                ->log('Memperbarui permintaan pembelian: Nomor ' . $permintaan->NomorPengajuan . ' (ID ' . $permintaan->id . ')');
        }

        return redirect()->route('pp.index')->with('success', 'Permintaan pembelian berhasil diperbarui.');
    }

    /**
     * Proses ACC (approval) Kepala Divisi pada permintaan pembelian
     */
    public function accKepalaDivisi($id)
    {
        $permintaan = PermintaanPembelian::findOrFail($id);
        $permintaan->update([
            'Status' => 'Disetujui Oleh Kepala Divisi',
            'StatusUpdate' => now(),
            'KepalaDivisi_Status' => 'Y',
            'KepalaDivisi_Pada' => now(),
            'KepalaDivisi_Oleh' => auth()->user()->id,
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($permintaan)
                ->withProperties(['ip' => request()->ip()])
                ->log('Permintaan pembelian diketahui Kepala Divisi: Nomor ' . $permintaan->NomorPengajuan . ' (ID ' . $permintaan->id . ')');
        }

        return redirect()->back()->with('success', 'Permintaan pembelian telah diketahui oleh Kepala Divisi.');
    }

    /**
     * Proses ACC (approval) Kepala Divisi Penunjang Medis/Umum pada permintaan pembelian
     */
    public function accKepalaDivisiPenunjang($id)
    {
        $permintaan = PermintaanPembelian::findOrFail($id);

        if (!filled($permintaan->KepalaDivisi_Status) || in_array($permintaan->KepalaDivisi_Status, ['N', 'P'])) {
            return redirect()->back()->with('error', 'Tidak Bisa Disetujui Karena Belum Disetujui Oleh Kadiv Bagian Terkait');
        }

        $permintaan->update([
            'Status' => 'Disetujui Oleh Kepala Divisi Penunjang Medis / Umum',
            'StatusUpdate' => now(),
            'Penunjang_Oleh' => auth()->user()->id,
            'Penunjang_Pada' => now(),
            'Penunjang_Status' => 'Y',
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($permintaan)
                ->withProperties(['ip' => request()->ip()])
                ->log('Permintaan pembelian disetujui Kepala Divisi Penunjang: Nomor ' . $permintaan->NomorPengajuan . ' (ID ' . $permintaan->id . ')');
        }

        return redirect()->back()->with('success', 'Permintaan pembelian telah diketahui oleh Kepala Divisi.');
    }

    /**
     * Proses ACC (approval) Direktur pada permintaan pembelian
     */
    public function accDirektur($id)
    {
        $permintaan = PermintaanPembelian::with('getJenisPermintaan')->findOrFail($id);

        if (!filled($permintaan->Penunjang_Status) || in_array($permintaan->Penunjang_Status, ['N', 'P'])) {
            return redirect()->back()->with('error', 'Tidak Bisa Disetujui Karena Belum Disetujui Oleh Kadiv Penunjang ' . $permintaan->getJenisPermintaan->Nama);
        }
        $permintaan->update([
            'Status' => 'Disetujui Oleh Direktur',
            'StatusUpdate' => now(),
            'Direktur_Status' => 'Y',
            'Direktur_Pada' => now(),
            'Direktur_Oleh' => auth()->user()->id,
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($permintaan)
                ->withProperties(['ip' => request()->ip()])
                ->log('Permintaan pembelian disetujui Direktur: Nomor ' . $permintaan->NomorPengajuan . ' (ID ' . $permintaan->id . ')');
        }

        return redirect()->back()->with('success', 'Permintaan pembelian telah disetujui oleh Direktur.');
    }

    /**
     * Proses ACC (approval) SMI/Logistik pada permintaan pembelian
     */
    public function accSmi($id)
    {
        $permintaan = PermintaanPembelian::findOrFail($id);
        if (!filled($permintaan->Direktur_Status) || in_array($permintaan->Direktur_Status, ['N', 'P'])) {
            return redirect()->back()->with('error', 'Tidak Bisa Disetujui Karena Belum Disetujui Oleh Direktur');
        }
        $permintaan->update([
            'Status' => 'Telah Diterima SMI',
            'StatusUpdate' => now(),
            'Logistik_Oleh' => auth()->user()->id,
            'Logistik_Status' => 'Y',
            'Logistik_Pada' => now(),
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($permintaan)
                ->withProperties(['ip' => request()->ip()])
                ->log('Permintaan pembelian disetujui/dikonfirmasi SMI/Logistik: Nomor ' . $permintaan->NomorPengajuan . ' (ID ' . $permintaan->id . ')');
        }

        return redirect()->back()->with('success', 'Permintaan pembelian telah dikonfirmasi/logistik oleh SMI.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $permintaan = PermintaanPembelian::find($id);

        if (!$permintaan) {
            return response()->json(['status' => 404, 'message' => 'Permintaan pembelian tidak ditemukan.']);
        }

        PermintaanPembelianDetail::where('IdPermintaan', $permintaan->id)->delete();

        $permintaan->delete();

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($permintaan)
                ->withProperties(['ip' => request()->ip()])
                ->log('Menghapus permintaan pembelian: Nomor ' . $permintaan->NomorPengajuan . ' (ID ' . $permintaan->id . ')');
        }

        return response()->json(['status' => 200, 'message' => 'Permintaan pembelian berhasil dihapus.']);
    }
}
