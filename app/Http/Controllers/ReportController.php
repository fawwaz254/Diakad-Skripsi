<?php

namespace App\Http\Controllers;

use Auth;
use Session;

use Validator;
use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Role;
use App\Models\Kelas;
use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\Staff;
use App\Models\Ekskul;
use App\Models\Gedung;
use App\Models\KelasMp;
use App\Models\Ruangan;
use App\Models\Sekolah;
use App\Models\BukuAlat;
use App\Models\Pengguna;
use App\Models\Semester;
use Carbon\CarbonPeriod;
use App\Models\HomeVisit;
use App\Models\Kerjasama;
use App\Models\Kurikulum;
use App\Models\PaketSoal;
use App\Models\WaliKelas;
use App\Models\MateriAjar;
use App\Models\Penerimaan;
use App\Models\PresensiMp;
use App\Models\KurikulumMp;
use App\Models\ArsipDokumen;
use App\Models\KegiatanGuru;
use App\Models\PrestasiGuru;
use App\Models\RaporSisipan;
use App\Models\RuanganKelas;
use App\Models\TagihanBiaya;
use Illuminate\Http\Request;

use App\Models\JadwalKelasMp;
use App\Models\KegiatanSiswa;

use App\Models\LowonganKerja;
use App\Models\PrestasiSiswa;
use App\Models\CalonSiswaBaru;
use App\Models\JadwalKegiatan;
use App\Models\PemasukanBiaya;
use App\Models\PresensiEkskul;
use App\Models\KomplainSarpras;
use App\Models\LogSesiPengguna;
use App\Models\PresensiMpSiswa;
use App\Models\SekretarisKelas;
use App\Models\PelanggaranSiswa;
use App\Models\PelatihEkskulSet;
use App\Models\PembinaEkskulSet;
use App\Models\PengeluaranBiaya;
use App\Models\PesertaEkskulSet;
use Yajra\Datatables\Datatables;
use App\Models\LaporanKerjaHarian;
use Illuminate\Support\Facades\DB;
use App\Models\LogAktivitasPengguna;
use App\Models\PresensiMpPelanggaran;
use App\Models\LaporanKerjaHarianMGMP;
use App\Libraries\Pendidikan\LibDataAkademik;
use Illuminate\Routing\Controller as BaseController;

class ReportController extends BaseController
{
    public function viewAllDiakad()
    {
        $semester_aktif = Semester::where('is_aktif_semester', '=', 1)->first();
        $role = Role::whereNotIn('nm_role', ['Siswa', 'Wali Murid', 'Administrator', 'Dapodik', 'Alumni', 'Rapor & Buku Induk', 'Tenaga Pendidik'])->orderBy('nm_role', 'asc')->get();
        $sekolah = Sekolah::orderBy('id_sekolah')->first();

        $data = array();

        foreach ($role as $key => $r) {

            $data[$key]['id_role'] = $r->id_role;
            $data[$key]['role'] = $r->nm_role;

            $data[$key]['status'] = '';
            $data[$key]['catatan'] = '';

            $temp = $this->checkProgress($r->id_role);
            $temp = $temp->original;
            $data[$key]['status'] = $temp['status'];
            $data[$key]['catatan'] = $temp['catatan'];
            $data[$key]['progress'] = $temp['progress'];
            $data[$key]['rowspan'] = count($temp['catatan']);
        }

        return view('reporting-dashboard.all-diakad', compact('data', 'semester_aktif', 'sekolah'));
    }

    public function printAllDiakad()
    {
        $semester_aktif = Semester::where('is_aktif_semester', '=', 1)->first();
        $role = Role::whereNotIn('nm_role', ['Siswa', 'Wali Murid', 'Administrator', 'Dapodik', 'Alumni', 'Rapor & Buku Induk', 'Tenaga Pendidik'])->orderBy('nm_role', 'asc')->get();
        $sekolah = Sekolah::orderBy('id_sekolah')->first();

        $data = array();

        foreach ($role as $key => $r) {

            $data[$key]['id_role'] = $r->id_role;
            $data[$key]['role'] = $r->nm_role;

            $data[$key]['status'] = '';
            $data[$key]['catatan'] = '';

            $temp = $this->checkProgress($r->id_role);
            $temp = $temp->original;
            $data[$key]['status'] = $temp['status'];
            $data[$key]['catatan'] = $temp['catatan'];
            $data[$key]['progress'] = $temp['progress'];
            $data[$key]['rowspan'] = count($temp['catatan']);
        }

        return view('reporting-dashboard.print-all-diakad', compact('data', 'semester_aktif', 'sekolah'));
    }

    public function count_multidimension($param)
    {
        $jumlah = 0;
        foreach ($param as $key => $value) {
            if ($value['status'] == 1) {
                $jumlah++;
            }
        }

        return $jumlah;
    }

    public function checkProgress($id_role)
    {
        $semester_aktif = Semester::where('is_aktif_semester', '=', 1)->first();
        $tahun_semester_aktif = $semester_aktif->thn_akademik_semester;
        $semester_aktif = $semester_aktif->id_semester;

        $role = Role::find($id_role);
        $status = '';
        $jumlah_diisi = 0;
        $param = array();
        if ($id_role == 1) { // Pendidikan

            $data_kelas = Kelas::where('is_aktif', 1)->get()->count();

            $wali_kelas = WaliKelas::whereIn('id_kelas', Kelas::pluck('id_kelas'))->where('id_semester', $semester_aktif)->count();
            $sekretaris_kelas = SekretarisKelas::whereIn('id_kelas', Kelas::pluck('id_kelas'))->where('id_semester', $semester_aktif)->count();
            $ruangan_kelas = RuanganKelas::whereIn('id_kelas', Kelas::where('is_aktif', 1)->get()->pluck('id_kelas'))->where('id_semester', $semester_aktif)->count();
            $kalender = JadwalKegiatan::where('id_semester', $semester_aktif)->count();

            $param[0]['catatan'] = 'Sudah melakukan input data kelas';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukan input data wali kelas , sekretaris dan ruangan pada semua kelas';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah melakukan input data kalender akademik';
            $param[2]['status'] = 0;

            if ($data_kelas) {
                $param[0]['status'] = 1;
            }

            if ($wali_kelas == $data_kelas && $sekretaris_kelas == $data_kelas && $wali_kelas == $ruangan_kelas) {
                $param[1]['status'] = 1;
            }

            if ($kalender) {
                $param[2]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 2) { // Guru

            $kelas_mp = KelasMp::where('id_semester', $semester_aktif)->pluck('id_kelas_mp');
            $presensi_mp = PresensiMp::whereIn('id_kelas_mp', $kelas_mp)->pluck('id_presensi_mp');
            $presensi_mp_siswa = PresensiMpSiswa::whereIn('id_presensi_mp', $presensi_mp)->count();

            $param[0]['catatan'] = 'Sudah pernah melakukan absen pada saat kbm';
            $param[0]['status'] = 0;

            if ($presensi_mp_siswa) {
                $param[0]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 5) { // Bimbingan & Konseling

            $pelanggaran_siswa = PelanggaranSiswa::where('id_semester', $semester_aktif)->count();
            $tindakan_pelanggaran = PelanggaranSiswa::where('is_sudah_tindakan', 1)->where('id_semester', $semester_aktif)->count();

            $param[0]['catatan'] = 'Sudah ada pelanggaran yang diinputkan pada semester yang aktif';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukan tindakan peda pelanggaran pada semester yang aktif';
            $param[1]['status'] = 0;

            if ($pelanggaran_siswa) {
                $param[0]['status'] = 1;
            }

            if ($tindakan_pelanggaran) {
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 6) { // Kesiswaan

            $data_ekskul = Ekskul::count();

            $param[0]['catatan'] = 'Sudah ada melakukan input date ekskul beserta pelatih , pembina dan peserta';
            $param[0]['status'] = 0;

            $pelatih_ekskul = PelatihEkskulSet::whereIn('id_ekskul', Ekskul::pluck('id_ekskul'))->where('is_aktif', 1)->count();
            $pembina_ekskul = PembinaEkskulSet::whereIn('id_ekskul', Ekskul::pluck('id_ekskul'))->where('is_aktif', 1)->count();
            $peserta_ekskul = PesertaEkskulSet::whereIn('id_ekskul', Ekskul::pluck('id_ekskul'))->get();

            if ($pelatih_ekskul == $data_ekskul && $pembina_ekskul == $data_ekskul && $peserta_ekskul->groupBy('id_ekskul')->count() >= $data_ekskul) {
                $param[0]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 7) { // Akademik

            // $kurikulum = Kurikulum::pluck('id_kurikulum');
            // $kurikulum_mp = KurikulumMp::distinct('id_kurikulum')->whereIn('id_kurikulum', $kurikulum)->count('id_kurikulum');

            $kelas_mp = KelasMp::where('id_semester', $semester_aktif)->pluck('id_kelas_mp');

            $jadwal_kelas_mp = JadwalKelasMp::distinct('id_kelas_mp')->whereIn('id_kelas_mp', $kelas_mp)->count('id_kelas_mp');

            // $param[0]['catatan'] = 'Sudah memasukkan kurikulum beserta mapel mapelnya';
            // $param[0]['status'] = 0;

            $param[0]['catatan'] = 'Sudah memasukkan usulan mata ajar pada semester yang aktif';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah memasukkan jadwal mengajar serta ruangan di usulan mata ajar pada semester yang aktif ';
            $param[1]['status'] = 0;

            // if ($kurikulum_mp > 0 && $kurikulum_mp == $kurikulum->count()) {
            //     $param[0]['status'] = 1;
            // }

            if ($kelas_mp->count()) {
                $param[0]['status'] = 1;
            }

            if ($jadwal_kelas_mp == $kelas_mp->count() && $kelas_mp->count() != 0) {
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 8) { // Sumber Daya

            $data_guru = Guru::count();
            $data_tendik = Staff::count();

            $param[0]['catatan'] = 'Sudah memasukkan data guru';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah memasukkan data staff';
            $param[1]['status'] = 0;

            if ($data_guru) {
                $param[0]['status'] = 1;
            }

            if ($data_tendik) {
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 9) { // Keuangan

            $tagihan_siswa = TagihanBiaya::whereHas('detail_biaya.biaya_sekolah', function ($q) use ($semester_aktif) {
                $q->where('id_semester', $semester_aktif);
            })->count();
            $pembayaran_siswa = TagihanBiaya::whereHas('detail_biaya.biaya_sekolah', function ($q) use ($semester_aktif) {
                $q->where('id_semester', $semester_aktif);
            })->where('is_tagih', 0)->count();
            $pemasukan_biaya = PemasukanBiaya::where('id_semester', $semester_aktif)->count();
            $pengeluaran_biaya = PengeluaranBiaya::where('id_semester', $semester_aktif)->count();
            $pembayaran_online = env("WINPAY_PRIVATE_KEY1");

            $param[0]['catatan'] = 'Sudah melakukan generate tagihan siswa pada semester yang aktif';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukan proses pembayaran siswa pada semester yang aktif';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah melakukan input pemasukan dan pengeluaran pada semester yang aktif';
            $param[2]['status'] = 0;

            $param[3]['catatan'] = 'Sudah menerapkan pembayaran online (lewat alfamart, indomaret , virtual account bank)';
            $param[3]['status'] = 0;

            if ($tagihan_siswa) $param[0]['status'] = 1;

            if ($pembayaran_siswa) $param[1]['status'] = 1;

            if ($pemasukan_biaya && $pengeluaran_biaya)  $param[2]['status'] = 1;

            if ($pembayaran_online != "") $param[3]['status'] = 1;

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 10) { // Sarana Prasarana

            $gedung = Gedung::count();
            $ruangan = ruangan::count();
            $bukualat = BukuAlat::count();

            $param[0]['catatan'] = 'Sudah melakukuan input data gedung';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukuan input data ruangan';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah melakukuan input data buku/alat';
            $param[2]['status'] = 0;

            if ($gedung) {
                $param[0]['status'] = 1;
            }

            if ($ruangan) {
                $param[1]['status'] = 1;
            }

            if ($bukualat) {
                $param[2]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 11) { // PPDB

            $penerimaan_online = Penerimaan::where('is_pendaftaran_online', 1)
                ->whereHas('semester', function ($q) use ($tahun_semester_aktif) {
                    $q->where('thn_akademik_semester', $tahun_semester_aktif);
                });

            $calon_siswa_online = CalonSiswaBaru::whereIn('id_penerimaan', $penerimaan_online->pluck('id_penerimaan'))->count();

            $param[0]['catatan'] = 'Sudah pernah melakukan penerimaan secara online pada tahun ' . $tahun_semester_aktif;
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah ada siswa yang mendaftar pada penerimaan secara online pada tahun ' . $tahun_semester_aktif;
            $param[1]['status'] = 0;

            if ($penerimaan_online->count()) {
                $param[0]['status'] = 1;
            }

            if ($calon_siswa_online) {
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 13) { // Pelatih Ekskul

            $presensi_ekskul = PresensiEkskul::where('id_semester', $semester_aktif)->count();

            $param[0]['catatan'] = 'Sudah melakukuan absensi ekskul pada semester yang aktif';
            $param[0]['status'] = 0;

            if ($presensi_ekskul) {
                $param[0]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 14) { // Sekretariat

            $arsip_dokumen = ArsipDokumen::count();

            $param[0]['catatan'] = 'Sudah melakukuan input data pada arsip dokumen';
            $param[0]['status'] = 0;

            if ($arsip_dokumen) {
                $param[0]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        } elseif ($id_role == 19) { // Humas

            $loker = LowonganKerja::count();
            $kerjasama = Kerjasama::count();

            $param[0]['catatan'] = 'Sudah melakukuan input data pada lowongan kerja';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukuan input data kerja sama';
            $param[1]['status'] = 0;

            if ($loker) {
                $param[0]['status'] = 1;
            }

            if ($kerjasama) {
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if ($jumlah_diisi == 0) {
                $status = 'Belum Digunakan';
            } elseif ($jumlah_diisi < count($param)) {
                $status = 'Sudah digunakan namun belum maksimal';
            } else {
                $status = 'Sudah digunakan dengan maksimal';
            }
        }

        $data['nm_role'] = $role->nm_role;
        $data['status'] = $status;
        $data['catatan'] = $param;
        if (count($param) > 0) {
            $data['progress'] = $jumlah_diisi / count($param) * 100;
        } else {
            $data['progress'] = 0;
        }


        return response()->json($data);
    }

    // Repor Wali kelas
    public function viewReportWaliKelas(Request $request)
    {
        $semester_aktif = Semester::where('is_aktif_semester', '=', 1)->first();
        $wali_kelas = WaliKelas::with('guru.pengguna', 'kelas')
            ->where('is_aktif', 1)->whereHas('kelas', function ($query) {
                $query->orderBy('tingkat')->orderBy('nm_kelas');
            })
            ->whereHas('guru.pengguna.status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })
            ->get();

        $sekolah = Sekolah::orderBy('id_sekolah')->first();

        $data = array();

        $wali_kelass = WaliKelas::where('is_aktif', 1)->with('guru')->get();
        $semua_siswas = Siswa::get();
        $biodata_siswas = Siswa::whereHas('pengguna', function ($query) {
            $query->whereNotNull('email_pengguna');
        })->get();
        $pelanggaran_siswas = PelanggaranSiswa::where('id_semester', $semester_aktif->id_semester)->get();
        $kegiatan_siswas = KegiatanSiswa::where('id_semester', $semester_aktif->id_semester)->get();
        $prestasi_siswas = PrestasiSiswa::where('id_semester', $semester_aktif->id_semester)->get();
        $kegiatan_siswa_approves = KegiatanSiswa::where('id_semester', $semester_aktif->id_semester)->get();
        $prestasi_siswa_approves = PrestasiSiswa::where('id_semester', $semester_aktif->id_semester)->get();
        $home_visits = HomeVisit::where('id_semester', $semester_aktif->id_semester)->get();
        $wali_murids = Siswa::whereHas('wali_murid', function ($query) {
            $query->where('is_aktif', 1);
        })->get();

        foreach ($wali_kelas as $key => $w) {

            $data[$key]['id_wali_kelas'] = $w->id_wali_kelas;
            $data[$key]['nama'] = $w->guru->pengguna->nm_pengguna;
            $data[$key]['kelas'] = $w->kelas->nm_kelas;
            $data[$key]['data'][1] = 'Biodata Siswa';
            $data[$key]['data'][2] = 'Pelanggaran Siswa';
            $data[$key]['data'][3] = 'Approve SKPI';
            $data[$key]['data'][4] = 'Home Visit';
            $data[$key]['data'][5] = 'Wali Murid';
            $data[$key]['jumlahData'] = '';
            $data[$key]['progress'] = '';
            $data[$key]['catatan'] = '';

            $temp = $this->checkDataWaliKelas($w->id_kelas,  $wali_kelass, $semua_siswas, $biodata_siswas, $pelanggaran_siswas, $kegiatan_siswas, $prestasi_siswas, $kegiatan_siswa_approves,  $prestasi_siswa_approves, $home_visits, $wali_murids);
            $temp = $temp->original;
            $data[$key]['status'][1] = $temp['status'][1];
            $data[$key]['status'][2] = $temp['status'][2];
            $data[$key]['status'][3] = $temp['status'][3];
            $data[$key]['status'][4] = $temp['status'][4];
            $data[$key]['status'][5] = $temp['status'][5];

            $data[$key]['persentase'][1] = $temp['persentase'][1];
            $data[$key]['persentase'][2] = $temp['persentase'][2];
            $data[$key]['persentase'][3] = $temp['persentase'][3];
            $data[$key]['persentase'][4] = $temp['persentase'][4];
            $data[$key]['persentase'][5] = $temp['persentase'][5];
            // $data[$key]['catatan'] = $temp['catatan'];
            $data[$key]['progres'] = $temp['progres'];
        }


        return view('reporting-dashboard.wali-kelas', compact('data', 'semester_aktif', 'sekolah'));
    }

    public function checkDataWaliKelas($id_kelas, $wali_kelass, $semua_siswas, $biodata_siswas, $pelanggaran_siswas, $kegiatan_siswas, $prestasi_siswas, $kegiatan_siswa_approves,  $prestasi_siswa_approves, $home_visits, $wali_murids)
    {
        $wali_kelas = $wali_kelass->where('id_kelas', $id_kelas)->first();
        $semua_siswa = $semua_siswas->where('id_kelas', $id_kelas)->count();

        $biodata_siswa = $biodata_siswas->where('id_kelas', $id_kelas)->count();
        //pelanggaran siswa
        $pelanggaran_siswa = $pelanggaran_siswas->where('id_kelas', $id_kelas)->count();
        //skpi
        $kegiatan_siswa = $kegiatan_siswas->where('id_kelas', $id_kelas)->count();
        $prestasi_siswa =  $prestasi_siswas->where('id_kelas', $id_kelas)->count();
        //skpi approve
        $kegiatan_siswa_approve = $kegiatan_siswa_approves->where('id_kelas', $id_kelas)->where('approved_by', $wali_kelas->guru->id_pengguna)->count();
        $prestasi_siswa_approve = $prestasi_siswa_approves->where('id_kelas', $id_kelas)->where('approved_by', $wali_kelas->guru->id_pengguna)->count();
        //home visit
        $home_visit = $home_visits->where('id_kelas', $id_kelas)->count();
        $wali_murid = $wali_murids->where('id_kelas', $id_kelas)->count();

        //Biodata Siswa
        $param['status'][1] = $biodata_siswa . ' / ' . $semua_siswa . ' Data';
        $param['persentase'][1] = ($biodata_siswa != 0) ? $biodata_siswa / $semua_siswa * 100 : 0;
        // Pelanggaran Siswa
        $param['status'][2] = $pelanggaran_siswa . ' Data';
        if ($pelanggaran_siswa >= 5) {
            $param['persentase'][2] = 100;
        } else {
            $param['persentase'][2] = $pelanggaran_siswa * 20;
        };
        // Approve SKPI
        $param['status'][3] = ($kegiatan_siswa_approve + $prestasi_siswa_approve) . '/' . ($kegiatan_siswa + $prestasi_siswa) . ' Data';
        $param['persentase'][3] = (($kegiatan_siswa_approve + $prestasi_siswa_approve) != 0) ? ($kegiatan_siswa_approve + $prestasi_siswa_approve) / ($kegiatan_siswa + $prestasi_siswa) * 100 : 0;
        // Home Visit
        $param['status'][4] = $home_visit . ' / ' . $semua_siswa . ' Data';
        $param['persentase'][4] = ($home_visit != 0) ? $home_visit / $semua_siswa * 100 : 0;
        // Wali murid
        $param['status'][5] = $wali_murid . ' / ' . $semua_siswa . ' Data';
        $param['persentase'][5] = ($wali_murid != 0) ? $wali_murid / $semua_siswa * 100 : 0;

        $data[1] = ($biodata_siswa != 0) ? ($biodata_siswa / $semua_siswa) * 20 : 0;
        $data[2] = ((($pelanggaran_siswa / 5) * 20) > 20) ? 20 : ($pelanggaran_siswa / 5) * 20;
        $data[3] = (($kegiatan_siswa + $prestasi_siswa) == 0) ? 0 : (($kegiatan_siswa_approve + $prestasi_siswa_approve) / ($kegiatan_siswa + $prestasi_siswa)) * 20;
        $data[4] = ($home_visit != 0) ? ($home_visit / $semua_siswa) * 20 : 0;
        $data[5] = ($wali_murid != 0) ? ($wali_murid / $semua_siswa) * 20 : 0;

        $total = 0;
        for ($i = 1; $i <= 5; $i++) {
            $total  += $data[$i];
        }

        $param['progres'] = $total;

        return response()->json($param);
    }

    public function viewReportGuru(Request $request)
    {
        $input = (object) $request->input();

        $validated = $request->validate([
            'filter_value' => 'sometimes|integer|digits_between:1,10',
            'filter_tanggal' => 'sometimes|integer: 7, 30, 365',
        ]);

        $filter_value = intval($validated['filter_value'] ?? 1);
        $filter_tanggal = intval($validated['filter_tanggal'] ?? 1);

        $chartData = [
            'labels' => [],
            'data' => []
        ];

        $semester_aktif = Semester::select('id_semester', 'nm_semester')->where('is_aktif_semester', 1)->first();
        $id_semester = $semester_aktif->id_semester;
        $sekolah = Sekolah::select('nm_singkat_sekolah')->orderBy('id_sekolah')->first();

        $guru = DB::table('guru as g')
            ->select(
                'g.id_pengguna',
                'g.nik_ptk',
                'g.tgl_lahir',
                'g.nm_ibu_kandung',
                'g.alamat_jalan',
                'g.npwp_ptk',
                'g.nomor_hp',
                'g.email',
                'g.nomor_sk_penugasan',
                'p.nm_pengguna',
                'g.created_at',
                'g.created_by',
                'g.deleted_at',
            )
            ->join('pengguna as p', 'g.id_pengguna', '=', 'p.id_pengguna')
            ->join('status_pengguna as sp', 'p.id_status_pengguna', '=', 'sp.id_status_pengguna')
            ->where('sp.nm_status_pengguna', 'AKTIF')
            ->whereNull('sp.deleted_at')
            ->whereNull('p.deleted_at')
            ->whereNull('g.deleted_at')
            ->get();

        $kegiatan_gurus = KegiatanGuru::select('id_kegiatan_guru', 'created_at', 'created_by', 'deleted_at')->get();
        $prestasi_gurus = PrestasiGuru::select('id_prestasi_guru', 'created_at', 'created_by', 'deleted_at')->get();

        $arsip_dokumens = ArsipDokumen::select('id_arsip_dokumen', 'created_at', 'created_by', 'deleted_at')->get();

        $rapor_sisipans = DB::table('rapor')
            ->select('id_rapor', 'id_semester', 'created_at', 'created_by', 'deleted_at')
            ->where('id_semester', $id_semester)
            ->where('nm_rapor', 'sisipan')
            ->whereNull('deleted_at')
            ->get();

        $presensi_mps = DB::table('presensi_mp as pm')
            ->select('pm.id_presensi_mp', 'pm.id_kelas_mp', 'pm.created_at', 'pm.created_by')
            ->join('kelas_mp as km', 'pm.id_kelas_mp', '=', 'km.id_kelas_mp')
            ->where('km.id_semester', $id_semester)
            ->whereNull('pm.deleted_at')
            ->whereNull('km.deleted_at')
            ->get();

        $materi_ajars = MateriAjar::select('id_materi_ajar', 'created_at', 'created_by', 'deleted_at')->get();
        $paket_soals = PaketSoal::select('id_paket_soal', 'created_at', 'created_by', 'deleted_at')->get();
        $jurnal_harians = DB::table('laporan_kerja_harian_mgmp')
            ->select('id_laporan_kerja_harian_mgmp', 'created_at', 'created_by', 'deleted_at')->whereNull('deleted_at')->get();

        $presensi_mp_siswa_pelanggarans = PresensiMpPelanggaran::select('id_presensi_mp_pelanggaran', 'created_at', 'created_by', 'deleted_at')->get();
        $pelanggaran_siswas = DB::table('pelanggaran_siswa')->select('id_pelanggaran_siswa', 'created_at', 'created_by', 'deleted_at')->whereNull('deleted_at')->get();

        $komplain_sarpass = KomplainSarpras::select('id_komplain_sarpras', 'created_at', 'created_by', 'deleted_at')->get();
        $laporan_kerja_harians = LaporanKerjaHarian::select('id_laporan_kerja_harian', 'created_at', 'created_by', 'deleted_at')->get();

        foreach ($guru as $key => $g) {
            $week = null;
            $month = null;
            $year = null;

            $chartData['labels'][] = $g->nm_pengguna;

            if ($filter_value == 3 || $filter_value == 6) {
                // Rapor sisipan dan presensi berdasarkan satu semester
                switch ($filter_tanggal) {
                    case 30:
                        $month = 6;
                        break;
                }
            } else {
                switch ($filter_tanggal) {
                    case 7:
                        $week = 1;
                        break;
                    case 30:
                        $month = 1;
                        break;
                    case 365:
                        $year = 1;
                        break;

                    default:
                        $week = null;
                        $month = null;
                        $year = null;
                        break;
                }
            }

            $temp = $this->checkDataGuru($g, $g->id_pengguna,  $kegiatan_gurus, $prestasi_gurus,  $arsip_dokumens,  $rapor_sisipans,  $materi_ajars, $paket_soals, $presensi_mps,  $jurnal_harians, $presensi_mp_siswa_pelanggarans,  $pelanggaran_siswas, $komplain_sarpass,  $laporan_kerja_harians, $week, $month, $year);

            $temp = $temp->original;
            $chartData['data'][] = $temp['status'][$filter_value];
        }

        array_multisort($chartData['data'], SORT_DESC, $chartData['labels']);

        return view('reporting-dashboard.testing', compact('semester_aktif', 'sekolah', 'chartData', 'filter_value', 'filter_tanggal'));
    }

    public function filterDataByDate($datas, $filter_tanggal, $id_guru, $month = null, $year = null, $semester_aktif = null)
    {
        switch ($filter_tanggal) {
            case 7:
                return $this->countDataBetweenWeek($id_guru, $datas);
            case 30:
                return $this->countDataBetweenMonth($datas, $id_guru, $month, $semester_aktif);
            case 365:
                return $this->countDataBetweenYear($datas, $id_guru, $year);
            default:
                return $this->countDataCreatedBy($datas, $id_guru);
        }
    }

    public function countDataCreatedBy($collection, $id_pengguna)
    {
        return $collection
            ->where('created_by', $id_pengguna)
            ->count();
    }

    public function countDataBetweenWeek($collection, $id_pengguna)
    {
        $startWeek = Carbon::now('Asia/Jakarta')->subWeek()->endOfDay();
        $endWeek = Carbon::now('Asia/Jakarta')->startOfDay();
        return $collection
            ->where('created_by', $id_pengguna)
            ->whereBetween('created_at', [$startWeek, $endWeek])
            ->count();
    }

    public function countDataBetweenMonth($month, $collection, $id_pengguna, $semester_aktif)
    {
        $startMonth = Carbon::now('Asia/Jakarta')->subMonth($month)->endOfDay();
        $endMonth = Carbon::now('Asia/Jakarta')->startOfDay();
        if (!$semester_aktif) {
            return $collection
                ->where('created_by', $id_pengguna)
                ->whereBetween('created_at', [$startMonth, $endMonth])
                ->count();
        } else {
            if ($semester_aktif->nm_semester == 'Ganjil') {
                return $collection
                    ->where('created_by', $id_pengguna)
                    ->whereMonth('created_at', '>=', 7)
                    ->count();
            } else {
                // Genap
                return $collection
                    ->where('created_by', $id_pengguna)
                    ->whereMonth('created_at', '<=', 6)
                    ->count();
            }
        }
    }

    public function countDataBetweenYear($year, $collection, $id_pengguna)
    {
        $startYear = Carbon::now('Asia/Jakarta')->subYear($year)->endOfDay();
        $endYear = Carbon::now('Asia/Jakarta')->startOfDay();

        return $collection
            ->where('created_by', $id_pengguna)
            ->whereBetween('created_at', [$startYear, $endYear])
            ->count();
    }

    public function checkDataGuru($guru, $id_pengguna,  $kegiatan_gurus, $prestasi_gurus,  $arsip_dokumens = null,  $rapor_sisipans = null,  $materi_ajars = null, $paket_soals = null, $presensi_mps = null,  $jurnal_harians = null, $presensi_mp_siswa_pelanggarans = null,  $pelanggaran_siswas = null, $komplain_sarpass = null,  $laporan_kerja_harians = null, $week = null, $month = null, $year = null, $semester_aktif = null)
    {
        $nilai_biodata = 0;
        $nilai_biodata += ($guru->nik_ptk) ? 1 : 0;
        $nilai_biodata += ($guru->tgl_lahir) ? 1 : 0;
        $nilai_biodata += ($guru->nm_ibu_kandung) ? 1 : 0;
        $nilai_biodata += ($guru->alamat_jalan) ? 1 : 0;
        $nilai_biodata += ($guru->npwp_ptk) ? 1 : 0;
        $nilai_biodata += ($guru->nomor_hp) ? 1 : 0;
        $nilai_biodata += ($guru->email) ? 1 : 0;
        $nilai_biodata += ($guru->nomor_sk_penugasan) ? 1 : 0;

        if ($week) {
            $nilai_biodata += $this->countDataBetweenWeek($kegiatan_gurus, $id_pengguna) ? 1 : 0;
            $nilai_biodata += $this->countDataBetweenWeek($prestasi_gurus, $id_pengguna) ? 1 : 0;

            $arsip_dokumen = $this->countDataBetweenWeek($arsip_dokumens, $id_pengguna);
            $rapor_sisipan = $this->countDataBetweenWeek($rapor_sisipans, $id_pengguna);
            $materi_ajar = $this->countDataBetweenWeek($materi_ajars, $id_pengguna);
            $paket_soal = $this->countDataBetweenWeek($paket_soals, $id_pengguna);
            $presensi_mp = $this->countDataBetweenWeek($presensi_mps, $id_pengguna);
            $jurnal_harian = $this->countDataBetweenWeek($jurnal_harians, $id_pengguna);
            $presensi_mp_siswa_pelanggaran = $this->countDataBetweenWeek($presensi_mp_siswa_pelanggarans, $id_pengguna);
            $pelanggaran_siswa = $this->countDataBetweenWeek($pelanggaran_siswas, $id_pengguna);
            $komplain_sarpas = $this->countDataBetweenWeek($komplain_sarpass, $id_pengguna);
            $laporan_kerja_harian = $this->countDataBetweenWeek($laporan_kerja_harians, $id_pengguna);
        } elseif ($month) {
            $nilai_biodata += $this->countDataBetweenMonth($month, $kegiatan_gurus, $id_pengguna, $semester_aktif) ? 1 : 0;
            $nilai_biodata += $this->countDataBetweenMonth($month, $prestasi_gurus, $id_pengguna, $semester_aktif) ? 1 : 0;

            $arsip_dokumen = $this->countDataBetweenMonth($month, $arsip_dokumens, $id_pengguna, $semester_aktif);
            $rapor_sisipan = $this->countDataBetweenMonth($month, $rapor_sisipans, $id_pengguna, $semester_aktif);
            $materi_ajar = $this->countDataBetweenMonth($month, $materi_ajars, $id_pengguna, $semester_aktif);
            $paket_soal = $this->countDataBetweenMonth($month, $paket_soals, $id_pengguna, $semester_aktif);
            $presensi_mp = $this->countDataBetweenMonth($month, $presensi_mps, $id_pengguna, $semester_aktif);
            $jurnal_harian = $this->countDataBetweenMonth($month, $jurnal_harians, $id_pengguna, $semester_aktif);
            $presensi_mp_siswa_pelanggaran = $this->countDataBetweenMonth($month, $presensi_mp_siswa_pelanggarans, $id_pengguna, $semester_aktif);
            $pelanggaran_siswa = $this->countDataBetweenMonth($month, $pelanggaran_siswas, $id_pengguna, $semester_aktif);
            $komplain_sarpas = $this->countDataBetweenMonth($month, $komplain_sarpass, $id_pengguna, $semester_aktif);
            $laporan_kerja_harian = $this->countDataBetweenMonth($month, $laporan_kerja_harians, $id_pengguna, $semester_aktif);
        } elseif ($year) {
            $nilai_biodata += $this->countDataBetweenYear($year, $kegiatan_gurus, $id_pengguna) ? 1 : 0;
            $nilai_biodata += $this->countDataBetweenYear($year, $prestasi_gurus, $id_pengguna) ? 1 : 0;

            $arsip_dokumen = $this->countDataBetweenYear($year, $arsip_dokumens, $id_pengguna);
            $rapor_sisipan = $this->countDataBetweenYear($year, $rapor_sisipans, $id_pengguna);
            $materi_ajar = $this->countDataBetweenYear($year, $materi_ajars, $id_pengguna);
            $paket_soal = $this->countDataBetweenYear($year, $paket_soals, $id_pengguna);
            $presensi_mp = $this->countDataBetweenYear($year, $presensi_mps, $id_pengguna);
            $jurnal_harian = $this->countDataBetweenYear($year, $jurnal_harians, $id_pengguna);
            $presensi_mp_siswa_pelanggaran = $this->countDataBetweenYear($year, $presensi_mp_siswa_pelanggarans, $id_pengguna);
            $pelanggaran_siswa = $this->countDataBetweenYear($year, $pelanggaran_siswas, $id_pengguna);
            $komplain_sarpas = $this->countDataBetweenYear($year, $komplain_sarpass, $id_pengguna);
            $laporan_kerja_harian = $this->countDataBetweenYear($year, $laporan_kerja_harians, $id_pengguna);
        } else {
            $nilai_biodata += $this->countDataCreatedBy($kegiatan_gurus, $id_pengguna) ? 1 : 0;
            $nilai_biodata += $this->countDataCreatedBy($prestasi_gurus, $id_pengguna) ? 1 : 0;

            $arsip_dokumen = $this->countDataCreatedBy($arsip_dokumens, $id_pengguna);
            $rapor_sisipan = $this->countDataCreatedBy($rapor_sisipans, $id_pengguna);
            $materi_ajar =  $this->countDataCreatedBy($materi_ajars, $id_pengguna);
            $paket_soal = $this->countDataCreatedBy($paket_soals, $id_pengguna);
            $presensi_mp =  $this->countDataCreatedBy($presensi_mps, $id_pengguna);
            $jurnal_harian = $this->countDataCreatedBy($jurnal_harians, $id_pengguna);
            $presensi_mp_siswa_pelanggaran =  $this->countDataCreatedBy($presensi_mp_siswa_pelanggarans, $id_pengguna);
            $pelanggaran_siswa =  $this->countDataCreatedBy($pelanggaran_siswas, $id_pengguna);
            $komplain_sarpas = $this->countDataCreatedBy($komplain_sarpass, $id_pengguna);
            $laporan_kerja_harian = $this->countDataCreatedBy($laporan_kerja_harians, $id_pengguna);
        }

        $param['status'][1] =  $nilai_biodata;
        $param['status'][2] =  $arsip_dokumen;
        $param['status'][3] =  $rapor_sisipan;
        $param['status'][4] =  $materi_ajar;
        $param['status'][5] =  $paket_soal;
        $param['status'][6] =  $presensi_mp;
        $param['status'][7] =  $jurnal_harian;
        $param['status'][8] =  $presensi_mp_siswa_pelanggaran + $pelanggaran_siswa;
        $param['status'][9] =  $komplain_sarpas;
        $param['status'][10] =  $laporan_kerja_harian;

        for ($i = 1; $i <= 10; $i++) {
            $param['status'][$i];
        }

        return response()->json($param);
    }

    public function logSesiPenggunaLogin(Request $request, $filter_day = 1, $filter_pengguna = '0')
    {
        $rangeDate = Carbon::now()->subDays($filter_day);

        $list_pengguna = LogSesiPengguna::select('id_pengguna', DB::raw('COUNT(id_pengguna) as total_count'))
            ->with('pengguna')
            ->when($filter_pengguna !== "0", function ($q) use ($filter_pengguna) {
                $q = $q->whereHas('pengguna', function ($q) use ($filter_pengguna) {
                    $q->whereIn('pengguna.status_join_table',  explode('-', $filter_pengguna));
                });
            })
            ->where('login_time', '>=', $rangeDate)
            ->groupBy('id_pengguna')
            ->orderBy('total_count', 'DESC')
            ->get();

        $list_guru = collect();
        $list_siswa = collect();
        $list_wali_murid = collect();

        $list_pengguna->each(function ($log_sesi_pengguna) use (&$list_guru, &$list_siswa, &$list_wali_murid) {
            switch ($log_sesi_pengguna->pengguna->status_join_table) {
                case 1:
                case 2:
                    $list_guru->push($log_sesi_pengguna);
                    break;
                case 3:
                    $list_siswa->push($log_sesi_pengguna);
                    break;
                case 4:
                    $list_wali_murid->push($log_sesi_pengguna);
                    break;
            }
        });

        $totalPengguna1HariTerakhir = $this->getTotalSesiPenggunaLogin(1, $filter_pengguna);
        $totalPengguna7HariTerakhir = $this->getTotalSesiPenggunaLogin(7, $filter_pengguna);
        $totalPengguna30HariTerakhir = $this->getTotalSesiPenggunaLogin(30, $filter_pengguna);

        $chart_data = LogSesiPengguna::selectRaw('DATE(login_time) as date, COUNT(DISTINCT id_pengguna) as count')
            ->with('pengguna')
            ->when($filter_pengguna !== "0", function ($q) use ($filter_pengguna) {
                $q = $q->whereHas('pengguna', function ($q) use ($filter_pengguna) {
                    $q->whereIn('pengguna.status_join_table',  explode('-', $filter_pengguna));
                });
            })
            ->where('login_time', '>=', $rangeDate)
            ->groupBy('date')
            ->pluck('count', 'date');

        $chart_label = $chart_data->keys()->map(function ($date) {
            return Carbon::parse($date)->translatedFormat('l, d M Y');
        });

        $chart_data = $chart_data->values();

        $chartDataString = implode(',', $chart_data->toArray());

        return view('reporting-dashboard.log-sesi-pengguna', compact('list_pengguna', 'list_guru', 'list_siswa', 'list_wali_murid', 'totalPengguna1HariTerakhir', 'totalPengguna7HariTerakhir', 'totalPengguna30HariTerakhir', 'chart_label', 'chart_data'));
    }

    private function getTotalSesiPenggunaLogin($filter_day, $filter_pengguna)
    {
        return LogSesiPengguna::select('id_pengguna', DB::raw('COUNT(id_pengguna) as total_count'))
            ->with('pengguna')
            ->when($filter_pengguna !== "0", function ($q) use ($filter_pengguna) {
                $q = $q->whereHas('pengguna', function ($q) use ($filter_pengguna) {
                    $q->whereIn('pengguna.status_join_table',  explode('-', $filter_pengguna));
                });
            })
            ->where('login_time', '>=', Carbon::now()->subDays($filter_day))
            ->groupBy('id_pengguna')
            ->orderBy('total_count', 'DESC')
            ->get()
            ->count();
    }

    public function filterLogSesiPenggunaLogin(Request $request)
    {
        $input = (object) $request->input();

        $day = $input->filter_day ?? 1;
        $pengguna = $input->filter_pengguna ?? 0;

        return [
            'status' => 204,
            'path' => 'analisis-log/penggunaan-diakad/' . $day . '/' . $pengguna
        ];
    }

    public function detailLogAktivitasPengguna(Request $request, $filter_day = 1)
    {
        $pengguna = Pengguna::find($request->id_pengguna);

        $log_sesi_pengguna = LogAktivitasPengguna::where('id_pengguna', $request->id_pengguna)
            ->select('route', 'method', 'created_at', DB::raw('count(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays($filter_day))
            ->groupBy('route', 'method', 'created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status_code' => 200,
            'status_text' => 'Success',
            'data' => [
                'nm_pengguna' => $pengguna->nm_pengguna,
                'log' => $log_sesi_pengguna,
            ]
        ]);
    }
}
