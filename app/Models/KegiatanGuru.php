<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PrestasiSiswa
 */
class KegiatanGuru extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan_guru';

    protected $primaryKey = 'id_kegiatan_guru';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $guarded = [];






}