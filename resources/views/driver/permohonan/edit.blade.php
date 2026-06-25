@extends('layouts.driver')

@section('content')

<div class="w-full min-h-screen bg-gray-100 px-6 md:px-10 py-8">

    <div class="mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-blue-700 flex items-center gap-3">
            <i class="fa fa-edit"></i>
            Edit Permohonan BBM
        </h2>
        <p class="text-gray-500 text-sm mt-1">
            Perbarui data permohonan Anda sebelum diproses admin.
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-xl mb-6">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('driver.permohonan.update', $data->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="w-full bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-10 space-y-8">

        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-8">

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Kendaraan*
                </label>
                <select name="id_kendaraan"
                        id="kendaraanSelect"
                        required
                        class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    @foreach($kendaraans as $k)
                        <option value="{{ $k->id }}"
                            data-bbm='@json($k->jenisbbms)'
                            {{ $data->id_kendaraan == $k->id ? 'selected' : '' }}>

                            {{ $k->no_polisi }} - {{ $k->merk }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Jenis BBM*
                </label>
                <select name="id_jenis_bbm"
                        id="jenisBBMSelect"
                        required
                        class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </select>
            </div>

        </div>

        <div class="grid md:grid-cols-2 gap-8">

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Tanggal*
                </label>
                <input type="date"
                    name="tanggal_permohonan"
                    value="{{ \Carbon\Carbon::parse($data->tanggal_permohonan)->format('Y-m-d') }}"
                    readonly
                    class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Jumlah Liter*
                </label>
                <input type="number"
                       step="0.01"
                       name="jumlah_liter"
                       value="{{ $data->jumlah_liter }}"
                       required
                       class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Odometer Sekarang*
            </label>
            <input type="number"
                   name="odometer"
                   value="{{ $data->odometer }}"
                   required
                   class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Foto Sisa BBM
            </label>

            @if($data->foto_sisa_bbm)
                <div id="oldPreview" class="mb-4">
                    <p class="text-xs text-gray-500 mb-2">Foto Saat Ini</p>
                    <img src="{{ asset('storage/'.$data->foto_sisa_bbm) }}"
                         class="w-48 rounded-lg border">
                </div>
            @endif

            <div class="space-y-3">

                <video id="video" autoplay playsinline class="w-full rounded-lg border bg-black"></video>
                <canvas id="canvas" class="hidden"></canvas>

                <img id="previewImage" class="hidden w-48 rounded-lg border shadow-sm">

                <div class="flex gap-3">
                    <button type="button"
                            onclick="startCamera()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Buka Kamera
                    </button>

                    <button type="button"
                            onclick="capture()"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg">
                        Ambil Foto
                    </button>
                </div>

                <input type="file" name="foto_sisa_bbm" id="foto_sisa_bbm" class="hidden">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">

            <a href="{{ route('driver.permohonan.index') }}"
            class="w-full sm:w-auto text-center
                    bg-gray-400 hover:bg-gray-500
                    text-white px-6 py-3 rounded-xl
                    text-sm font-medium transition">
                Batal
            </a>

            <button type="submit"
                    class="w-full sm:w-auto
                        bg-blue-600 hover:bg-blue-700
                        text-white px-6 py-3 rounded-xl
                        text-sm font-semibold
                        flex items-center justify-center gap-2 transition">
                <i class="fa fa-save"></i>
                Simpan Perubahan
            </button>

</div>

    </form>
</div>

<script>
let stream;

async function startCamera() {

    stream = await navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: "environment"
        }
    });

    document.getElementById('video').srcObject = stream;
}

function getGPS() {

    return new Promise((resolve, reject) => {

        navigator.geolocation.getCurrentPosition(

            pos => resolve({
                lat: pos.coords.latitude,
                lng: pos.coords.longitude,
                accuracy: pos.coords.accuracy
            }),

            err => reject(err),

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
    });
}

async function getAddress(lat, lng) {

    try {

        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
        );

        const data = await response.json();

        const addr = data.address || {};

        let lokasi = [
            addr.road,
            addr.suburb,
            addr.village,
            addr.city || addr.town,
            addr.state
        ]
        .filter(Boolean)
        .join(', ');

        return lokasi || data.display_name || 'Lokasi tidak ditemukan';

    } catch (error) {

        return 'Lokasi tidak tersedia';
    }
}

async function capture() {

    try {

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        ctx.drawImage(video, 0, 0);

        let gps = await getGPS();

        let lokasi = await getAddress(gps.lat, gps.lng);

        let now = new Date();

        let timestamp =
            now.toLocaleDateString('id-ID') + " " +
            now.toLocaleTimeString('id-ID');

        ctx.fillStyle = "rgba(0,0,0,0.65)";

        ctx.fillRect(
            0,
            canvas.height - 170,
            canvas.width,
            170
        );

        ctx.fillStyle = "white";
        ctx.font = "16px Arial";

        ctx.fillText(
            `Akurasi GPS: ±${Math.round(gps.accuracy)} meter`,
            10,
            canvas.height - 125
        );

        ctx.fillText(
            `GPS: ${gps.lat}, ${gps.lng}`,
            10,
            canvas.height - 90
        );

        ctx.fillText(
            `Waktu: ${timestamp}`,
            10,
            canvas.height - 55
        );

        let lokasiText = lokasi.length > 90
            ? lokasi.substring(0, 90) + '...'
            : lokasi;

        ctx.fillText(
            `Lokasi: ${lokasiText}`,
            10,
            canvas.height - 20
        );

        canvas.toBlob(blob => {

            let file = new File(
                [blob],
                "bbm.jpg",
                {
                    type: "image/jpeg"
                }
            );

            let dt = new DataTransfer();

            dt.items.add(file);

            document.getElementById('foto_sisa_bbm').files =
                dt.files;

            document.getElementById('previewImage').src =
                URL.createObjectURL(blob);

            document.getElementById('previewImage')
                .classList.remove('hidden');

            const old = document.getElementById('oldPreview');

            if (old) {
                old.style.display = "none";
            }

        }, "image/jpeg", 0.9);

        document.getElementById('latitude').value =
            gps.lat;

        document.getElementById('longitude').value =
            gps.lng;

    } catch (error) {

        alert(
            'Gagal mengambil lokasi atau kamera. Pastikan izin GPS dan kamera diaktifkan.'
        );

        console.error(error);
    }
}

document.addEventListener('DOMContentLoaded', function () {

    const kendaraanSelect =
        document.getElementById('kendaraanSelect');

    const jenisBBMSelect =
        document.getElementById('jenisBBMSelect');

    function updateBBM() {

        let opt =
            kendaraanSelect.options[
                kendaraanSelect.selectedIndex
            ];

        let bbms =
            JSON.parse(opt.dataset.bbm || '[]');

        jenisBBMSelect.innerHTML = '';

        bbms.forEach(bbm => {

            let selected =
                bbm.id == "{{ $data->id_jenis_bbm }}"
                ? 'selected'
                : '';

            jenisBBMSelect.innerHTML += `
                <option value="${bbm.id}" ${selected}>
                    ${bbm.nama_jenis}
                </option>
            `;
        });
    }

    kendaraanSelect.addEventListener(
        'change',
        updateBBM
    );

    updateBBM();
});
</script>

@endsection