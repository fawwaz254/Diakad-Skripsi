<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TutupBukuRapb
 */
class TutupBukuRapb extends Model
{
    use SoftDeletes;

    protected $table = 'tutup_buku_rapb';

    protected $primaryKey = 'id_tutup_buku_rapb';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_semester_mulai',
        'id_semester_selesai',
        'nm_tutup_buku_rapb',
        'jml_rapb_penerimaan',
        'jml_realisasi_penerimaan',
        'jml_selisih_penerimaan',
        'jml_rapb_pengeluaran',
        'jml_realisasi_pengeluaran',
        'jml_selisih_pengeluaran',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];






}