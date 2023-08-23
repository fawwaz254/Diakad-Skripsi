<?php

namespace App\Imports;

use DB;
use App\Models\Kota;
use App\Models\Agama;
use App\Models\Siswa;
use App\Models\Voucher;
use App\Models\Pengguna;
use App\Models\Provinsi;
use App\Libraries\LibGlobal;
use App\Models\JenisTinggal;
use App\Models\JenisLayakPip;
use App\Models\Jalur as Jalur;
use App\Models\JenisPekerjaan;
use App\Models\Kelas as Kelas;
use App\Models\JenisPendidikan;
use App\Models\KebutuhanKhusus;
use App\Models\JenisPenghasilan;
use App\Models\JenisTransportasi;
use Illuminate\Support\Collection;
use App\Models\Semester as Semester;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Hash;
use App\Models\Penerimaan as Penerimaan;
use Barryvdh\Debugbar\Facade as Debugbar;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\StatusPengguna as StatusPengguna;

class UploadToInsertUpdateSiswa implements ToCollection, WithHeadingRow
{
    protected $auth_data;
    protected $now;
    protected $message = array();

    public function __construct($auth_data, $now)
    {
        $this->auth_data = $auth_data;
        $this->now = $now;
    }

    /**
     * @param Collection $row
     *
     * @Debugbar::error( \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $data)
    {
        set_time_limit(9800);
        $data_nis = array();
        foreach ($data as $key => $data_row) {
            if (!empty($data_row['nis'])) {
                $data_nis[] = (string) $data_row['nis'];
            }
        }

        $data_siswa = Siswa::whereIn('nis_siswa', $data_nis)->get();
        $data_status_pengguna = StatusPengguna::where('status_join_table', '=', '3')->get();
        $data_kelas = Kelas::where('is_aktif', 1)->get();
        $data_jalur = Jalur::get();
        $data_semester_masuk = Semester::get();
        $data_penerimaan = Penerimaan::get();
        $data_agama = Agama::get();
        $data_kota = Kota::get();
        $data_kebutuhan_khusus = KebutuhanKhusus::get();
        $data_provinsi = Provinsi::get();
        $data_jenis_tinggal = JenisTinggal::get();
        $data_jenis_transportasi = JenisTransportasi::get();
        $data_jenis_layak_pip = JenisLayakPip::get();
        $data_jenis_pendidikan = JenisPendidikan::get();
        $data_jenis_pekerjaan = JenisPekerjaan::get();
        $data_jenis_penghasilan = JenisPenghasilan::get();

        $arr = array();
        foreach ($data as $key => $data_row) {
            $value = new \stdClass();
            foreach ($data_row as $key => $temp_item) {
                $value->$key = $temp_item;
            }

            if (empty($value->nis)) {
                continue;
            }

            $check_nis_siswa = Siswa::where('nis_siswa', (string) $value->nis)->first();

            // $check_nisn_siswa = Siswa::where('nisn_siswa', (string) $value->nisn)->first();

            // if ($check_nis_siswa) {
            //     Debugbar::error(
            //         'Upload Data Siswa Gagal, NIS ' . $value->nis . ' ditemukan sama di dalam sistem'
            //     );
            // } else{

            $status = $data_status_pengguna->firstWhere('nm_status_pengguna', '=', $value->status_siswa);

            if (empty($status)) {
                $this->message[] = 'Upload Data Siswa Gagal, status ' . $value->status_siswa . ' tidak ditemukan di dalam sistem';
                Debugbar::error('Upload Data Siswa Gagal, status ' . $value->status_siswa . ' tidak ditemukan di dalam sistem');
            }

            // find id_kelas
            $kelas = $data_kelas->firstWhere('nm_kelas', '=', $value->kelas);

            if (empty($kelas)) {
                $this->message[] = 'Upload Data Siswa Gagal, kelas ' . $value->kelas . ' tidak ditemukan di dalam sistem';
                Debugbar::error('Upload Data Siswa Gagal, kelas ' . $value->kelas . ' tidak ditemukan di dalam sistem');
            }

            //find id_jalur
            $jalur = $data_jalur->firstWhere('nm_jalur', '=', $value->jalur);

            if (empty($jalur)) {
                $this->message[] = 'Upload Data Siswa Gagal, jalur ' . $value->jalur . ' tidak ditemukan di dalam sistem';
                Debugbar::error('Upload Data Siswa Gagal, jalur ' . $value->jalur . ' tidak ditemukan di dalam sistem');
            }

            //find jenis_kelamin
            if ($value->jenis_kelamin == "L") {
                $jenis_kelamin = 1;
            } elseif ($value->jenis_kelamin == "P") {
                $jenis_kelamin = 2;
            } else {
                $jenis_kelamin = null;
            }

            //find id_semester
            $semester_masuk = $data_semester_masuk->firstWhere('kode_semester', '=', $value->semester_masuk);

            if (empty($semester_masuk)) {
                $this->message[] = 'Upload Data Siswa Gagal, semester masuk ' . $value->semester_masuk . ' tidak ditemukan di dalam sistem';
                Debugbar::error('Upload Data Siswa Gagal, semester masuk ' . $value->semester_masuk . ' tidak ditemukan di dalam sistem');
            }

            //find id_penerimaan
            $id_penerimaan = $data_penerimaan->firstWhere('tahun_penerimaan', '=', (int) $value->tahun_masuk);

            if (empty($id_penerimaan)) {
                $this->message[] = 'Upload Data Siswa Gagal, tahun masuk ' . $value->tahun_masuk . ' tidak ditemukan di dalam sistem';
                Debugbar::error('Upload Data Siswa Gagal, tahun masuk ' . $value->tahun_masuk . ' tidak ditemukan di dalam sistem');
            }

            // find is_orang_tua
            if ($value->orang_tua_kandung == 1 || $value->orang_tua_kandung == 0) {
                $is_orang_tua = (int) $value->orang_tua_kandung;
            } else {
                $is_orang_tua = null;
            }

            // find kode voucher
            if (empty($value->kode_voucher)) {
                $kode_voucher = null;
            } else {
                $find_voucher = Voucher::where('kode_voucher', $value->kode_voucher)->first();
                if ($find_voucher) {
                    $kode_voucher = $find_voucher->id_voucher;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, kode voucher ' . $value->kode_voucher . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, kode voucher ' . $value->kode_voucher . ' tidak ditemukan di dalam sistem');
                }
            }

            // find nik siswa
            if (empty($value->nik)) {
                $nik = null;
            } else {
                $nik = $value->nik;
            }

            //find id agama
            if (empty($value->agama)) {
                $agama = null;
            } else {
                $find_agama = $data_agama->firstWhere('kode_agama', $value->agama);
                if ($find_agama) {
                    $agama = $find_agama->id_agama;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, agama ' . $value->agama . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, agama ' . $value->agama . ' tidak ditemukan di dalam sistem');
                }
            }

            // find id kota lahir
            if (empty($value->kota_lahir)) {
                $kota_lahir = null;
            } else {
                $find_kota = $data_kota->firstWhere('nm_kota', $value->kota_lahir);
                if ($find_kota) {
                    $kota_lahir = $find_kota->id_kota;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, kota lahir ' . $value->kota_lahir . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, kota lahir ' . $value->kota_lahir . ' tidak ditemukan di dalam sistem');
                }
            }

            // tanggal lahir
            if (empty($value->tanggal_lahir)) {
                $tanggal_lahir = null;
            } else {
                $tanggal_lahir = date('Y-m-d', strtotime($value->tanggal_lahir));
            }

            // find kota ksk
            if (empty($value->nama_kota_ksk)) {
                $kota_ksk = null;
            } else {
                $find_kota_ksk = $data_kota->firstWhere('nm_kota', $value->nama_kota_ksk);
                if ($find_kota_ksk) {
                    $kota_ksk = $find_kota_ksk->id_kota;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, nama kota ksk ' . $value->nama_kota_ksk . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, nama kota ksk ' . $value->nama_kota_ksk . ' tidak ditemukan di dalam sistem');
                }
            }

            // nomor ksk

            if (empty($value->nomor_ksk)) {
                $nomor_ksk = null;
            } else {
                $nomor_ksk = $value->nomor_ksk;
            }

            // nomor identitas

            if (empty($value->nomor_identitas_ktp_sim_lainya)) {
                $nomor_identitas = null;
            } else {
                $nomor_identitas = $value->nomor_identitas_ktp_sim_lainya;
            }

            // nomor akta lahir

            if (empty($value->nomor_registrasi_akta_lahir)) {
                $nomor_akta_lahir = null;
            } else {
                $nomor_akta_lahir = $value->nomor_registrasi_akta_lahir;
            }

            //find kewarganegaraan
            if ($value->kewarganegaraan == 1 || $value->kewarganegaraan == 0) {
                $kewarganegaraan = (int) $value->kewarganegaraan;
            } else {
                $kewarganegaraan = null;
            }

            // nm_kewarganegaraan
            if (empty($value->nama_kewarganegaraan_jika_dari_wna)) {
                $nm_kewarganegaraan = null;
            } else {
                $nm_kewarganegaraan = $value->nama_kewarganegaraan_jika_dari_wna;
            }

            // find kebutuhan khusus
            if (empty($value->kebutuhan_khusus)) {
                $kebutuhan_khusus = null;
            } else {
                $find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus);
                if ($find_kebutuhan_khusus) {
                    $kebutuhan_khusus = $find_kebutuhan_khusus->id_kebutuhan_khusus;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, Kebutuhan Khusus ' . $value->kebutuhan_khusus . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, Kebutuhan Khusus ' . $value->kebutuhan_khusus . ' tidak ditemukan di dalam sistem');
                }
            }

            // alamat jalan
            if (empty($value->alamat_jalan)) {
                $alamat_jalan = null;
            } else {
                $alamat_jalan = $value->alamat_jalan;
            }

            // alamat dusun
            if (empty($value->alamat_dusun)) {
                $alamat_dusun = null;
            } else {
                $alamat_dusun = $value->alamat_dusun;
            }

            // alamat kelurahan
            if (empty($value->alamat_kelurahan)) {
                $alamat_kelurahan = null;
            } else {
                $alamat_kelurahan = $value->alamat_kelurahan;
            }

            // alamat rt
            if (empty($value->alamat_rt)) {
                $alamat_rt = null;
            } else {
                $alamat_rt = $value->alamat_rt;
            }

            // alamat rw
            if (empty($value->alamat_rw)) {
                $alamat_rw = null;
            } else {
                $alamat_rw = $value->alamat_rw;
            }

            // alamat kecamatan
            if (empty($value->alamat_kecamatan)) {
                $alamat_kecamatan = null;
            } else {
                $alamat_kecamatan = $value->alamat_kecamatan;
            }

            // alamat kodepos
            if (empty($value->alamat_kodepos)) {
                $alamat_kodepos = null;
            } else {
                $alamat_kodepos = $value->alamat_kodepos;
            }

            // find alamat kota
            if (empty($value->alamat_kota)) {
                $alamat_kota = null;
            } else {
                $find_alamat_kota = $data_kota->firstWhere('nm_kota', $value->alamat_kota);
                if ($find_alamat_kota) {
                    $alamat_kota = $find_alamat_kota->id_kota;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, alamat kota ' . $value->alamat_kota . ' tidak ditemukan di dalam sistem');
                }
            }

            // find id provinsi
            if (empty($value->alamat_provinsi)) {
                $alamat_provinsi = null;
            } else {
                $find_provinsi = $data_provinsi->firstWhere('nm_provinsi', $value->alamat_provinsi);
                if ($find_provinsi) {
                    $alamat_provinsi = $find_provinsi->id_provinsi;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, alamat provinsi ' . $value->alamat_provinsi . ' tidak ditemukan di dalam sistem');
                }
            }

            // alamat lattitude
            if (empty($value->alamat_latitude)) {
                $alamat_latitude = null;
            } else {
                $alamat_latitude = $value->alamat_latitude;
            }

            // alamat longitude
            if (empty($value->alamat_longitude)) {
                $alamat_longitude = null;
            } else {
                $alamat_longitude = $value->alamat_longitude;
            }

            // nomor hp
            if (empty($value->nomor_hp)) {
                $nomor_hp = null;
            } else {
                $nomor_hp = $value->nomor_hp;
            }

            // find jenis tinggal
            if (empty($value->jenis_tinggal)) {
                $jenis_tinggal = null;
            } else {
                $find_jenis_tinggal = $data_jenis_tinggal->firstWhere('kode_jenis_tinggal', $value->jenis_tinggal);
                if ($find_jenis_tinggal) {
                    $jenis_tinggal = $find_jenis_tinggal->id_jenis_tinggal;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, jenis tinggal ' . $value->jenis_tinggal . ' tidak ditemukan di dalam sistem');
                }
            }

            // anak ke
            if (empty($value->anak_ke)) {
                $anak_ke = null;
            } else {
                $anak_ke = (int) $value->anak_ke;
            }

            // dari x bersaudara
            if (empty($value->dari_berapa_bersaudara)) {
                $dari_berapa_bersaudara = null;
            } else {
                $dari_berapa_bersaudara = (int) $value->dari_berapa_bersaudara;
            }

            // jarak rumah ke sekolah (km)
            if (empty($value->jarak_rumah_ke_sekolah_km)) {
                $jarak_rumah_ke_sekolah_km = null;
            } else {
                $jarak_rumah_ke_sekolah_km = (float) $value->jarak_rumah_ke_sekolah_km;
            }

            // waktu tempuh sekolah (jam)
            if (empty($value->waktu_tempuh_ke_sekolah_jam)) {
                $waktu_tempuh_ke_sekolah_jam = null;
            } else {
                $waktu_tempuh_ke_sekolah_jam = (float) $value->waktu_tempuh_ke_sekolah_jam;
            }

            // waktu tempuh sekkolah (menit)
            if (empty($value->waktu_tempuh_ke_sekolah_menit)) {
                $waktu_tempuh_ke_sekolah_menit = null;
            } else {
                $waktu_tempuh_ke_sekolah_menit = (float) $value->waktu_tempuh_ke_sekolah_menit;
            }

            // find jenis transportasi
            if (empty($value->jenis_transportasi)) {
                $jenis_transportasi = null;
            } else {
                $find_jenis_transportasi = $data_jenis_transportasi->firstWhere('kode_jenis_transportasi', $value->jenis_transportasi);
                if ($find_jenis_transportasi) {
                    $jenis_transportasi = $find_jenis_transportasi->id_jenis_transportasi;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, jenis transportasi ' . $value->jenis_transportasi . ' tidak ditemukan di dalam sistem');
                }
            }

            // nomor kks
            if (empty($value->nomor_kartu_keluarga_sejahtera)) {
                $nomor_kartu_keluarga_sejahtera = null;
            } else {
                $nomor_kartu_keluarga_sejahtera = $value->nomor_kartu_keluarga_sejahtera;
            }

            // is penerima kps
            if ($value->merupakan_penerima_kartu_perlindungan_sosial == 1 || $value->merupakan_penerima_kartu_perlindungan_sosial == 0) {
                $merupakan_penerima_kartu_perlindungan_sosial = (int) $value->merupakan_penerima_kartu_perlindungan_sosial;
            } else {
                $merupakan_penerima_kartu_perlindungan_sosial = null;
            }

            // nomor kps
            if (empty($value->nomor_kartu_perlindungan_sosial)) {
                $nomor_kartu_perlindungan_sosial = null;
            } else {
                $nomor_kartu_perlindungan_sosial = $value->nomor_kartu_perlindungan_sosial;
            }

            // is punya kip
            if ($value->merupakan_penerima_kartu_indonesia_pintar == 1 || $value->merupakan_penerima_kartu_indonesia_pintar == 0) {
                $merupakan_penerima_kartu_indonesia_pintar = (int) $value->merupakan_penerima_kartu_indonesia_pintar;
            } else {
                $merupakan_penerima_kartu_indonesia_pintar = null;
            }

            // nomor kip
            if (empty($value->nomor_kartu_indonesia_pintar)) {
                $nomor_kartu_indonesia_pintar = null;
            } else {
                $nomor_kartu_indonesia_pintar = $value->nomor_kartu_indonesia_pintar;
            }

            // nama tertera kip
            if (empty($value->nama_tertera_pada_kip)) {
                $nama_tertera_pada_kip = null;
            } else {
                $nama_tertera_pada_kip = $value->nama_tertera_pada_kip;
            }

            // is layak pip
            if ($value->layak_pip == 1 || $value->layak_pip == 0) {
                $layak_pip = (int) $value->layak_pip;
            } else {
                $layak_pip = null;
            }

            // find jenis layak pip
            if (empty($value->jenis_layak_pip)) {
                $jenis_layak_pip = null;
            } else {
                $find_jenis_layak = $data_jenis_layak_pip->firstWhere('kode_jenis_layak_pip', $value->jenis_layak_pip);
                if ($find_jenis_layak) {
                    $jenis_layak_pip = $find_jenis_layak->id_jenis_layak_pip;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem';
                    Debugbar::error('Upload Data Siswa Gagal, jenis layak pip ' . $value->jenis_layak_pip . ' tidak ditemukan di dalam sistem');
                }
            }

            // asal sekolah
            if (empty($value->asal_sekolah)) {
                $asal_sekolah = null;
            } else {
                $asal_sekolah = $value->asal_sekolah;
            }

            // id kota sekolah asal
            if (empty($value->kota_asal_sekolah_sebelumnya)) {
                $kota_asal_sekolah_sebelumnya = null;
            } else {
                $find_kota_sekolah_asal = $data_kota->firstWhere('nm_kota', $value->kota_asal_sekolah_sebelumnya);
                if ($find_kota_sekolah_asal) {
                    $kota_asal_sekolah_sebelumnya = $find_alamat_kota->id_kota;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, kota asal sekolah sebelumnya ' . $value->kota_asal_sekolah_sebelumnya . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // nomor ujian sebelumnya
            if (empty($value->nomor_ujian_sebelumnya)) {
                $nomor_ujian_sebelumnya = null;
            } else {
                $nomor_ujian_sebelumnya = $value->nomor_ujian_sebelumnya;
            }

            // nomor ijasah sebelumnya
            if (empty($value->nomor_ijasah_sebelumnya)) {
                $nomor_ijasah_sebelumnya = null;
            } else {
                $nomor_ijasah_sebelumnya = $value->nomor_ijasah_sebelumnya;
            }

            // nomor skhus sebelumnya
            if (empty($value->nomor_skhus_sebelumnya)) {
                $nomor_skhus_sebelumnya = null;
            } else {
                $nomor_skhus_sebelumnya = $value->nomor_skhus_sebelumnya;
            }

            // nomor shun
            if (empty($value->nomor_shun_sebelumnya)) {
                $nomor_shun_sebelumnya = null;
            } else {
                $nomor_shun_sebelumnya = $value->nomor_shun_sebelumnya;
            }

            // nilai shun
            if (empty($value->nilai_shun_sebelumnya)) {
                $nilai_shun_sebelumnya = null;
            } else {
                $nilai_shun_sebelumnya = (float) $value->nilai_shun_sebelumnya;
            }

            // tahun lulus
            if (empty($value->tahun_lulus)) {
                $tahun_lulus = null;
            } else {
                $tahun_lulus = (int) $value->tahun_lulus;
            }

            // no peserta unas
            if (empty($value->nomor_peserta_unas)) {
                $nomor_peserta_unas = null;
            } else {
                $nomor_peserta_unas = $value->nomor_peserta_unas;
            }

            // nama ayah
            if (empty($value->nama_ayah)) {
                $nama_ayah = null;
            } else {
                $nama_ayah = $value->nama_ayah;
            }

            // nik ayah
            if (empty($value->nik_ayah)) {
                $nik_ayah = null;
            } else {
                $nik_ayah = $value->nik_ayah;
            }

            // tanggal lahir ayah
            if (empty($value->tanggal_lahir_ayah)) {
                $tanggal_lahir_ayah = null;
            } else {
                $tanggal_lahir_ayah = date('Y-m-d', strtotime($value->tanggal_lahir_ayah));
            }

            // jenis pendidikan ayah
            if (empty($value->jenis_pendidikan_ayah)) {
                $jenis_pendidikan_ayah = null;
            } else {
                $find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_ayah);
                if ($find_jenis_pendidikan) {
                    $jenis_pendidikan_ayah = $find_jenis_pendidikan->id_jenis_pendidikan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis pendidikan ayah ' . $value->jenis_pendidikan_ayah . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // jenis pekerjaan ayah
            if (empty($value->jenis_pekerjaan_ayah)) {
                $jenis_pekerjaan_ayah = null;
            } else {
                $find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_ayah);
                if ($find_jenis_pekerjaan) {
                    $jenis_pekerjaan_ayah = $find_jenis_pekerjaan->id_jenis_pekerjaan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis pekerjaan ayah ' . $value->jenis_pekerjaan_ayah . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // jenis penghasilan ayah
            if (empty($value->jenis_penghasilan_ayah)) {
                $jenis_penghasilan_ayah = null;
            } else {
                $find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_ayah);
                if ($find_jenis_penghasilan) {
                    $jenis_penghasilan_ayah = $find_jenis_penghasilan->id_jenis_penghasilan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis penghasilan ayah ' . $value->jenis_penghasilan_ayah . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // kebutuhan khusus ayah
            if (empty($value->kebutuhan_khusus_ayah)) {
                $kebutuhan_khusus_ayah = null;
            } else {
                $find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_ayah);
                if ($find_kebutuhan_khusus) {
                    $kebutuhan_khusus_ayah = $find_kebutuhan_khusus->id_kebutuhan_khusus;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, kebutuhan khusus ayah ' . $value->kebutuhan_khusus_ayah . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // nama ibu
            if (empty($value->nama_ibu)) {
                $nama_ibu = null;
            } else {
                $nama_ibu = $value->nama_ibu;
            }

            // nik ibu
            if (empty($value->nik_ibu)) {
                $nik_ibu = null;
            } else {
                $nik_ibu = $value->nik_ibu;
            }

            // tanggal lahir ibu
            if (empty($value->tanggal_lahir_ibu)) {
                $tanggal_lahir_ibu = null;
            } else {
                $tanggal_lahir_ibu = date('Y-m-d', strtotime($value->tanggal_lahir_ibu));
            }

            // jenis pendidikan ibu
            if (empty($value->jenis_pendidikan_ibu)) {
                $jenis_pendidikan_ibu = null;
            } else {
                $find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_ibu);
                if ($find_jenis_pendidikan) {
                    $jenis_pendidikan_ibu = $find_jenis_pendidikan->id_jenis_pendidikan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis pendidikan ibu ' . $value->jenis_pendidikan_ibu . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // jenis pekerjaan ibu
            if (empty($value->jenis_pekerjaan_ibu)) {
                $jenis_pekerjaan_ibu = null;
            } else {
                $find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_ibu);
                if ($find_jenis_pekerjaan) {
                    $jenis_pekerjaan_ibu = $find_jenis_pekerjaan->id_jenis_pekerjaan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis pekerjaan ibu ' . $value->jenis_pekerjaan_ibu . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // jenis penghasilan ibu
            if (empty($value->jenis_penghasilan_ibu)) {
                $jenis_penghasilan_ibu = null;
            } else {
                $find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_ibu);
                if ($find_jenis_penghasilan) {
                    $jenis_penghasilan_ibu = $find_jenis_penghasilan->id_jenis_penghasilan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis penghasilan ibu ' . $value->jenis_penghasilan_ibu . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // kebutuhan khusus ibu
            if (empty($value->kebutuhan_khusus_ibu)) {
                $kebutuhan_khusus_ibu = null;
            } else {
                $find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_ibu);
                if ($find_kebutuhan_khusus) {
                    $kebutuhan_khusus_ibu = $find_kebutuhan_khusus->id_kebutuhan_khusus;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, kebutuhan khusus ibu ' . $value->kebutuhan_khusus_ibu . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // nama wali
            if (empty($value->nama_wali)) {
                $nama_wali = null;
            } else {
                $nama_wali = $value->nama_wali;
            }

            // nik wali
            if (empty($value->nik_wali)) {
                $nik_wali = null;
            } else {
                $nik_wali = $value->nik_wali;
            }

            // tanggal lahir wali
            if (empty($value->tanggal_lahir_wali)) {
                $tanggal_lahir_wali = null;
            } else {
                $tanggal_lahir_wali = date('Y-m-d', strtotime($value->tanggal_lahir_wali));
            }

            // jenis pendidikan wali
            if (empty($value->jenis_pendidikan_wali)) {
                $jenis_pendidikan_wali = null;
            } else {
                $find_jenis_pendidikan = $data_jenis_pendidikan->firstWhere('kode_jenis_pendidikan', $value->jenis_pendidikan_wali);
                if ($find_jenis_pendidikan) {
                    $jenis_pendidikan_wali = $find_jenis_pendidikan->id_jenis_pendidikan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis pendidikan wali ' . $value->jenis_pendidikan_wali . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // jenis pekerjaan wali
            if (empty($value->jenis_pekerjaan_wali)) {
                $jenis_pekerjaan_wali = null;
            } else {
                $find_jenis_pekerjaan = $data_jenis_pekerjaan->firstWhere('kode_jenis_pekerjaan', $value->jenis_pekerjaan_wali);
                if ($find_jenis_pekerjaan) {
                    $jenis_pekerjaan_wali = $find_jenis_pekerjaan->id_jenis_pekerjaan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis pekerjaan wali ' . $value->jenis_pekerjaan_wali . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // jenis penghasilan wali
            if (empty($value->jenis_penghasilan_wali)) {
                $jenis_penghasilan_wali = null;
            } else {
                $find_jenis_penghasilan = $data_jenis_penghasilan->firstWhere('kode_jenis_penghasilan', $value->jenis_penghasilan_wali);
                if ($find_jenis_penghasilan) {
                    $jenis_penghasilan_wali = $find_jenis_penghasilan->id_jenis_penghasilan;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, jenis penghasilan wali ' . $value->jenis_penghasilan_wali . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // kebutuhan khusus wali
            if (empty($value->kebutuhan_khusus_wali)) {
                $kebutuhan_khusus_wali = null;
            } else {
                $find_kebutuhan_khusus = $data_kebutuhan_khusus->firstWhere('kode_kebutuhan_khusus', $value->kebutuhan_khusus_wali);
                if ($find_kebutuhan_khusus) {
                    $kebutuhan_khusus_wali = $find_kebutuhan_khusus->id_kebutuhan_khusus;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, kebutuhan khusus wali ' . $value->kebutuhan_khusus_wali . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // alamat jalan orang tua
            if (empty($value->alamat_jalan_orang_tua)) {
                $alamat_jalan_orang_tua = null;
            } else {
                $alamat_jalan_orang_tua = $value->alamat_jalan_orang_tua;
            }

            // alamat dusun orang tua
            if (empty($value->alamat_dusun_orang_tua)) {
                $alamat_dusun_orang_tua = null;
            } else {
                $alamat_dusun_orang_tua = $value->alamat_dusun_orang_tua;
            }

            // alamat kelurahan orang tua
            if (empty($value->alamat_kelurahan_orang_tua)) {
                $alamat_kelurahan_orang_tua = null;
            } else {
                $alamat_kelurahan_orang_tua = $value->alamat_kelurahan_orang_tua;
            }

            // alamat rt orang tua
            if (empty($value->alamat_rt_orang_tua)) {
                $alamat_rt_orang_tua = null;
            } else {
                $alamat_rt_orang_tua = $value->alamat_rt_orang_tua;
            }

            // alamat rw orang tua
            if (empty($value->alamat_rw_orang_tua)) {
                $alamat_rw_orang_tua = null;
            } else {
                $alamat_rw_orang_tua = $value->alamat_rw_orang_tua;
            }

            // alamat kecamatan orang tua
            if (empty($value->alamat_kecamatan_orang_tua)) {
                $alamat_kecamatan_orang_tua = null;
            } else {
                $alamat_kecamatan_orang_tua = $value->alamat_kecamatan_orang_tua;
            }

            // alamat kodepos orang tua
            if (empty($value->alamat_kodepos_orang_tua)) {
                $alamat_kodepos_orang_tua = null;
            } else {
                $alamat_kodepos_orang_tua = $value->alamat_kodepos_orang_tua;
            }

            // find alamat kota orang tua
            if (empty($value->alamat_kota_orang_tua)) {
                $alamat_kota_orang_tua = null;
            } else {
                $find_alamat_kota = $data_kota->firstWhere('nm_kota', $value->alamat_kota_orang_tua);
                if ($find_alamat_kota) {
                    $alamat_kota_orang_tua = $find_alamat_kota->id_kota;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, alamat kota orang tua ' . $value->alamat_kota_orang_tua . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // find id provinsi orang tua
            if (empty($value->alamat_provinsi_orang_tua)) {
                $alamat_provinsi_orang_tua = null;
            } else {
                $find_provinsi = $data_provinsi->firstWhere('nm_provinsi', $value->alamat_provinsi_orang_tua);
                if ($find_provinsi) {
                    $alamat_provinsi_orang_tua = $find_provinsi->id_provinsi;
                } else {
                    $this->message[] = 'Upload Data Siswa Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, alamat provinsi orang tua ' . $value->alamat_provinsi_orang_tua . ' tidak ditemukan di dalam sistem'
                    );
                }
            }

            // no telp orang tua
            if (empty($value->no_telepon_orang_tua)) {
                $no_telepon_orang_tua = null;
            } else {
                $no_telepon_orang_tua = $value->no_telepon_orang_tua;
            }

            // no hp orang tua
            if (empty($value->no_hp_orang_tua)) {
                $no_hp_orang_tua = null;
            } else {
                $no_hp_orang_tua = $value->no_hp_orang_tua;
            }

            // email orang tua
            if (empty($value->email_orang_tua)) {
                $email_orang_tua = null;
            } else {
                $email_orang_tua = $value->email_orang_tua;
            }

            // tinggi badan
            if (empty($value->tinggi_badan_cm)) {
                $tinggi_badan_cm = null;
            } else {
                $tinggi_badan_cm = (float) $value->tinggi_badan_cm;
            }

            // berat badan
            if (empty($value->berat_badan_kg)) {
                $berat_badan_kg = null;
            } else {
                $berat_badan_kg = (float) $value->berat_badan_kg;
            }

            // is berjilbab
            if ($value->apakah_berjilbab == 1 || $value->apakah_berjilbab == 0) {
                $apakah_berjilbab = (int) $value->apakah_berjilbab;
            } else {
                $apakah_berjilbab = null;
            }

            // is buta warna
            if ($value->apakah_buta_warna == 1 || $value->apakah_buta_warna == 0) {
                $apakah_buta_warna = (int) $value->apakah_buta_warna;
            } else {
                $apakah_buta_warna = null;
            }

            // ukuran baju
            if (empty($value->ukuran_baju)) {
                $ukuran_baju = null;
            } else {
                $ukuran_baju = $value->ukuran_baju;
            }

            // riwayat penyakit
            if (empty($value->riwayat_penyakit)) {
                $riwayat_penyakit = null;
            } else {
                $riwayat_penyakit = $value->riwayat_penyakit;
            }

            // golongan darah
            if ($value->golongan_darah == 'A' || $value->golongan_darah == 'B' || $value->golongan_darah == 'O' || $value->golongan_darah == 'AB') {
                $golongan_darah = $value->golongan_darah;
            } else {
                $golongan_darah = null;
            }

            // riwayat kelainan jasmani
            if (empty($value->riwayat_kelainan_jasmani)) {
                $riwayat_kelainan_jasmani = null;
            } else {
                $riwayat_kelainan_jasmani = $value->riwayat_kelainan_jasmani;
            }

            if ($id_penerimaan == null || $semester_masuk == null || $jenis_kelamin == null || $jalur == null || $kelas == null || $status == null) {
                $this->message[] = 'Upload Data Siswa Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"';
                Debugbar::error(
                    'Upload Data Siswa Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"'
                );
            } else {
                //generate id
                $id_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
                $id_pengguna = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
                $id_c_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
                $id_admisi = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
                $id_jalur_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
                $id_log_kelas_siswa = $this->auth_data->sekolah_data->prefix . rand(10000000, 99999999) . uniqid();
                $arr[] = array(
                    'id_log_kelas_siswa' => $id_log_kelas_siswa,
                    'id_siswa' => $id_siswa,
                    'id_pengguna' => $id_pengguna,
                    'id_c_siswa' => $id_c_siswa,
                    'id_admisi' => $id_admisi,
                    'id_jalur_siswa' => $id_jalur_siswa,
                    'id_penerimaan' => $id_penerimaan->id_penerimaan,
                    'nis' => (string) $value->nis,
                    'nisn' => (string) $value->nisn,
                    'nama_lengkap' => $value->nama_lengkap,
                    'status_siswa' => $status->id_status_pengguna,
                    'kelas' => $kelas->id_kelas,
                    'tahun_masuk' => (int) $value->tahun_masuk,
                    'jalur' => $jalur->id_jalur,
                    'jenis_kelamin' => $jenis_kelamin,
                    'semester_masuk' => $semester_masuk['id_semester'],
                    'id_sekolah' => $this->auth_data->pengguna->id_sekolah,
                    'created_by' => $this->auth_data->pengguna->id_pengguna,
                    'is_orang_tua' => $is_orang_tua,
                    'kode_voucher' => $kode_voucher,
                    'nik_siswa' => $nik,
                    'id_agama' => $agama,
                    'id_kota_lahir' => $kota_lahir,
                    'tgl_lahir' => $tanggal_lahir,
                    'id_kota_ksk' => $kota_ksk,
                    'nomor_ksk' => $nomor_ksk,
                    'nomor_identitas' => $nomor_identitas,
                    'nomor_akta_lahir' => $nomor_akta_lahir,
                    'kewarganegaraan' => $kewarganegaraan,
                    'nm_kewarganegaraan' => $nm_kewarganegaraan,
                    'id_kebutuhan_khusus' => $kebutuhan_khusus,
                    'alamat_jalan' => $alamat_jalan,
                    'alamat_dusun' => $alamat_dusun,
                    'alamat_kelurahan' => $alamat_kelurahan,
                    'alamat_rt' => $alamat_rt,
                    'alamat_rw' => $alamat_rw,
                    'alamat_kecamatan' => $alamat_kecamatan,
                    'alamat_kodepos' => $alamat_kodepos,
                    'alamat_kota' => $alamat_kota,
                    'alamat_provinsi' => $alamat_provinsi,
                    'alamat_latitude' => $alamat_latitude,
                    'alamat_longitude' => $alamat_longitude,
                    'nomor_hp' => $nomor_hp,
                    'id_jenis_tinggal' => $jenis_tinggal,
                    'anak_ke' => $anak_ke,
                    'dari_x_bersaudara' => $dari_berapa_bersaudara,
                    'jarak_rumah_sekolah' => $jarak_rumah_ke_sekolah_km,
                    'waktu_tempuh_sekolah_jam' => $waktu_tempuh_ke_sekolah_jam,
                    'waktu_tempuh_sekolah_menit' => $waktu_tempuh_ke_sekolah_menit,
                    'id_jenis_transportasi' => $jenis_transportasi,
                    'nomor_kks' => $nomor_kartu_keluarga_sejahtera,
                    'is_penerima_kps' => $merupakan_penerima_kartu_perlindungan_sosial,
                    'nomor_kps' => $nomor_kartu_perlindungan_sosial,
                    'is_punya_kip' => $merupakan_penerima_kartu_indonesia_pintar,
                    'nomor_kip' => $nomor_kartu_indonesia_pintar,
                    'nm_tertera_kip' => $nama_tertera_pada_kip,
                    'is_layak_pip' => $layak_pip,
                    'id_jenis_layak_pip' => $jenis_layak_pip,
                    'asal_sekolah' => $asal_sekolah,
                    'id_kota_sekolah_asal' => $kota_asal_sekolah_sebelumnya,
                    'nomor_ujian_sebelumnya' => $nomor_ujian_sebelumnya,
                    'nomor_ijasah_sebelumnya' => $nomor_ijasah_sebelumnya,
                    'nomor_skhus_sebelumnya' => $nomor_skhus_sebelumnya,
                    'nomor_shun' => $nomor_shun_sebelumnya,
                    'nilai_shun' => $nilai_shun_sebelumnya,
                    'tahun_lulus' => $tahun_lulus,
                    'nomor_peserta_unas' => $nomor_peserta_unas,
                    'nm_ayah' => $nama_ayah,
                    'nik_ayah' => $nik_ayah,
                    'tgl_lahir_ayah' => $tanggal_lahir_ayah,
                    'id_jenis_pendidikan_ayah' => $jenis_pendidikan_ayah,
                    'id_jenis_pekerjaan_ayah' => $jenis_pekerjaan_ayah,
                    'id_jenis_penghasilan_ayah' => $jenis_penghasilan_ayah,
                    'id_kebutuhan_khusus_ayah' => $kebutuhan_khusus_ayah,
                    'nm_ibu' => $nama_ibu,
                    'nik_ibu' => $nik_ibu,
                    'tgl_lahir_ibu' => $tanggal_lahir_ibu,
                    'id_jenis_pendidikan_ibu' => $jenis_pendidikan_ibu,
                    'id_jenis_pekerjaan_ibu' => $jenis_pekerjaan_ibu,
                    'id_jenis_penghasilan_ibu' => $jenis_penghasilan_ibu,
                    'id_kebutuhan_khusus_ibu' => $kebutuhan_khusus_ibu,
                    'nm_wali' => $nama_wali,
                    'nik_wali' => $nik_wali,
                    'tgl_lahir_wali' => $tanggal_lahir_wali,
                    'id_jenis_pendidikan_wali' => $jenis_pendidikan_wali,
                    'id_jenis_pekerjaan_wali' => $jenis_pekerjaan_wali,
                    'id_jenis_penghasilan_wali' => $jenis_penghasilan_wali,
                    'id_kebutuhan_khusus_wali' => $kebutuhan_khusus_wali,
                    'alamat_jalan_ortu' => $alamat_jalan_orang_tua,
                    'alamat_dusun_ortu' => $alamat_dusun_orang_tua,
                    'alamat_kelurahan_ortu' => $alamat_kelurahan_orang_tua,
                    'alamat_rt_ortu' => $alamat_rt_orang_tua,
                    'alamat_rw_ortu' => $alamat_rw_orang_tua,
                    'alamat_kecamatan_ortu' => $alamat_kecamatan_orang_tua,
                    'alamat_kodepos_ortu' => $alamat_kodepos_orang_tua,
                    'alamat_kota_ortu' => $alamat_kota_orang_tua,
                    'alamat_provinsi_ortu' => $alamat_provinsi_orang_tua,
                    'nomor_telp_ortu' => $no_telepon_orang_tua,
                    'nomor_hp_ortu' => $no_hp_orang_tua,
                    'email_ortu' => $email_orang_tua,
                    'tinggi_badan' => $tinggi_badan_cm,
                    'berat_badan' => $berat_badan_kg,
                    'is_berjilbab' => $apakah_berjilbab,
                    'is_buta_warna' => $apakah_buta_warna,
                    'ukuran_baju' => $ukuran_baju,
                    'riwayat_penyakit' => $riwayat_penyakit,
                    'golongan_darah' => $golongan_darah,
                    'riwayat_kelainan_jasmani' => $riwayat_kelainan_jasmani,

                );
            }
        }

        // if ($arr) {

        if (count($arr) != 0) {
            foreach ($arr as $data_siswa_1) {
                $jumlah_nis = 0;
                $jumlah_nisn = 0;
                foreach ($arr as $data_siswa_2) {
                    if ($data_siswa_1['nis'] == $data_siswa_2['nis']) {
                        $jumlah_nis++;
                    }

                    if ($data_siswa_1['nisn'] == $data_siswa_2['nisn']) {
                        $jumlah_nisn++;
                    }
                }

                if ($jumlah_nis > 1) {
                    $this->message[] = 'Upload Data Siswa Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, ditemukan NIS ' . $data_siswa_1['nis'] . ' yang sama di dalam file yang diupload'
                    );
                }

                if (!empty($data_siswa_1['nisn']) && $jumlah_nisn > 1) {
                    $this->message[] = 'Upload Data Siswa Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload';
                    Debugbar::error(
                        'Upload Data Siswa Gagal, ditemukan NISN ' . $data_siswa_1['nisn'] . ' yang sama di dalam file yang diupload'
                    );
                }
            }

            DB::beginTransaction();
            try {
                $pengguna_center = [];
                foreach ($arr as $data_siswa) {
                    $row_calon_siswa_baru = [
                        'id_penerimaan' => $data_siswa['id_penerimaan'],
                        'nm_c_siswa' => $data_siswa['nama_lengkap'],
                        'jenis_kelamin' => $data_siswa['jenis_kelamin'],
                        'nisn_siswa' => $data_siswa['nisn'],
                        'nis_siswa' => $data_siswa['nis'],

                        'kode_voucher' => $data_siswa['kode_voucher'],
                        'nik_siswa' => $data_siswa['nik_siswa'],
                        'id_agama' => $data_siswa['id_agama'],
                        'id_kota_lahir' => $data_siswa['id_kota_lahir'],
                        'tgl_lahir' => $data_siswa['tgl_lahir'],
                        'id_kota_ksk' => $data_siswa['id_kota_ksk'],
                        'nomor_ksk' => $data_siswa['nomor_ksk'],
                        'nomor_identitas' => $data_siswa['nomor_identitas'],
                        'nomor_akta_lahir' => $data_siswa['nomor_akta_lahir'],
                        'kewarganegaraan' => $data_siswa['kewarganegaraan'],
                        'nm_kewarganegaraan' => $data_siswa['nm_kewarganegaraan'],
                        'id_kebutuhan_khusus' => $data_siswa['id_kebutuhan_khusus'],
                        'alamat_jalan' => $data_siswa['alamat_jalan'],
                        'alamat_dusun' => $data_siswa['alamat_dusun'],
                        'alamat_kelurahan' => $data_siswa['alamat_kelurahan'],
                        'alamat_rt' => $data_siswa['alamat_rt'],
                        'alamat_rw' => $data_siswa['alamat_rw'],
                        'alamat_kecamatan' => $data_siswa['alamat_kecamatan'],
                        'alamat_kodepos' => $data_siswa['alamat_kodepos'],
                        'alamat_kota' => $data_siswa['alamat_kota'],
                        'alamat_provinsi' => $data_siswa['alamat_provinsi'],
                        'alamat_latitude' => $data_siswa['alamat_latitude'],
                        'alamat_longitude' => $data_siswa['alamat_longitude'],
                        'nomor_hp' => $data_siswa['nomor_hp'],
                        'id_jenis_tinggal' => $data_siswa['id_jenis_tinggal'],
                        'anak_ke' => $data_siswa['anak_ke'],
                        'dari_x_bersaudara' => $data_siswa['dari_x_bersaudara'],
                        'jarak_rumah_sekolah' => $data_siswa['jarak_rumah_sekolah'],
                        'waktu_tempuh_sekolah_jam' => $data_siswa['waktu_tempuh_sekolah_jam'],
                        'waktu_tempuh_sekolah_menit' => $data_siswa['waktu_tempuh_sekolah_menit'],
                        'id_jenis_transportasi' => $data_siswa['id_jenis_transportasi'],
                        'nomor_kks' => $data_siswa['nomor_kks'],
                        'is_penerima_kps' => $data_siswa['is_penerima_kps'],
                        'nomor_kps' => $data_siswa['nomor_kps'],
                        'is_punya_kip' => $data_siswa['is_punya_kip'],
                        'nomor_kip' => $data_siswa['nomor_kip'],
                        'nm_tertera_kip' => $data_siswa['nm_tertera_kip'],
                        'is_layak_pip' => $data_siswa['is_layak_pip'],
                        'id_jenis_layak_pip' => $data_siswa['id_jenis_layak_pip'],
                        'asal_sekolah' => $data_siswa['asal_sekolah'],
                        'nomor_ujian_sebelumnya' => $data_siswa['nomor_ujian_sebelumnya'],
                        'nomor_ijasah_sebelumnya' => $data_siswa['nomor_ijasah_sebelumnya'],
                        'nomor_skhus_sebelumnya' => $data_siswa['nomor_skhus_sebelumnya'],

                        'created_at' => $this->now,
                        'created_by' => $data_siswa['created_by'],
                        'updated_at' => $this->now,
                        'updated_by' => $data_siswa['created_by'],
                    ];

                    $check_nis_siswa = Siswa::where('nis_siswa', $data_siswa['nis'])->first();

                    if ($check_nis_siswa) {
                        DB::table('calon_siswa_baru')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_baru);
                    } else {
                        $row_calon_siswa_baru['id_c_siswa'] = $data_siswa['id_c_siswa'];

                        DB::table('calon_siswa_baru')->insert($row_calon_siswa_baru);
                    }

                    $row_calon_siswa_fisik = [
                        'tinggi_badan' => $data_siswa['tinggi_badan'],
                        'berat_badan' => $data_siswa['berat_badan'],
                        'is_berjilbab' => $data_siswa['is_berjilbab'],
                        'is_buta_warna' => $data_siswa['is_buta_warna'],
                        'ukuran_baju' => $data_siswa['ukuran_baju'],
                        'riwayat_penyakit' => $data_siswa['riwayat_penyakit'],
                        'golongan_darah' => $data_siswa['golongan_darah'],
                        'riwayat_kelainan_jasmani' => $data_siswa['riwayat_kelainan_jasmani'],

                        'created_at' => $this->now,
                        'created_by' => $data_siswa['created_by'],
                        'updated_at' => $this->now,
                        'updated_by' => $data_siswa['created_by'],
                    ];

                    if ($check_nis_siswa) {
                        DB::table('calon_siswa_fisik')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_fisik);
                    } else {
                        $row_calon_siswa_fisik['id_c_siswa'] = $data_siswa['id_c_siswa'];

                        DB::table('calon_siswa_fisik')->insert($row_calon_siswa_fisik);
                    }

                    $row_calon_siswa_ortu = [
                        'nm_ayah' => $data_siswa['nm_ayah'],
                        'nik_ayah' => $data_siswa['nik_ayah'],
                        'tgl_lahir_ayah' => $data_siswa['tgl_lahir_ayah'],
                        'id_jenis_pendidikan_ayah' => $data_siswa['id_jenis_pendidikan_ayah'],
                        'id_jenis_pekerjaan_ayah' => $data_siswa['id_jenis_pekerjaan_ayah'],
                        'id_jenis_penghasilan_ayah' => $data_siswa['id_jenis_penghasilan_ayah'],
                        'id_kebutuhan_khusus_ayah' => $data_siswa['id_kebutuhan_khusus_ayah'],
                        'nm_ibu' => $data_siswa['nm_ibu'],
                        'nik_ibu' => $data_siswa['nik_ibu'],
                        'tgl_lahir_ibu' => $data_siswa['tgl_lahir_ibu'],
                        'id_jenis_pendidikan_ibu' => $data_siswa['id_jenis_pendidikan_ibu'],
                        'id_jenis_pekerjaan_ibu' => $data_siswa['id_jenis_pekerjaan_ibu'],
                        'id_jenis_penghasilan_ibu' => $data_siswa['id_jenis_penghasilan_ibu'],
                        'id_kebutuhan_khusus_ibu' => $data_siswa['id_kebutuhan_khusus_ibu'],
                        'nm_wali' => $data_siswa['nm_wali'],
                        'nik_wali' => $data_siswa['nik_wali'],
                        'tgl_lahir_wali' => $data_siswa['tgl_lahir_wali'],
                        'id_jenis_pendidikan_wali' => $data_siswa['id_jenis_pendidikan_wali'],
                        'id_jenis_pekerjaan_wali' => $data_siswa['id_jenis_pekerjaan_wali'],
                        'id_jenis_penghasilan_wali' => $data_siswa['id_jenis_penghasilan_wali'],
                        'id_kebutuhan_khusus_wali' => $data_siswa['id_kebutuhan_khusus_wali'],
                        'alamat_jalan_ortu' => $data_siswa['alamat_jalan_ortu'],
                        'alamat_dusun_ortu' => $data_siswa['alamat_dusun_ortu'],
                        'alamat_kelurahan_ortu' => $data_siswa['alamat_kelurahan_ortu'],
                        'almat_rt_ortu' => $data_siswa['alamat_rt_ortu'],
                        'alamat_rw_ortu' => $data_siswa['alamat_rw_ortu'],
                        'alamat_kecamatan_ortu' => $data_siswa['alamat_kecamatan_ortu'],
                        'alamat_kodepos_ortu' => $data_siswa['alamat_kodepos_ortu'],
                        'alamat_kota_ortu' => $data_siswa['alamat_kota_ortu'],
                        'alamat_provinsi_ortu' => $data_siswa['alamat_provinsi_ortu'],
                        'nomor_telp_ortu' => $data_siswa['nomor_telp_ortu'],
                        'nomor_hp_ortu' => $data_siswa['nomor_hp_ortu'],
                        'email_ortu' => $data_siswa['email_ortu'],

                        'created_at' => $this->now,
                        'created_by' => $data_siswa['created_by'],
                        'updated_at' => $this->now,
                        'updated_by' => $data_siswa['created_by'],
                    ];

                    if ($check_nis_siswa) {
                        DB::table('calon_siswa_ortu')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($row_calon_siswa_ortu);
                    } else {
                        $row_calon_siswa_ortu['id_c_siswa'] = $data_siswa['id_c_siswa'];

                        DB::table('calon_siswa_ortu')->insert($row_calon_siswa_ortu);
                    }

                    $calon_siswa_sekolah = [
                        'nm_sekolah_asal' => $data_siswa['asal_sekolah'],
                        'id_kota_sekolah_asal' => $data_siswa['id_kota_sekolah_asal'],
                        'nomor_shun' => $data_siswa['nomor_shun'],
                        'nilai_shun' => $data_siswa['nilai_shun'],
                        'nomor_ijasah' => $data_siswa['nomor_ijasah_sebelumnya'],
                        'tahun_lulus' => $data_siswa['tahun_lulus'],
                        'nomor_peserta_unas' => $data_siswa['nomor_peserta_unas'],
                        'nisn' => $data_siswa['nisn'],

                        'created_at' => $this->now,
                        'created_by' => $data_siswa['created_by'],
                        'updated_at' => $this->now,
                        'updated_by' => $data_siswa['created_by'],
                    ];

                    if ($check_nis_siswa) {
                        DB::table('calon_siswa_sekolah')->where('id_c_siswa', $check_nis_siswa->id_c_siswa)->update($calon_siswa_sekolah);
                    } else {
                        $calon_siswa_sekolah['id_c_siswa'] = $data_siswa['id_c_siswa'];

                        DB::table('calon_siswa_sekolah')->insert($calon_siswa_sekolah);
                    }

                    if ($check_nis_siswa) {
                        DB::table('pengguna')->where('id_pengguna', $check_nis_siswa->id_pengguna)->update(['nm_pengguna' => $data_siswa['nama_lengkap']]);
                    } else {
                        DB::table('pengguna')->insert(
                            [
                                'id_pengguna' => $data_siswa['id_pengguna'],
                                'id_status_pengguna' => $data_siswa['status_siswa'],
                                'id_sekolah' => $data_siswa['id_sekolah'],
                                'nm_pengguna' => $data_siswa['nama_lengkap'],
                                'username' => $data_siswa['nis'],
                                'password' => Hash::make($data_siswa['nis']),
                                'must_change_password' => 1,
                                'status_join_table' => 3,
                                'created_at' => $this->now,
                                'created_by' => $data_siswa['created_by'],
                            ]
                        );
                    }

                    $row_siswa = [
                        'id_kelas' => $data_siswa['kelas'],
                        'nisn_siswa' => $data_siswa['nisn'],

                        'is_orang_tua' => $data_siswa['is_orang_tua'],

                        'thn_masuk_siswa' => $data_siswa['tahun_masuk'],
                        'created_at' => $this->now,
                        'created_by' => $data_siswa['created_by'],
                        'updated_at' => $this->now,
                        'updated_by' => $data_siswa['created_by'],
                    ];

                    if ($check_nis_siswa) {
                        DB::table('siswa')->where('id_siswa', $check_nis_siswa->id_siswa)->update($row_siswa);
                    } else {
                        $row_siswa['id_siswa'] = $data_siswa['id_siswa'];
                        $row_siswa['id_pengguna'] = $data_siswa['id_pengguna'];
                        $row_siswa['id_c_siswa'] = $data_siswa['id_c_siswa'];
                        $row_siswa['id_kelompok_biaya'] = null;
                        $row_siswa['nis_siswa'] = $data_siswa['nis'];

                        DB::table('siswa')->insert($row_siswa);
                    }

                    if ($check_nis_siswa) { } else {
                        DB::table('admisi')->insert(
                            [
                                'id_admisi' => $data_siswa['id_admisi'],
                                'id_siswa' => $data_siswa['id_siswa'],
                                'id_semester' => $data_siswa['semester_masuk'],
                                'id_status_pengguna' => $data_siswa['status_siswa'],
                                'id_jalur' => $data_siswa['jalur'],
                                'created_at' => $this->now,
                                'created_by' => $data_siswa['created_by'],
                            ]
                        );

                        DB::table('jalur_siswa')->insert(
                            [
                                'id_jalur_siswa' => $data_siswa['id_jalur_siswa'],
                                'id_siswa' => $data_siswa['id_siswa'],
                                'id_semester' => $data_siswa['semester_masuk'],
                                'id_jalur' => $data_siswa['jalur'],
                                'id_admisi' => $data_siswa['id_admisi'],
                                'is_jalur_aktif' => 1,
                                'created_at' => $this->now,
                                'created_by' => $data_siswa['created_by'],
                            ]
                        );

                        DB::table('role_pengguna')->insert(
                            [
                                'id_pengguna' => $data_siswa['id_pengguna'],
                                'id_role' => 3,
                                'keterangan_role_pengguna' => "Input Pendidikan",
                                'is_aktif' => 1,
                                'created_at' => $this->now,
                                'created_by' => $data_siswa['created_by'],
                            ]
                        );

                        DB::table('log_kelas_siswa')->insert(
                            [
                                'id_log_kelas_siswa' => $data_siswa['id_log_kelas_siswa'],
                                'id_siswa' => $data_siswa['id_siswa'],
                                'id_kelas' => $data_siswa['kelas'],
                                'created_at' => $this->now,
                                'updated_at' => $this->now,
                                'created_by' => $data_siswa['created_by'],
                            ]
                        );

                        $pengguna_center[] = [
                            "id_pengguna" => $data_siswa['id_pengguna'],
                            "id_sekolah" => $data_siswa['id_sekolah'],
                            "username" => $data_siswa['nis'],
                        ];
                    }
                }

                LibGlobal::insertUpdateUserInCenter($pengguna_center);
                DB::commit();
                $this->message[] = 'Save Siswa Successfully';
                Debugbar::error('Save Siswa Successfully');
            } catch (\Exception $e) {

                DB::rollback();
                // something went wrong
                //    Debugbar::error( (env('APP_DEBUG', 'true') == 'true') ? 'tesst' : 'Operation error');
                $this->message[] = (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error';
                Debugbar::error((env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error');
            }
        } else {
            $this->message[] = 'File Excel Anda Kosong';
            Debugbar::error("File Excel Anda Kosong");
        }
    }

    public function getMessage()
    {
        return $this->message;
    }
}
