<?php

namespace App\Http\Controllers;

use App\Models\MasterVendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterVendorController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterVendor::query();

            if ($request->has('jenis') && $request->jenis !== null && $request->jenis !== '') {
                $data->where('Jenis', $request->jenis);
            }

            $data = $data->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('vendor.edit', $encryptedId) . '" class="btn btn-sm btn-warning">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    ';
                })
                ->addColumn('Status', function ($row) {
                    $label = $row->Status === 'Y' ? 'Aktif' : 'Tidak Aktif';
                    $icon = $row->Status === 'Y'
                        ? '<span class="badge bg-success"><i class="fa fa-check"></i> ' . $label . '</span>'
                        : '<span class="badge bg-danger"><i class="fa fa-times"></i> ' . $label . '</span>';
                    return $icon;
                })
                ->rawColumns(['action', 'Status'])
                ->make(true);
        }
        return view('master.vendor.index');
    }

    public function create()
    {
        return view('master.vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Alamat' => 'nullable|string|max:255',
            'NoHp' => 'nullable|string|max:15',
            'Email' => 'nullable|string|max:255',
            'Npwp' => 'nullable|string|max:255',
            'Nib' => 'nullable|string|max:255',
            'NamaPic' => 'required|string|max:255',
            'NoHpPic' => 'required|string|max:15'
        ]);

        MasterVendor::create([
            'Nama' => $request->Nama,
            'Alamat' => $request->Alamat,
            'NoHp' => $request->NoHp,
            'Email' => $request->Email,
            'Npwp' => $request->Npwp,
            'Nib' => $request->Nib,
            'NamaPic' => $request->NamaPic,
            'NoHpPic' => $request->NoHpPic,
            'UserCreate' => auth()->user()->name,
            'Status' => 'Y',
        ]);

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user()->id)
                ->withProperties(['ip' => request()->ip()])
                ->log('Menambah master vendor baru: ' . $request->Nama);
        }

        return redirect()->route('vendor.index')->with('success', 'Master vendor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $vendor = MasterVendor::findOrFail($id);
        return view('master.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Alamat' => 'nullable|string|max:255',
            'NoHp' => 'nullable|string|max:100',
            'Email' => 'nullable|string|max:150',
            'NamaPic' => 'required|string|max:150',
            'Npwp' => 'nullable|string|max:255',
            'Nib' => 'nullable|string|max:255',
            'NoHpPic' => 'required|string|max:100',
            'Status' => 'required|string|max:100',
        ]);

        $vendor = MasterVendor::findOrFail($id);
        $vendor->update([
            'Nama' => $request->Nama,
            'Alamat' => $request->Alamat,
            'NoHp' => $request->NoHp,
            'Email' => $request->Email,
            'NamaPic' => $request->NamaPic,
            'Npwp' => $request->Npwp,
            'Nib' => $request->Nib,
            'NoHpPic' => $request->NoHpPic,
            'UserUpdate' => auth()->user()->name,
            'Status' => $request->Status,
        ]);

        if (function_exists('activity')) {
            $oldData = $vendor->getOriginal();
            activity()
                ->causedBy(auth()->user()->id)
                ->withProperties([
                    'ip' => request()->ip(),
                    'old' => $oldData,
                    'new' => $vendor->getAttributes()
                ])
                ->log('Memperbarui master vendor: ' . $request->Nama);
        }
        return redirect()->route('vendor.index')->with('success', 'Master vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $vendor = MasterVendor::find($id);

        if (!$vendor) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }

        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user()->id)
                ->withProperties(['ip' => request()->ip()])
                ->log('Menghapus master vendor: ' . $vendor->Nama);
        }

        $vendor->update([
            'UserDelete' => auth()->user()->name,
        ]);
        $vendor->delete();

        return response()->json(['status' => 200, 'message' => 'Master vendor berhasil dihapus']);
    }
}
