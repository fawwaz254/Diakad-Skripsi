<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TutupBukuBulananKas
 */
class TutupBukuBulananKas extends Model
{
    use SoftDeletes;

    protected $table = 'tutup_buku_bulanan_kas';

    protected $primaryKey = 'id_tutup_buku_bulanan_kas';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester_mulai',
        'id_semester_selesai',
        'id_bulan',
        'kas_spp',
        'kas_rapb_penerimaan',
        'kas_rapb_pengeluaran',
        'kas_akhir_bulan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function semester_mulai()
    {
        return $this->belongsTo(Semester::class, 'id_semester_mulai','id_semester');
    }

    public function semester_selesai()
    {
        return $this->belongsTo(Semester::class, 'id_semester_selesai','id_semester');
    }

    public function scopeIsInputByPengguna($query, $id_pengguna){
        return $query->where('tutup_buku_bulanan_kas.created_by', $id_pengguna);
    }
}