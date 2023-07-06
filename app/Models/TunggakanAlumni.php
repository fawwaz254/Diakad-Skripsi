<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TunggakanAlumni extends Model
{
    use SoftDeletes;

    protected $table = 'tunggakan_alumni';

    protected $primaryKey = 'id_tunggakan_alumni';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nis',
        'nm_siswa',
        'eks',
        'tahun_pelajaran',
        'jumlah_tunggakan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    protected $guarded = [];
}
