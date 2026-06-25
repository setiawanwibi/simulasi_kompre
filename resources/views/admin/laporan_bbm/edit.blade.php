@extends('layouts.admin')

@section('title', 'Edit Laporan BBM')

@section('content')
<div class="w-full min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            Edit Laporan BBM
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Perbarui data laporan pengisian bahan bakar.
        </p>
    </div>

    <form action="{{ route('admin.laporan-bbm.update',$laporan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="w-full bg-white border border-gray-300 rounded-2xl shadow-sm">

            <div class="p-5 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal
                    </label>
                    <input type="date"
                        value="{{ $laporan->tanggal }}"
                        readonly
                        class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg bg-gray-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Driver
                    </label>
                    <select name="id_driver"
                        class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($drivers as $d)
                        <option value="{{ $d->id }}"
                            {{ $laporan->id_driver==$d->id?'selected':'' }}>
                            {{ $d->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kendaraan
                    </label>
                    <select name="id_kendaraan"
                        id="kendaraanSelect"
                        class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($kendaraans as $k)
                        <option value="{{ $k->id }}"
                            data-bbm='@json($k->jenisbbms)'
                            {{ $laporan->id_kendaraan==$k->id?'selected':'' }}>

                            {{ $k->no_polisi }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis BBM
                    </label>
                    <select name="id_jenis_bbm"
                        id="jenisBBMSelect"
                        class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($jenisBBMs as $b)
                        <option value="{{ $b->id }}"
                            {{ $laporan->id_jenis_bbm==$b->id?'selected':'' }}>
                            {{ $b->nama_jenis }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                   <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Liter
                    </label>

                    <input type="number"
                        name="jumlah_liter"
                        value="{{ (int) $laporan->jumlah_liter }}" step="1" min="1"
                        required
                        class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Odometer
                    </label>

                    <input type="number"
                        name="odometer"
                        value="{{ $laporan->odometer }}"
                        class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                            focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="px-5 md:px-10 pb-6 md:pb-10">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea name="keterangan_admin" rows="4"
                    class="w-full px-4 py-2.5 md:py-3 border border-gray-400 rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $laporan->keterangan_admin }}</textarea>
            </div>

            <div class="px-5 md:px-10 py-4 md:py-6 border-t border-gray-300 bg-gray-50 
                        rounded-b-2xl flex flex-col md:flex-row gap-3 md:gap-0 
                        md:justify-between md:items-center">

                <a href="{{ route('admin.laporan-bbm.index') }}"
                   class="w-full md:w-auto text-center px-4 md:px-6 py-2 
                          border border-gray-400 text-gray-700 rounded-lg
                          hover:bg-gray-100 transition text-sm md:text-base">
                    Batal
                </a>

                <button type="submit"
                    class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white 
                           px-5 md:px-8 py-2.5 md:py-3 rounded-lg
                           text-sm md:text-base font-semibold shadow transition duration-200">
                    Simpan Perubahan
                </button>

            </div>

        </div>
    </form>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const kendaraanSelect = document.getElementById('kendaraanSelect');
    const jenisBBMSelect = document.getElementById('jenisBBMSelect');

    function updateBBM() {

        const opt = kendaraanSelect.options[kendaraanSelect.selectedIndex];
        const bbms = JSON.parse(opt.dataset.bbm || '[]');

        const selectedBBM = "{{ $laporan->id_jenis_bbm }}";

        jenisBBMSelect.innerHTML = '';

        bbms.forEach(bbm => {

            jenisBBMSelect.innerHTML += `
                <option value="${bbm.id}"
                    ${bbm.id == selectedBBM ? 'selected' : ''}>
                    ${bbm.nama_jenis}
                </option>
            `;
        });
    }

    kendaraanSelect.addEventListener('change', updateBBM);

    updateBBM();
});
</script>
@endsection