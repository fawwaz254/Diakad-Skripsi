<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UnitKerja
 */
class UnitKerja extends Model
{
    use SoftDeletes;

    protected $table = 'unit_kerja';

    protected $primaryKey = 'id_unit_kerja';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_unit_kerja',
        'deskripsi_unit_kerja',
        'tipe_unit_kerja',
        'id_unit_kerja_induk',
        'nm_singkatan_unit',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_unit_kerja', 'id_unit_kerja');
    }
}
