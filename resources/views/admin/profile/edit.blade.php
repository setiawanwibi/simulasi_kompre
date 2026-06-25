@extends('layouts.admin')

@section('title','Pengaturan Akun')

@section('content')
<div class="w-full min-h-screen bg-gray-100 p-8">

    <div class="w-full">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Pengaturan Akun
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Kelola informasi akun dan keamanan Anda.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 px-5 py-3 border border-green-400 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 px-5 py-3 border border-red-400 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 px-5 py-3 border border-red-400 bg-red-100 text-red-700 rounded-lg">
                <b>Terjadi kesalahan:</b>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf

        <div class="w-full bg-white border border-gray-300 rounded-2xl shadow-sm">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 p-10">

                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-6 border-b pb-3">
                        Informasi Akun
                    </h3>

                    <div class="space-y-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" name="name"
                                value="{{ $user->name }}"
                                required
                                class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email"
                                value="{{ $user->email }}"
                                required
                                class="w-full px-4 py-3 border border-gray-400 rounded-lg
                                       focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-6 border-b pb-3">
                        Keamanan Password
                    </h3>

                    <div class="space-y-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Lama
                            </label>
                            <div class="relative">
                                <input type="password" name="password_lama"
                                    class="password-field w-full px-4 py-3 border border-gray-400 rounded-lg
                                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="button"
                                    class="toggle-pass absolute right-4 top-3 text-gray-600">
                                    👁
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password_baru"
                                    class="password-field w-full px-4 py-3 border border-gray-400 rounded-lg
                                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="button"
                                    class="toggle-pass absolute right-4 top-3 text-gray-600">
                                    👁
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password_baru_confirmation"
                                    class="password-field w-full px-4 py-3 border border-gray-400 rounded-lg
                                           focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="button"
                                    class="toggle-pass absolute right-4 top-3 text-gray-600">
                                    👁
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="px-6 md:px-10 py-6 border-t border-gray-300 bg-gray-50 rounded-b-2xl flex justify-center md:justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white 
                           px-8 py-3 rounded-lg font-semibold 
                           transition duration-200 shadow 
                           w-full md:w-auto">
                    Simpan 
                </button>
            </div>

        </div>
        </form>

    </div>
</div>


<script>
document.querySelectorAll('.toggle-pass').forEach(btn => {
    btn.addEventListener('click', function () {
        let input = this.parentElement.querySelector('.password-field');
        input.type = input.type === "password" ? "text" : "password";
    });
});
</script>

@endsection
