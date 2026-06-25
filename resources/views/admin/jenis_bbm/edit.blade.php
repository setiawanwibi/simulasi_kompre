@extends('layouts.admin')
@section('title', 'Edit Jenis BBM')

@section('content')
<div class="w-full min-h-screen bg-gray-100 p-8">

    <div class="w-full">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Edit Jenis BBM
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Perbarui informasi jenis bahan bakar.
            </p>
        </div>

        <form action="{{ route('admin.jenis-bbm.update', $jenisbbm->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="w-full bg-white border border-gray-300 rounded-2xl shadow-sm">

                <div class="p-10 grid grid-cols-1 lg:grid-cols-2 gap-10">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Jenis BBM
                        </label>
                        <input type="text"
                               name="nama_jenis"
                               value="{{ $jenisbbm->nama_jenis }}"
                               required
                               class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga per Liter
                        </label>
                        <input type="number"
                               name="harga_per_liter"
                               value="{{ $jenisbbm->harga_per_liter }}"
                               required
                               class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>

                <div class="px-10 py-6 border-t border-gray-300 bg-gray-50 rounded-b-2xl">

                    <div class="flex flex-col items-end gap-3">

                        <a href="{{ route('admin.jenis-bbm.index') }}"
                           class="w-48 text-center px-6 py-2 border border-gray-400 text-gray-700 rounded-lg
                                  hover:bg-gray-100 transition">
                            Batal
                        </a>

                        <button type="submit"
                            class="w-48 bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg
                                   font-semibold shadow transition duration-200">
                            Simpan 
                        </button>

                    </div>

                </div>

            </div>
        </form>

    </div>
</div>
@endsection