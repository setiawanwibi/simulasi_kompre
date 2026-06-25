@extends('layouts.admin')

@section('title', 'Manajemen Akun')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-user text-[#233e8b]"></i>
                Manajemen Akun
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                Kelola akun admin dan driver yang terdaftar dalam sistem
            </p>
        </div>

        <a href="{{ route('admin.akun.create') }}"
        class="bg-green-600 hover:bg-green-700
                text-white px-5 py-2.5 rounded-xl text-sm
                flex items-center justify-center gap-2
                transition shadow-sm w-full md:w-auto">
            <i class="fa-solid fa-plus"></i>
            Tambah Akun
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

    <div class="hidden md:block bg-white rounded-2xl shadow-sm overflow-x-auto">

        <table class="min-w-full text-sm text-gray-600">

            <thead class="bg-blue-100 text-blue-800 uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">No</th>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-center">Role</th>
                    <th class="px-6 py-3 text-center w-32">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-4">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $user->name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $user->email }}
                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($user->role == 'admin')
                            <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                                Admin
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                                Driver
                            </span>
                        @endif

                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center items-center gap-4 text-lg">

                            <a href="{{ route('admin.akun.edit', $user->id) }}"
                               class="text-yellow-500 hover:text-yellow-700 transition"
                               title="Edit">
                                <i class="fa fa-pen"></i>
                            </a>

                            <form action="{{ route('admin.akun.destroy', $user->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Yakin hapus akun ini?')"
                                    class="text-red-500 hover:text-red-700 transition"
                                    title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="5"
                        class="text-center text-gray-400 py-8">
                        Belum ada data akun.
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>
    </div>

    <div class="md:hidden space-y-4">

        @forelse($users as $user)
        <div class="bg-white rounded-2xl shadow-sm p-5 space-y-3 border border-gray-100">

            <div class="flex justify-between items-start">

                <div>
                    <div class="font-semibold text-[#233e8b] text-base">
                        {{ $user->name }}
                    </div>

                    <div class="text-sm text-gray-500 mt-1">
                        {{ $user->email }}
                    </div>

                    <div class="mt-2">

                        @if($user->role == 'admin')
                            <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                                Admin
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                                Driver
                            </span>
                        @endif

                    </div>
                </div>

                <div class="flex gap-4 text-lg">

                    <a href="{{ route('admin.akun.edit', $user->id) }}"
                       class="text-yellow-500 hover:text-yellow-700 transition">
                        <i class="fa fa-pen"></i>
                    </a>

                    <form action="{{ route('admin.akun.destroy', $user->id) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button
                            onclick="return confirm('Yakin hapus akun ini?')"
                            class="text-red-500 hover:text-red-700 transition">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>

                </div>

            </div>

        </div>

        @empty
            <div class="text-center text-gray-400 py-8">
                Belum ada data akun.
            </div>
        @endforelse

    </div>

</div>

<script>
    setTimeout(() => {
        const success = document.getElementById('alert-success');
        const error = document.getElementById('alert-error');

        if (success) success.style.display = 'none';
        if (error) error.style.display = 'none';
    }, 3000);
</script>

@endsection