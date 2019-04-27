<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkKaryaTulis
 */
class PtkKaryaTulis extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_karya_tulis';

    protected $primaryKey = 'id_ptk_karya_tulis';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'judul_karya_tulis',
        'tahun_pembuatan_karya_tulis',
        'publikasi_karya_tulis',
        'keterangan_karya_tulis',
        'url_publikasi_karya_tulis',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}