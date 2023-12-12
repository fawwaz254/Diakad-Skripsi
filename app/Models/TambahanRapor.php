<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TambahanRapor extends Model
{
    use SoftDeletes;

    protected $table = 'tambahan_rapor';

    protected $primaryKey = 'id_tambahan_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelompok_tambahan_rapor',
        'nm_tambahan_rapor',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
    public function kelompok_tambahan_rapor()
    {
        return $this->belongsTo(KelompokTambahanRapor::class, 'id_kelompok_tambahan_rapor');
    }
}
