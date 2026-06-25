<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanBBM extends Model
{
    protected $table = 'permohonan_bbms';

    protected $fillable = [
        'id_user',
        'id_kendaraan',
        'id_jenis_bbm',
        'tanggal_permohonan',
        'jumlah_liter',
        'foto_sisa_bbm',
        'odometer',
        'odometer_sebelumnya',
        'status',
        'keterangan_admin',
    ];

    protected $casts = [
        'tanggal_permohonan' => 'date',
        'jumlah_liter' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    public function jenisbbm()
    {
        return $this->belongsTo(JenisBBM::class, 'id_jenis_bbm');
    }

    public function laporan()
    {
        return $this->hasOne(LaporanBBM::class, 'id_permohonan');
    }
}
