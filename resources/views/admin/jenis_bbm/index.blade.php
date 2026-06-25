@extends('layouts.admin')
@section('title', 'Jenis BBM')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-gas-pump text-[#233e8b]"></i>
                Data Jenis BBM
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                Kelola data jenis bahan bakar dan harga per liter
            </p>
        </div>

        <a href="{{ route('admin.jenis-bbm.create') }}"
           class="bg-green-600 hover:bg-green-700
                  text-white px-4 py-2 rounded-xl text-sm
                  flex items-center gap-2 transition shadow-sm
                  w-full md:w-auto justify-center">
            <i class="fa-solid fa-plus"></i>
            Tambah Jenis BBM
        </a>

    </div>
 
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif


    <div class="hidden md:block bg-white shadow-sm rounded-2xl overflow-hidden">

        <table class="w-full text-sm text-gray-600">

            <thead class="bg-blue-100 text-blue-800 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Jenis</th>
                    <th class="px-4 py-3 text-left">Harga / Liter</th>
                    <th class="px-4 py-3 text-center w-[120px]">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse ($jenisbbms as $item)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $item->nama_jenis }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        Rp {{ number_format($item->harga_per_liter, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-3 text-base">

                            <a href="{{ route('admin.jenis-bbm.edit', $item->id) }}"
                               class="text-yellow-500 hover:text-yellow-700"
                               title="Edit">
                                <i class="fa fa-pen"></i>
                            </a>

                            <form action="{{ route('admin.jenis-bbm.destroy', $item->id) }}"
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
                    <td colspan="4" class="text-center text-gray-400 py-6">
                        Belum ada data jenis BBM
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>


    <div class="md:hidden space-y-4">

        @forelse ($jenisbbms as $item)
        <div class="bg-white shadow-sm rounded-2xl p-4">

            <div class="flex justify-between items-start">

                <div>
                    <div class="font-medium text-gray-800">
                        {{ $item->nama_jenis }}
                    </div>
                    <div class="text-sm text-gray-500 mt-1">
                        Rp {{ number_format($item->harga_per_liter, 0, ',', '.') }} / Liter
                    </div>
                </div>

                <div class="flex gap-4 text-base">

                    <a href="{{ route('admin.jenis-bbm.edit', $item->id) }}"
                       class="text-yellow-500">
                        <i class="fa fa-pen"></i>
                    </a>

                    <form action="{{ route('admin.jenis-bbm.destroy', $item->id) }}"
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

        </div>

        @empty
            <div class="text-center text-gray-400 py-6">
                Belum ada data jenis BBM
            </div>
        @endforelse

    </div>

</div>

@endsection