<?php

namespace App\Models;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniWirausaha extends Model
{
    use SoftDeletes;

    protected $table = 'alumni_wirausaha';
    protected $primaryKey = 'id_alumni_wirausaha';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id_alumni_wirausaha',
        'id_alumni',
        'nm_usaha',
        'alamat',
        'kontak',
        'bidang_usaha',
        'jumlah_karyawan',
        'tahun_rintis',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni');
    }
}
