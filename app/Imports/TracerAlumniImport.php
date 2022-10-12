<?php

namespace App\Imports;

use Barryvdh\Debugbar\Facade as Debugbar;
use App\Imports\Hash;

use App\Libraries\LibGlobal;
use App\Models\CalonSiswaBaru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Penerimaan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use DB;

class TracerAlumniImport implements ToCollection, WithHeadingRow
{
    protected $auth_data;
    protected $now;

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

        $data_kelas = Kelas::get();
        $data_jurusan = Jurusan::get();
        $data_penerimaan = Penerimaan::get();
        // $data_siswa = CalonSiswaBaru::get();

        $arr = array();
        foreach ($data as $key => $data_row) {
            $value = new \stdClass();
            foreach ($data_row as $key => $temp_item) {
                $value->$key = $temp_item;
            }

            $id_penerimaan 		= $data_penerimaan->firstWhere('tahun_penerimaan', '=', (int)$value->tahun_masuk);

            if (empty($id_penerimaan)) {
                $id_penerimaan = $data_penerimaan->firstWhere('tahun_penerimaan', '=', 2022);
                Debugbar::error( 'Upload Data Siswa Gagal, tahun masuk ' . $value->tahun_masuk . ' tidak ditemukan di dalam sistem');
            }


            $kelas             = $data_kelas->firstWhere('nm_kelas', '=', $value->kelas);

            if (empty($kelas)) {
                Debugbar::error('Upload Data Siswa Gagal, kelas ' . $value->kelas . ' tidak ditemukan di dalam sistem');
            }

            $jurusan    = $data_jurusan->firstWhere('nm_jurusan', '=', $value->jurusan);

            if (empty($jurusan)) {
                Debugbar::error('Upload Data Siswa Gagal, jurusan ' . $value->jurusan . ' tidak ditemukan di dalam sistem');
            }

            $siswa    =  CalonSiswaBaru::where('nm_c_siswa', '=', $value->nama_lengkap)->first();
            if (!empty($siswa)) {
                Debugbar::error('Upload Data Siswa Gagal, nama ' . $value->nama_lengkap . ' sudah ditemukan di dalam sistem');
            }

            if ($kelas == null || $jurusan == null || $siswa != null) {
                Debugbar::error(
                    'Upload Data Siswa Gagal, Data Tidak Valid pada Siswa "' . $value->nama_lengkap . '"'
                );
            } else {
                //generate id
                // $id_c_siswa         = $this->auth_data->sekolah_data->prefix . strtotime($this->now) . uniqid();
                // $id_alumni         = $this->auth_data->sekolah_data->prefix . strtotime($this->now) . uniqid();
                // $id_alumni_smp         = $this->auth_data->sekolah_data->prefix . strtotime($this->now) . uniqid();

                $arr[] = array(
                    //table siswa baru
                    // 'id_c_siswa'                     => $id_c_siswa,
                    'nama_lengkap'                     => $value->nama_lengkap,
                    'id_jurusan'                    => $jurusan->id_jurusan,
                    'created_by'                    => $this->auth_data->pengguna->id_pengguna,
                    'alamat_jalan'                    => $value->alamat,
                    'id_penerimaan'                     => $id_penerimaan->id_penerimaan,
                    'nomor_hp'                        => $value->nomor_hp,
                    //table alumni
                    // 'id_alumni'                     => $id_alumni,
                    'id_kelas'                       => $kelas->id_kelas,
                    'email'                         => $value->email,
                    'tahun_lulus'                   => $value->tahun_lulus,
                    'url_medsos'                    => $value->alamat_url_medsos,
                    'status'                        => 'smp',
                    'status_verifikasi'               => 1,
                    //table alumni smp
                    // 'id_alumni_smp'                 => $id_alumni_smp,
                    'nm_sekolah'                    => $value->nama_sekolah,
                    'alamat_sekolah'                => $value->alamat_sekolah,
                    'jurusan'                       => $value->jurusan,
                    'jenis_sekolah'                 => $value->jenis_sekolah,
                    'tahun_masuk_sekolah'           => (int) $value->tahun_masuk,
                );
            }}
            // dd($arr);

            if (count($arr) != 0) {

                DB::beginTransaction();
                try {
                    // $pengguna_center = [];
                    foreach ($arr as $data_siswa) {
                        $now = Carbon::now(env('APP_TIMEZONE', ''));
                        //--siswa baru
                        $row_calon_siswa_baru = [
                            'id_c_siswa'     => $this->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'nm_c_siswa'     => $data_siswa['nama_lengkap'],
                            'id_jurusan'     => $data_siswa['id_jurusan'],
                            'id_penerimaan'   => $data_siswa['id_penerimaan'],
                            'nm_c_siswa'   => $data_siswa['nama_lengkap'],
                            'alamat_jalan'   => $data_siswa['alamat_jalan'],
                            'nomor_hp'       => $data_siswa['nomor_hp'],
                            'created_at'     => $this->now,
                            'created_by'     => $data_siswa['created_by'],
                        ];

                        DB::table('calon_siswa_baru')->insert($row_calon_siswa_baru);
                        //---alumni
                        $row_alumni = [
                            'id_alumni'                     => $this->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'id_c_siswa'                    => $row_calon_siswa_baru['nm_c_siswa'],
                            'id_kelas'                      => $data_siswa['id_kelas'],
                            'email'                         => $data_siswa['email'],
                            'tahun_lulus'                   => $data_siswa['tahun_lulus'],
                            'url_medsos'                    => $data_siswa['url_medsos'],
                            'status'                        => $data_siswa['status'],
                            'status_verifikasi'               => $data_siswa['status_verifikasi'],
                            'created_by'                     => $data_siswa['created_by'],
                        ];

                        DB::table('alumni')->insert($row_alumni);
                        //--alumni smp
                        $row_alumni_smp = [
                            'id_alumni_smp'                 => $this->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'id_alumni'                     =>  $row_alumni['id_alumni'],
                            'nm_sekolah'                    => $data_siswa['nm_sekolah'],
                            'alamat_sekolah'                => $data_siswa['alamat_sekolah'],
                            'jurusan'                       => $data_siswa['jurusan'],
                            'jenis_sekolah'                 => $data_siswa['jenis_sekolah'],
                            'tahun_masuk_sekolah'           => $data_siswa['tahun_masuk_sekolah'],
                            'created_at'                    => $this->now,
                            'created_by'                    => $data_siswa['created_by'],
                        ];

                        DB::table('alumni_smp')->insert($row_alumni_smp);
                    }

                    DB::commit();

                    Debugbar::error('Save Siswa Tracer alumni Successfully');
                } catch (\Exception $e) {

                    DB::rollback();
                    // something went wrong
                    //    Debugbar::error( (env('APP_DEBUG', 'true') == 'true') ? 'tesst' : 'Operation error');
                    Debugbar::error((env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error');
                }
            } else {
                Debugbar::error("File Excel Anda Kosong");
            }
        }
    }

