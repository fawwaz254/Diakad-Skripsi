<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KomponenJenisRapor extends Model
{
    use SoftDeletes;

    protected $table = 'komponen_jenis_rapor';

    protected $primaryKey = 'id_komponen_jenis_rapor';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_jenis_rapor',
        'nm_komponen_jenis_rapor',
        'urutan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function jenis_rapor()
    {
        return $this->belongsTo(JenisRapor::class, 'id_jenis_rapor');
    }
}
