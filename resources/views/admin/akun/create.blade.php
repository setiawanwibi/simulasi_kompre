@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('content')
<div class="w-full min-h-screen bg-gray-100 p-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Tambah Akun
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Buat akun baru untuk admin atau driver dalam sistem.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 px-5 py-3 border border-green-400 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-5 py-3 border border-red-400 bg-red-100 text-red-700 rounded-lg">
            <b>Terjadi kesalahan:</b>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('admin.akun.store') }}" method="POST">
        @csrf

        <div class="w-full bg-white border border-gray-300 rounded-2xl shadow-sm">

            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama
                    </label>
                    <input type="text"
                           name="name"
                           required
                           class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           required
                           class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>

                    <select name="role"
                            required
                            class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="driver">Driver</option>

                    </select>
                </div>

            </div>

            <div class="px-10 py-6 border-t border-gray-300 bg-gray-50 rounded-b-2xl 
                        flex flex-col items-end gap-3">

                <a href="{{ route('admin.akun.index') }}"
                class="px-6 py-2 border border-gray-400 text-gray-700 rounded-lg
                        hover:bg-gray-100 transition w-full md:w-auto text-center">
                    ← Kembali
                </a>

                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg
                        font-semibold shadow transition duration-200
                        w-full md:w-auto">
                    Simpan
                </button>

            </div>

        </div>
    </form>

</div>
@endsection