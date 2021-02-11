<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class LowonganKerja extends Model
{
    use SoftDeletes;

    protected $table = 'lowongan_kerja';

    protected $primaryKey = 'id_lowongan_kerja';

	public $timestamps = true;

    protected $fillable = [
        'id_lowongan_kerja',
        'deskripsi_lowongan_kerja',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}