<?php

namespace App\Http\Controllers\Guru\PembinaEkskul;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Ekskul;
use App\Models\PelatihEkskulSet;
use App\Models\PresensiEkskul;
use App\Models\PresensiEkskulPeserta;
use App\Models\PengambilanEkskul;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Guru;
use App\Models\NilaiEkskul;
use App\Models\PembinaEkskulSet;
use App\Models\Pengguna;
use App\Models\Semester;
use Auth;
use DB;
use Session;
use Validator;

class RekapNilaiEkskulController extends BaseController
{
    protected $modul_url = 'pembina-ekskul';
    protected $menu_url = 'rekap-nilai-ekskul';

    public function viewRekapNilaiEkskul(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                    ->where('is_aktif_semester','=','1')
                                    ->first();

        // get all data ekskul by id_pengguna guru
        $data_ekskul = LibGuru::fetchDataEkskulGuru($auth_data, $auth_data->pengguna->id_pengguna);

        return view(
            'guru/pembina-ekskul/rekap-nilai-ekskul/view-rekap-nilai-ekskul',
            compact('auth_data','data_ekskul', 'data_semester', 'semester_aktif')
        );
    }

    public function viewDetailRekapNilaiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_ekskul_guru = LibGuru::fetchDataEkskulGuru($auth_data, $auth_data->pengguna->id_pengguna);
        $arr_id_ekskul = implode(',', $data_ekskul_guru->pluck('id_ekskul')->toArray());

        $data_validate = [
            'id_ekskul' => $id_ekskul,
            'id_semester' => $id_semester
        ];

        $validator = Validator::make($data_validate, [
            'id_ekskul' => 'required|exists:ekskul|in:' . $arr_id_ekskul,
            'id_semester' => 'required|exists:semester'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Parameter yang dimasukkan tidak valid'
            ];
        }

        $data_ekskul = Ekskul::find($id_ekskul);
        $semester = Semester::find($id_semester);

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

        return view('guru/pembina-ekskul/rekap-nilai-ekskul/view-detail-rekap-nilai-ekskul',compact('auth_data','data_ekskul', 'semester', 'list_komponen', 'jumlah_persentase_komponen', 'list_siswa', 'id_semester', 'id_ekskul'));
    }

    public function printRekapNilaiEkskul(Request $request, $id_semester, $id_ekskul)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_ekskul_guru = LibGuru::fetchDataEkskulGuru($auth_data, $auth_data->pengguna->id_pengguna);
        $arr_id_ekskul = implode(',', $data_ekskul_guru->pluck('id_ekskul')->toArray());

        $data_validate = [
            'id_ekskul' => $id_ekskul,
            'id_semester' => $id_semester
        ];

        $validator = Validator::make($data_validate, [
            'id_ekskul' => 'required|exists:ekskul|in:' . $arr_id_ekskul,
            'id_semester' => 'required|exists:semester'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Parameter yang dimasukkan tidak valid'
            ];
        }

        $data_ekskul = Ekskul::find($id_ekskul);
        $semester = Semester::find($id_semester);
        $data_pelatih = PelatihEkskulSet::with('pelatih_ekskul.pengguna')
                                        ->where('id_ekskul', $id_ekskul)
                                        ->where('is_aktif', 1)
                                        ->get()
                                        ->map(function($m){
                                            $pelatih = $m->pelatih_ekskul;
                                            return $pelatih ? $pelatih->pengguna->nm_pengguna . 
                                            (($pelatih->pengguna->gelar_belakang != null) ? ', ' . $pelatih->pengguna->gelar_belakang : null) : null;
                                        })->toArray();

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
            'guru/pembina-ekskul/rekap-nilai-ekskul/print-rekap-nilai-ekskul',
            compact('auth_data','data_ekskul', 'semester', 'list_komponen', 'jumlah_persentase_komponen', 'list_siswa', 'data_pelatih')
        );
    }
}
