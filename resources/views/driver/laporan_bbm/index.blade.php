@extends('layouts.driver')

@section('content')

<div class="space-y-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
            <i class="fa-solid fa-gas-pump text-[#233e8b]"></i>
            Laporan BBM
        </h1>
        <p class="text-sm text-gray-400 mt-1">
            Riwayat laporan pengisian BBM kendaraan
        </p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm">

        <form method="GET" class="grid md:grid-cols-4 gap-4 text-sm">

            <div>
                <label class="block mb-1 font-medium text-gray-600">
                    Tanggal Awal
                </label>
                <input type="date" name="tgl_awal"
                       value="{{ request('tgl_awal') }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#233e8b] focus:outline-none">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-600">
                    Tanggal Akhir
                </label>
                <input type="date" name="tgl_akhir"
                       value="{{ request('tgl_akhir') }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#233e8b] focus:outline-none">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-600">
                    No. Polisi
                </label>
                <input type="text" name="nopol"
                       value="{{ request('nopol') }}"
                       placeholder="Masukkan No Polisi Kendaraan"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#233e8b] focus:outline-none">
            </div>

            <div class="flex items-end gap-2">

                <button type="submit"
                        class="bg-[#233e8b] hover:bg-[#1a2f6b]
                               text-white px-4 py-2 rounded-xl text-sm
                               transition shadow-sm">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i>
                    Cari
                </button>

                <a href="{{ route('driver.laporan.index') }}"
                   class="bg-red-500 hover:bg-red-600
                          text-white px-4 py-2 rounded-xl text-sm
                          transition shadow-sm">
                    Reset
                </a>

            </div>

        </form>

    </div>


    <div class="hidden md:block bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full text-sm text-gray-600">

            <thead class="bg-blue-100 text-blue-800 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Kendaraan</th>
                    <th class="px-4 py-3 text-left">BBM</th>
                    <th class="px-4 py-3 text-left">Liter</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-center w-[160px]">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

            @forelse($laporans as $l)
            <tr class="hover:bg-gray-50 transition">

                <td class="px-4 py-3">
                    {{ $loop->iteration }}
                </td>

                <td class="px-4 py-3">
                    {{ \Carbon\Carbon::parse($l->tanggal)->format('d-m-Y') }}
                </td>

                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800">
                        {{ $l->no_polisi ?? '-' }}
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $l->kendaraan->merk ?? '' }}
                    </div>
                </td>

                <td class="px-4 py-3">
                    {{ $l->nama_jenis_bbm ?? '-' }}
                </td>

                <td class="px-4 py-3">
                    {{ $l->jumlah_liter }} L
                </td>

                <td class="px-4 py-3 font-medium text-gray-800">
                    Rp {{ number_format($l->total_biaya, 0, ',', '.') }}
                </td>

                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-2 text-xs">

                        <a href="{{ route('driver.laporan.show',$l->id) }}"
                           class="bg-green-600 hover:bg-green-700
                                  text-white px-3 py-1.5 rounded-xl
                                  flex items-center gap-1 shadow-sm">
                            <i class="fa fa-arrow-right"></i>
                            Akses
                        </a>

                        <a href="{{ route('driver.laporan.nota',$l->id) }}"
                           class="bg-[#233e8b] hover:bg-[#1a2f6b]
                                  text-white px-3 py-1.5 rounded-xl shadow-sm">
                            <i class="fa fa-file-pdf mr-1"></i>
                            Nota
                        </a>

                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7"
                    class="text-center text-gray-400 py-6">
                    Belum ada laporan.
                </td>
            </tr>
            @endforelse

            </tbody>

        </table>
    </div>


    <div class="md:hidden space-y-4">

        @forelse($laporans as $l)
        <div class="bg-white rounded-2xl shadow-sm p-4 space-y-3">

            {{-- Menggunakan Grid untuk memisahkan konten (kiri) dan tombol (kanan) --}}
            <div class="grid grid-cols-12 gap-2">

                <div class="col-span-8 space-y-2">
                    <div class="font-semibold text-[#233e8b] text-base mb-1">
                        {{ \Carbon\Carbon::parse($l->tanggal)->format('d-m-Y') }}
                    </div>

                    <div class="text-sm text-gray-600">
                        Kendaraan:
                        <span class="font-medium text-gray-800">
                            {{ $l->no_polisi ?? '-' }}
                        </span>
                    </div>

                    <div class="text-xs text-gray-400">
                        {{ $l->kendaraan->merk ?? '-' }}
                    </div>

                    <div class="text-sm text-gray-600">
                        Jenis BBM: 
                        <span class="font-medium text-gray-800">
                            {{ $l->nama_jenis_bbm ?? '-' }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600">
                        Jumlah: 
                        <span class="font-medium text-gray-800">
                            {{ $l->jumlah_liter }} L
                        </span>
                    </div>

                    <div class="text-sm font-semibold text-gray-900 pt-1">
                        Total: Rp {{ number_format($l->total_biaya, 0, ',', '.') }}
                    </div>
                </div>

                <div class="col-span-4 flex flex-col gap-2">
                    <a href="{{ route('driver.laporan.show',$l->id) }}"
                       class="bg-green-600 hover:bg-green-700
                              text-white py-2 text-[11px] rounded-xl
                              flex items-center justify-center gap-1 shadow-sm">
                        <i class="fa fa-arrow-right"></i>
                        Akses
                    </a>
                    
                    <a href="{{ route('driver.laporan.nota',$l->id) }}"
                       class="bg-[#233e8b] hover:bg-[#1a2f6b]
                              text-white py-2 text-[11px] rounded-xl 
                              flex items-center justify-center gap-1 shadow-sm">
                        <i class="fa fa-file-pdf"></i>
                        Nota PDF
                    </a>
                </div>

            </div>

        </div>
        @empty
            <div class="text-center text-gray-400 py-6">
                Belum ada laporan.
            </div>
        @endforelse

    </div>

</div>

@endsection