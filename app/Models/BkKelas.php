<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WaliKelas
 */
class BkKelas extends Model
{
    use SoftDeletes;

    protected $table = 'bk_kelas';

    protected $primaryKey = 'id_bk_kelas';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_kelas',
        'id_guru',
        'id_semester',
        'is_aktif',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function guru(){
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }


}