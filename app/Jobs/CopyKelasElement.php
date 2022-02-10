<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\RuanganKelas;
use App\Models\SekretarisKelas;
use App\Models\WaliKelas;
use App\Models\BkKelas;

class CopyKelasElement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $input;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(object $input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now(env('APP_TIMEZONE', ''));
        if (! empty($this->input->sekretaris) && $this->input->sekretaris == 1) {
            // proses tabel sekretaris_kelas
            $sekretaris_kelas_set = SekretarisKelas::where('id_semester', '=', $this->input->id_semester_copy)->get();

            foreach ($sekretaris_kelas_set->chunk(25) as $sekretaris_chunk) {
                foreach ($sekretaris_chunk as $sekretaris) {
                    $id_sekretaris_kelas        = $this->input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $id_kelas                   = $sekretaris->id_kelas;
                    $id_siswa                   = $sekretaris->id_siswa;
                    $id_semester                = $this->input->id_semester_paste;

                    if (! empty($this->input->is_aktif_sekretaris) && $this->input->is_aktif_sekretaris == 11) {
                        $is_aktif   = 1;

                        // proses update is_aktif menjadi 0 All Record
                        $sekretaris_set                 = SekretarisKelas::join('semester', 'semester.id_semester', '=', 'sekretaris_kelas.id_semester')
                                                    ->where('sekretaris_kelas.id_kelas', '=', $id_kelas)
                                                    ->where('sekretaris_kelas.is_aktif', '=', 1)
                                                    ->first();

                        $sekretaris_set->is_aktif       = 0;
                        $sekretaris_set->updated_by     = $this->input->auth_data->pengguna->id_pengguna;
                        $sekretaris_set->updated_at     = $now;
                        $sekretaris_set->save();

                        SekretarisKelas::insert(array(
                            'id_sekretaris_kelas'       => $id_sekretaris_kelas,
                            'id_kelas'                  => $id_kelas,
                            'id_siswa'                  => $id_siswa,
                            'id_semester'               => $id_semester,
                            'is_aktif'                  => $is_aktif,
                            'created_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'created_at'                => $now,
                            'updated_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'updated_at'                => $now
                        ));
                    } else {
                        $is_aktif = 0;

                        SekretarisKelas::insert(array(
                            'id_sekretaris_kelas'       => $id_sekretaris_kelas,
                            'id_kelas'                  => $id_kelas,
                            'id_siswa'                  => $id_siswa,
                            'id_semester'               => $id_semester,
                            'is_aktif'                  => $is_aktif,
                            'created_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'created_at'                => $now,
                            'updated_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'updated_at'                => $now
                        ));
                    }
                }
            }
        }

        if (! empty($this->input->ruangan) && $this->input->ruangan == 2) {
            // proses tabel ruangan_kelas
            $ruangan_kelas_set = RuanganKelas::where('id_semester', '=', $this->input->id_semester_copy)->get();

            foreach ($ruangan_kelas_set->chunk(25) as $ruangan_chunk) {
                foreach ($ruangan_chunk as $ruangan) {
                    $id_ruangan_kelas           = $this->input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $id_kelas                   = $ruangan->id_kelas;
                    $id_ruangan                 = $ruangan->id_ruangan;
                    $id_semester                = $this->input->id_semester_paste;

                    if (! empty($this->input->is_aktif_ruangan) && $this->input->is_aktif_ruangan == 22) {
                        $is_aktif   = 1;

                        // proses update is_aktif menjadi 0 All Record
                        $ruangan_set                 = RuanganKelas::join('semester', 'semester.id_semester', '=', 'ruangan_kelas.id_semester')
                                                    ->where('ruangan_kelas.id_kelas', '=', $id_kelas)
                                                    ->where('ruangan_kelas.is_aktif', '=', 1)
                                                    ->first();

                        $ruangan_set->is_aktif       = 0;
                        $ruangan_set->updated_by     = $this->input->auth_data->pengguna->id_pengguna;
                        $ruangan_set->updated_at     = $now;
                        $ruangan_set->save();

                        RuanganKelas::insert(array(
                        'id_ruangan_kelas'          => $id_ruangan_kelas,
                        'id_kelas'                  => $id_kelas,
                        'id_ruangan'                => $id_ruangan,
                        'id_semester'               => $id_semester,
                        'is_aktif'                  => $is_aktif,
                        'created_by'                => $this->input->auth_data->pengguna->id_pengguna,
                        'created_at'                => $now,
                        'updated_by'                => $this->input->auth_data->pengguna->id_pengguna,
                        'updated_at'                => $now
                    ));
                    } else {
                        $is_aktif = 0;

                        RuanganKelas::insert(array(
                        'id_ruangan_kelas'          => $id_ruangan_kelas,
                        'id_kelas'                  => $id_kelas,
                        'id_ruangan'                => $id_ruangan,
                        'id_semester'               => $id_semester,
                        'is_aktif'                  => $is_aktif,
                        'created_by'                => $this->input->auth_data->pengguna->id_pengguna,
                        'created_at'                => $now,
                        'updated_by'                => $this->input->auth_data->pengguna->id_pengguna,
                        'updated_at'                => $now
                    ));
                    }
                }
            }
        }

        if (! empty($this->input->wali_kelas) && $this->input->wali_kelas == 3) {
            // proses tabel wali_kelas
            $wali_kelas_set = WaliKelas::where('id_semester', '=', $this->input->id_semester_copy)->get();

            foreach ($wali_kelas_set->chunk(25) as $wali_kelas_chunk) {
                foreach ($wali_kelas_chunk as $wali_kelas) {
                    $id_wali_kelas          = $this->input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $id_kelas               = $wali_kelas->id_kelas;
                    $id_guru                = $wali_kelas->id_guru;
                    $id_semester            = $this->input->id_semester_paste;

                    if (! empty($this->input->is_aktif_wali_kelas) && $this->input->is_aktif_wali_kelas == 33) {
                        $is_aktif   = 1;

                        // proses update is_aktif menjadi 0 All Record
                        if ($wali_kelas                 = WaliKelas::join('semester', 'semester.id_semester', '=', 'wali_kelas.id_semester')
                                                    ->where('wali_kelas.id_kelas', '=', $id_kelas)
                                                    ->where('wali_kelas.is_aktif', '=', 1)
                                                    ->first()) {
                            $wali_kelas->is_aktif       = 0;
                            $wali_kelas->updated_by     = $this->input->auth_data->pengguna->id_pengguna;
                            $wali_kelas->updated_at     = $now;
                            $wali_kelas->save();
                        }

                        WaliKelas::insert(array(
                            'id_wali_kelas'             => $id_wali_kelas,
                            'id_kelas'                  => $id_kelas,
                            'id_guru'                   => $id_guru,
                            'id_semester'               => $id_semester,
                            'is_aktif'                  => $is_aktif,
                            'created_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'created_at'                => $now,
                            'updated_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'updated_at'                => $now
                        ));
                    } else {
                        $is_aktif = 0;

                        WaliKelas::insert(array(
                            'id_wali_kelas'          => $id_wali_kelas,
                            'id_kelas'               => $id_kelas,
                            'id_guru'                => $id_guru,
                            'id_semester'            => $id_semester,
                            'is_aktif'               => $is_aktif,
                            'created_by'             => $this->input->auth_data->pengguna->id_pengguna,
                            'created_at'             => $now,
                            'updated_by'             => $this->input->auth_data->pengguna->id_pengguna,
                            'updated_at'             => $now
                        ));
                    }
                }
            }
        }
        //guru bk

        if (! empty($this->input->guru_bk) && $this->input->guru_bk == 4) {
            // proses tabel wali_kelas
            $guru_bk_set = BkKelas::where('id_semester', '=', $this->input->id_semester_copy)->get();

            foreach ($guru_bk_set->chunk(25) as $guru_bk_chunk) {
                foreach ($guru_bk_chunk as $guru_bk) {
                    $id_bk_kelas          = $this->input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $id_kelas               = $guru_bk->id_kelas;
                    $id_pengguna             = $guru_bk->id_pengguna;
                    $id_semester            = $this->input->id_semester_paste;

                    if (! empty($this->input->is_aktif_guru_bk) && $this->input->is_aktif_guru_bk == 44) {
                        $is_aktif   = 1;

                        // proses update is_aktif menjadi 0 All Record
                        if ($guru_bk                 = BkKelas::join('semester', 'semester.id_semester', '=', 'bk_kelas.id_semester')
                                                    ->where('bk_kelas.id_kelas', '=', $id_kelas)
                                                    ->where('bk_kelas.is_aktif', '=', 1)
                                                    ->first()) {
                            $guru_bk->is_aktif       = 1;
                            $guru_bk->updated_by     = $this->input->auth_data->pengguna->id_pengguna;
                            $guru_bk->updated_at     = $now;
                            $guru_bk->save();
                        }

                        BkKelas::insert(array(
                            'id_bk_kelas'                 => $id_bk_kelas,
                            'id_kelas'                  => $id_kelas,
                            'id_pengguna'               => $id_pengguna,
                            'id_semester'               => $id_semester,
                            'is_aktif'                  => $is_aktif,
                            'created_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'created_at'                => $now,
                            'updated_by'                => $this->input->auth_data->pengguna->id_pengguna,
                            'updated_at'                => $now
                        ));
                    } else {
                        $is_aktif = 0;

                        WaliKelas::insert(array(
                            'id_bk_kelas'          => $id_bk_kelas,
                            'id_kelas'               => $id_kelas,
                            'id_guru'                => $id_guru,
                            'id_semester'            => $id_semester,
                            'is_aktif'               => $is_aktif,
                            'created_by'             => $this->input->auth_data->pengguna->id_pengguna,
                            'created_at'             => $now,
                            'updated_by'             => $this->input->auth_data->pengguna->id_pengguna,
                            'updated_at'             => $now
                        ));
                    }
                }
            }
        }

    }
}
