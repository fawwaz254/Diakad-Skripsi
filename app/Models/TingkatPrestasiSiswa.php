<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TingkatPrestasiSiswa
 */
class TingkatPrestasiSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'tingkat_prestasi_siswa';

    protected $primaryKey = 'id_tingkat_prestasi_siswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_tingkat_prestasi_siswa',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}