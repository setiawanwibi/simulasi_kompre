@extends('layouts.admin')

@section('title', 'Data Kendaraan')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-car text-[#233e8b]"></i>
                Data Kendaraan
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                Data kendaraan operasional PLN UP3 Tanjung Karang
            </p>
        </div>

        <a href="{{ route('admin.kendaraan.create') }}"
           class="bg-green-600 hover:bg-green-700
                  text-white px-4 py-2 rounded-xl text-sm
                  flex items-center justify-center gap-2
                  transition shadow-sm w-full md:w-auto">
            <i class="fa-solid fa-plus"></i>
            Tambah Kendaraan
        </a>

    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif


    <div class="bg-white shadow-sm rounded-2xl overflow-hidden hidden md:block">

        <table class="w-full text-sm text-gray-600">

            <thead class="bg-blue-100 text-blue-800 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">No Polisi</th>
                    <th class="px-4 py-3 text-left">Merk</th>
                    <th class="px-4 py-3 text-left">Jenis</th>
                    <th class="px-4 py-3 text-left">Tahun</th>
                    <th class="px-4 py-3 text-left">Odometer</th>
                    <th class="px-4 py-3 text-center w-[120px]">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse ($kendaraans as $kendaraan)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $kendaraan->no_polisi }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $kendaraan->merk }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $kendaraan->jenis }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $kendaraan->tahun }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $kendaraan->odometer_terakhir ?? 0 }} km
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-3 text-base">

                            <a href="{{ route('admin.kendaraan.edit', $kendaraan->id) }}"
                               class="text-yellow-500 hover:text-yellow-700"
                               title="Edit">
                                <i class="fa fa-pen"></i>
                            </a>

                            <form action="{{ route('admin.kendaraan.destroy', $kendaraan->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700"
                                    title="Hapus">
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
                        Belum ada data kendaraan.
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>


    <div class="space-y-4 md:hidden">

        @forelse ($kendaraans as $kendaraan)
        <div class="bg-white shadow-sm rounded-2xl p-4 border">

            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-xs text-gray-400">No Polisi</p>
                    <p class="font-semibold text-gray-800">
                        {{ $kendaraan->no_polisi }}
                    </p>
                </div>

                <div class="flex gap-3 text-base">
                    <a href="{{ route('admin.kendaraan.edit', $kendaraan->id) }}"
                       class="text-yellow-500">
                        <i class="fa fa-pen"></i>
                    </a>

                    <form action="{{ route('admin.kendaraan.destroy', $kendaraan->id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-500">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

        <div class="grid grid-cols-1 gap-3 text-sm text-gray-600">

            <div>
                <p class="text-xs text-gray-400">Merk</p>
                <p>{{ $kendaraan->merk }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Jenis Kendaraan</p>
                <p>{{ $kendaraan->jenis }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-400">Tahun</p>
                <p>{{ $kendaraan->tahun }}</p>
            </div>
            
            <div>
                <p class="text-xs text-gray-400">Odometer</p>
                <p>{{ $kendaraan->odometer_terakhir ?? 0 }} km</p>
            </div>
        </div>
        
    </div>

        @empty
            <div class="text-center text-gray-400 py-6">
                Belum ada data kendaraan.
            </div>
        @endforelse

    </div>

</div>
@endsection