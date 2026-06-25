<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisBBM;
use Illuminate\Http\Request;

class JenisBBMController extends Controller
{
    public function index()
    {
        $jenisbbms = JenisBBM::all();
        return view('admin.jenis_bbm.index', compact('jenisbbms'));
    }

    public function create()
    {
        return view('admin.jenis_bbm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:50',
            'harga_per_liter' => 'required|numeric'
        ]);

        JenisBBM::create($request->all());

        return redirect()->route('admin.jenis-bbm.index')
            ->with('success', 'Jenis BBM berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jenisbbm = JenisBBM::findOrFail($id);
        return view('admin.jenis_bbm.edit', compact('jenisbbm'));
    }

    public function update(Request $request, $id)
    {
        $jenisbbm = JenisBBM::findOrFail($id);

        $request->validate([
            'nama_jenis' => 'required|string|max:50',
            'harga_per_liter' => 'required|numeric'
        ]);

        $jenisbbm->update($request->all());

        return redirect()->route('admin.jenis-bbm.index')
            ->with('success', 'Jenis BBM berhasil diperbarui');
    }

    public function destroy($id)
    {
        JenisBBM::destroy($id);

        return redirect()->route('admin.jenis-bbm.index')
            ->with('success', 'Jenis BBM berhasil dihapus');
    }
}
