@extends('layouts.admin')

@section('title', 'Tambah Jenis BBM')

@section('content')
<div class="w-full bg-gray-100 p-4 md:p-8">

    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            Tambah Jenis BBM
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Tambahkan jenis bahan bakar baru beserta harga per liter.
        </p>
    </div>

    <form action="{{ route('admin.jenis-bbm.store') }}" method="POST">
        @csrf

        <div class="w-full bg-white border border-gray-300 rounded-2xl shadow-sm">

            <div class="p-5 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Jenis BBM
                    </label>
                    <input type="text"
                           name="nama_jenis"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Harga per Liter
                    </label>
                    <input type="number"
                           name="harga_per_liter"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            <div class="px-5 md:px-10 py-4 md:py-6 border-t border-gray-300 bg-gray-50 
                        rounded-b-2xl flex flex-col items-end gap-3">

                <a href="{{ route('admin.jenis-bbm.index') }}"
                   class="px-4 py-2 border border-gray-400 text-gray-700 rounded-lg
                          hover:bg-gray-100 transition text-sm w-full md:w-auto text-center">
                    Batal
                </a>

                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white 
                           px-5 py-2 rounded-lg
                           text-sm font-medium shadow transition duration-200
                           w-full md:w-auto">
                    Simpan
                </button>

            </div>

        </div>
    </form>

</div>
@endsection