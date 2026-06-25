<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanBBM extends Model
{
    protected $table = 'laporan_bbms';

    protected $fillable = [
        'id_permohonan',
        'id_admin',
        'id_driver',
        'id_kendaraan',
        'no_polisi',
        'id_jenis_bbm',
        'tanggal',
        'jumlah_liter',
        'harga_per_liter',
        'total_biaya',
        'keterangan_admin',
        'catatan_driver',
        'foto_driver',
        'odometer',
        'nama_driver',
        'nama_admin',
        'nama_jenis_bbm',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'id_driver');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    public function jenisBBM()
    {
        return $this->belongsTo(JenisBBM::class, 'id_jenis_bbm');
    }

    public function permohonan()
    {
        return $this->belongsTo(PermohonanBBM::class, 'id_permohonan');
    }
}
