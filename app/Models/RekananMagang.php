<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RekananMagang extends Model
{
  use SoftDeletes;

  use SoftDeletes;

  protected $table = 'rekanan_magang';

  protected $primaryKey = 'id_rekanan_magang';

  public $timestamps = true;

  public $incrementing = false;

  protected $fillable = [
    'nm_rekanan_magang',
    'nomor_telp_rekanan_magang',
    'nomor_hp_rekanan_magang',
    'alamat_rekanan_magang',
    'tgl_awal_kerjasama',
    'tgl_akhir_kerjasama',
    'kuota_rekanan_magang',
    'contact_person_rekanan_magang',
    'id_sekolah',
    'created_by',
    'updated_by',
    'deleted_by'
  ];

  protected $guarded = [];

  public function pengambilanMagang()
  {
    return $this->hasMany(PengambilanMagang::class, 'id_rekanan_magang');
  }
}
