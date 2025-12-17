<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\MasterMerk;
use App\Models\MasterSatuan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterBarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterBarang::with('getSatuan', 'getMerk')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('barang.edit', $row->id) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '">Hapus</button>
                    ';
                })
                ->editColumn('Satuan', function ($row) {
                    return optional($row->getSatuan)->NamaSatuan ?? '-';
                })
                ->editColumn('Merek', function ($row) {
                    return optional($row->getMerk)->Nama ?? '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master.barang.index');
    }

    public function create()
    {
        $satuan = MasterSatuan::all();
        $merekList = MasterMerk::all();

        return view('master.barang.create', compact('satuan', 'merekList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Jenis' => 'required|string|max:190',
            'Satuan' => 'required|string|max:190',
            'Merek' => 'required|string|max:190',
            'Tipe' => 'required',
        ]);

        MasterBarang::create([
            'KodeBarang' => $this->generateKode($request->Jenis),
            'Nama' => $request->Nama,
            'Jenis' => $request->Jenis,
            'Satuan' => $request->Satuan,
            'Merek' => $request->Merek,
            'Tipe' => $request->Tipe,
            'KodePerusahaan' => auth()->user()->kodeperusahaan,
            'UserCreate' => auth()->user()->name,
        ]);
        if (function_exists('activity')) {
            activity()
                ->causedBy(auth()->user()->id)
                ->withProperties(['ip' => request()->ip()])
                ->log('Menambah master barang baru: ' . $request->Nama);
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    private function generateKode($jenis)
    {
        $bulan = date('m');
        $tahun = date('y');
        $jenis = strtoupper($jenis);

        if ($jenis === 'MEDIS') {
            $prefix = "M{$tahun}{$bulan}";
        } elseif ($jenis === 'UMUM') {
            $prefix = "U{$tahun}{$bulan}";
        } else {
            $prefix = "X{$tahun}{$bulan}";
        }

        $maxKode = MasterBarang::where('KodeBarang', 'like', $prefix . '%')
            ->max('KodeBarang');

        if ($maxKode) {
            $lastNumber = (int) substr($maxKode, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function edit($id)
    {
        $barang = MasterBarang::findOrFail($id);
        $satuan = MasterSatuan::all();
        $merekList = MasterMerk::all();
        return view('master.barang.edit', compact('barang', 'satuan', 'merekList'));
    }

    public function update(Request $request, $id)
    {
        $barang = MasterBarang::findOrFail($id);

        $request->validate([
            'Nama' => 'required|string|max:255',
            'Jenis' => 'required|string|max:190',
            'Satuan' => 'required|string|max:190',
            'Merek' => 'required|string|max:190',
            'Tipe' => 'required|string|max:190',
        ]);

        $barang->update([
            'Nama' => $request->Nama,
            'Jenis' => $request->Jenis,
            'Satuan' => $request->Satuan,
            'Merek' => $request->Merek,
            'Tipe' => $request->Tipe,
            'KodePerusahaan' => $request->KodePerusahaan,
            'UserUpdate' => auth()->user()->name,
        ]);
        activity()
            ->causedBy(auth()->user())
            ->performedOn($barang)
            ->withProperties(['ip' => request()->ip()])
            ->log('Memperbarui master barang: ' . $request->Nama);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $barang = MasterBarang::find($id);

        if (!$barang) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }

        $barang->UserDelete = auth()->user()->name;
        $barang->save();

        $barang->delete();

        return response()->json(['status' => 200, 'message' => 'Barang berhasil dihapus']);
    }
}
