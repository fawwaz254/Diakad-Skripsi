<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InformasiTambahan extends Model
{
    use SoftDeletes;

    protected $table = 'informasi_tambahan';

    protected $primaryKey = 'id_informasi_tambahan';

	public $timestamps = true;

    public $incrementing = false;
    
    protected $fillable = [
        'id_siswa',
        'id_kelas',
        'id_semester',
        'nm_informasi_tambahan',
        'status',
        'keterangan',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];
}
