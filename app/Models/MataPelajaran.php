<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MataPelajaran
 */
class MataPelajaran extends Model
{
    use SoftDeletes;

    protected $table = 'mata_pelajaran';

    protected $primaryKey = 'id_mata_pelajaran';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_jurusan',
        'id_jenis_mata_pelajaran',
        'kd_mata_pelajaran',
        'nm_mata_pelajaran',
        'nm_mata_pelajaran_en',
        'kredit_semester',
        'kredit_tatap_muka',
        'kredit_praktikum',
        'kredit_tutor',
        'kredit_prak_lapangan',
        'kredit_simulasi',
        'tingkat_semester',
        'nilai_kkm',
        'ada_sap',
        'ada_silabus',
        'ada_bahan_ajar',
        'ada_diktat',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function jenis_mata_pelajaran()
    {
        return $this->belongsTo('App\Models\JenisMataPelajaran', 'id_jenis_mata_pelajaran');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Models\Jurusan', 'id_jurusan');
    }

    public function urutan_rapor_sisipan()
    {
        return $this->belongsTo('App\Models\UrutanRaporSisipan', 'id_mata_pelajaran', 'id_mata_pelajaran')->withDefault([
            'urutan' => 99,
        ]);;
    }
}
