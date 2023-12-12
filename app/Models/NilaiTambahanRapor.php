<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NilaiTambahanRapor extends Model
{
    use SoftDeletes;

    protected $table = 'nilai_tambahan_rapor';

    protected $primaryKey = 'id_nilai_tambahan_rapor';

    public $timestamps = true;

    public $incrementing = false;
    protected $fillable = [
        'id_tambahan_rapor',
        'id_siswa',
        'id_semester',
        'nilai',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $guarded = [];

    public function tambahan_rapor()
    {
        return $this->belongsTo(TambahanRapor::class, 'id_tambahan_rapor');
    }
}
