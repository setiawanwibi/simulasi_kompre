@extends('layouts.driver')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

        <div>
            <h1 class="text-lg sm:text-xl font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-file-circle-check text-[#233e8b]"></i>
                Riwayat Permohonan
            </h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">
                Daftar permohonan pengisian BBM yang telah diajukan
            </p>
        </div>

        <a href="{{ route('driver.permohonan.create') }}"
           class="bg-green-600 hover:bg-green-700
                  text-white px-4 py-2 rounded-xl text-sm
                  flex items-center justify-center gap-2
                  transition shadow-sm w-full sm:w-auto">
            <i class="fa-solid fa-plus"></i>
            Buat Permohonan
        </a>

    </div>

    @if(session('success'))
        <div id="alert-success"
            class="w-full px-6 py-4 rounded-xl text-sm shadow-sm border
            {{ str_contains(session('success'), 'dihapus') 
                ? 'bg-red-100 text-red-700 border-red-400' 
                : 'bg-green-100 text-green-700 border-green-400' }}">
            
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="alert-error"
            class="w-full px-6 py-4 rounded-xl text-sm shadow-sm border
                bg-red-100 text-red-700 border-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-2xl shadow-sm">

        <form method="GET"
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">

            <div>
                <label class="block mb-1 font-medium text-gray-600">
                    Tanggal Awal
                </label>
                <input type="date" name="tgl_awal"
                       value="{{ request('tgl_awal') }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2
                              focus:ring-2 focus:ring-[#233e8b] focus:outline-none">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-600">
                    Tanggal Akhir
                </label>
                <input type="date" name="tgl_akhir"
                       value="{{ request('tgl_akhir') }}"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2
                              focus:ring-2 focus:ring-[#233e8b] focus:outline-none">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-600">
                    No. Polisi
                </label>
                <input type="text" name="nopol"
                       value="{{ request('nopol') }}"
                       placeholder="Masukkan No Polisi"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2
                              focus:ring-2 focus:ring-[#233e8b] focus:outline-none">
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2">

                <button type="submit"
                        class="bg-[#233e8b] hover:bg-[#1a2f6b]
                               text-white px-4 py-2 rounded-xl text-sm
                               transition shadow-sm w-full sm:w-auto">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i>
                    Cari
                </button>

                <a href="{{ route('driver.permohonan.index') }}"
                   class="bg-red-500 hover:bg-red-600
                          text-white px-4 py-2 rounded-xl text-sm
                          transition shadow-sm text-center w-full sm:w-auto">
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
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center w-[120px]">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

            @forelse($data as $d)
            <tr class="hover:bg-gray-50 transition">

                <td class="px-4 py-3">{{ $loop->iteration }}</td>

                <td class="px-4 py-3">
                    {{ \Carbon\Carbon::parse($d->tanggal_permohonan)->format('d-m-Y') }}
                </td>

                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800">
                        {{ $d->no_polisi ?? $d->kendaraan?->no_polisi ?? '-' }}
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $d->kendaraan->merk ?? '' }}
                    </div>
                </td>

                <td class="px-4 py-3">
                    {{ $d->nama_jenis_bbm ?? $d->jenisbbm?->nama_jenis ?? '-' }}
                </td>

                <td class="px-4 py-3">
                    {{ $d->jumlah_liter }} L
                </td>

                <td class="px-4 py-3">
                    @if($d->status == 'Pending')
                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                            Pending
                        </span>
                    @elseif($d->status == 'Disetujui')
                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                            Disetujui
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                            Ditolak
                        </span>
                    @endif
                </td>

                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-3 text-base">

                        <a href="{{ route('driver.permohonan.show',$d->id) }}"
                           class="text-[#233e8b] hover:text-[#1a2f6b] transition">
                            <i class="fa fa-eye"></i>
                        </a>

                        <a href="{{ $d->status == 'Pending' ? route('driver.permohonan.edit',$d->id) : '#' }}"
                           class="{{ $d->status == 'Pending'
                                    ? 'text-yellow-500 hover:text-yellow-700'
                                    : 'text-gray-400 pointer-events-none cursor-not-allowed' }}">
                            <i class="fa fa-pen"></i>
                        </a>

                        <form action="{{ route('driver.permohonan.destroy',$d->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                {{ $d->status != 'Pending' ? 'disabled' : '' }}
                                onclick="return confirm('Hapus data ini?')"
                                class="{{ $d->status == 'Pending'
                                        ? 'text-red-500 hover:text-red-700'
                                        : 'text-gray-400 cursor-not-allowed' }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7"
                    class="text-center text-gray-400 py-6">
                    Belum ada data.
                </td>
            </tr>
            @endforelse

            </tbody>
        </table>
    </div>


    <div class="md:hidden space-y-4">

        @forelse($data as $d)
        <div class="bg-white rounded-2xl shadow-sm p-4 space-y-3">

            <div class="flex justify-between items-start">

                <div>
                    <div class="font-semibold text-[#233e8b] text-sm">
                        {{ \Carbon\Carbon::parse($d->tanggal_permohonan)->format('d-m-Y') }}
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $d->no_polisi ?? $d->kendaraan?->no_polisi ?? '-' }}
                    </div>
                </div>

                <div class="flex gap-3 text-base">

                    <a href="{{ route('driver.permohonan.show',$d->id) }}"
                       class="text-[#233e8b]">
                        <i class="fa fa-eye"></i>
                    </a>

                    <a href="{{ $d->status == 'Pending' ? route('driver.permohonan.edit',$d->id) : '#' }}"
                       class="{{ $d->status == 'Pending'
                                ? 'text-yellow-500'
                                : 'text-gray-400 pointer-events-none' }}">
                        <i class="fa fa-pen"></i>
                    </a>

                    <form action="{{ route('driver.permohonan.destroy',$d->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            {{ $d->status != 'Pending' ? 'disabled' : '' }}
                            class="{{ $d->status == 'Pending'
                                    ? 'text-red-500'
                                    : 'text-gray-400' }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>

                </div>
            </div>

            <div class="text-sm text-gray-600">
                Kendaraan:
                <span class="font-medium">
                    {{ $d->kendaraan->merk ?? '-' }}
                </span>
            </div>

            <div class="text-sm">
                Jenis BBM:
                <span class="font-medium">
                    {{ $d->nama_jenis_bbm ?? $d->jenisbbm?->nama_jenis ?? '-' }}
                </span>
            </div>

            <div class="text-sm">
                Jumlah Liter:
                <span class="font-medium">
                    {{ $d->jumlah_liter }} L
                </span>
            </div>

            <div class="text-sm">
                Status:
                @if($d->status == 'Pending')
                    <span class="text-yellow-600 font-medium">Pending</span>
                @elseif($d->status == 'Disetujui')
                    <span class="text-green-600 font-medium">Disetujui</span>
                @else
                    <span class="text-red-600 font-medium">Ditolak</span>
                @endif
            </div>

        </div>
        @empty
            <div class="text-center text-gray-400 py-6">
                Belum ada data.
            </div>
        @endforelse

    </div>

</div>

@endsection