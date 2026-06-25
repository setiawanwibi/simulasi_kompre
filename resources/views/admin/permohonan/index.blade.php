@extends('layouts.admin')
@section('title','Permohonan BBM')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-[#233e8b]"></i>
                Riwayat Permohonan
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                Kelola seluruh permohonan pengisian BBM dari driver
            </p>
        </div>

    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl text-sm">
        {{ session('error') }}
    </div>
    @endif
    
    <form method="GET" class="bg-white shadow-sm rounded-2xl p-4">

        <div class="grid md:grid-cols-6 gap-3 text-sm">

            <div>
                <label class="text-gray-600">Tanggal Awal</label>
                <input type="date"
                    name="start"
                    value="{{ request('start') }}"
                    class="w-full border rounded-xl px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-gray-600">Tanggal Akhir</label>
                <input type="date"
                    name="end"
                    value="{{ request('end') }}"
                    class="w-full border rounded-xl px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-gray-600">Driver</label>
                <select name="driver"
                    class="w-full border rounded-xl px-3 py-2 mt-1">
                    <option value="">Semua</option>
                    @foreach($drivers as $d)
                    <option value="{{ $d->id }}"
                        {{ request('driver')==$d->id?'selected':'' }}>
                        {{ $d->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-gray-600">No Polisi</label>
                <select name="nopol"
                    class="w-full border rounded-xl px-3 py-2 mt-1">
                    <option value="">Semua</option>
                    @foreach($kendaraans as $k)
                    <option value="{{ $k->id }}"
                        {{ request('nopol')==$k->id?'selected':'' }}>
                        {{ $k->no_polisi }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-gray-600">
                    Cari Driver / No Polisi
                </label>

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full border rounded-xl px-3 py-2 mt-1"
                    placeholder="Masukkan nama atau nomor polisi">
            </div>

        </div>

        <div class="mt-4 flex gap-2">

            <button type="submit"
                class="bg-[#233e8b] hover:bg-[#1b3272]
                    text-white px-5 py-2 rounded-xl text-sm transition">
                Cari
            </button>

            <a href="{{ route('admin.permohonan.index') }}"
                class="bg-red-500 hover:bg-red-600
                    text-white px-5 py-2 rounded-xl text-sm transition">
                Reset
            </a>

        </div>

    </form>

    <div class="hidden md:block bg-white shadow-sm rounded-2xl overflow-hidden">

        <table class="w-full text-sm text-gray-600">

            <thead class="bg-blue-100 text-blue-800 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Driver</th>
                    <th class="px-4 py-3 text-left">No.Polisi</th>
                    <th class="px-4 py-3 text-left">Liter</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($data as $d)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>

                    <td class="px-4 py-3">
                        {{ \Carbon\Carbon::parse($d->tanggal_permohonan)->format('d-m-Y') }}
                    </td>

                    <td class="px-4 py-3">{{ $d->nama_driver ?? $d->user?->name ?? '-' }}</td>

                    <td class="px-4 py-3">
                        {{ $d->no_polisi ?? $d->kendaraan?->no_polisi ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $d->jumlah_liter }}
                    </td>

                    <td class="px-4 py-3">
                        @if($d->status=='Pending')
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                            Pending
                        </span>
                        @elseif($d->status=='Disetujui')
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                            Disetujui
                        </span>
                        @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                            Ditolak
                        </span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        <button
                            class="text-blue-500 hover:text-blue-700 hover:bg-gray-100 rounded p-1 transition"
                            onclick='showDetail(
                                {{ $d->id }},
                                @json($d->nama_driver ?? $d->user?->name ?? "-"),
                                @json($d->no_polisi ?? $d->kendaraan?->no_polisi ?? "-"),
                                @json($d->nama_jenis_bbm ?? $d->jenisbbm?->nama_jenis ?? "-"),
                                {{ $d->jumlah_liter }},
                                @json($d->odometer),
                                @json($d->odometer_sebelumnya ?? 0),
                                @json($d->tanggal_permohonan),
                                @json(
                                    $d->foto_sisa_bbm
                                    ? asset("storage/".$d->foto_sisa_bbm)
                                    : asset("images/no-image.png")
                                ),
                                @json($d->status),
                                @json($d->keterangan_admin)
                            )'>
                            👁
                        </button>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $data->links() }}
    </div>
    
    <div class="md:hidden space-y-4">

        @forelse($data as $d)

        <div class="bg-white shadow-sm rounded-2xl p-4 space-y-3">

            <div class="flex justify-between items-start">

                <div>
                    <p class="font-semibold text-gray-700">
                        {{ $d->nama_driver ?? $d->user?->name ?? '-' }}
                    </p>

                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($d->tanggal_permohonan)->format('d-m-Y') }}
                    </p>
                </div>

                @if($d->status=='Pending')
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                    Pending
                </span>
                @elseif($d->status=='Disetujui')
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                    Disetujui
                </span>
                @else
                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                    Ditolak
                </span>
                @endif

            </div>

            <div class="text-sm text-gray-600 space-y-1">
                <div><strong>No Polisi:</strong> {{ $d->no_polisi ?? $d->kendaraan?->no_polisi ?? '-' }}</div>
                <div><strong>Liter:</strong> {{ $d->jumlah_liter }}</div>
                <div><strong>Jenis:</strong> {{ $d->nama_jenis_bbm ?? $d->jenisbbm?->nama_jenis ?? '-' }}</div>
            </div>

            <div class="pt-2">
                <button
                    class="text-blue-500 text-sm"
                    onclick='showDetail(
                        {{ $d->id }},
                        @json($d->nama_driver ?? $d->user?->name ?? "-"),
                        @json($d->no_polisi ?? $d->kendaraan?->no_polisi ?? "-"),
                        @json($d->nama_jenis_bbm ?? $d->jenisbbm?->nama_jenis ?? "-"),
                        {{ $d->jumlah_liter }},
                        @json($d->odometer),
                        @json($d->odometer_sebelumnya ?? 0),
                        @json($d->tanggal_permohonan),
                        @json(
                            $d->foto_sisa_bbm
                            ? asset('storage/'.$d->foto_sisa_bbm)
                            : asset('images/no-image.png')
                        ),
                        @json($d->status),
                        @json($d->keterangan_admin)
                    )'>
                    👁 Lihat Detail
                </button>
            </div>

        </div>

        @empty

        <div class="text-center text-gray-400 py-6">
            Belum ada data permohonan
        </div>

        @endforelse

    </div>

</div>


<div id="modalDetail"
     class="fixed top-0 left-0 w-screen h-screen hidden z-[9999]">

    <div class="w-full h-full flex items-center justify-center"
         onclick="closeModal()">

        <div onclick="event.stopPropagation()"
             class="bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl p-8 mx-4 border">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">
                    Detail Permohonan BBM
                </h2>

                <button onclick="closeModal()"
                    class="text-gray-500 text-2xl hover:text-black">
                    ✕
                </button>
            </div>

            <table class="w-full text-sm">

                <tr>
                    <td class="py-3 w-40 font-medium">Driver</td>
                    <td id="d_driver"></td>
                </tr>

                <tr>
                    <td class="py-3 font-medium">No Polisi</td>
                    <td id="d_kendaraan"></td>
                </tr>

                <tr>
                    <td class="py-3 font-medium">Jenis BBM</td>
                    <td id="d_bbm"></td>
                </tr>

                <tr>
                    <td class="py-3 font-medium">Liter</td>
                    <td id="d_liter"></td>
                </tr>

                <tr>
                    <td class="py-3 font-medium">Odometer Saat Pengajuan</td>
                    <td id="d_odometer"></td>
                </tr>

                <tr>
                    <td class="py-3 font-medium">
                        Odometer Terakhir
                    </td>

                    <td>
                        <div class="inline-flex items-center gap-2
                                    px-3 py-2 rounded-lg
                                    bg-red-50 border border-red-200">

                            <i class="fa-solid fa-gauge-high text-red-500 text-sm"></i>

                            <span id="d_odometer_terakhir"
                                class="font-semibold text-red-600">
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="py-3 font-medium">Tanggal</td>
                    <td id="d_tanggal"></td>
                </tr>

                <tr id="row_keterangan" class="hidden">
                    <td class="py-3 font-medium">Alasan Penolakan</td>

                    <td>
                        <div id="d_keterangan"
                            class="p-3 rounded-xl text-sm border">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="py-3 font-medium">Foto</td>
                    <td>
                        <div class="w-60 h-40 rounded-xl overflow-hidden border">
                            <img id="d_foto"
                                 class="w-full h-full object-cover">
                        </div>
                    </td>
                </tr>

            </table>

            <div id="actionButtons" class="mt-6"></div>

        </div>
    </div>
</div>


<script>
function showDetail(id, driver, kendaraan, bbm, liter, odometer, odometer_terakhir, tanggal, foto, status, keterangan)
{
    document.getElementById('modalDetail')
        .classList.remove('hidden');

    document.getElementById('d_driver').innerText = driver;
    document.getElementById('d_kendaraan').innerText = kendaraan;
    document.getElementById('d_bbm').innerText = bbm;
    document.getElementById('d_liter').innerText = liter;
    document.getElementById('d_odometer').innerText =
    odometer ? odometer + ' km' : '-';
    document.getElementById('d_odometer_terakhir').innerText =
    odometer_terakhir ? odometer_terakhir + ' km' : '-';
    document.getElementById('d_tanggal').innerText = tanggal;
    document.getElementById('d_foto').src = foto;
    document.getElementById('d_foto').onerror = function () {
        this.src = "{{ asset('images/no-image.png') }}";
    };
    if(keterangan){
        document.getElementById('row_keterangan').classList.remove('hidden');
        document.getElementById('d_keterangan').innerText = keterangan;
    }else{
        document.getElementById('row_keterangan').classList.add('hidden');
    }
    
    let buttons = '';

    if(status === 'Pending') {

    buttons = `
    <form id="formAction" method="POST">
        <input type="hidden"
            name="_token"
            value="{{ csrf_token() }}">

        <label class="font-medium">
            Keterangan Admin
        </label>

        <textarea name="keterangan_admin"
            class="w-full border rounded-xl p-3 mt-2 mb-3"
            required></textarea>

        <div class="flex gap-3">

            <button type="submit"
                formaction="/admin/permohonan/${id}/approve"
                class="bg-green-600 hover:bg-green-700
                text-white px-5 py-2 rounded-xl flex-1">
                Setujui & Buat Laporan
            </button>

            <button type="submit"
                formaction="/admin/permohonan/${id}/reject"
                class="bg-red-600 hover:bg-red-700
                text-white px-5 py-2 rounded-xl flex-1">
                Tolak
            </button>

        </div>
    </form>
    `;
    }

    document.getElementById('actionButtons').innerHTML = buttons;
}

function closeModal(){
    document.getElementById('modalDetail')
        .classList.add('hidden');
}

document.addEventListener('keydown', function(e){
    if(e.key === "Escape"){
        closeModal();
    }
});
</script>
@endsection