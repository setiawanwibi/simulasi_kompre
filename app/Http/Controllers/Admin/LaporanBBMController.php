<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\LaporanBBM;
use App\Models\PermohonanBBM;
use App\Models\JenisBBM;
use App\Models\Kendaraan;
use App\Models\User;

class LaporanBBMController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanBBM::with(['admin','driver','kendaraan','jenisBBM']);

        if($request->start){
            $query->whereDate('tanggal','>=',$request->start);
        }

        if($request->end){
            $query->whereDate('tanggal','<=',$request->end);
        }

        if($request->driver){
            $query->where('id_driver',$request->driver);
        }

        if($request->nopol){
            $query->where('id_kendaraan',$request->nopol);
        }

        if($request->search){

            $s = $request->search;

            $query->where(function($q) use ($s){

                $q->where('nama_driver', 'like', "%$s%")

                ->orWhere('no_polisi', 'like', "%$s%");

            });
        }

        if($request->export == 'pdf'){
            
            $laporans = $query->orderBy('tanggal','asc')->get();

            $totalLiter = $laporans->sum('jumlah_liter');
            $totalBiaya = $laporans->sum('total_biaya');

            $pdf = Pdf::loadView(
                'admin.laporan_bbm.export_pdf',
                [
                    'laporans'=>$laporans,
                    'request'=>$request,
                    'totalLiter'=>$totalLiter,
                    'totalBiaya'=>$totalBiaya
                ]
            )->setPaper('A4','landscape');

            return $pdf->stream('laporan-bbm.pdf');
        }

        $laporans = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $drivers = User::where('role','driver')->get();
        $kendaraans = Kendaraan::all();

        return view('admin.laporan_bbm.index', compact(
            'laporans','drivers','kendaraans'
        ));
}

    public function create(Request $request)
    {
        $lastOdo = Kendaraan::pluck('odometer_terakhir','id');

        $permohonan = null;

        if ($request->permohonan_id) {
            $permohonan = PermohonanBBM::find($request->permohonan_id);
        }

        return view('admin.laporan_bbm.create', [
            'permohonan' => $permohonan,
            'drivers' => User::where('role','driver')->get(),
            'kendaraans' => Kendaraan::with('jenisbbms')->get(),
            'jenisBBMs' => JenisBBM::all(),
            'lastOdo' => $lastOdo
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_permohonan' => 'nullable',
            'id_driver' => 'required|exists:users,id',
            'id_kendaraan' => 'required|exists:kendaraans,id',
            'id_jenis_bbm' => 'required|exists:jenis_bbms,id',
            'tanggal' => 'required|date',
            'jumlah_liter' => 'required|integer|min:1',
            'keterangan_admin' => 'nullable|string',
            'odometer' => 'nullable|numeric'
        ]);

        $jenisBBM = JenisBBM::findOrFail($request->id_jenis_bbm);
        $driver = User::findOrFail($request->id_driver);
        $admin = Auth::user();
        $odometer = $request->odometer;

        if (!$odometer && $request->id_permohonan) {
            $permohonan = PermohonanBBM::find($request->id_permohonan);
            $odometer = $permohonan?->odometer;
        }

        $kendaraan = Kendaraan::findOrFail($request->id_kendaraan);

        if ($odometer && $odometer <= $kendaraan->odometer_terakhir) {
            return back()->withInput()->with('error',
                'Odometer harus lebih besar dari '.$kendaraan->odometer_terakhir.' km'
            );
        }

        LaporanBBM::create([
            'id_permohonan' => $request->id_permohonan,
            'id_admin' => Auth::id(),
            'id_driver' => $request->id_driver,
            'nama_driver' => $driver->name,
            'nama_admin' => $admin->name,
            'nama_jenis_bbm' => $jenisBBM->nama_jenis,
            'id_kendaraan' => $request->id_kendaraan,
            'no_polisi' => $kendaraan->no_polisi,
            'id_jenis_bbm' => $request->id_jenis_bbm,
            'tanggal' => now(),
            'jumlah_liter' => (int) $request->jumlah_liter,
            'odometer' => $odometer,
            'harga_per_liter' => $jenisBBM->harga_per_liter,
            'total_biaya' => $request->jumlah_liter * $jenisBBM->harga_per_liter,
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        if ($odometer) {
            $kendaraan->update([
                'odometer_terakhir' => $odometer
            ]);
        }

        return redirect()
            ->route('admin.laporan-bbm.index')
            ->with('success','Laporan berhasil disimpan');
    }

    public function show($id)
    {
        $laporan = LaporanBBM::with([
            'admin',
            'driver',
            'kendaraan',
            'jenisBBM'
        ])->findOrFail($id);

        $selisih_km = null;
        $info_odometer = null;

        if ($laporan->odometer && $laporan->kendaraan) {
            $selisih_km = $laporan->kendaraan->odometer_terakhir - $laporan->odometer;
            $info_odometer = 'Odometer sekarang: '.$laporan->kendaraan->odometer_terakhir.' km';
        }

        return view('admin.laporan_bbm.show', compact(
            'laporan',
            'selisih_km',
            'info_odometer'
        ));
    }

    public function edit($id)
    {
        $laporan = LaporanBBM::findOrFail($id);

        return view('admin.laporan_bbm.edit', [
            'laporan' => $laporan,
            'drivers' => User::where('role', 'driver')->get(),
            'kendaraans' => Kendaraan::with('jenisbbms')->get(),
            'jenisBBMs' => JenisBBM::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanBBM::findOrFail($id);

        $user = Auth::user();
        if (
            $user->role !== 'super_admin' &&
            $laporan->id_admin !== $user->id
        ) {
            abort(403, 'Anda tidak memiliki akses mengedit laporan ini.');
        }

        $request->validate([
            'id_driver' => 'required|exists:users,id',
            'id_kendaraan' => 'required|exists:kendaraans,id',
            'id_jenis_bbm' => 'required|exists:jenis_bbms,id',
            'jumlah_liter' => 'required|integer|min:1',
            'keterangan_admin' => 'nullable|string',
            'odometer' => 'nullable|numeric',
        ]);

        $jenisBBM = JenisBBM::findOrFail($request->id_jenis_bbm);
        $driver = User::findOrFail($request->id_driver);
        $admin = Auth::user();
        $kendaraan = Kendaraan::findOrFail($request->id_kendaraan);

        $odometer = $request->odometer;

        if (
            $odometer &&
            $odometer != $laporan->odometer &&
            $odometer <= $kendaraan->odometer_terakhir
        ) {
            return back()->withInput()->with(
                'error',
                'Odometer harus lebih besar dari '.$kendaraan->odometer_terakhir.' km'
            );
        }

        $laporan->update([
            'id_driver' => $request->id_driver,
            'nama_driver' => $driver->name,
            'nama_admin' => $admin->name,
            'nama_jenis_bbm' => $jenisBBM->nama_jenis,
            'id_kendaraan' => $request->id_kendaraan,
            'no_polisi' => $kendaraan->no_polisi,
            'id_jenis_bbm' => $request->id_jenis_bbm,
            'tanggal' => $laporan->tanggal,
            'jumlah_liter' => $request->jumlah_liter,
            'odometer' => $odometer,
            'harga_per_liter' => $jenisBBM->harga_per_liter,
            'total_biaya' => $request->jumlah_liter * $jenisBBM->harga_per_liter,
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        if ($odometer && $odometer > $kendaraan->odometer_terakhir) {
            $kendaraan->update([
                'odometer_terakhir' => $odometer
            ]);
        }

        return redirect()
            ->route('admin.laporan-bbm.index')
            ->with('success', 'Laporan BBM berhasil diperbarui');
    }

    public function destroy($id)
    {
        $laporan = LaporanBBM::findOrFail($id);

        if (
            Auth::user()->role !== 'super_admin' &&
            $laporan->id_admin !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses menghapus laporan ini.');
        }

        $kendaraan = Kendaraan::find($laporan->id_kendaraan);

        $laporan->delete();

        if ($kendaraan) {

            $last = LaporanBBM::where('id_kendaraan', $kendaraan->id)
                ->whereNotNull('odometer')
                ->latest()
                ->first();

            $kendaraan->update([
                'odometer_terakhir' => $last?->odometer ?? 0
            ]);
        }

        return redirect()
            ->route('admin.laporan-bbm.index')
            ->with('success', 'Laporan BBM berhasil dihapus');
    }

    public function nota($id)
    {
        $laporan = LaporanBBM::with([
            'driver',
            'kendaraan',
            'jenisBBM'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.laporan_bbm.nota', compact('laporan'))
            ->setPaper('A4');

        return $pdf->stream('nota-bbm.pdf');
    }
}