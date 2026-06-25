<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JenisBBM;
use App\Models\LaporanBBM;
use App\Models\PermohonanBBM;
use App\Models\Kendaraan;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermohonanBBM::with(['user','kendaraan','jenisbbm']);

        if($request->start){
            $query->whereDate('tanggal_permohonan','>=',$request->start);
        }

        if($request->end){
            $query->whereDate('tanggal_permohonan','<=',$request->end);
        }

        if($request->driver){
            $query->where('id_user',$request->driver);
        }

        if($request->nopol){
            $query->where('id_kendaraan',$request->nopol);
        }

        if ($request->filled('search')) {

            $s = trim($request->search);

            $query->where(function ($q) use ($s) {

                $q->where('nama_driver', 'LIKE', "%{$s}%")
                ->orWhere('no_polisi', 'LIKE', "%{$s}%")

                ->orWhereHas('user', function ($u) use ($s) {

                        $u->where('name', 'LIKE', "%{$s}%");
                })
                ->orWhereHas('kendaraan', function ($k) use ($s) {

                        $k->where('no_polisi', 'LIKE', "%{$s}%");
                });
            });
        }

        $data = $query->latest()
        ->paginate(15)
        ->withQueryString();

        $drivers = \App\Models\User::where('role','driver')->get();
        $kendaraans = \App\Models\Kendaraan::all();

        return view('admin.permohonan.index', compact(
            'data','drivers','kendaraans'
        ));
    }

    public function approve(Request $request, $id)
    {
        $permohonan = PermohonanBBM::findOrFail($id);
        $kendaraan = Kendaraan::findOrFail($permohonan->id_kendaraan);

        $odo = $permohonan->odometer;

        if ($odo && $odo <= $kendaraan->odometer_terakhir) {
            return back()->with('error',
                'Odometer harus lebih besar dari '.$kendaraan->odometer_terakhir.' km'
            );
        }

        $permohonan->status = 'Disetujui';
        $permohonan->keterangan_admin = $request->keterangan_admin;
        $permohonan->save();

        $jenis = JenisBBM::find($permohonan->id_jenis_bbm);
        $harga = $jenis->harga_per_liter ?? 0;
        $total = $permohonan->jumlah_liter * $harga;

        LaporanBBM::create([
            'id_permohonan'   => $permohonan->id,
            'id_driver'       => $permohonan->id_user,
            'nama_driver'     => $permohonan->user?->name,
            'id_kendaraan'    => $permohonan->id_kendaraan,
            'no_polisi'       => $kendaraan->no_polisi,
            'id_jenis_bbm'    => $permohonan->id_jenis_bbm,
            'nama_jenis_bbm'  => $jenis?->nama_jenis,
            'jumlah_liter'    => $permohonan->jumlah_liter,
            'harga_per_liter' => $harga,
            'total_biaya'     => $total,
            'tanggal'         => $permohonan->tanggal_permohonan,
            'id_admin'        => Auth::id(),
            'nama_admin'      => Auth::user()->name,
            'keterangan_admin'=> $request->keterangan_admin,
            'odometer'        => $odo ?? null
        ]);

        if ($odo) {
            $kendaraan->update([
                'odometer_terakhir' => $odo
            ]);
        }

        return redirect()->route('admin.laporan-bbm.index')
            ->with('success','Permohonan disetujui & laporan berhasil dibuat');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required'
        ]);

        $p = PermohonanBBM::findOrFail($id);

        $p->status = 'Ditolak';
        $p->keterangan_admin = $request->keterangan_admin;
        $p->save();

        return back()->with('success','Permohonan ditolak');
    }
}