<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page{
    size:A4;
    margin:0;
}

body{
    font-family:Arial, Helvetica, sans-serif;
    background:#fff;
}

.nota{
    width:690px;
    margin:18px auto;
    border:1.5px solid #000;
    padding:16px 20px;
    transform:scale(0.92);
    transform-origin:top center;
}

.center{text-align:center;}
.bold{font-weight:bold;}

.header{
    border-bottom:1px solid #000;
    padding-bottom:6px;
    margin-bottom:10px;
}

.judul-nota{
    font-weight:bold;
    margin:6px 0 8px 0;
}

.box-kepada{
    border:1px solid #000;
    padding:6px;
    margin-bottom:10px;
    font-weight:bold;
}

.desc{
    margin-top:8px;
    line-height:1.5;
    text-align:justify;
}

.tbl{
    width:100%;
    margin-top:10px;
}

.tbl td{
    padding:2px 4px;
}

.tidak{
    border:3px solid red;
    color:red;
    font-weight:bold;
    width:130px;
    text-align:center;
    padding:6px;
    margin-top:10px;
}

.cap-bbm{
    border:3px solid #1e3a8a;
    color:#1e3a8a;
    font-weight:bold;
    width:170px;
    text-align:center;
    padding:10px 6px;
    margin-top:12px;
    font-size:28px;
    letter-spacing:1px;
    text-transform:uppercase;
}

.ttd-area{
    margin-top:15px;
}

.tanggal{
    text-align:center;
    margin-bottom:10px;
}

.pln{
    text-align:center;
    margin-top:10px;
}

.ttd-space{
    height:90px; 
}

.ttd-line{
    margin-top:5px;
}
</style>
</head>

<body>

<div class="nota">

<div class="header center bold">
PT PLN (PERSERO) UNIT INDUK DISTRIBUSI LAMPUNG<br>
UNIT PELAKSANA PELAYANAN PELANGGAN TANJUNG KARANG
</div>

<div class="center judul-nota">
NOTA PENGAMBILAN BAHAN BAKAR
</div>

<div class="box-kepada center">
KEPADA : SPBU 24.351.140 Jl. Pagar Alam Kedaton - B. Lampung
</div>

<div class="desc">
Harap kendaraan Dinas PT PLN (Persero) Unit Induk Distribusi Lampung 
Unit Pelaksana Pelayanan Pelanggan Tanjung Karang ini diisi Bahan Bakar Minyak (BBM)
PERTAMAX TURBO/PERTAMAX/PERTALITE/DEXLITE/OLI*)
</div>

<table class="tbl">
<tr>
<td width="220">No. Polisi</td>
<td>: {{ $laporan->no_polisi ?? '-' }}</td>
</tr>

<tr>
<td>Jumlah Pengisian</td>
<td>: {{ $laporan->jumlah_liter }} Liter</td>
</tr>

<tr>
<td>Jenis BBM</td>
<td>: {{ strtoupper($laporan->nama_jenis_bbm ?? '-') }}</td>
</tr>

<tr>
<td>Untuk Pemakaian Tanggal</td>
<td>: {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</td>
</tr>
</table>

<br>

<table width="100%">
<tr>

<td width="40%" valign="top">

<div class="tidak">
TIDAK DAPAT<br>DIUANGKAN
</div>

<div class="cap-bbm">
{{ strtoupper($laporan->nama_jenis_bbm ?? '-') }}
</div>

</td>
<td>

<div class="ttd-area">

<div class="tanggal">
Bandar Lampung, {{ \Carbon\Carbon::now()->format('d-m-Y') }}
</div>

<div class="pln">
PT. PLN (Persero) UID LAMPUNG<br>
UNIT PELAKSANA PELAYANAN PELANGGAN<br>
TANJUNG KARANG

<div class="ttd-space"></div>

<div class="ttd-line">
( ............................................. )
</div>

</div>

</div>

</td>

</tr>
</table>

</div>

</body>
</html>