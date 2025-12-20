<?php

namespace App\Http\Controllers;

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
        $user = User::get();
        $jabatan = MasterJabatan::get();
        $departemen = MasterDepartemen::get();
        return view('master.pengaturan-ttd.atur-ttd', compact('KodePerusahaan', 'form', 'user', 'jabatan', 'departemen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'UserId' => $request->UserId,
            'JabatanId' => $request->JabatanId,
            'DepartemenId' => $request->DepartemenId,
            'Urutan' => $request->Urutan,
            'Wajib' => $request->Wajib,
        ];

        $data['UserCreate'] = auth()->id();
        if (is_array($request->UserId)) {
            foreach ($request->UserId as $key => $userId) {
                MasterApproval::create([
                    'KodePerusahaan' => $request->KodePerusahaan,
                    'JenisForm' => $request->JenisForm,
                    'UserId' => $userId,
                    'JabatanId' => $request->JabatanId[$key] ?? null,
                    'DepartemenId' => $request->DepartemenId[$key] ?? null,
                    'Urutan' => $request->Urutan[$key] ?? null,
                    'Wajib' => $request->Wajib[$key] ?? null,
                    'UserCreate' => auth()->id(),
                ]);
            }
        } else {
            MasterApproval::create($data);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterApproval $masterApproval)
    {
        //
    }
}
