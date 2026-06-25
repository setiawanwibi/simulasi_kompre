<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JenisBBM;

class Kendaraan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'no_polisi',
        'merk',
        'jenis',
        'tahun',
        'odometer_terakhir',
    ];

    public function jenisbbms()
    {
        return $this->belongsToMany(
            JenisBBM::class,
            'jenisbbm_kendaraan',
            'kendaraan_id',
            'jenisbbm_id'
        );
    }
}