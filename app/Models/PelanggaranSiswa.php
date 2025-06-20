<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PelanggaranSiswa
 */
class PelanggaranSiswa extends Model
{
    use SoftDeletes;

    protected $table = 'pelanggaran_siswa';

    protected $primaryKey = 'id_pelanggaran_siswa';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_siswa',
        'id_guru_input',
        'id_subkategori_pelanggaran',
        'id_semester',
        'catatan_pelanggaran',
        'catatan_pelanggaran_khusus',
        'tgl_pelanggaran',
        'aktor_input_pelanggaran',
        'is_sudah_tindakan',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function aktor_input_to_text()
    {
        switch ($this->aktor_input_pelanggaran) {
            case 1:
                return 'Role BK';
                break;
            case 2:
                return 'Kesiswaan';
                break;
            case 3:
                return 'Wali Kelas';
                break;
            case 4:
                return 'Guru Reguler';
                break;
            case 5:
                return 'Guru Piket';
                break;
            default:
                return '';
                break;
        }
    }

    public function subkategori_pelanggaran()
    {
        return $this->belongsTo(SubkategoriPelanggaran::class, 'id_subkategori_pelanggaran');
    }
}
