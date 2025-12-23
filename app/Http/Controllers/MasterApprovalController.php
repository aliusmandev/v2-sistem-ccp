<?php

namespace App\Http\Controllers;

use App\Models\DokumenApproval;
use App\Models\MasterApproval;
use App\Models\MasterDepartemen;
use App\Models\MasterForm;
use App\Models\MasterJabatan;
use App\Models\MasterPerusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Laraindo\RupiahFormat;
use Yajra\DataTables\DataTables;

class MasterApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterPerusahaan::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->Kode);
                    return '
                        <a href="' . route('master-approval.create', $encryptedId) . '" class="btn btn-sm btn-success">
                            <i class="fa fa-pen"></i> Atur TTD
                        </a>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.pengaturan-ttd.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $id = decrypt($id);

        $perusahaan = MasterPerusahaan::where('Kode', $id)->first();
        // dd($perusahaan);
        $form = MasterForm::get();
        return view('master.pengaturan-ttd.create', compact('form', 'perusahaan'));
    }

    public function aturTtd($id, $KodePerusahaan)
    {
        $id = decrypt($id);
        $KodePerusahaan = decrypt($KodePerusahaan);
        $form = MasterForm::with('getApproval')->where('id', $id)->first();
        $user = User::with('getJabatan', 'getDepartemen')->get();
        $jabatan = MasterJabatan::get();
        $departemen = MasterDepartemen::get();
        return view('master.pengaturan-ttd.atur-ttd', compact('KodePerusahaan', 'form', 'user', 'jabatan', 'departemen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hapus semua approval yang ada sesuai KodePerusahaan & JenisForm
        MasterApproval::where('KodePerusahaan', $request->KodePerusahaan)
            ->where('JenisForm', $request->JenisForm)
            ->delete();

        if (is_array($request->Urutan)) {
            foreach ($request->Urutan as $key => $urutan) {
                MasterApproval::create([
                    'Urutan' => $urutan,
                    'KodePerusahaan' => $request->KodePerusahaan,
                    'JenisForm' => $request->JenisForm,
                    'UserId' => $request->UserId[$key] ?? null,
                    'JabatanId' => $request->JabatanId[$key] ?? null,
                    'DepartemenId' => $request->DepartemenId[$key] ?? null,
                    'Wajib' => $request->Wajib[$key] ?? null,
                    'UserCreate' => auth()->id(),
                ]);
            }
        } else {
            MasterApproval::create([
                'Urutan' => $request->Urutan ?? null,
                'KodePerusahaan' => $request->KodePerusahaan,
                'JenisForm' => $request->JenisForm,
                'UserId' => $request->UserId,
                'JabatanId' => $request->JabatanId ?? null,
                'DepartemenId' => $request->DepartemenId ?? null,
                'Wajib' => $request->Wajib ?? null,
                'UserCreate' => auth()->id(),
            ]);
        }

        return redirect()
            ->route('master-approval.create', encrypt($request->KodePerusahaan))
            ->with('success', 'Approval berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterApproval $masterApproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterApproval $masterApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterApproval $masterApproval)
    {
        //
    }
    public function validasi($id)
    {
        $approval = DokumenApproval::where('ApprovalToken', $id)
            ->where('Status', 'Approved')
            ->first();

        if (!$approval) {
            return view('approval.validasi', [
                'status' => 'TIDAK SAH'
            ]);
        }

        return view('approval.validasi-valid', [
            'status' => 'SAH',
            'approval' => $approval
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterApproval $masterApproval)
    {
        //
    }
}
