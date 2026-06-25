<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\PermohonanBBM;
use App\Models\Kendaraan;
use App\Models\JenisBBM;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermohonanBBM::where('id_user', Auth::id())
            ->with(['kendaraan', 'jenisbbm']);

        if ($request->filled('tgl_awal')) {

            $query->whereDate(
                'tanggal_permohonan',
                '>=',
                $request->tgl_awal
            );
        }

        if ($request->filled('tgl_akhir')) {

            $query->whereDate(
                'tanggal_permohonan',
                '<=',
                $request->tgl_akhir
            );
        }

        if ($request->filled('nopol')) {

            $query->where(
                'no_polisi',
                'like',
                '%' . $request->nopol . '%'
            );
        }

        $data = $query
            ->latest('tanggal_permohonan')
            ->get();

        return view(
            'driver.permohonan.index',
            compact('data')
        );
    }

    public function create()
    {
        $kendaraans = Kendaraan::with('jenisbbms')->get();
        $jenis = JenisBBM::all();

        $lastOdo = Kendaraan::pluck('odometer_terakhir','id');

        return view('driver.permohonan.create',
            compact('kendaraans','jenis','lastOdo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kendaraan'      => 'required|exists:kendaraans,id',
            'id_jenis_bbm'      => 'required|exists:jenis_bbms,id',
            'tanggal_permohonan'=> 'required|date',
            'jumlah_liter'      => 'required|numeric|min:1',
            'odometer'          => 'nullable|numeric|min:0',
            'foto_sisa_bbm'     => 'required|image|max:10240'
        ]);

        $kendaraan = Kendaraan::findOrFail($request->id_kendaraan);
        $jenis = JenisBBM::findOrFail($request->id_jenis_bbm);
        if (!$kendaraan->jenisbbms->contains($request->id_jenis_bbm)) {
            return back()->withInput()->with(
                'error',
                'Jenis BBM tidak sesuai dengan kendaraan.'
            );
}

        if ($request->filled('odometer') && $request->odometer <= $kendaraan->odometer_terakhir) {
            return back()->with('error',
                'Odometer harus lebih besar dari '.$kendaraan->odometer_terakhir.' km'
            )->withInput();
        }

        $foto = $request->file('foto_sisa_bbm')
            ->store('permohonan','public');

        PermohonanBBM::create([
            'id_user'             => Auth::id(),
            'nama_driver'         => Auth::user()->name,
            'id_kendaraan'        => $request->id_kendaraan,
            'no_polisi'           => $kendaraan->no_polisi,
            'id_jenis_bbm'        => $request->id_jenis_bbm,
            'nama_jenis_bbm'      => $jenis->nama_jenis,
            'tanggal_permohonan'  => now()->toDateString(),
            'waktu_permohonan'   => $request->waktu_permohonan,
            'latitude'           => $request->latitude,
            'longitude'          => $request->longitude,
            'jumlah_liter'       => $request->jumlah_liter,
            'odometer'           => $request->odometer,
            'odometer_sebelumnya'=> $kendaraan->odometer_terakhir,
            'foto_sisa_bbm'      => $foto,
            'status'             => 'Pending'
        ]);

        return redirect()
            ->route('driver.permohonan.index')
            ->with('success','Permohonan berhasil dibuat');
    }

    public function show($id)
    {
        $data = PermohonanBBM::where('id_user', Auth::id())
            ->with(['kendaraan','jenisbbm'])
            ->findOrFail($id);

        return view('driver.permohonan.show', compact('data'));
    }

    public function edit($id)
    {
        $data = PermohonanBBM::where('id_user', Auth::id())
            ->findOrFail($id);

        if ($data->status !== 'Pending') {
            return back()->with('error','Tidak bisa edit, sudah diproses admin');
        }

        $kendaraans = Kendaraan::with('jenisbbms')->get();
        $jenis = JenisBBM::all();

        $lastOdo = Kendaraan::pluck('odometer_terakhir','id');

        return view('driver.permohonan.edit',
            compact('data','kendaraans','jenis','lastOdo'));
    }

    public function update(Request $request, $id)
    {
        $data = PermohonanBBM::where('id_user', Auth::id())
            ->findOrFail($id);

        if ($data->status !== 'Pending') {
            return back()->with('error','Tidak bisa edit, sudah diproses admin');
        }

        $request->validate([
            'id_kendaraan'      => 'required|exists:kendaraans,id',
            'id_jenis_bbm'      => 'required|exists:jenis_bbms,id',
            'jumlah_liter'      => 'required|numeric|min:1',
            'odometer'          => 'required|numeric|min:0',
            'foto_sisa_bbm'     => 'nullable|image|max:10240'
        ]);

        $kendaraan = Kendaraan::findOrFail($request->id_kendaraan);
        $jenis = JenisBBM::findOrFail($request->id_jenis_bbm);
        if (!$kendaraan->jenisbbms->contains($request->id_jenis_bbm)) {
            return back()->withInput()->with(
                'error',
                'Jenis BBM tidak sesuai dengan kendaraan.'
            );
        }

        if ($request->filled('odometer') && $request->odometer <= $kendaraan->odometer_terakhir) {
            return back()->with('error',
                'Odometer harus lebih besar dari '.$kendaraan->odometer_terakhir.' km'
            )->withInput();
        }

        $foto = $data->foto_sisa_bbm;

        if ($request->hasFile('foto_sisa_bbm')) {

            if ($data->foto_sisa_bbm) {
                Storage::disk('public')->delete($data->foto_sisa_bbm);
            }

            $foto = $request->file('foto_sisa_bbm')
                ->store('permohonan','public');
        }

        $data->update([
            'id_kendaraan'        => $request->id_kendaraan,
            'no_polisi'           => $kendaraan->no_polisi,
            'id_jenis_bbm'        => $request->id_jenis_bbm,
            'nama_jenis_bbm'      => $jenis->nama_jenis,
            'nama_driver'         => Auth::user()->name,
            'tanggal_permohonan'  => $data->tanggal_permohonan,
            'jumlah_liter'       => $request->jumlah_liter,
            'odometer'           => $request->odometer,
            'odometer_sebelumnya'=> $kendaraan->odometer_terakhir,
            'foto_sisa_bbm'      => $foto,
        ]);

        return redirect()
            ->route('driver.permohonan.index')
            ->with('success','Permohonan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = PermohonanBBM::where('id_user', Auth::id())
            ->findOrFail($id);

        if ($data->status !== 'Pending') {
            return back()->with('error','Tidak bisa hapus, sudah diproses');
        }

        if ($data->foto_sisa_bbm) {
            Storage::disk('public')->delete($data->foto_sisa_bbm);
        }

        $data->delete();

        return back()->with('success','Permohonan berhasil dihapus');
    }
}