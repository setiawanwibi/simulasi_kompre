@extends('layouts.admin')

@section('title', 'Laporan BBM')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-file-lines text-[#233e8b]"></i>
                Data Laporan BBM
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                Kelola seluruh laporan pengisian BBM kendaraan
            </p>
        </div>

        <a href="{{ route('admin.laporan-bbm.create') }}"
           class="w-full md:w-auto bg-green-600 hover:bg-green-700
                  text-white px-4 py-2 rounded-xl text-sm
                  flex items-center justify-center gap-2 transition shadow-sm">
            <i class="fa-solid fa-plus"></i>
            Buat Laporan
        </a>

    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif

    <form method="GET" class="bg-white shadow-sm rounded-2xl p-4">

        <div class="grid md:grid-cols-6 gap-3 text-sm">

            <div>
                <label class="text-gray-600">Tanggal Awal</label>
                <input type="date" name="start" value="{{ request('start') }}"
                    class="w-full border rounded-xl px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-gray-600">Tanggal Akhir</label>
                <input type="date" name="end" value="{{ request('end') }}"
                    class="w-full border rounded-xl px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-gray-600">Driver</label>
                <select name="driver" class="w-full border rounded-xl px-3 py-2 mt-1">
                    <option value="">Semua</option>
                    @foreach($drivers as $d)
                    <option value="{{ $d->id }}" {{ request('driver')==$d->id?'selected':'' }}>
                        {{ $d->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-gray-600">No Polisi</label>
                <select name="nopol" class="w-full border rounded-xl px-3 py-2 mt-1">
                    <option value="">Semua</option>
                    @foreach($kendaraans as $k)
                    <option value="{{ $k->id }}" {{ request('nopol')==$k->id?'selected':'' }}>
                        {{ $k->no_polisi }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-6">
                <label class="text-gray-600">Cari Driver / No Polisi</label>

                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full border rounded-xl px-3 py-2 mt-1"
                    placeholder="Masukkan Nama Driver atau No Polisi">

                <div class="flex flex-col md:flex-row gap-2 mt-3">

                    <button type="submit"
                        class="w-full md:w-auto bg-[#233e8b] hover:bg-[#1b3272]
                               text-white px-4 py-2 rounded-xl text-sm
                               flex items-center justify-center gap-2 transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Cari
                    </button>

                    <a href="{{ route('admin.laporan-bbm.index') }}"
                        class="w-full md:w-auto bg-red-500 hover:bg-red-600
                            text-white px-4 py-2 rounded-xl text-sm
                            flex items-center justify-center gap-2 transition">
                        <i class="fa-solid fa-rotate"></i>
                        Reset
                    </a>

                </div>
            </div>

        </div>
    </form>

    <div>
        <a href="{{ route('admin.laporan-bbm.index', array_merge(request()->all(), ['export'=>'pdf'])) }}"
           class="bg-red-700 hover:bg-red-800
                  text-white px-4 py-2 rounded-xl text-sm
                  flex items-center gap-2 w-fit transition shadow-sm">
            <i class="fa-solid fa-file-pdf"></i>
            Export PDF
        </a>
    </div>

    <div class="hidden md:block bg-white shadow-sm rounded-2xl overflow-hidden">

        <table class="w-full text-sm text-gray-600">

            <thead class="bg-blue-100 text-blue-800 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Admin</th>
                    <th class="px-4 py-3 text-left">Driver</th>
                    <th class="px-4 py-3 text-left">No Polisi</th>
                    <th class="px-4 py-3 text-left">Liter</th>
                    <th class="px-4 py-3 text-left">Odometer</th>
                    <th class="px-4 py-3 text-left">Keterangan</th>
                    <th class="px-4 py-3 text-center w-[140px]">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($laporans as $laporan)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">{{ ($laporans->currentPage() - 1) * $laporans->perPage() + $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d-m-Y') }}</td>
                    <td class="px-4 py-3">{{ $laporan->admin?->name ?? $laporan->nama_admin ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $laporan->driver?->name ?? $laporan->nama_driver ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $laporan->no_polisi ?? '-' }}</td>
                    <td class="px-4 py-3">{{ rtrim(rtrim(number_format($laporan->jumlah_liter,2,'.',''), '0'), '.') }} L</td>
                    <td class="px-4 py-3">{{ $laporan->odometer ? $laporan->odometer.' km' : '-' }}</td>
                    <td class="px-4 py-3">{{ $laporan->keterangan_admin ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.laporan-bbm.show',$laporan->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fa fa-eye"></i>
                            </a>
                            @php
                                $canEdit =
                                    auth()->user()->role === 'super_admin' ||
                                    $laporan->id_admin === auth()->id();
                            @endphp

                            @if($canEdit)
                            <a href="{{ route('admin.laporan-bbm.edit',$laporan->id) }}"
                            class="text-yellow-500 hover:text-yellow-700">
                                <i class="fa fa-pen"></i>
                            </a>
                            @else
                            <button disabled
                                    class="text-gray-300 cursor-not-allowed">
                                <i class="fa fa-pen"></i>
                            </button>
                            @endif
                            @php
                                $canDelete =
                                    auth()->user()->role === 'super_admin' ||
                                    $laporan->id_admin === auth()->id();
                            @endphp

                            @if($canDelete)
                            <form action="{{ route('admin.laporan-bbm.destroy',$laporan->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Hapus data ini?')"
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <button disabled
                                    class="text-gray-300 cursor-not-allowed">
                                <i class="fa fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-6 text-gray-400">
                        Belum ada data laporan BBM
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $laporans->links() }}
    </div>

    <div class="md:hidden space-y-4">
        @forelse ($laporans as $laporan)
        <div class="bg-white shadow-sm rounded-2xl p-4 text-sm space-y-2">

            <div class="flex justify-between">
                <span class="font-semibold text-gray-700">
                    {{ $laporan->driver?->name ?? $laporan->nama_driver ?? '-' }}
                </span>
                <span class="text-gray-400">
                    {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d-m-Y') }}
                </span>
            </div>

            <div class="text-gray-600">
                <div><strong>No Polisi:</strong> {{ $laporan->kendaraan?->no_polisi ?? $laporan->no_polisi ?? '-' }}</div>
                <div><strong>Liter:</strong> {{ rtrim(rtrim(number_format($laporan->jumlah_liter,2,'.',''), '0'), '.') }} L</div>
                <div><strong>Odometer:</strong> {{ $laporan->odometer ? $laporan->odometer.' km' : '-' }}</div>
                <div><strong>Keterangan:</strong> {{ $laporan->keterangan_admin ?? '-' }}</div>
            </div>

            <div class="flex justify-end gap-4 pt-2 text-base">
                <a href="{{ route('admin.laporan-bbm.show',$laporan->id) }}" class="text-blue-600">
                    <i class="fa fa-eye"></i>
                </a>
                @php
                    $canEdit =
                        auth()->user()->role === 'super_admin' ||
                        $laporan->id_admin === auth()->id();
                @endphp

                @if($canEdit)
                <a href="{{ route('admin.laporan-bbm.edit',$laporan->id) }}"
                class="text-yellow-500 hover:text-yellow-700">
                    <i class="fa fa-pen"></i>
                </a>
                @else
                <button disabled
                        class="text-gray-300 cursor-not-allowed">
                    <i class="fa fa-pen"></i>
                </button>
                @endif
                @php
                    $canDelete =
                        auth()->user()->role === 'super_admin' ||
                        $laporan->id_admin === auth()->id();
                @endphp

                @if($canDelete)
                <form action="{{ route('admin.laporan-bbm.destroy',$laporan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button onclick="return confirm('Hapus data ini?')"
                            class="text-red-500 hover:text-red-700">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                @else
                <button disabled
                        class="text-gray-300 cursor-not-allowed">
                    <i class="fa fa-trash"></i>
                </button>
                @endif
            </div>

        </div>
        @empty
        <div class="text-center text-gray-400 py-6">
            Belum ada data laporan BBM
        </div>
        @endforelse
</div>

@endsection