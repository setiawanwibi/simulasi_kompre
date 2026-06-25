<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanBBM;
use App\Models\PermohonanBBM;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start
            ? Carbon::parse($request->start)->toDateString()
            : Carbon::now()->startOfMonth()->toDateString();

        $end = $request->end
            ? Carbon::parse($request->end)->toDateString()
            : Carbon::now()->endOfMonth()->toDateString();

        $periodeLabel = Carbon::parse($start)->translatedFormat('d F Y')
                        .' - '.
                        Carbon::parse($end)->translatedFormat('d F Y');


        $totalLaporan = LaporanBBM::whereBetween('tanggal', [$start,$end])->count();

        $totalPermohonan = PermohonanBBM::whereBetween('created_at', [$start,$end])->count();

        $totalLiter = LaporanBBM::whereBetween('tanggal', [$start,$end])
                        ->sum('jumlah_liter');

        $totalBiaya = LaporanBBM::whereBetween('tanggal', [$start,$end])
                        ->sum('total_biaya');


        $kendaraan = LaporanBBM::select(
                'kendaraans.id',
                'kendaraans.no_polisi',
                'kendaraans.merk',
                DB::raw('SUM(laporan_bbms.jumlah_liter) as total_liter'),
                DB::raw('SUM(laporan_bbms.total_biaya) as total_biaya'),
                DB::raw('MIN(laporan_bbms.odometer) as odo_awal'),
                DB::raw('MAX(laporan_bbms.odometer) as odo_akhir'),
                DB::raw('COUNT(laporan_bbms.id) as total_laporan')
            )
            ->join('kendaraans','kendaraans.id','=','laporan_bbms.id_kendaraan')
            ->whereBetween('laporan_bbms.tanggal',[$start,$end])
            ->whereNotNull('laporan_bbms.odometer')
            ->groupBy(
                'kendaraans.id',
                'kendaraans.no_polisi',
                'kendaraans.merk'
            )
            ->get()
            ->map(function($k) use ($start,$end){

                $jarak = $k->odo_akhir - $k->odo_awal;
                $k->jarak_tempuh = $jarak > 0 ? $jarak : 0;

                $literTerakhir = LaporanBBM::where('id_kendaraan',$k->id)
                    ->whereBetween('tanggal',[$start,$end])
                    ->orderBy('tanggal','desc')
                    ->value('jumlah_liter');

                $literAnalisis = $k->total_liter - $literTerakhir;

                if($literAnalisis > 0 && $k->jarak_tempuh > 0){
                    $k->efisiensi = $k->jarak_tempuh / $literAnalisis;
                }else{
                    $k->efisiensi = 0;
                }

                return $k;
            });

        $standarBoros = 8;
        $standarIrit  = 11;

        $standarMinimum = $standarBoros;

        $minimalLiterValid = 10;
        $minimalJarakValid = 100;


        $perbandingan = $kendaraan->map(function($k) use (
            $standarBoros,
            $standarIrit,
            $minimalLiterValid,
            $minimalJarakValid
        ){

            if(
                $k->total_liter < $minimalLiterValid ||
                $k->jarak_tempuh < $minimalJarakValid
            ){
                $k->kategori = 'Belum Cukup Data';
            }
            elseif($k->efisiensi < $standarBoros){
                $k->kategori = 'Boros';
            }
            elseif($k->efisiensi > $standarIrit){
                $k->kategori = 'Irit';
            }
            else{
                $k->kategori = 'Normal';
            }
            return $k;
        });

        $anomali = $perbandingan
            ->filter(function($k) use (
                $standarBoros,
                $minimalLiterValid,
                $minimalJarakValid
            ){

                return
                    $k->total_liter >= $minimalLiterValid &&
                    $k->jarak_tempuh >= $minimalJarakValid &&
                    $k->efisiensi < $standarBoros;

            })
            ->sortBy('efisiensi')
            ->values();


        $chartLabels = $kendaraan->map(function($k){
            return $k->no_polisi . ' - ' . $k->merk;
        });
        $chartData   = $kendaraan->pluck('efisiensi');

        return view('admin.dashboard',[
            'start' => $start,
            'end' => $end,
            'periodeLabel' => $periodeLabel,
            'totalLaporan' => $totalLaporan,
            'totalPermohonan' => $totalPermohonan,
            'totalBiaya' => $totalBiaya,
            'totalLiter' => $totalLiter,
            'kendaraan' => $kendaraan,
            'perbandingan' => $perbandingan,
            'anomali' => $anomali,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'standarBoros' => $standarBoros,
            'standarIrit' => $standarIrit,
            'standarMinimum' => $standarMinimum,
            'minimalLiterValid' => $minimalLiterValid,
            'minimalJarakValid' => $minimalJarakValid
        ]);

    }
}


