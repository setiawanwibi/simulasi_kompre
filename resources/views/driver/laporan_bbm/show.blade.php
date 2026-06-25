@extends('layouts.driver')
@section('title','Detail Laporan')

@section('content')

<div class="space-y-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-700">
            Detail Laporan BBM
        </h2>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="grid md:grid-cols-2 gap-6 text-sm">

            <div>
                <div class="mb-4">
                    <div class="text-gray-500">Tanggal</div>
                    <div class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-gray-500">Kendaraan</div>
                    <div class="font-medium">
                        {{ $laporan->no_polisi ?? '-' }}
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-gray-500">Jenis BBM</div>
                    <div class="font-medium">
                        {{ $laporan->nama_jenis_bbm ?? '-' }}
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-gray-500">Jumlah Liter</div>
                    <div class="font-medium">
                        {{ $laporan->jumlah_liter }} Liter
                    </div>
                </div>
            </div>


            <div>
                <div class="mb-4">
                    <div class="text-gray-500">Harga per Liter</div>
                    <div class="font-medium">
                        Rp {{ number_format($laporan->harga_per_liter) }}
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-gray-500">Total Biaya</div>
                    <div class="text-lg font-bold text-blue-700">
                        Rp {{ number_format($laporan->total_biaya) }}
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-gray-500">Keterangan Admin</div>
                    <div class="font-medium">
                        {{ $laporan->keterangan_admin ?? '-' }}
                    </div>
                </div>
            </div>

        </div>

    </div>


    <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">

        <form method="POST"
              action="{{ route('driver.laporan.update',$laporan->id) }}"
              enctype="multipart/form-data"
              class="space-y-4">

            @csrf
            @method('PUT')

            <div>
                <label class="block mb-2 font-medium text-sm">
                    Catatan Driver
                </label>

                <textarea name="catatan_driver"
                          rows="3"
                          required
                          placeholder="Buat catatan ketika terjadi penggunaan BBM tambahan"
                          class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">{{ $laporan->catatan_driver }}</textarea>
            </div>


            <div>
                <label class="block mb-2 font-medium text-sm">
                    Upload Foto Bukti
                </label>

                <video id="video" autoplay playsinline class="w-full rounded-lg border bg-black"></video>
                <canvas id="canvas" class="hidden"></canvas>

                <div class="flex gap-3 mt-3">
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

                <input type="file" name="foto_driver" id="fotoInput" class="hidden" accept="image/*">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
            </div>


            @if($laporan->foto_driver)
            <div>
                <label class="block mb-2 font-medium text-sm">
                    Foto Saat Ini
                </label>

                <img src="{{ asset('storage/'.$laporan->foto_driver) }}"
                     class="w-48 rounded-lg shadow">
            </div>
            @endif

            <div id="previewContainer" class="hidden">
                <label class="block mb-2 font-medium text-sm">
                    Preview Foto Baru
                </label>

                <img id="previewImage"
                     class="w-48 rounded-lg shadow border">
            </div>

            <div class="flex flex-wrap gap-3 pt-4">

                <button class="bg-green-600 hover:bg-green-700
                               text-white px-4 py-2 rounded-lg text-sm transition">
                    Simpan Catatan
                </button>

                <a href="{{ route('driver.laporan.index') }}"
                   class="bg-gray-500 hover:bg-gray-600
                          text-white px-4 py-2 rounded-lg text-sm transition">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>


<script>
document.getElementById('fotoInput').addEventListener('change', function(event) {

    const file = event.target.files[0];
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');

    if (file) {

        const reader = new FileReader();

        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }

        reader.readAsDataURL(file);
    }
});

let stream;

async function startCamera() {

    try {

        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: "environment"
            }
        });

        document.getElementById('video').srcObject = stream;

    } catch (error) {

        alert('Kamera tidak dapat diakses');

        console.log(error);
    }
}

function getGPS() {

    return new Promise((resolve, reject) => {

        navigator.geolocation.getCurrentPosition(

            position => {

                resolve({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });

            },

            error => {
                reject(error);
            }

        );

    });
}

async function capture() {

    try {

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        ctx.drawImage(video, 0, 0);

        let gps = await getGPS();
        let now = new Date().toLocaleString();

        // background watermark
        ctx.fillStyle = "rgba(0,0,0,0.5)";
        ctx.fillRect(0, canvas.height - 80, canvas.width, 80);

        // text watermark
        ctx.fillStyle = "white";
        ctx.font = "16px Arial";

        ctx.fillText(
            `GPS: ${gps.lat}, ${gps.lng}`,
            10,
            canvas.height - 45
        );

        ctx.fillText(
            `Waktu: ${now}`,
            10,
            canvas.height - 20
        );

        canvas.toBlob((blob) => {

            let file = new File(
                [blob],
                "bukti-driver.jpg",
                {
                    type: "image/jpeg"
                }
            );

            let dt = new DataTransfer();

            dt.items.add(file);

            const input = document.getElementById('fotoInput');

            input.files = dt.files;

            // preview image
            previewImage.src = URL.createObjectURL(blob);

            previewContainer.classList.remove('hidden');

        }, "image/jpeg", 0.85);

        // simpan gps ke hidden input
        document.getElementById('latitude').value = gps.lat;
        document.getElementById('longitude').value = gps.lng;

    } catch (error) {

        console.log(error);

        alert('Gagal mengambil foto');
    }
}
</script>

@endsection
