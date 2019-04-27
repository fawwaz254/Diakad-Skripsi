<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;


class MagangSiswa extends Model
{
  use SoftDeletes;

  protected $table = 'magang';

  protected $primaryKey = 'id_magang';

  public $timestamps = true;

  public $incrementing = false;

  protected $fillable = [
      'nm_magang',
      'keterangan_magang',
      'id_sekolah',
      'created_by',
      'updated_by',
      'deleted_by'
  ];

  protected $guarded = [];






}
