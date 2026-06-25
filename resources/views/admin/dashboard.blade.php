@extends('layouts.admin')
@section('title','Dashboard')

@section('content')

<div class="w-full max-w-full overflow-x-hidden">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Ringkasan Penggunaan BBM</h2>
        <p class="text-sm text-gray-500 mt-1">
            Periode: 
            {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }}
            -
            {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
        </p>
    </div>

    <form method="GET" class="bg-white p-4 rounded-xl shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3 sm:items-end">
            <div>
                <label class="text-xs text-gray-500">Tanggal Awal</label>
                <input type="date" name="start" value="{{ $start }}"
                       class="w-full sm:w-auto border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-xs text-gray-500">Tanggal Akhir</label>
                <input type="date" name="end" value="{{ $end }}"
                       class="w-full sm:w-auto border rounded-lg px-3 py-2 text-sm">
            </div>

            <button class="w-full sm:w-auto bg-blue-500 text-white px-4 py-2 rounded-lg text-sm">
                Terapkan
            </button>

            <a href="{{ route('admin.dashboard') }}"
               class="w-full sm:w-auto bg-red-500 text-white px-4 py-2 rounded-lg text-sm text-center">
                Reset
            </a>

            <button class="w-full sm:w-auto bg-green-500 text-white px-4 py-2 rounded-lg text-sm"
                    formaction="{{ route('admin.laporan-bbm.index') }}">
                Laporan BBM
            </button>

        </div>
    </form>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-xs text-gray-500">Total Laporan</p>
            <h3 class="text-xl font-bold text-gray-700">{{ $totalLaporan }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-xs text-gray-500">Total Permohonan</p>
            <h3 class="text-xl font-bold text-gray-700">{{ $totalPermohonan }}</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4">
            <p class="text-xs text-gray-500">Total Pengeluaran</p>
            <h3 class="text-lg font-bold text-gray-700">Rp {{ number_format($totalBiaya) }}</h3>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">Total  Liter</p>
            <h3 class="text-lg font-bold text-gray-700">{{ number_format($totalLiter,0) }} L</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
        <h3 class="font-semibold text-gray-700 mb-2">Penggunaan BBM</h3>
        <p class="text-xs text-gray-500 mb-3">Top 10 kendaraan dengan penggunaan BBM terefisien.</p>
        <canvas id="efisiensiChart" class="w-full"></canvas>
    </div>

<style>
#efisiensiChart{height:300px !important;}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('efisiensiChart'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'KM/L',
            data: @json($chartData),
            backgroundColor: '#3b82f6'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

@php
$normal = $perbandingan->where('kategori','Normal')->count();
$irit = $perbandingan->where('kategori','Irit')->count();
$boros = $perbandingan->where('kategori','Boros')->count();
@endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center gap-3">
            <div class="text-xl">🚗</div>
            <div>
                <p class="text-sm text-gray-500">Kendaraan Normal</p>
                <h3 class="font-bold text-lg">{{ $normal }} Unit</h3>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <div class="text-xl">🚙</div>
            <div>
                <p class="text-sm text-gray-500">Kendaraan Irit</p>
                <h3 class="font-bold text-lg">{{ $irit }} Unit</h3>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <div class="text-xl">🚘</div>
            <div>
                <p class="text-sm text-gray-500">Kendaraan Boros</p>
                <h3 class="font-bold text-lg">{{ $boros }} Unit</h3>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
        <h3 class="font-semibold text-gray-700 mb-1">Deteksi Anomali Efisiensi</h3>
        <p class="text-xs text-gray-500 mb-4">Kendaraan dengan efisiensi di bawah standar minimal operasional kendaraan</p>

        @if($anomali->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($anomali as $a)
                    <div class="p-4 rounded-xl border bg-red-50 border-red-200">
                    <div class="font-bold text-gray-700">{{ $a->no_polisi }}</div>
                    <div class="text-sm text-gray-500 mb-2">{{ $a->merk }}</div>
                    <div class="text-sm text-gray-600">Konsumsi BBM: {{ number_format($a->efisiensi,2) }} KM/L</div>
                    <div class="text-sm text-gray-600">Standar Minimum: {{ number_format($standarMinimum,2) }} KM/L</div>
                    <div class="mt-2 font-bold text-red-600">⚠ BOROS</div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-sm text-gray-500">✅ Tidak ada kendaraan yang berada di bawah standar efisiensi.</div>
        @endif
    </div>

    <div class="bg-blue-50 rounded-xl shadow-sm p-5 mb-6 border border-blue-100">
        <div class="mb-4">
            <h3 class="font-semibold text-gray-700">Perbandingan Seluruh Kendaraan</h3>

            <p class="text-xs text-gray-500 mt-1">
                Analisis efisiensi dilakukan jika kendaraan memiliki minimal 
                {{ $minimalLiterValid }} liter BBM dan jarak tempuh minimal 
                {{ $minimalJarakValid }} KM.
            </p>
            <p class="text-xs text-gray-500 mt-1"> Catatan: Jarak tempuh dihitung berdasarkan selisih odometer antar laporan BBM, sehingga pengisian BBM terakhir belum termasuk dalam analisis.</p>
        </div>

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">No Polisi</th>
                        <th class="p-3 text-left">Merk</th>
                        <th class="p-3 text-center">Total Liter</th>
                        <th class="p-3 text-center">Jarak Tempuh</th>
                        <th class="p-3 text-center font-bold">Efisiensi (KM/L)</th>
                        <th class="p-3 text-center">Total Biaya</th>
                        <th class="p-3 text-center">Kategori</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($perbandingan as $i => $k)
                    <tr class="border-b hover:bg-blue-50 transition">
                        <td class="p-3">{{ $i+1 }}</td>
                        <td class="p-3">{{ $k->no_polisi }}</td>
                        <td class="p-3">{{ $k->merk }}</td>
                        <td class="p-3 text-center">{{ number_format($k->total_liter,0) }} L</td>
                        <td class="p-3 text-center">{{ number_format($k->jarak_tempuh,0) }} KM</td>
                        <td class="p-3 text-center font-bold">{{ number_format($k->efisiensi,2) }}</td>
                        <td class="p-3 text-center">Rp. {{ number_format($k->total_biaya) }}
                        <td class="p-3 text-center">
                        @if($k->kategori=='Boros')
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs">Boros</span>
                        @elseif($k->kategori=='Irit')
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs">Irit</span>
                        @elseif($k->kategori=='Belum Cukup Data')
                            <span class="bg-gray-400 text-white px-3 py-1 rounded-full text-xs">Belum Cukup Data</span>
                        @else
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs">Normal</span>
                        @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
