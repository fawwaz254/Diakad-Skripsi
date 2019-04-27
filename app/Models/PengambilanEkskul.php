<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PengambilanEkskul
 */
class PengambilanEkskul extends Model
{
    use SoftDeletes;

    protected $table = 'pengambilan_ekskul';

    protected $primaryKey = 'id_pengambilan_ekskul';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_ekskul',
        'id_siswa',
        'id_semester',
        'nilai_angka',
        'nilai_huruf',
        'persentase_presensi',
        'is_tampil',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}