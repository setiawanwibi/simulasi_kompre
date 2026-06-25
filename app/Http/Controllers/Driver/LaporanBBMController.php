<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanBBM;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBBMController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanBBM::with([
            'kendaraan',
            'jenisBBM',
            'permohonan'
        ])
        ->where('id_driver', Auth::id());

        if ($request->filled('tgl_awal')) {

            $query->whereDate(
                'tanggal',
                '>=',
                $request->tgl_awal
            );
        }

        if ($request->filled('tgl_akhir')) {

            $query->whereDate(
                'tanggal',
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

        $laporans = $query
            ->latest('tanggal')
            ->get();

        return view(
            'driver.laporan_bbm.index',
            compact('laporans')
        );
    }

    public function show($id)
    {
        $laporan = LaporanBBM::where('id', $id)
            ->where('id_driver', Auth::id())
            ->firstOrFail();

        return view('driver.laporan_bbm.show', compact('laporan'));
    }

    public function nota($id)
    {
        $laporan = LaporanBBM::with([
            'kendaraan',
            'jenisBBM',
            'driver',
            'admin'
        ])
        ->where('id',$id)
        ->where('id_driver',auth()->id())
        ->firstOrFail();

        $pdf = Pdf::loadView('driver.laporan_bbm.nota', compact('laporan'))
            ->setPaper('A4','portrait');

        return $pdf->download('nota-bbm-'.$laporan->id.'.pdf');
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanBBM::where('id',$id)
            ->where('id_driver',auth()->id())
            ->firstOrFail();

        $request->validate([
            'catatan_driver' => 'required|string',
            'foto_driver' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        if($request->hasFile('foto_driver')){
            $path = $request->file('foto_driver')->store('bukti-bbm','public');
            $laporan->foto_driver = $path;
        }

        $laporan->catatan_driver = $request->catatan_driver;
        $laporan->save();

        return back()->with('success','Laporan berhasil diperbarui');
    }

}
