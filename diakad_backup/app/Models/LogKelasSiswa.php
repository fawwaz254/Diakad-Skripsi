<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LogKelasSiswa
 */
class LogKelasSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'log_kelas_siswa';

    protected $primaryKey = 'id_log_kelas_siswa';

	public $timestamps = true;

    protected $fillable = [
        'id_siswa',
        'id_kelas',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }





}