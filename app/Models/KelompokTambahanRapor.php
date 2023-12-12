<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelompokTambahanRapor extends Model
{
    use SoftDeletes;
    protected $table = 'kelompok_tambahan_rapor';

    protected $primaryKey = 'id_kelompok_tambahan_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'nm_kelompok_tambahan_rapor',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];


    public function tambahan_rapor()
    {
        return $this->hasMany(TambahanRapor::class, 'id_kelompok_tambahan_rapor');
    }
}
