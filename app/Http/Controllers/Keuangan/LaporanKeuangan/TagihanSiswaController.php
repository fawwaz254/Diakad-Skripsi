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

        $list_data = array();
        
        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function($q) use ($id_semester_mulai, $id_semester_selesai){
                $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
            })->get();

            $list_data = Siswa::with(['tagihan_biaya' => function($q) use ($data_detail_biaya){
                                    $q->where('is_tagih', 1)
                                        ->with('pembayaran')
                                        ->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'));
                                },'pengguna','kelas','tagihan_biaya.potongan']);

            if(!empty($kelas)){
                $list_data = $list_data->whereHas('kelas', function($q) use ($kelas){
                    $q->where('id_kelas', $kelas);
                });
            }
        }

        return Datatables::of($list_data)
                ->addColumn('total_tagihan_bulan', function ($item) {
                    $sumPembayaran = $item->tagihan_biaya->sum(function($sum){
                                            return $sum->pembayaran->sum('besar_pembayaran');
                                        });
                    $diskonTagihan = $item->tagihan_biaya->sum(function($sum){
                        return $sum->potongan->total_potongan ?? 0;
                    });
                    return 'Rp'.number_format($item->tagihan_biaya->sum('besar_biaya') - $sumPembayaran - $diskonTagihan);
                })
                ->addColumn('tagihan_bulan', function ($item) use ($data_detail_biaya,$tahun) {
                    $array_tagihan_bulan = array();
                    foreach($item->tagihan_biaya as $tagihan){
                            
                        $x =   $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya);

                        $nominal = $x->besar_biaya - $tagihan->pembayaran->sum('besar_pembayaran') - ($tagihan->potongan->total_potongan ?? 0);

                        $tagihan_bulan['id_bulan'] = 13;
                        $tagihan_bulan['jenis_tagihan'] = $x->biaya->nm_biaya;
                        $tagihan_bulan['judul'] = $tagihan_bulan['jenis_tagihan'];
                        $tagihan_bulan['biaya'] = 'Rp'.number_format($nominal);

                        if($x->id_jenis_detail_biaya == 4){
                            $tagihan_bulan['id_bulan'] = $x->bulan->id_bulan;
                            $tagihan_bulan['nm_bulan'] = $x->bulan->nm_bulan;
                            if($x->bulan->id_bulan <7){
                                $tagihan_bulan['judul'] = $tagihan_bulan['judul']. ' '.$tagihan_bulan['nm_bulan'].' '.($tahun+1);
                            }
                            else{
                                $tagihan_bulan['judul'] = $tagihan_bulan['judul']. ' '.$tagihan_bulan['nm_bulan'].' '.$tahun;
                            }
                        }else{
                            $tagihan_bulan['judul'] = $tagihan_bulan['judul']. ' '.$x->keterangan_biaya;
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

    public function printTagihanSiswa(Request $request, $tahun, $id_kelas){

        set_time_limit(0);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas_data = Kelas::find($id_kelas);

        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function($q) use ($id_semester_mulai, $id_semester_selesai){
            $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
        })->get();

        $list_data = Siswa::with(['tagihan_biaya' => function($q) use ($data_detail_biaya){
            $q->where('is_tagih', 1)
                ->with('pembayaran')
                ->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'));
        },'pengguna', 'kelas', 'tagihan_biaya.potongan']);

        $list_data = $list_data->whereHas('kelas', function($q) use ($id_kelas){
            $q->where('id_kelas', $id_kelas);
        });

        $all_data = $list_data->get();
        $all_data = $all_data->map(function($data) use ($data_detail_biaya){
            $tagihan = [];
            
            foreach($data->tagihan_biaya as $tagihan_siswa){

                $x = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan_siswa->id_detail_biaya);
                $nominal = $x->besar_biaya - $tagihan_siswa->pembayaran->sum('besar_pembayaran') - ($tagihan_siswa->potongan->total_potongan ?? 0);
                
                $tagihan_bulan['id_bulan'] = 13;
                $tagihan_bulan['jenis_tagihan'] = $x->biaya->nm_biaya;
                $tagihan_bulan['judul'] = $tagihan_bulan['jenis_tagihan'];
                $tagihan_bulan['belum_bayar'] = $nominal;
                $tagihan_bulan['total_potongan'] = ($tagihan_siswa->potongan->total_potongan ?? 0);
                $tagihan_bulan['sudah_bayar'] = $tagihan_siswa->pembayaran->sum('besar_pembayaran');
                $tagihan_bulan['tagihan'] = $x->besar_biaya;

                if($x->id_jenis_detail_biaya == 4){
                    $tagihan_bulan['id_bulan'] = $x->bulan->id_bulan;
                    $tagihan_bulan['nm_bulan'] = $x->bulan->nm_bulan;
                    $tagihan_bulan['judul'] = $tagihan_bulan['judul']. ' '.$tagihan_bulan['nm_bulan'];
                }else{
                    $tagihan_bulan['judul'] = $tagihan_bulan['judul']. ' '.$x->keterangan_biaya;
                }
                $tagihan[] = $tagihan_bulan;
            }

            if(!empty($tagihan)){
                $tagihan = collect($tagihan)->sortBy('id_bulan')->toArray();
            }

            $data['tagihan'] = $tagihan;
            return $data; 
        });

        return view('keuangan/laporan-keuangan/tagihan-siswa/print-tagihan-siswa', compact('auth_data', 'semester_mulai', 'semester_selesai', 'all_data', 'kelas_data'));
    }


}