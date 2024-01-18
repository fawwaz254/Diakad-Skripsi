<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Kelas
 */
class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelas';

    protected $primaryKey = 'id_kelas';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_jurusan',
        'nm_kelas',
        'tingkat',
        'keterangan_kelas',
        'is_aktif',
        'id_jenis_rapor',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function check_siswa()
    {
        return $this->siswa()->take(1);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function siswa_one()
    {
        return $this->hasOne(Siswa::class, 'id_kelas')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('nm_status_pengguna', '=', 'AKTIF');
        });
    }


    public function tagihan()
    {
        return $this->hasMany(TagihanBiaya::class, 'id_kelas');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }

    public function whatsapp_group()
    {
        return $this->hasOne(WhatsappGroup::class, 'id_kelas');
    }

    public function point_kpi()
    {
        return $this->hasMany(PointKPI::class, 'tingkat_kelas', 'tingkat');
    }

    public function jenis_rapor()
    {
        return $this->belongsTo(JenisRapor::class, 'id_jenis_rapor');
    }

    public function jenis_keterangan()
    {
        switch ($this->type_rapor) {
            case 1:
                return 'Otomatis';
                break;
            case 2:
                return '2 Kategori';
                break;
            case 3:
                return 'Manual';
                break;
            default:
                return '';
                break;
        }
    }
}
