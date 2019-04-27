<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PelatihEkskul
 */
class PelatihEkskul extends Model
{
    use SoftDeletes;

    protected $table = 'pelatih_ekskul';

    protected $primaryKey = 'id_pelatih_ekskul';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_pengguna',
        'nomor_hp_pelatih_ekskul',
        'alamat_pelatih_ekskul',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}