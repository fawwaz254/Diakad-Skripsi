<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
    use SoftDeletes;

    protected $table = 'soal';

    protected $primaryKey = 'id_soal';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_pengguna',
        'id_pilihan_soal_benar',
        'content',
        'text',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = [];

    public function tipe_soal_to_text()
    {
        switch ($this->id_tipe_soal) {
            case 1:
                return 'Pilihan Ganda';
                break;
            case 2:
                return 'Isian';
                break;
            case 3:
                return 'File';
                break;
            case 4:
                return 'Pilihan Ganda Kompleks';
                break;
            case 5:
                return 'Isian Singkat';
                break;
            case 6:
                return 'Menjodohkan';
                break;
            case 7:
                return 'True/False';
                break;
            default:
                return '';
                break;
        }
    }

    public function pengguna()
    {
        return $this->belongsTo('App\Models\Pengguna', 'id_pengguna')->withDefault([
            'nm_pengguna' => ''
        ]);
    }

    public function kategori_soal()
    {
        return $this->belongsTo('App\Models\KategoriSoal', 'id_kategori_soal')->withDefault([
            'nm_kategori_soal' => ''
        ]);
    }

    public function pilihan_soal()
    {
        return $this->hasMany('App\Models\PilihanSoal', 'id_soal');
    }

    public function pilihan_pertanyaan()
    {
        return $this->hasMany('App\Models\PilihanPertanyaan', 'id_soal')->orderBy('nomer', 'asc');
    }

    public function pilihan_jawaban()
    {
        return $this->hasMany('App\Models\PilihanJawaban', 'id_soal')->orderBy('nomer', 'asc');
    }

    public function detail_paket_soal()
    {
        return $this->hasMany('App\Models\DetailPaketSoal', 'id_soal');
    }
}
