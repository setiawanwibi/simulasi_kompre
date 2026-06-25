@extends('layouts.admin')

@section('title', 'Tambah Kendaraan')

@section('content')
<div class="w-full min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            Tambah Kendaraan
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Tambahkan data kendaraan baru ke dalam sistem.
        </p>
    </div>

    <form action="{{ route('admin.kendaraan.store') }}" method="POST">
        @csrf

        <div class="w-full bg-white border border-gray-300 rounded-2xl shadow-sm">

            <div class="p-5 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        No Polisi
                    </label>

                    <input type="text"
                           name="no_polisi"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Merk
                    </label>

                    <input type="text"
                           name="merk"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis
                    </label>

                    <input type="text"
                           name="jenis"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun
                    </label>

                    <input type="number"
                           name="tahun"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Odometer
                    </label>

                    <input type="number"
                           name="odometer_terakhir"
                           min="0"
                           value="0"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Jenis BBM yang Diizinkan
                    </label>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                        @foreach($jenisbbm as $j)

                        <label class="flex items-center gap-2 border rounded-lg px-3 py-2 hover:bg-gray-50">

                            <input type="checkbox"
                                   name="jenisbbm[]"
                                   value="{{ $j->id }}"
                                   class="rounded text-blue-600">

                            <span class="text-sm text-gray-700">
                                {{ $j->nama_jenis }}
                            </span>

                        </label>

                        @endforeach

                    </div>

                </div>

            </div>

            <div class="px-5 md:px-10 py-4 md:py-6 border-t border-gray-300 bg-gray-50 
                        rounded-b-2xl flex flex-col items-end gap-3">

                <a href="{{ route('admin.kendaraan.index') }}"
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