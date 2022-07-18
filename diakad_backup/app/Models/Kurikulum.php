<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Kurikulum
 */
class Kurikulum extends Model
{
    use SoftDeletes;

    protected $table = 'kurikulum';

    protected $primaryKey = 'id_kurikulum';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_jurusan',
        'id_semester_mulai',
        'nm_kurikulum',
        'tahun_kurikulum',
        'nomor_sk_kurikulum',
        'keterangan_kurikulum',
        'berlaku_mulai',
        'berlaku_sampai',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }

    public function mapel()
    {
        return $this->hasMany(KurikulumMp::class, 'id_kurikulum');
    }



}