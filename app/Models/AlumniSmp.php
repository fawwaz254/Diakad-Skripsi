<?php

namespace App\Models;

use App\Traits\BaseModelTraits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniSmp extends Model
{
  use SoftDeletes;
  use BaseModelTraits;

  protected $table = 'alumni_smp';
  protected $primaryKey = 'id_alumni_smp';
  public $timestamps = true;
  public $incrementing = false;

  protected $fillable = [
    'id_alumni_smp',
    'id_alumni',
    'nm_sekolah',
    'alamat_sekolah',
    'jurusan',
    'jenis_sekolah',
    'tahun_masuk_sekolah',
    'created_by',
    'updated_by',
    'deleted_by'
  ];
}
