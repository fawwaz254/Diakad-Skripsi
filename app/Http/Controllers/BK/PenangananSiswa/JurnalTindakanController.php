<?php

namespace App\Http\Controllers\BK\PenangananSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\PelanggaranSiswa;
use App\Models\TindakanPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\KesimpulanPelanggaran;
use App\Models\JenisTindakan;
use App\Models\Setting;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class JurnalTindakanController extends BaseController
{

    public function viewJurnalTindakan(Request $request) {
        # code...
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester  = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas     = LibKelas::fetchDataKelas($auth_data);

        return view('bk/penanganan-siswa/jurnal-tindakan/view-jurnal-tindakan', compact('auth_data','semester_aktif','data_semester','data_kelas'));
    }

    public function actionPostJurnalTindakan(Request $request)
    {
        # code...
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester'   => 'required',
            'id_kelas'      => 'required',
            'id_siswa'      => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'penanganan-siswa/jurnal-tindakan/print/'.$input->id_semester.'/'.$input->id_kelas.'/'.$input->id_siswa
            ];
        }
    }

  
    public function printJurnalTindakan(Request $request, $id_semester, $id_kelas, $id_siswa) {

        # code...
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $sekolah_data = $auth_data->sekolah_data;

        $siswa      = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, $id_siswa);

        $semester   = Semester::find($id_semester);

        // $list_data = LibSiswa::fetchPelanggaranNonKBM($auth_data, $siswa->id_pengguna);

        $list_data = Siswa::select('siswa.id_siswa', 'subkategori_pelanggaran.id_subkategori_pelanggaran','subkategori_pelanggaran.poin_subkategori_pelanggaran', 'subkategori_pelanggaran.nm_subkategori_pelanggaran','subkategori_pelanggaran.keterangan_subkategori_pelanggaran', 'kategori_pelanggaran.nm_kategori_pelanggaran', DB::raw('COUNT(subkategori_pelanggaran.id_subkategori_pelanggaran) as frekuensi') , DB::RAW('SUM(subkategori_pelanggaran.poin_subkategori_pelanggaran) as jumlah_poin'))
                    ->join('pelanggaran_siswa', 'pelanggaran_siswa.id_siswa', '=', 'siswa.id_siswa')
                    ->join('subkategori_pelanggaran', 'pelanggaran_siswa.id_subkategori_pelanggaran', '=', 'subkategori_pelanggaran.id_subkategori_pelanggaran')
                    ->join('kategori_pelanggaran', 'kategori_pelanggaran.id_kategori_pelanggaran', '=', 'subkategori_pelanggaran.id_kategori_pelanggaran')
                    ->leftJoin('tindakan_pelanggaran', 'tindakan_pelanggaran.id_pelanggaran_siswa', '=', 'pelanggaran_siswa.id_pelanggaran_siswa')
                    ->leftJoin('jenis_tindakan', 'jenis_tindakan.id_jenis_tindakan', '=', 'tindakan_pelanggaran.id_jenis_tindakan')
                    ->where('siswa.id_siswa', '=', $siswa->id_siswa)
                    ->groupBy('siswa.id_siswa', 'subkategori_pelanggaran.id_subkategori_pelanggaran','subkategori_pelanggaran.poin_subkategori_pelanggaran', 'subkategori_pelanggaran.nm_subkategori_pelanggaran','subkategori_pelanggaran.keterangan_subkategori_pelanggaran', 'kategori_pelanggaran.nm_kategori_pelanggaran')
                    ->orderBy('pelanggaran_siswa.tgl_pelanggaran', 'desc')
                    ->get();

        $setting_bk = Setting::where('key_setting','is_master_kesimpulan_bk')->first()->value;
        $kategori_pelanggaran = null;
        $deskripsi_perilaku_1 = null;
        $deskripsi_perilaku_2 = null;
        $catatan_sekolah = null;

        if($setting_bk){
            $catatan_sekolah = 'Mohon orang tua untuk mempertahankan dan meningkatkan perilaku siswa untuk lebih positif, sehingga tidak melakukan pelanggaran tata tertib sekolah';
            $kategori_pelanggaran = 'Tidak Ada';
            $deskripsi_perilaku_1 = 'Tidak ada permasalahan yang tercata di BK';
            $deskripsi_perilaku_2 = '';
            if($list_data->count()>0){
                $total_poin = $list_data->sum('jumlah_poin');
                $data = KesimpulanPelanggaran::where('poin_bawah_kesimpulan_pelanggaran','<=',$total_poin)
                                              ->where('poin_atas_kesimpulan_pelanggaran','>=',$total_poin)
                                              ->first();
                if($data){
                    $kategori_pelanggaran = strip_tags($data->deskripsi_kesimpulan_pelanggaran_2);
                    $deskripsi_perilaku_1 = strip_tags($data->deskripsi_kesimpulan_pelanggaran_1);
                }

                $total_pelanggaran_yang_dilakukan = $list_data->sum('frekuensi');
                if($total_pelanggaran_yang_dilakukan==1){
                    $deskripsi_perilaku_2 = 'Ada perubahan perilaku siswa yang lebih baik setelah ditangani sekolah';
                }
                elseif($total_pelanggaran_yang_dilakukan==2){
                     $deskripsi_perilaku_2 = 'Ada perubahan perilaku siswa yang cukup baik setelah ditangani sekolah';
                }
                else{
                    $deskripsi_perilaku_2 = 'Belum ada perubahan perilaku siswa setelah ditangani sekolah';
                }

            }
        }

        return view('bk/penanganan-siswa/jurnal-tindakan/print-jurnal-tindakan', compact('siswa', 'sekolah_data', 'semester', 'list_data','kategori_pelanggaran','setting_bk','deskripsi_perilaku_1','deskripsi_perilaku_2','catatan_sekolah'));
    }

}