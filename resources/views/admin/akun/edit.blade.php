@extends('layouts.admin')

@section('title', 'Edit Akun')

@section('content')
<div class="w-full min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            Edit Akun
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Perbarui informasi akun pengguna dalam sistem.
        </p>
    </div>

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


    <form action="{{ route('admin.akun.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="w-full bg-white border border-gray-300 rounded-2xl shadow-sm">

            <div class="p-5 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ $user->name }}"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ $user->email }}"
                           required
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password (Opsional)
                    </label>
                    <input type="password"
                           name="password"
                           placeholder="Kosongkan jika tidak ingin mengganti password"
                           class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>

                    <input type="text"
                           value="{{ ucfirst($user->role) }}"
                           disabled
                           class="w-full px-4 py-2.5 md:py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                </div>

            </div>

            <div class="px-5 md:px-10 py-4 md:py-6 border-t border-gray-300 bg-gray-50 
                        rounded-b-2xl flex flex-col items-end gap-3">

                <a href="{{ route('admin.akun.index') }}"
                   class="px-4 py-2 border border-gray-400 text-gray-700 rounded-lg
                          hover:bg-gray-100 transition text-sm w-full md:w-auto text-center">
                    ← Kembali
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