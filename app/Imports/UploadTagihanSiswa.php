<?php

namespace App\Imports;

use App\Models\BiayaSekolah;
use App\Models\DetailBiaya;
use App\Models\Kelas as Kelas;
use App\Models\PembayaranBiaya;
use App\Models\Semester as Semester;
use App\Models\Siswa;
use App\Models\TagihanBiaya;
use DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UploadTagihanSiswa implements ToCollection, WithHeadingRow
{
    protected $go;

    public function __construct($go)
    {
        $this->go = $go;
    }

    /**
     * @param Collection $row
     *
     * @Debugbar::error( \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $data_excel)
    {
        set_time_limit(9800);
        $data_semester = Semester::whereIn('tahun_ajaran', [
            '2016/2017',
            '2017/2018',
            '2018/2019',
            '2019/2020',
            '2020/2021',
        ])->get();

        $data_biaya_sekolah = BiayaSekolah::whereIn('id_semester', $data_semester->pluck('id_semester'))->get();

        DB::beginTransaction();

        try {
            foreach ($data_excel as $key => $item) {
                $data_siswa = Siswa::where('nis_siswa', $item['nis'])->first();
                $data_kelas = Kelas::where('nm_kelas', $item['tingkat'])->first();

                $semester_ganjil = $data_semester->where('tahun_ajaran', $item['thajar'])->where('nm_semester', 'Ganjil')->first();
                $semester_genap = $data_semester->where('tahun_ajaran', $item['thajar'])->where('nm_semester', 'Genap')->first();

                if ($item['kelas'] == 3) {
                    $id_kelompok_biaya = 'C7nJ915662799905d5b89363cbb6';
                } else {
                    $id_kelompok_biaya = 'C7nJ915662799655d5b891d18162';
                }

                $biaya_sekolah_ganjil = $data_biaya_sekolah->where('id_kelompok_biaya', $id_kelompok_biaya)->where('id_semester', $semester_ganjil->id_semester)->first();
                $biaya_sekolah_genap = $data_biaya_sekolah->where('id_kelompok_biaya', $id_kelompok_biaya)->where('id_semester', $semester_genap->id_semester)->first();

                $detail_biaya_ganjil = DetailBiaya::where('id_biaya_sekolah', $biaya_sekolah_ganjil->id_biaya_sekolah)->get();
                $detail_biaya_genap = DetailBiaya::where('id_biaya_sekolah', $biaya_sekolah_genap->id_biaya_sekolah)->get();

                foreach ($detail_biaya_ganjil as $detail_biaya) {
                    if ($tagihan_biaya = TagihanBiaya::where('id_siswa', $data_siswa->id_siswa)->where('id_detail_biaya', $detail_biaya->id_detail_biaya)->first()) {

                    } else {
                        $tagihan_biaya = new TagihanBiaya;
                        $tagihan_biaya->id_tagihan_biaya = generate_id();
                        $tagihan_biaya->id_siswa = $data_siswa->id_siswa;
                        $tagihan_biaya->id_kelas = $data_kelas->id_kelas;
                        $tagihan_biaya->id_detail_biaya = $detail_biaya->id_detail_biaya;
                        $tagihan_biaya->besar_biaya = $detail_biaya->besar_biaya;
                        $tagihan_biaya->denda_biaya = 0;
                        $tagihan_biaya->is_tagih = 1;
                        $tagihan_biaya->keterangan = '-';
                        $tagihan_biaya->created_at = '2022-08-31 00:00:00';
                        $tagihan_biaya->save();
                    }

                    if ($detail_biaya->id_bulan == 7) {
                        if (!empty($item['jul'])) {
                            if ($item['jul'] != 'NULL') {
                                $this->savePembayaran($item['jul'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 8) {
                        if (!empty($item['agst'])) {
                            if ($item['agst'] != 'NULL') {
                                $this->savePembayaran($item['agst'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 9) {
                        if (!empty($item['sep'])) {
                            if ($item['sep'] != 'NULL') {
                                $this->savePembayaran($item['sep'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 10) {
                        if (!empty($item['okt'])) {
                            if ($item['okt'] != 'NULL') {
                                $this->savePembayaran($item['okt'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 11) {
                        if (!empty($item['nop'])) {
                            if ($item['nop'] != 'NULL') {
                                $this->savePembayaran($item['nop'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 12) {
                        if (!empty($item['des'])) {
                            if ($item['des'] != 'NULL') {
                                $this->savePembayaran($item['des'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }
                }

                foreach ($detail_biaya_genap as $detail_biaya) {
                    if ($tagihan_biaya = TagihanBiaya::where('id_siswa', $data_siswa->id_siswa)->where('id_detail_biaya', $detail_biaya->id_detail_biaya)->first()) {

                    } else {
                        $tagihan_biaya = new TagihanBiaya;
                        $tagihan_biaya->id_tagihan_biaya = generate_id();
                        $tagihan_biaya->id_siswa = $data_siswa->id_siswa;
                        $tagihan_biaya->id_kelas = $data_kelas->id_kelas;
                        $tagihan_biaya->id_detail_biaya = $detail_biaya->id_detail_biaya;
                        $tagihan_biaya->besar_biaya = $detail_biaya->besar_biaya;
                        $tagihan_biaya->denda_biaya = 0;
                        $tagihan_biaya->is_tagih = 1;
                        $tagihan_biaya->keterangan = '-';
                        $tagihan_biaya->created_at = '2022-08-31 00:00:00';
                        $tagihan_biaya->save();
                    }

                    if ($detail_biaya->id_bulan == 1) {
                        if (!empty($item['jan'])) {
                            if ($item['jan'] != 'NULL') {
                                $this->savePembayaran($item['jan'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 2) {
                        if (!empty($item['feb'])) {
                            if ($item['feb'] != 'NULL') {
                                $this->savePembayaran($item['feb'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 3) {
                        if (!empty($item['mar'])) {
                            if ($item['mar'] != 'NULL') {
                                $this->savePembayaran($item['mar'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 4) {
                        if (!empty($item['apr'])) {
                            if ($item['apr'] != 'NULL') {
                                $this->savePembayaran($item['apr'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 5) {
                        if (!empty($item['mei'])) {
                            if ($item['mei'] != 'NULL') {
                                $this->savePembayaran($item['mei'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                    if ($detail_biaya->id_bulan == 6) {
                        if (!empty($item['jun'])) {
                            if ($item['jun'] != 'NULL') {
                                $this->savePembayaran($item['jun'], $tagihan_biaya, $semester_genap);
                            }
                        }
                    }

                }
            }

            DB::commit();

            echo "Sukses";
        } catch (\Exception $e) {
            DB::rollback();

            echo "Failed : " . $e->getMessage();
        }
    }

    protected function savePembayaran($data_biaya, $tagihan_biaya, $semester)
    {
        $pembayaran_biaya = new PembayaranBiaya;
        $pembayaran_biaya->id_pembayaran_biaya = generate_id();
        $pembayaran_biaya->id_tagihan_biaya = $tagihan_biaya->id_tagihan_biaya;
        $pembayaran_biaya->id_staff_bayar = 'C7nJ915631725125d2c1ea09e914';
        $pembayaran_biaya->id_semester_bayar = $semester->id_semester;
        $pembayaran_biaya->besar_pembayaran = $tagihan_biaya->besar_biaya;
        $pembayaran_biaya->tgl_pembayaran = date_format(date_create($data_biaya), "Y-m-d H:i:s");
        $pembayaran_biaya->keterangan = 'Input By Excel';
        $pembayaran_biaya->created_at = '2022-08-31 00:00:00';
        $pembayaran_biaya->save();

        $tagihan_biaya->besar_pembayaran = $tagihan_biaya->besar_pembayaran + $pembayaran_biaya->besar_pembayaran;
        $tagihan_biaya->tgl_pelunasan = $pembayaran_biaya->tgl_pembayaran;
        $tagihan_biaya->is_tagih = 0;
        $tagihan_biaya->save();
    }
}
