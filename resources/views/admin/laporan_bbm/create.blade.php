@extends('layouts.admin')

@section('title','Buat Laporan BBM')

@section('content')
<div class="w-full min-h-screen bg-gray-100 p-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Buat Laporan BBM
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Input data pengisian bahan bakar kendaraan.
        </p>
    </div>

    @if(session('error'))
        <div class="mb-6 px-5 py-3 border border-red-400 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-5 py-3 border border-red-400 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.laporan-bbm.store') }}" method="POST">
    @csrf

    @if($permohonan)
        <input type="hidden" name="id_driver" value="{{ $permohonan->id_driver }}">
        <input type="hidden" name="id_kendaraan" value="{{ $permohonan->id_kendaraan }}">
        <input type="hidden" name="id_permohonan" value="{{ $permohonan->id }}">
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white border border-gray-300 rounded-2xl shadow-sm">

                <div class="p-8 space-y-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal
                        </label>
                        <input type="date"
                            name="tanggal"
                            value="{{ now()->toDateString() }}"
                            readonly
                            class="w-full px-4 py-3 border border-gray-400 rounded-lg bg-gray-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis BBM*
                        </label>
                        <select name="id_jenis_bbm"
                            id="jenisBBMSelect"
                            class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"
                            required>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Liter*
                        </label>
                        <input type="number"
                               name="jumlah_liter"
                               required
                               class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Odometer*
                        </label>
                        <input type="number"
                               id="odometerInput"
                               name="odometer"
                               value="{{ old('odometer') }}"
                               placeholder="Wajib masukkan data odometer asli saat ini"
                               required
                               class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500">

                        <p class="text-xs text-gray-500 mt-2">
                            Tidak boleh lebih kecil dari odometer sebelumnya.
                        </p>

                        <div id="minimalOdoBox"
                             class="mt-3 px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
                            Minimal Odometer:
                            <b id="minimalOdoText">-</b>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan_admin"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                </div>

                <div class="px-8 py-5 bg-gray-50 border-t border-gray-300 rounded-b-2xl flex justify-end">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold shadow">
                        Simpan Laporan
                    </button>
                </div>

            </div>

        </div>

        <div class="space-y-6">

            <div class="bg-white border border-gray-300 rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Info Driver</h3>

                <select name="id_driver"
                        id="driverSelect"
                        class="w-full px-4 py-3 border border-gray-400 rounded-lg mb-4"
                        {{ $permohonan ? 'disabled' : '' }} required>

                    <option value="">-- Pilih Driver --</option>

                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}"
                            data-nama="{{ $driver->name }}"
                            data-email="{{ $driver->email }}"
                            {{ $permohonan && $permohonan->id_driver == $driver->id ? 'selected' : '' }}>
                            Driver {{ $driver->id }} - {{ $driver->name }}
                        </option>
                    @endforeach
                </select>

                <div id="driverCard" class="hidden text-sm text-gray-600 space-y-1">
                    <p>Nama : <b id="driverNama"></b></p>
                    <p>Email : <b id="driverEmail"></b></p>
                </div>
            </div>

            <div class="bg-white border border-gray-300 rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-700 mb-4">Info Kendaraan</h3>

                <select name="id_kendaraan"
                        id="kendaraanSelect"
                        class="w-full px-4 py-3 border border-gray-400 rounded-lg mb-4"
                        {{ $permohonan ? 'disabled' : '' }} required>

                    <option value="">-- Pilih Kendaraan --</option>

                    @foreach($kendaraans as $kendaraan)
                    <option value="{{ $kendaraan->id }}"
                        data-nopol="{{ $kendaraan->no_polisi }}"
                        data-merk="{{ $kendaraan->merk }}"
                        data-tipe="{{ $kendaraan->jenis }}"
                        data-tahun="{{ $kendaraan->tahun }}"
                        data-odometer="{{ $lastOdo[$kendaraan->id] ?? 0 }}"
                        data-bbm='@json($kendaraan->jenisbbms)'
                        {{ $permohonan && $permohonan->id_kendaraan == $kendaraan->id ? 'selected' : '' }}>

                        {{ $kendaraan->no_polisi }} - {{ $kendaraan->merk }}
                    </option>
                    @endforeach
                </select>

                <div id="kendaraanCard" class="hidden text-sm text-gray-600 space-y-1">
                    <p>No Polisi : <b id="kendaraanNopol"></b></p>
                    <p>Merk : <b id="kendaraanMerk"></b></p>
                    <p>Tipe : <b id="kendaraanTipe"></b></p>
                    <p>Tahun : <b id="kendaraanTahun"></b></p>
                    <p>Odometer Terakhir : <b id="kendaraanOdo"></b> km</p>
                </div>
            </div>

        </div>

    </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const driverSelect = document.getElementById('driverSelect');
    const kendaraanSelect = document.getElementById('kendaraanSelect');
    const jenisBBMSelect = document.getElementById('jenisBBMSelect');
    const driverCard = document.getElementById('driverCard');
    const kendaraanCard = document.getElementById('kendaraanCard');
    const minimalOdoText = document.getElementById('minimalOdoText');

    function updateDriverInfo(){
        const opt = driverSelect.options[driverSelect.selectedIndex];
        if(!opt.value){
            driverCard.classList.add('hidden');
            return;
        }
        driverCard.classList.remove('hidden');
        document.getElementById('driverNama').innerText = opt.dataset.nama;
        document.getElementById('driverEmail').innerText = opt.dataset.email;
    }

    function updateKendaraanInfo(){

        const opt = kendaraanSelect.options[kendaraanSelect.selectedIndex];

        if(!opt.value){
            kendaraanCard.classList.add('hidden');
            minimalOdoText.innerText = '-';
            jenisBBMSelect.innerHTML =
                '<option value="">-- Pilih Jenis BBM --</option>';
            return;
        }

        kendaraanCard.classList.remove('hidden');

        document.getElementById('kendaraanNopol').innerText = opt.dataset.nopol;
        document.getElementById('kendaraanMerk').innerText = opt.dataset.merk;
        document.getElementById('kendaraanTipe').innerText = opt.dataset.tipe;
        document.getElementById('kendaraanTahun').innerText = opt.dataset.tahun;
        document.getElementById('kendaraanOdo').innerText = opt.dataset.odometer ?? 0;

        minimalOdoText.innerText = (opt.dataset.odometer ?? 0) + " km";

        let bbms = JSON.parse(opt.dataset.bbm || '[]');

        jenisBBMSelect.innerHTML = '';

        bbms.forEach(bbm => {
            jenisBBMSelect.innerHTML += `
                <option value="${bbm.id}">
                    ${bbm.nama_jenis} (Rp ${Number(bbm.harga_per_liter).toLocaleString('id-ID')})
                </option>
            `;
        });
    }
    driverSelect?.addEventListener('change', updateDriverInfo);
    kendaraanSelect?.addEventListener('change', updateKendaraanInfo);

    updateDriverInfo();
    updateKendaraanInfo();
});
</script>

@endsection
