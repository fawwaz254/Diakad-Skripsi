<?php

namespace App\Http\Controllers\Siswa\Kesiswaan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Ekskul;
use App\Models\PengambilanEkskul;
use App\Models\PesertaEkskulSet;
use App\Models\PresensiEkskul;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\NilaiEkskul;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class NilaiEkskulController extends BaseController
{

	protected $modul_url = 'kesiswaan';
    protected $menu_url = 'nilai-ekskul';

	public function viewNilaiEkskul(Request $request){

		$input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        $data_ekskul = PesertaEkskulSet::with('ekskul')->where('id_siswa',$siswa->id_siswa)->get();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('siswa.kesiswaan.absensi-ekskul.view-absensi-ekskul',compact('auth_data','data_ekskul','data_semester'));

	}

	public function viewDetailNilaiEkskul(Request $request, $id_semester, $id_ekskul){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_ekskul = Ekskul::find($id_ekskul);
        $semester = Semester::find($id_semester);
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $list_komponen = LibGuru::fetchDataKomponenNilaiEkskul($auth_data, $id_semester, $id_ekskul);

        $jumlah_persentase_komponen = $list_komponen->sum('persentase_komponen_ekskul');

        $list_siswa = PengambilanEkskul::select('siswa.nis_siswa',
                                                'pengguna.nm_pengguna',
                                                'pengambilan_ekskul.id_pengambilan_ekskul',
                                                'pengambilan_ekskul.nilai_angka',
                                                'pengambilan_ekskul.nilai_huruf',
                                                'siswa.id_siswa',
                                                'kelas.nm_kelas'
                                                )
                        ->join('siswa','siswa.id_siswa','=','pengambilan_ekskul.id_siswa')
                        ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                        ->join('kelas', 'kelas.id_kelas', '=', 'pengambilan_ekskul.id_kelas')
                        ->where('pengambilan_ekskul.id_ekskul','=',$id_ekskul)
                        ->where('pengambilan_ekskul.id_semester','=',$id_semester)
                        ->where('pengambilan_ekskul.id_siswa',$siswa->id_siswa)
                        ->get();
        
        $list_siswa = $list_siswa->map(function($row) use ($list_komponen){
            $list_nilai_ekskul = []; 
            foreach($list_komponen as $komponen){
                $nilai = NilaiEkskul::where('id_komponen_ekskul', $komponen->id_komponen_ekskul)
                                    ->where('id_pengambilan_ekskul', $row->id_pengambilan_ekskul)
                                    ->first();
                $list_nilai_ekskul[] = [
                    'id_komponen_ekskul' => $komponen->id_komponen_ekskul,
                    'nilai_komponen_ekskul' => !empty($nilai) ? $nilai->besar_nilai_ekskul : 0
                ];
            }
            $data = $row;
            $data->nilai_ekskul = $list_nilai_ekskul;
            return $data;
        });


        return view(
            'siswa/kesiswaan/nilai-ekskul/view-detail-nilai-ekskul'
           ,compact('auth_data','data_ekskul', 'semester', 'list_komponen', 'jumlah_persentase_komponen', 'list_siswa', 'id_semester', 'id_ekskul')
        );

    }

}