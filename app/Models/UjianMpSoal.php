<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UjianMpSoal
 */
class UjianMpSoal extends Model
{
    use SoftDeletes;

    protected $table = 'ujian_mp_soal';

    protected $primaryKey = 'id_ujian_mp_soal';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ujian_mp',
        'isi_soal',
        'isi_pilihan_a',
        'isi_pilihan_b',
        'isi_pilihan_c',
        'isi_pilihan_d',
        'isi_pilihan_e',
        'isi_pilihan_f',
        'kunci_pilihan',
        'kunci_esai',
        'nomor_soal',
        'is_random_soal',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}