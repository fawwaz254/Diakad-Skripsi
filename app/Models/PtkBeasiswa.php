<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkBeasiswa
 */
class PtkBeasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_beasiswa';

    protected $primaryKey = 'id_ptk_beasiswa';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'jenis_beasiswa',
        'keterangan_beasiswa',
        'tahun_mulai_beasiswa',
        'tahun_selesai_beasiswa',
        'is_masih_menerima',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}