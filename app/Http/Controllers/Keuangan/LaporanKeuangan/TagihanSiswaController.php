<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\Datatables\Datatables;

use App\Models\Biaya;
use App\Models\BiayaSekolah;
use App\Models\Bulan;
use App\Models\DetailBiaya;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelompokBiaya;
use App\Models\KelompokBiayaInternal;
use App\Models\JenisDetailBiaya;
use App\Models\Rapb;
use App\Models\PembayaranBiaya;
use App\Models\Realisasi;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Staff;
use App\Models\SubkategoriRapb;
use App\Models\TagihanBiaya;
use App\Models\TutupBukuBulananBiaya;
use App\Models\TutupBukuBulananKas;
use App\Models\TutupBukuTahunanBiaya;

use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibDataAkademik;;

use Auth;
use DB;
use Excel;
use Session;
use Validator;

class TagihanSiswaController extends BaseController
{
    public function viewTagihanSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        
        return view('keuangan/laporan-keuangan/tagihan-siswa/view-tagihan-siswa', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelas'));

    }

    public function datatablesTagihanSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun      = $input->tahun_akademik_semester;
        $kelas      = $input->kelas;

        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;
        
        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function($q) use ($id_semester_mulai, $id_semester_selesai){
                $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
            })->get();

            $list_data = Siswa::with(['tagihan_biaya' => function($q) use ($data_detail_biaya){
                $q->where('is_tagih', 1)
                    ->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'));
            }])
            ->with('pengguna', 'kelas');

            if(!empty($kelas)){
                $list_data = $list_data->whereHas('kelas', function($q) use ($kelas){
                    $q->where('id_kelas', $kelas);
                });
            }
        }else{
            $list_data = array();
        }

        return Datatables::of($list_data)
                ->addColumn('total_tagihan_bulan', function ($item) {
                    return 'Rp'.number_format($item->tagihan_biaya->sum('besar_biaya'));
                })
                ->addColumn('tagihan_bulan', function ($item) use ($data_detail_biaya) {
                    $array_tagihan_bulan = array();
                    foreach($item->tagihan_biaya as $tagihan){
                        $tagihan_bulan['id_bulan'] = 13;
                        $tagihan_bulan['jenis_tagihan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->biaya->nm_biaya;
                        $tagihan_bulan['judul'] = $tagihan_bulan['jenis_tagihan'];
                        $tagihan_bulan['biaya'] = 'Rp'.number_format($data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->besar_biaya);

                        if($data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->id_jenis_detail_biaya == 4){
                            $tagihan_bulan['id_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->id_bulan;
                            $tagihan_bulan['nm_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->nm_bulan;
                            $tagihan_bulan['judul'] = $tagihan_bulan['judul']. ' '.$tagihan_bulan['nm_bulan'];
                        }else{
                            $tagihan_bulan['judul'] = $tagihan_bulan['judul']. ' '.$data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->keterangan_biaya;
                        }
                        $array_tagihan_bulan[] = $tagihan_bulan;
                    }

                    if(!empty($array_tagihan_bulan)){
                        $array_tagihan_bulan = collect($array_tagihan_bulan)->sortBy('id_bulan')->toArray();
                    }

                    return $array_tagihan_bulan;
                })
                ->make(true);
    }


}