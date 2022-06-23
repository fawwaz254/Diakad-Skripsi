<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Agama
 */
class MateriAjar extends Model
{
    use SoftDeletes;

    protected $table = 'materi_ajar';

    protected $primaryKey = 'id_materi_ajar';

	public $timestamps = true;

    public $incrementing = false;

    protected $guarded = [];

    public function materi_ajar_file()
    {
        return $this->hasMany(MateriAjarFile::class, 'id_materi_ajar','id_materi_ajar');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class,'tingkat', 'id_kelas');
    }

    public function  materi_ajar_view(){
        return $this->hasMany(MateriAjarView::class, 'id_materi_ajar','id_materi_ajar');
    }


}