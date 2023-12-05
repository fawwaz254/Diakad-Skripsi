<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisRapor extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_rapor';

    protected $primaryKey = 'id_jenis_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_kelas',
        'nm_jenis_rapor',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function komponen_jenis_rapor()
    {
        return $this->hasMany(KomponenJenisRapor::class, 'id_jenis_rapor');
    }
}
