@extends('layouts.admin')
@section('title','Detail Permohonan')

@section('content')

<h4>Detail Permohonan</h4>

<table class="table">
<tr>
    <td>Driver</td>
    <td>{{ $d->user->name }}</td>
</tr>

<tr>
    <td>Kendaraan</td>
    <td>{{ $d->kendaraan->no_polisi }}</td>
</tr>

<tr>
    <td>BBM</td>
    <td>{{ $d->jenisbbm->nama_jenis }}</td>
</tr>

<tr>
    <td>Liter</td>
    <td>{{ $d->jumlah_liter }}</td>
</tr>

<tr>
    <td>Status</td>
    <td>{{ $d->status }}</td>
</tr>
</table>

<a href="{{ route('admin.permohonan.index') }}"
   class="btn btn-secondary">
   Kembali
</a>

@endsection
