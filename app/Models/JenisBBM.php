<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kendaraan;

class JenisBBM extends Model
{
    protected $table = 'jenis_bbms';

    protected $fillable = [
        'nama_jenis',
        'harga_per_liter'
    ];

    public function kendaraans()
    {
        return $this->belongsToMany(
            Kendaraan::class,
            'jenisbbm_kendaraan',
            'jenisbbm_id',
            'kendaraan_id'
        );
    }
}