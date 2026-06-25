<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\JenisBBM;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::orderBy('created_at', 'desc')->get();
        return view('admin.kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        $jenisbbm = JenisBBM::all();

        return view('admin.kendaraan.create', compact('jenisbbm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_polisi' => 'required|unique:kendaraans,no_polisi',
            'merk' => 'required',
            'jenis' => 'required',
            'tahun' => 'required|integer',
            'odometer_terakhir' => 'required|integer|min:0',
        ]);

        $kendaraan = Kendaraan::create([
            'no_polisi' => $request->no_polisi,
            'merk' => $request->merk,
            'jenis' => $request->jenis,
            'tahun' => $request->tahun,
            'odometer_terakhir' => $request->odometer_terakhir,
        ]);

        $kendaraan->jenisbbms()->sync($request->jenisbbm);

        return redirect()
            ->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $jenisbbm = JenisBBM::all();

        return view('admin.kendaraan.edit', compact(
            'kendaraan',
            'jenisbbm'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_polisi' => 'required',
            'merk' => 'required',
            'jenis' => 'required',
            'tahun' => 'required|numeric',
        ]);

        $kendaraan = Kendaraan::findOrFail($id);

        $kendaraan->update([
            'no_polisi' => $request->no_polisi,
            'merk' => $request->merk,
            'jenis' => $request->jenis,
            'tahun' => $request->tahun,
        ]);
        $kendaraan->jenisbbms()->sync($request->jenisbbm);
        
        return redirect()->route('admin.kendaraan.index')
                        ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('admin.kendaraan.index')
                        ->with('success', 'Data kendaraan berhasil dihapus');
    }

}

