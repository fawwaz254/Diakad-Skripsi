<?php

namespace App\Models;

use App\Models\CalonSiswaBaru;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Siswa
 */
class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $primaryKey = 'id_siswa';

    protected $keyType = 'string';

    public $timestamps = true;

    public $incrementing = false;

    protected $fillable = [
        'id_siswa',
        'id_pengguna',
        'id_c_siswa',
        'id_kelompok_biaya',
        'id_kelas',
        'id_wali_murid',
        'is_orang_tua',
        'nis_siswa',
        'nisn_siswa',
        'thn_masuk_siswa',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function tunggakan_alumni()
    {
        return $this->belongsTo(TunggakanAlumni::class, 'nis_siswa', 'nis');
    }

    public function calon_siswa()
    {
        return $this->belongsTo(CalonSiswaBaru::class, 'id_c_siswa');
    }


    public function nilai_pribadi_sisipan()
    {
        return $this->hasmany(NilaiPribadiSisipan::class, 'id_siswa');
    }


    public function kpi()
    {
        return $this->hasMany(Kpi::class, 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withDefault([
            'nm_kelas' => '-'
        ]);
    }

    public function tagihan_biaya()
    {
        return $this->hasMany(TagihanBiaya::class, 'id_siswa');
    }

    public function tagihan_tertagih()
    {
        return $this->hasMany(TagihanBiaya::class, 'id_siswa')->where('is_tagih', 1);
    }

    public function wali_murid()
    {
        return $this->belongsTo(WaliMurid::class, 'id_wali_murid');
    }

    public function kegiatan_siswa()
    {
        return $this->hasMany(KegiatanSiswa::class, 'id_siswa');
    }

    public function prestasi_siswa()
    {
        return $this->hasMany(PrestasiSiswa::class, 'id_siswa');
    }

    public function informasi_tambahan()
    {
        return $this->hasMany(InformasiTambahan::class, 'id_siswa');
    }

    public function log_kelas_siswa()
    {
        return $this->hasMany(LogKelasSiswa::class, 'id_siswa');
    }

    public function nilai_rapor_sisipan()
    {
        return $this->hasMany(NilaiRaporSisipan::class, 'id_siswa');
    }

    public function nilai_rapor()
    {
        return $this->hasMany(NilaiRapor::class, 'id_siswa');
    }

    public function nilai_komponen_kpi()
    {
        return $this->hasMany(NilaiKomponenKpi::class, 'id_siswa');
    }

    public function last_kelas_siswa()
    {
        return $this->hasOne(LogKelasSiswa::class, 'id_siswa')->orderBy('created_at', 'desc');
    }

    public function pengajuan_wisuda()
    {
        return $this->hasOne(PengajuanWisuda::class, 'id_siswa');
    }

    public function pelanggaranTerlambat()
    {
        return $this->belongsTo(PelanggaranSiswa::class, 'id_siswa', 'id_siswa');
    }

    public function predikat_kpi()
    {
        return $this->hasMany(PredikatKPI::class, 'id_siswa', 'id_siswa');
    }

    public function wa_notif_kehadiran_siswa()
    {
        return $this->hasMany(WaNotifKehadiranSiswa::class, 'id_siswa');
    }

    public function all_tagihan()
    {
        $tagihan = $this->tagihan_biaya()->isTagih()->with(
            [
                'detail_biaya' => function ($q) {
                    return $q->isValid()->with('bulan', 'biaya', 'biaya_sekolah.semester')->orderBy('created_at', 'desc');
                }
            ]
        );

        $collection = collect();

        $tagihan->each(function ($item, $key) use ($collection) {
            $data['id_tagihan_biaya'] = $item->id_tagihan_biaya;
            $data['nm_semester'] = $item->detail_biaya->biaya_sekolah->semester->tahun_ajaran . " " . $item->detail_biaya->biaya_sekolah->semester->nm_semester;
            $data['kode_semester'] = $item->detail_biaya->biaya_sekolah->semester->kode_semester;
            if ($item->detail_biaya->id_jenis_detail_biaya == 4) {
                $data['nm_biaya'] = $item->detail_biaya->biaya->nm_biaya . " (" . $item->detail_biaya->bulan->nm_bulan . ")";
            } else {
                $data['nm_biaya'] = $item->nm_biaya . " " . $item->keterangan;
            }
            $data['besar_biaya'] = $item->besar_biaya;
            $data['besar_biaya_text'] = 'Rp' . number_format($item->besar_biaya);
            $collection->push($data);
        });

        return $collection;
    }

    public function scopeIsAktifWaliMurid($query, $id_wali_murid)
    {
        return $query->where('id_wali_murid', '=', $id_wali_murid)->where('is_aktif_wali_murid', 1);
    }

    public function scopePenggunaSekolah($query, $id_sekolah)
    {
        return $query->whereHas('pengguna', function ($q) use ($id_sekolah) {
            return $q->where('id_sekolah', $id_sekolah);
        });
    }
}
