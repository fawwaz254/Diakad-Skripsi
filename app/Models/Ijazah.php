<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ijazah extends Model
{
    use SoftDeletes;

    protected $table = 'ijazah';

    protected $primaryKey = 'id_ijazah';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_sekolah',
        'tgl_pengambilan_ijazah',
        'penerima_ijazah',
        'id_pemberi_ijazah',
        'catatan_ijazah',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}
