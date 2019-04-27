<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalonSiswaFisik
 */
class CalonSiswaFisik extends Model
{
    use SoftDeletes;

    protected $table = 'calon_siswa_fisik';

    protected $primaryKey = 'id_c_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_c_siswa',
        'tinggi_badan',
        'berat_badan',
        'is_berjilbab',
        'is_buta_warna',
        'ukuran_baju',
        'riwayat_penyakit',
        'golongan_darah',
        'riwayat_kelainan_jasmani',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}