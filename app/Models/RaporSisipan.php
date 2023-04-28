<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RaporSisipan extends Model
{
    use SoftDeletes;

    protected $table = 'rapor_sisipan';

    protected $primaryKey = 'id_rapor_sisipan';

    public $timestamps = true;

    public $incrementing = false;
    protected $guarded = [];
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran');
    }
    public function nilai_rapor_sisipan()
    {
        return $this->hasMany(NilaiRaporSisipan::class, 'id_rapor_sisipan', 'id_rapor_sisipan');
    }
}
