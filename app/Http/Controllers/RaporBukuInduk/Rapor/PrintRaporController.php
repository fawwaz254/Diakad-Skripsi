<?php

namespace App\Http\Controllers\RaporBukuInduk\Rapor;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Ekskul;
use App\Models\Kota;
use App\Models\LogKelasSiswa;
use App\Models\NilaiMp;
use App\Models\PengambilanEkskul;
use App\Models\PengambilanMagang;
use App\Models\PengambilanMp;
use App\Models\PresensiEkskulPeserta;
use App\Models\PresensiMpSiswa;
use App\Models\PrestasiSiswa;
use App\Models\RaporDeskripsi;
use App\Models\RaporKategori;
use App\Models\RaporKelompok;
use App\Models\RaporKelompokMp;
use App\Models\RaporSiswa;
use App\Models\RaporSubkelompokMp;
use App\Models\Siswa as Siswa;
use App\Models\StandarNilai;
use App\Models\WaliKelas;
use Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Session;
use Validator;

class PrintRaporController extends BaseController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function printRaporSiswa()
    {
        $request = $this->request;
        $input = (object) $request->input();
        $auth_data = auth_data();
        $id_siswa = $input->id_siswa;
        $id_kelas = $input->id_kelas;
        $id_semester = $input->id_semester;
        // $keputusan = $input->keputusan;
        $catatan = $input->deskripsi_catatan_wali_kelas;
        $now = Carbon::now();

        // get all data rapor by id_siswa, id_kelas
        $data_rapor = RaporSiswa::where([
            'id_siswa' => $id_siswa,
            'id_kelas' => $id_kelas,
            'id_semester' => $id_semester
        ])->get();

        $pengambilanMpAll = PengambilanMp::where('id_siswa', $id_siswa)
            ->where('kelas_mp.id_kelas', $id_kelas)
            ->where('pengambilan_mp.id_semester', $id_semester)
            ->join('kelas_mp', function ($join) {
                $join->on('kelas_mp.id_kelas_mp', '=', 'pengambilan_mp.id_kelas_mp');
                $join->whereNull('kelas_mp.deleted_at');
            })
            ->join('mata_pelajaran', function ($join) {
                $join->on('kelas_mp.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran');
                $join->whereNull('mata_pelajaran.deleted_at');
            })
            ->join('jenis_mata_pelajaran', function ($join) {
                $join->on('jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran');
                $join->whereNull('jenis_mata_pelajaran.deleted_at');
            })
            ->get();
        // dd($pengambilanMpAll);

        $presensiMpSiswa = PresensiMpSiswa::join('presensi_mp', function ($join) {
            $join->on('presensi_mp.id_presensi_mp', '=', 'presensi_mp_siswa.id_presensi_mp');
            $join->whereNull('presensi_mp.deleted_at');
        })
            ->join('kelas_mp', function ($join) {
                $join->on('kelas_mp.id_kelas_mp', '=', 'presensi_mp.id_kelas_mp');
                $join->whereNull('kelas_mp.deleted_at');
            })
            ->where('id_siswa', $id_siswa)
            ->where('id_kelas', $id_kelas)
            ->where('id_semester', $id_semester)
            ->get();

        // Modul Nilai ekskul belum berjalan (29-dec), jadi masih input manual direct ke DB 
        $pengambilanEkskulAll = PengambilanEkskul::where('id_siswa', $id_siswa)
            ->where('id_kelas', $id_kelas)
            ->where('id_semester', $id_semester)
            ->join('ekskul', function ($join) {
                $join->on('ekskul.id_ekskul', '=', 'pengambilan_ekskul.id_ekskul');
                $join->whereNull('ekskul.deleted_at');
            })
            ->get();

        $presensiEkskulSiswa = PresensiEkskulPeserta::select('presensi_ekskul_peserta.*', 'ekskul.id_ekskul', 'ekskul.nm_ekskul')
            ->join('presensi_ekskul', function ($join) {
                $join->on('presensi_ekskul.id_presensi_ekskul', '=', 'presensi_ekskul_peserta.id_presensi_ekskul');
                $join->whereNull('presensi_ekskul.deleted_at');
            })
            ->join('ekskul', function ($join) {
                $join->on('ekskul.id_ekskul', '=', 'presensi_ekskul.id_ekskul');
                $join->whereNull('ekskul.deleted_at');
            })
            ->where('id_siswa', $id_siswa)
            ->where('id_kelas', $id_kelas)
            ->where('id_semester', $id_semester)
            ->get();

        $standar_nilai = StandarNilai::get();
        $all_ekskul = Ekskul::get();

        // $text_keputusan = RaporDeskripsi::KEPUTUSAN[$keputusan];

        DB::beginTransaction();
        try {
            // pengisian data rapor = Mapel
            foreach ($pengambilanMpAll as $mp) {
                // insert rapor_deskripsi first, then rapor_siswa
                $rapor_siswa = null;
                if ($data_rapor->isNotEmpty())
                    $rapor_siswa = $data_rapor->shift();

                // start rapor_deskripsi
                if (isset($rapor_siswa->id_rapor_deskripsi) && !empty($rapor_siswa->id_rapor_deskripsi)) {
                    $rapor_deskripsi = RaporDeskripsi::find($rapor_siswa->id_rapor_deskripsi);
                } else {
                    $rapor_deskripsi = new RaporDeskripsi();
                    $rapor_deskripsi->id_rapor_deskripsi = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                }

                $rapor_kelompok_mp = RaporKelompokMp::where('id_mata_pelajaran', $mp->id_mata_pelajaran)->first();
                $rapor_subkelompok_mp = RaporSubkelompokMp::where('id_mata_pelajaran', $mp->id_mata_pelajaran)->first();
                if (empty($rapor_kelompok_mp) && !empty($rapor_subkelompok_mp))
                    $rapor_kelompok_mp = RaporKelompokMp::find($rapor_subkelompok_mp->id_rapor_kelompok_mp);

                $rapor_deskripsi->id_rapor_subkategori      = null;
                $rapor_deskripsi->id_rapor_kelompok_mp      = !empty($rapor_kelompok_mp) ? $rapor_kelompok_mp->id_rapor_kelompok_mp : null;
                $rapor_deskripsi->id_rapor_subkelompok_mp   = !empty($rapor_subkelompok_mp) ? $rapor_subkelompok_mp->id_rapor_subkelompok_mp : null;
                $rapor_deskripsi->id_ekstrakurikuler        = null;
                $rapor_deskripsi->predikat_rapor_deskripsi  = $mp->nilai_huruf;
                // $rapor_deskripsi->deskripsi_rapor           = $text_keputusan; // ini akan diisi kenaikan kelas (rapor smt GENAP)
                if (empty($rapor_deskripsi->created_by)) {
                    $rapor_deskripsi->created_by            = auth_data()->pengguna->id_pengguna;
                } else {
                    $rapor_deskripsi->updated_by            = auth_data()->pengguna->id_pengguna;
                }
                $rapor_deskripsi->save();
                // end rapor_deskripsi

                // start rapor_siswa
                if (empty($rapor_siswa)) {
                    $rapor_siswa = new RaporSiswa();
                    $rapor_siswa->id_rapor_siswa     = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                }

                $sakit = $presensiMpSiswa->where('kehadiran', 2)->where('id_mata_pelajaran', $mp->id_mata_pelajaran)->count();
                $izin = $presensiMpSiswa->where('kehadiran', 3)->where('id_mata_pelajaran', $mp->id_mata_pelajaran)->count();
                $tanpa_keterangan = $presensiMpSiswa->where('kehadiran', 4)->where('id_mata_pelajaran', $mp->id_mata_pelajaran)->count();

                $rapor_siswa->id_siswa           = $id_siswa;
                $rapor_siswa->id_kelas           = $id_kelas;
                $rapor_siswa->id_semester        = $mp->id_semester;
                $rapor_siswa->id_rapor_deskripsi = $rapor_deskripsi->id_rapor_deskripsi;
                $rapor_siswa->jumlah_sakit       = $sakit;
                $rapor_siswa->jumlah_izin        = $izin;
                $rapor_siswa->jumlah_tanpa_keterangan = $tanpa_keterangan;
                $rapor_siswa->id_prestasi_siswa  = null; //belum diisi
                $rapor_siswa->deskripsi_catatan_wali_kelas = $catatan;
                $rapor_siswa->nilai_kkm          = $mp->nilai_kkm;
                $rapor_siswa->nilai_angka        = round($mp->nilai_angka);
                $rapor_siswa->nilai_huruf        = $mp->nilai_huruf;
                if (empty($rapor_siswa->created_by)) {
                    $rapor_siswa->created_by         = auth_data()->pengguna->id_pengguna;
                } else {
                    $rapor_siswa->updated_by         = auth_data()->pengguna->id_pengguna;
                }
                $rapor_siswa->save();
                // end rapor_siswa
            }

            // pengisian data rapor = Ekskul
            foreach ($pengambilanEkskulAll as $ekskul) {
                // insert rapor_deskripsi first, then rapor_siswa
                $rapor_siswa_ekskul = null;
                if ($data_rapor->isNotEmpty())
                    $rapor_siswa_ekskul = $data_rapor->shift();

                // start rapor_deskripsi
                if (isset($rapor_siswa_ekskul->id_rapor_deskripsi) && !empty($rapor_siswa_ekskul->id_rapor_deskripsi)) {
                    $rapor_deskripsi_ekskul = RaporDeskripsi::find($rapor_siswa_ekskul->id_rapor_deskripsi);
                } else {
                    $rapor_deskripsi_ekskul = new RaporDeskripsi();
                    $rapor_deskripsi_ekskul->id_rapor_deskripsi = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                }

                $deskripsi_nilai_ekskul = $ekskul->nilai_huruf;

                $rapor_deskripsi_ekskul->id_rapor_subkategori = null;
                $rapor_deskripsi_ekskul->id_rapor_kelompok_mp = null;
                $rapor_deskripsi_ekskul->id_rapor_subkelompok_mp = null;
                $rapor_deskripsi_ekskul->id_ekstrakurikuler = $ekskul->id_ekskul;
                $rapor_deskripsi_ekskul->predikat_rapor_deskripsi = $ekskul->nilai_huruf;
                $rapor_deskripsi_ekskul->deskripsi_rapor = $deskripsi_nilai_ekskul;
                $rapor_deskripsi_ekskul->created_by = auth_data()->pengguna->id_pengguna;
                $rapor_deskripsi_ekskul->save();
                // end rapor_deskripsi

                // start rapor_siswa_ekskul
                if (empty($rapor_siswa_ekskul)) {
                    $rapor_siswa_ekskul = new RaporSiswa();
                    $rapor_siswa_ekskul->id_rapor_siswa     = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                }

                $sakit = $presensiEkskulSiswa->where('kehadiran', 2)
                    ->where('id_ekskul', $ekskul->id_ekskul)
                    ->count();
                $izin = $presensiEkskulSiswa->where('kehadiran', 3)
                    ->where('id_ekskul', $ekskul->id_ekskul)
                    ->count();
                $tanpa_keterangan = $presensiEkskulSiswa->where('kehadiran', 4)
                    ->where('id_ekskul', $ekskul->id_ekskul)
                    ->count();

                $rapor_siswa_ekskul->id_siswa           = $id_siswa;
                $rapor_siswa_ekskul->id_kelas           = $id_kelas;
                $rapor_siswa_ekskul->id_semester        = $ekskul->id_semester;
                $rapor_siswa_ekskul->id_rapor_deskripsi = $rapor_deskripsi_ekskul->id_rapor_deskripsi;
                $rapor_siswa_ekskul->jumlah_sakit       = $sakit;
                $rapor_siswa_ekskul->jumlah_izin        = $izin;
                $rapor_siswa_ekskul->jumlah_tanpa_keterangan = $tanpa_keterangan;
                $rapor_siswa_ekskul->id_prestasi_siswa  = null; //belum diisi
                $rapor_siswa_ekskul->deskripsi_catatan_wali_kelas = $catatan;
                $rapor_siswa_ekskul->nilai_kkm          = null;
                $rapor_siswa_ekskul->nilai_angka        = round($ekskul->nilai_angka);
                $rapor_siswa_ekskul->nilai_huruf        = $ekskul->nilai_huruf;
                $rapor_siswa_ekskul->created_by         = auth_data()->pengguna->id_pengguna;
                $rapor_siswa_ekskul->save();
                // end rapor_siswa_ekskul
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return [
                'status'     => 300, // GAGAL
                'message'    => 'Insert Data Rapor Siswa Gagal'
            ];
        }

        if ($data_rapor->isNotEmpty()) {
            // delete old data_rapor remains
            // delete rapor_siswa then rapor_deskripsi
            $rapor_deskripsi_ids = $data_rapor->pluck('id_rapor_deskripsi');
            $rapor_siswa_ids = $data_rapor->pluck('id_rapor_siswa');
            $rapor_siswa_delete = RaporSiswa::whereIn('id_rapor_siswa', $rapor_siswa_ids)->delete();
            $rapor_deskripsi_delete = RaporDeskripsi::whereIn('id_rapor_deskripsi', $rapor_deskripsi_ids)->delete();
        }

        $format_rapor_kategori = RaporKategori::orderBy('urutan_rapor_kategori')->get();
        $format_rapor_kelompok = RaporKelompok::orderBy('urutan_rapor_kelompok')->get();
        $format_rapor_kelompok_mp = RaporKelompokMp::leftJoin('rapor_subkelompok_mp', 'rapor_kelompok_mp.id_rapor_kelompok_mp', 'rapor_subkelompok_mp.id_rapor_kelompok_mp')->get();

        $new_data_rapor = RaporSiswa::select(
            'rapor_siswa.*',
            'rapor_deskripsi.*',
            'rapor_kelompok_mp.id_rapor_kelompok',
            'rapor_kelompok_mp.id_rapor_kelompok_mp',
            'rapor_kelompok_mp.nm_rapor_kelompok_mp',
            'rapor_kelompok_mp.urutan_rapor_kelompok_mp',
            'mp.id_mata_pelajaran',
            'mp.nm_mata_pelajaran',
            'semester.nm_semester',
            'semester.thn_akademik_semester',
            'semester.tahun_ajaran'
        )
            ->join('rapor_deskripsi', function ($join) {
                $join->on('rapor_siswa.id_rapor_deskripsi', 'rapor_deskripsi.id_rapor_deskripsi');
                $join->whereNull('rapor_deskripsi.deleted_at');
            })
            ->leftJoin('rapor_subkategori', function ($join) {
                $join->on('rapor_deskripsi.id_rapor_subkategori', 'rapor_subkategori.id_rapor_subkategori');
                $join->whereNull('rapor_subkategori.deleted_at');
            })
            ->leftJoin('rapor_kelompok_mp', function ($join) {
                $join->on('rapor_deskripsi.id_rapor_kelompok_mp', 'rapor_kelompok_mp.id_rapor_kelompok_mp');
                $join->whereNull('rapor_kelompok_mp.deleted_at');
            })
            ->leftJoin('mata_pelajaran as mp', function ($join) {
                $join->on('rapor_kelompok_mp.id_mata_pelajaran', '=', 'mp.id_mata_pelajaran');
                $join->where('rapor_kelompok_mp.id_mata_pelajaran', '!=', null);
            })
            ->join('semester', function ($join) {
                $join->on('rapor_siswa.id_semester', 'semester.id_semester');
                $join->whereNull('semester.deleted_at');
            })
            ->where('rapor_deskripsi.id_ekstrakurikuler', '=', null)
            ->where([
                'id_siswa' => $id_siswa,
                'id_kelas' => $id_kelas,
                'rapor_siswa.id_semester' => $id_semester
            ])->get();
        // dd($new_data_rapor);
        // get all data detail rapor based on new rapor_siswa
        $data_detail_rapor = [];
        $j = 20;
        foreach ($new_data_rapor as $new_rapor) {
            $rapor = $new_rapor;
            if ($rapor->nm_matapelajaran == null && $rapor->nm_rapor_kelompok_mp != null) {
                $subKelompok = RaporSubkelompokMp::find($rapor->id_rapor_subkelompok_mp);
                $rapor->id_mata_pelajaran = $subKelompok->mata_pelajaran->id_mata_pelajaran;
                $rapor->nm_mata_pelajaran = $subKelompok->mata_pelajaran->nm_mata_pelajaran;
            }

            // get all nilai from nilai_mp based on komponen_nilai
            $idPengambilanMp = $pengambilanMpAll->where('id_mata_pelajaran', $rapor->id_mata_pelajaran)->first()->id_pengambilan_mp ?? '';

            $allNilai = NilaiMp::where('id_pengambilan_mp', $idPengambilanMp)
                ->join('komponen_mp', function ($join) {
                    $join->on('komponen_mp.id_komponen_mp', 'nilai_mp.id_komponen_mp');
                    $join->whereNull('komponen_mp.deleted_at');
                })
                ->get();

            $nilai_komponen = []; // umumnya nilai pengetahuan & keterampilan
            foreach ($allNilai as $nilai) {
                $nilai_komponen[$nilai->nm_komponen_mp] = $nilai->besar_nilai_mp;
            }

            $rapor->nilai_komponen = $nilai_komponen;
            $rapor->urutan = $rapor->urutan_rapor_kelompok_mp;

            if ($rapor->nm_matapelajaran == null && $rapor->nm_rapor_kelompok_mp != null) {
                $rapor->urutan = $j + $rapor->urutan_rapor_kelompok_mp;
                $j++;
            }

            $data_detail_rapor[] = $rapor;
        }

        $data_ekskul = RaporSiswa::select('*')
            ->join('rapor_deskripsi', 'rapor_siswa.id_rapor_deskripsi', 'rapor_deskripsi.id_rapor_deskripsi')
            ->join('ekskul', 'ekskul.id_ekskul', '=', 'rapor_deskripsi.id_ekstrakurikuler')
            ->where('id_ekstrakurikuler', '!=', null)
            ->where([
                'id_siswa' => $id_siswa,
                'id_kelas' => $id_kelas,
                'id_semester' => $id_semester
            ])->get();

        $data_magang = PengambilanMagang::select('pengambilan_magang.*', 'rekanan_magang.nm_rekanan_magang', 'rekanan_magang.alamat_rekanan_magang', 'periode_magang.nm_periode_magang', 'periode_magang.tgl_magang_mulai', 'periode_magang.tgl_magang_selesai')
            ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', 'pengambilan_magang.id_rekanan_magang')
            ->join('periode_magang', 'periode_magang.id_periode_magang', 'pengambilan_magang.id_periode_magang')
            ->where('status_apv_pengambilan_magang', 1)
            ->where([
                'id_siswa' => $id_siswa,
                'id_kelas' => $id_kelas,
                'id_semester' => $id_semester
            ])->get();

        $data_prestasi = PrestasiSiswa::where([
            'id_siswa' => $id_siswa,
            'id_kelas' => $id_kelas,
            'id_semester' => $id_semester
        ])->get();

        $presensi = [
            'sakit' => collect($data_detail_rapor)->sum('jumlah_sakit'),
            'izin' => collect($data_detail_rapor)->sum('jumlah_izin'),
            'tanpa_keterangan' => collect($data_detail_rapor)->sum('jumlah_tanpa_keterangan')
        ];

        $data_siswa = Siswa::find($id_siswa);
        $wali_kelas = WaliKelas::join('guru', function ($join) {
            $join->on('guru.id_guru', '=', 'wali_kelas.id_guru');
            $join->whereNull('guru.deleted_at');
        })
            ->join('pengguna', function ($join) {
                $join->on('pengguna.id_pengguna', '=', 'guru.id_pengguna');
                $join->whereNull('pengguna.deleted_at');
            })
            ->where('id_kelas', $id_kelas)
            ->where('id_semester', $id_semester)
            ->first();

        $kepala_sekolah = $auth_data->sekolah_data->nm_kepala_sekolah;
        $nip_kepala_sekolah = $auth_data->sekolah_data->nip_kepala_sekolah;
        $kota = Kota::find($auth_data->sekolah_data->alamat_kota);

        $pdf = PDF::loadView('rapor-buku-induk/rapor/cari-siswa/download-rapor-siswa', compact('auth_data', 'data_detail_rapor', 'data_siswa', 'data_ekskul', 'data_magang', 'data_prestasi', 'presensi', 'format_rapor_kategori', 'format_rapor_kelompok', 'format_rapor_kelompok_mp', 'wali_kelas', 'kepala_sekolah', 'nip_kepala_sekolah', 'kota'))->setPaper('legal', 'potrait');

        return [
            'status'     => 200, // SUCCESS
            'message'    => 'Insert Data Rapor Siswa Berhasil',
            'pdf' => $pdf
        ];
    }
}
