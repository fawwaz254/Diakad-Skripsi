<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Semester
 */
class Semester extends Model
{
    use SoftDeletes;

    protected $table = 'semester';

    protected $primaryKey = 'id_semester';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'nm_semester',
        'thn_akademik_semester',
        'tahun_ajaran',
        'is_aktif_semester',
        'kode_semester',
        'id_sekolah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function is_aktif_to_text(){
        if($this->is_aktif_semester == 1){
            return 'Aktif';
        }else{
            return 'Non-Aktif';
        }
    }

    public function isAktif(){
        if($this->is_aktif_semester == 1){
            return true;
        }else{
            return false;
        }
    }

    public function semesterLengkap(){
        return $this->tahun_ajaran . ' ('. $this->nm_semester . ')';
    }
}