@extends('layouts.driver')

@section('title','Detail Permohonan')

@section('content')

<div class="max-w-6xl mx-auto py-10 px-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Detail Permohonan BBM
            </h1>
            <p class="text-gray-500 mt-1">
                Informasi lengkap permohonan pengisian bahan bakar
            </p>
        </div>

        <div class="mt-4 md:mt-0">
            <a href="{{ route('driver.permohonan.index') }}"
               class="bg-[#233e8b] hover:bg-[#1a2f6b] text-white px-5 py-2 rounded-lg shadow transition">
                ⬅ Kembali
            </a>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">

                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">
                    Informasi Permohonan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

                    <div>
                        <p class="text-gray-500">Tanggal Permohonan</p>
                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($data->tanggal_permohonan)->format('d-m-Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Driver</p>
                        <p class="text-gray-700 mt-1">
                            {{ auth()->user()->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Kendaraan</p>
                        <p class="font-semibold">
                            {{ $data->kendaraan->merk ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">No Polisi</p>
                        <p class="font-semibold">
                            {{ $data->kendaraan?->no_polisi ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jenis BBM</p>
                        <p class="font-semibold">
                            {{ $data->jenisbbm?->nama_jenis ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Jumlah Liter</p>
                        <p class="font-semibold">
                            {{ $data->jumlah_liter }} L
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-gray-500">Status</p>
                        <p class="font-semibold
                            @if($data->status == 'Pending') text-yellow-600
                            @elseif($data->status == 'Disetujui') text-green-600
                            @else text-red-600
                            @endif">
                            {{ $data->status }}
                        </p>
                    </div>
                    @if($data->keterangan_admin)
                    <div class="md:col-span-2">
                        <p class="text-gray-500">Alasan Penolakan</p>

                        <div class="
                            mt-1 p-3 rounded-xl text-sm
                            @if($data->status == 'Ditolak')
                                bg-red-50 border border-red-200 text-red-700
                            @elseif($data->status == 'Disetujui')
                                bg-green-50 border border-green-200 text-green-700
                            @else
                                bg-gray-50 border border-gray-200 text-gray-700
                            @endif
                        ">
                            {{ $data->keterangan_admin }}
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">

                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">
                    Data Odometer
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-gray-500">Odometer Saat Pengajuan</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->odometer ?? '-' }} km
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Tanggal Dibuat</p>
                        <p class="font-semibold text-gray-800">
                            {{ $data->created_at->format('d-m-Y H:i') }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <div class="space-y-6">

            {{-- FOTO --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-3">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">
                    Bukti Sisa BBM
                </h2>

                @if($data->foto_sisa_bbm)
                    <img src="{{ asset('storage/' . $data->foto_sisa_bbm) }}"
                        alt="Foto BBM"
                        class="w-full h-auto rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition block">
                @else
                    <p class="text-gray-500 text-sm">
                        Tidak ada foto
                    </p>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection