<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TutupBukuTahunanBiaya
 */
class TutupBukuTahunanBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'tutup_buku_tahunan_biaya';

    protected $primaryKey = 'id_tutup_buku_tahunan_biaya';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester_mulai',
        'id_semester_selesai',
        'jml_tunggakan_biaya',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function scopeIsInputByPengguna($query, $id_pengguna){
        return $query->where('tutup_buku_tahunan_biaya.created_by', $id_pengguna);
    }

    public function semester_mulai()
    {
        return $this->belongsTo(Semester::class, 'id_semester_mulai','id_semester');
    }

    public function semester_selesai()
    {
        return $this->belongsTo(Semester::class, 'id_semester_selesai','id_semester');
    }
}