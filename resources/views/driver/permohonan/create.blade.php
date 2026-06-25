@extends('layouts.driver')

@section('content')

<div class="w-full min-h-screen bg-gray-100 px-6 md:px-10 py-8">
    <div class="mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-blue-700 flex items-center gap-3">
            <i class="fa fa-file-alt"></i>
            Tambah Permohonan
        </h2>
        <p class="text-gray-500 text-sm mt-1">
            Ajukan permohonan pengisian bahan bakar kendaraan.
        </p>
    </div>

    <form action="{{ route('driver.permohonan.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="w-full bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-10 space-y-8">

        @csrf

        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Tanggal*
                </label>

                <input type="hidden" name="tanggal_permohonan" value="{{ now()->toDateString() }}">
                <input type="hidden" name="waktu_permohonan" id="waktu_permohonan">

                <input type="text"
                       value="{{ now()->format('d-m-Y H:i') }}"
                       readonly
                       class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 bg-gray-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    No. Polisi*
                </label>
                <select name="id_kendaraan"
                        id="kendaraanSelect"
                        required
                        class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Pilih Kendaraan</option>
                    @foreach($kendaraans as $k)
                    <option value="{{ $k->id }}"
                        data-nopol="{{ $k->no_polisi }}"
                        data-merk="{{ $k->merk }}"
                        data-jenis="{{ $k->jenis }}"
                        data-tahun="{{ $k->tahun }}"
                        data-odometer="{{ $lastOdo[$k->id] ?? 0 }}"
                        data-bbm='@json($k->jenisbbms)'>{{ $k->no_polisi }} - {{ $k->merk }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="infoKendaraan"
             class="hidden bg-blue-50 border border-blue-200 p-5 rounded-xl text-sm text-gray-700">
            <div class="grid md:grid-cols-5 gap-4">
                <div><b>No Polisi:</b><br><span id="v_nopol"></span></div>
                <div><b>Merk:</b><br><span id="v_merk"></span></div>
                <div><b>Jenis:</b><br><span id="v_jenis"></span></div>
                <div><b>Tahun:</b><br><span id="v_tahun"></span></div>
                <div><b>Odometer:</b><br><span id="v_odometer"></span></div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Jenis BBM*
                </label>
                <select name="id_jenis_bbm"
                        id="jenisBBMSelect"
                        required
                        class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Pilih Jenis BBM</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Jumlah Liter*
                </label>
                <input type="number"
                       step="0.01"
                       name="jumlah_liter"
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
                   required
                   class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Upload Foto Sisa BBM*
            </label>

            <div class="space-y-3">
                <video id="video" autoplay playsinline class="w-full rounded-lg border bg-black"></video>
                <canvas id="canvas" class="hidden"></canvas>

                <img id="previewImage" class="hidden w-64 rounded-lg border shadow-sm">

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

        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('driver.permohonan.index') }}"
               class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                Batal
            </a>

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
                <i class="fa fa-save"></i>
                Simpan
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const kendaraanSelect = document.getElementById('kendaraanSelect');
    const jenisBBMSelect = document.getElementById('jenisBBMSelect');

    if (kendaraanSelect) {

        kendaraanSelect.addEventListener('change', function () {

            let opt = this.options[this.selectedIndex];

            if (!this.value) {

                document.getElementById('infoKendaraan')
                    .classList.add('hidden');

                jenisBBMSelect.innerHTML =
                    '<option value="">Pilih Jenis BBM</option>';

                return;
            }

            document.getElementById('infoKendaraan')
                .classList.remove('hidden');

            document.getElementById('v_nopol').innerText =
                opt.dataset.nopol;

            document.getElementById('v_merk').innerText =
                opt.dataset.merk;

            document.getElementById('v_jenis').innerText =
                opt.dataset.jenis;

            document.getElementById('v_tahun').innerText =
                opt.dataset.tahun;

            document.getElementById('v_odometer').innerText =
                (opt.dataset.odometer ?? 0) + ' km';

            let bbms = JSON.parse(opt.dataset.bbm || '[]');

            jenisBBMSelect.innerHTML =
                '<option value="">Pilih Jenis BBM</option>';

            bbms.forEach(bbm => {

                jenisBBMSelect.innerHTML += `
                    <option value="${bbm.id}">
                        ${bbm.nama_jenis}
                    </option>
                `;
            });
        });
    }
});

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

        canvas.toBlob((blob) => {

            if (!blob) {
                alert('Gagal mengambil foto');
                return;
            }

            const file = new File(
                [blob],
                `bbm_${Date.now()}.jpg`,
                {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                }
            );

            const dt = new DataTransfer();

            dt.items.add(file);

            const inputFile =
                document.getElementById('foto_sisa_bbm');

            inputFile.files = dt.files;

            document.getElementById('previewImage').src =
                URL.createObjectURL(file);

            document.getElementById('previewImage')
                .classList.remove('hidden');

        }, 'image/jpeg', 1);

        document.getElementById('latitude').value =
            gps.lat;

        document.getElementById('longitude').value =
            gps.lng;

        document.getElementById('waktu_permohonan').value =
            new Date().toISOString();

    } catch (error) {

        alert(
            'Gagal mengambil lokasi atau kamera. Pastikan izin GPS dan kamera diaktifkan.'
        );

        console.error(error);
    }
}
</script>

@endsection