@extends('layouts.admin')

@section('title','Detail Laporan')

@section('content')

<div class="w-full max-w-6xl mx-auto py-6 md:py-10 px-4 md:px-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 md:mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Detail Laporan BBM
            </h1>
            <p class="text-gray-500 mt-1 text-sm md:text-base">
                Informasi lengkap pengisian bahan bakar kendaraan
            </p>
        </div>

        <div class="flex gap-2 md:gap-3 mt-4 md:mt-0">
            <a href="{{ route('admin.laporan-bbm.nota',$laporan->id) }}"
               target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white 
                      text-sm md:text-base
                      px-3 md:px-5 py-1.5 md:py-2 
                      rounded-lg shadow transition">
                🖨 Cetak Nota
            </a>

            <a href="{{ route('admin.laporan-bbm.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white 
                      text-sm md:text-base
                      px-3 md:px-5 py-1.5 md:py-2 
                      rounded-lg shadow transition">
                ⬅ Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6 space-y-4">

                <h2 class="text-base md:text-lg font-semibold text-gray-700 border-b pb-2">
                    Informasi Transaksi
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">

                    <div>
                        <p class="text-gray-500">Tanggal</p>
                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d-m-Y') }}
                        </p>
                    </div>

                <div>
                    <p class="text-gray-500">Penginput</p>

                    <p class="font-semibold">
                        @if($laporan->nama_admin)
                            {{ $laporan->nama_admin }} (Admin)

                        @elseif($laporan->admin)
                            {{ $laporan->admin->name }} (Admin)

                        @elseif($laporan->nama_driver)
                            {{ $laporan->nama_driver }} (Driver)

                        @elseif($laporan->driver)
                            {{ $laporan->driver->name }} (Driver)

                        @else
                            -
                        @endif
                    </p>
                </div>

                    <div>
                        <p class="text-gray-500">Driver</p>
                        <p class="font-semibold">{{ $laporan->nama_driver ?? $laporan->driver?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Kendaraan</p>
                        <p class="font-semibold">{{ $laporan->no_polisi ?? $laporan->kendaraan?->no_polisi ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jenis BBM</p>
                        <p class="font-semibold">{{ $laporan->nama_jenis_bbm ?? $laporan->jenisBBM?->nama_jenis ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jumlah Liter</p>
                        <p class="font-semibold">{{ $laporan->jumlah_liter }} L</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Harga per Liter</p>
                        <p class="font-semibold">
                            Rp {{ number_format($laporan->harga_per_liter) }}
                        </p>
                    </div>

                </div>

                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-4">
                    <p class="text-sm text-green-700">Estimasi Total Biaya</p>
                    <p class="text-xl md:text-2xl font-bold text-green-800">
                        Rp {{ number_format($laporan->total_biaya) }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm mt-4">Keterangan Admin</p>
                    <p class="text-gray-700">
                        {{ $laporan->keterangan_admin ?? '-' }}
                    </p>
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6 space-y-4">

                <h2 class="text-base md:text-lg font-semibold text-gray-700 border-b pb-2">
                    Odometer Kendaraan
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-gray-500">Odometer Saat Isi</p>
                        <p class="font-semibold text-gray-800">
                            {{ $laporan->odometer ?? '-' }} km
                        </p>
                    </div>

                    @if(isset($odometer_sebelumnya))
                    <div>
                        <p class="text-gray-500">Odometer Sebelumnya</p>
                        <p class="font-semibold text-gray-800">
                            {{ $odometer_sebelumnya }} km
                            <span class="text-xs text-gray-500">
                                ({{ $tanggal_odometer_sebelumnya }})
                            </span>
                        </p>
                    </div>
                    @endif

                </div>

                @if(isset($selisih_km))
                <div class="mt-4">
                    <p class="text-gray-500 text-sm mb-1">
                        Pemakaian Sejak Pengisian Terakhir
                    </p>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="text-lg md:text-xl font-bold text-blue-700">
                            {{ $selisih_km }} km
                        </p>
                    </div>
                </div>
                @endif

                @if(isset($selisih_km) && $selisih_km > 1000)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded text-sm">
                    ⚠ Perhatian: Selisih odometer cukup jauh sejak pengisian terakhir.
                </div>
                @endif

            </div>

        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6 space-y-3">
                <h2 class="text-base md:text-lg font-semibold text-gray-700 border-b pb-2">
                    Catatan Driver
                </h2>

                <div>
                    <p class="text-gray-500 text-sm">Catatan Driver</p>
                    <p class="text-gray-700 mt-1 text-sm md:text-base">
                        {{ $laporan->catatan_driver ?? 'Tidak ada catatan dari driver' }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6 space-y-3">
                <h2 class="text-base md:text-lg font-semibold text-gray-700 border-b pb-2">
                    Foto Bukti 
                </h2>

                @if($laporan->foto_driver)
                    <img src="{{ asset('storage/'.$laporan->foto_driver) }}"
                         class="w-full rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                @else
                    <p class="text-gray-500 text-sm">
                        Tidak ada foto
                    </p>
                @endif
            </div>

        </div>

    </div>

</div>

@endsection