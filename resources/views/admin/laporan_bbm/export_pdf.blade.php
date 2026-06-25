<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    size: A4 portrait;
    margin: 20px;
}

body{
    font-family: Arial, sans-serif;
    font-size: 13px;
}

.header-wrap{
    width:100%;
    position:relative;
    margin-bottom:5px;
}

.logo{
    position:absolute;
    left:0;
    top:2;
    width:40px;
}

.title{
    text-align:center;
    padding-top:5px;
}

.title h1{
    margin:0;
    font-size:20px;
    font-weight:bold;
}

.title h2{
    margin:0;
    font-size:20px;
}

.line{
    border-bottom:2px solid #000;
    margin-top:20px;
    margin-bottom:10px;
}

.info{
    margin-bottom:10px;
    font-size:12px;
}

table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
}

th, td{
    border:1px solid #000;
    padding:4px;
    text-align:center;
    font-size:12px;
}

th{
    background:#eee;
}

.col-no{ width:20px; }
.col-tgl{ width:70px; }
.col-liter{ width:60px; }
.col-biaya{ width:90px; }
.col-driver{ width:140px; }
.col-km{ width:60px; }
.col-paraf{ width:60px; }
.col-polisi{ width:110px; }
.col-ket{ width:auto; }

/* supaya kolom no kecil */
td:first-child,
th:first-child{
    padding-left:2px;
    padding-right:2px;
}

.paraf{
    height:26px;
}
</style>
</head>

<body>

{{-- HEADER --}}
<div class="header-wrap">

<img src="{{ public_path('logo_pln.jpg') }}" class="logo">

<div class="title">
<h1>MONITORING KENDARAAN DINAS OPERASIONAL</h1>
<h2>PT PLN (PERSERO) UP3 TANJUNG KARANG</h2>
</div>

</div>

<div class="line"></div>

<div class="info">
Tanggal Rekap Laporan : {{ now()->format('d-m-Y H:i') }} <br>

@if(request('start'))
Periode :
{{ \Carbon\Carbon::parse(request('start'))->translatedFormat('d F Y') }}
-
{{ \Carbon\Carbon::parse(request('end'))->translatedFormat('d F Y') }}
@endif
</div>

<table>

<thead>
<tr>
<th class="col-no">No</th>
<th class="col-tgl">Tanggal</th>
<th class="col-liter">Liter</th>
<th class="col-harga">Biaya</th>
<th class="col-driver">Driver</th>
<th class="col-km">Stand KM</th>
<th class="col-paraf">Paraf</th>
<th class="col-polisi">No Polisi</th>
<th class="col-ket">Keterangan</th>
</tr>
</thead>


<tbody>

@foreach($laporans as $l)

<tr>

<td class="col-no">{{ $loop->iteration }}</td>

<td>
{{ \Carbon\Carbon::parse($l->tanggal)->format('d/m/Y') }}
</td>

<td>
{{ $l->jumlah_liter }}
</td>

<td>
Rp. {{ number_format($l->jumlah_liter * ($l->harga_per_liter ?? 0)) }}
</td>

<td>
{{ $l->driver?->name ?? '-' }}
</td>

<td>
{{ $l->odometer }}
</td>

<td class="paraf"></td>

<td>
{{ $l->no_polisi ?? '-' }}
</td>

<td>
{{ $l->keterangan_admin }}
</td>

</tr>

@endforeach

</tbody>


<tfoot>
<tr>
<th colspan="2">TOTAL</th>
<th>{{ number_format($totalLiter,1) }}</th>

<th>
Rp. {{ number_format($laporans->sum(fn($l) => $l->jumlah_liter * ($l->harga_per_liter ?? 0))) }}
</th>

<th colspan="5"></th>
</tr>
</tfoot>

</table>


<br><br><br>

<table width="100%" style="border:none;">
<tr style="border:none;">

<td width="60%" style="border:none;"></td>

<td align="center" style="border:none;">
Mengetahui,<br>
Supervisor Keuangan & Umum<br><br><br><br><br>

( __________________ )
</td>

</tr>
</table>

</body>
</html>