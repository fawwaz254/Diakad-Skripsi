<?php

namespace App\Http\Controllers\WaliMurid\Kesiswaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Ekskul;
use App\Models\PengambilanEkskul;
use App\Models\PesertaEkskulSet;
use App\Models\PresensiEkskul;
use App\Models\Siswa;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class AbsensiEkskulController extends Controller
{
    protected $modul_url = 'kesiswaan';
    protected $menu_url = 'absensi-ekskul';

	public function viewAbsensiEkskul(Request $request){

		$input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;
        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        // $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        $data_ekskul = PesertaEkskulSet::with('ekskul')->where('id_siswa',$data_anak_murid_aktif->id_siswa)->get();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('siswa.kesiswaan.absensi-ekskul.view-absensi-ekskul',compact('auth_data','data_ekskul','data_semester'));

	}

	public function viewDetailAbsensiEkskul(Request $request, $id_semester, $id_ekskul){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $semester_aktif = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        
        $data_ekskul = Ekskul::find($id_ekskul);
        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);
        // $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        
        $data_siswa = PengambilanEkskul::with('siswa', 'siswa.pengguna', 'siswa.pengguna.status_pengguna', 'kelas')->where('id_semester', $id_semester)->where('id_ekskul', $id_ekskul)->where('id_siswa',$data_anak_murid_aktif->id_siswa)->get();

        $data_presensi = PresensiEkskul::with('presensi_ekskul_peserta')
                                    ->where('id_ekskul', $id_ekskul)
                                    ->where('id_semester', $id_semester)
                                    ->orderBy('pertemuan_ke', 'asc')
                                    ->get();

        return view(
            'siswa/kesiswaan/absensi-ekskul/view-detail-absensi-ekskul',
            compact('auth_data', 'semester_aktif', 'data_ekskul', 'data_siswa', 'data_presensi', 'id_semester', 'id_ekskul')
        );

    }
}
