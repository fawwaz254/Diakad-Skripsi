<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PtkNilaiTes
 */
class PtkNilaiTes extends Model
{
    use SoftDeletes;

    protected $table = 'ptk_nilai_tes';

    protected $primaryKey = 'id_ptk_nilai_tes';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_staff',
        'id_guru',
        'jenis_tes',
        'nm_tes',
        'penyelenggara_tes',
        'tahun_tes',
        'nilai_skor_tes',
        'nomor_peserta_tes',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}