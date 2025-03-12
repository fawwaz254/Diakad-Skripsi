<?php

namespace App\Http\Controllers\Guru\Penilaian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use App\Models\KomponenMp as KomponenMp;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\PeraturanNilai as PeraturanNilai;
use App\Models\NilaiMp as NilaiMp;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\NilaiMpSubKomponen;
use App\Models\SubKomponenMp;
use Auth;
use DB;
use Session;
use Validator;

class RekapNilaiController extends BaseController
{
    protected $modul_url = 'penilaian';
    protected $menu_url = 'rekap-nilai';

    public function viewRekapNilai(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        // get all data kelas_mp by id_pengguna guru
        $data_kelas = LibGuru::fetchDataKelasGuru($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        /** groupping by tahun_ajaran and nm_semester */
        $grup_semester_kelas = $data_kelas->groupBy('tahun_ajaran')->transform(function($item, $k) {
            return $item->groupBy('nm_semester');
        });

        return view('guru/penilaian/rekap-nilai/view-rekap-nilai',compact('auth_data','data_kelas','grup_semester_kelas'));
    }

    public function viewDetailRekapNilai(Request $request, $id_kelas_mp){
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);
        
        $komponenData = KomponenMp::select('id_komponen_mp', 'nm_komponen_mp', 'persentase_komponen_mp')
        ->where('id_kelas_mp','=',$id_kelas_mp)->get();

        $jumlah_komponen = $komponenData->sum('persentase_komponen_mp');
        $jumlah_subkomponen = 0;
        $list_data = [];
        if(!empty($komponenData)){
            $count = [];
            foreach($komponenData->pluck('id_komponen_mp') as $x){
                $count[] = SubKomponenMp::where('id_komponen_mp', $x)->count();
            }
            $jumlah_subkomponen = in_array(0, $count) ? 0 : collect($count)->sum();
            
            foreach($komponenData as $komp){
                $list_data[$komp->nm_komponen_mp] = LibGuru::fetchDataSubKomponenNilai($auth_data, $komp->id_komponen_mp);
            }
        }

        $list_siswa = PengambilanMp::select('siswa.nis_siswa','pengguna.nm_pengguna','pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf','siswa.id_siswa','pengambilan_mp.id_pengambilan_mp', 'pengambilan_mp.id_kelas_mp')
            ->join('siswa','siswa.id_siswa','=','pengambilan_mp.id_siswa')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->where('pengambilan_mp.id_kelas_mp','=',$id_kelas_mp)->get();

        $pengambilan_mp = PengambilanMp::where('pengambilan_mp.id_kelas_mp','=',$id_kelas_mp)->first();

        $list_siswa = $list_siswa->map(function($row) use ($list_data){
            $list_subkomponen = []; 
            foreach($list_data as $komponen){
                foreach($komponen as $subKomponen){
                    $nilai = NilaiMpSubKomponen::where('id_subkomponen_mp', $subKomponen->id_subkomponen_mp)
                                                ->where('id_pengambilan_mp', $row->id_pengambilan_mp)
                                                ->first(); 
                    $list_subkomponen[] = [
                        'id_komponen_mp' => $subKomponen->id_komponen_mp,
                        'id_subkomponen_mp' => $subKomponen->id_subkomponen_mp,
                        'nilai_subkomponen_mp' => !empty($nilai) ? $nilai->besar_nilai_mp : 0
                    ];
                }
            }
            $data = $row;
            $data->nilai_siswa_komponen = $list_subkomponen;
            return $data;
        });

        return view('guru/penilaian/rekap-nilai/view-detail-rekap-nilai',compact('auth_data','data_kelas', 'list_data','jumlah_komponen','jumlah_subkomponen','list_siswa','pengambilan_mp', 'id_kelas_mp'));
    }

    public function printRekapNilai(Request $request, $id_kelas_mp)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        $komponenData = KomponenMp::select('id_komponen_mp', 'nm_komponen_mp', 'persentase_komponen_mp')
        ->where('id_kelas_mp','=',$id_kelas_mp)->get();

        $list_data = [];
        if(!empty($komponenData)){            
            foreach($komponenData as $komp){
                $list_data[$komp->nm_komponen_mp] = LibGuru::fetchDataSubKomponenNilai($auth_data, $komp->id_komponen_mp);
            }
        }

        $list_siswa = PengambilanMp::select('siswa.nis_siswa','pengguna.nm_pengguna','pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf','siswa.id_siswa','pengambilan_mp.id_pengambilan_mp', 'pengambilan_mp.id_kelas_mp')
            ->join('siswa','siswa.id_siswa','=','pengambilan_mp.id_siswa')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->where('pengambilan_mp.id_kelas_mp','=',$id_kelas_mp)->get();

        $list_siswa = $list_siswa->map(function($row) use ($list_data){
            $list_subkomponen = []; 
            foreach($list_data as $komponen){
                foreach($komponen as $subKomponen){
                    $nilai = NilaiMpSubKomponen::where('id_subkomponen_mp', $subKomponen->id_subkomponen_mp)
                                                ->where('id_pengambilan_mp', $row->id_pengambilan_mp)
                                                ->first(); 
                    $list_subkomponen[] = [
                        'id_komponen_mp' => $subKomponen->id_komponen_mp,
                        'id_subkomponen_mp' => $subKomponen->id_subkomponen_mp,
                        'nilai_subkomponen_mp' => !empty($nilai) ? $nilai->besar_nilai_mp : 0
                    ];
                }
            }
            $data = $row;
            $data->nilai_siswa_komponen = $list_subkomponen;
            return $data;
        });

        return view('guru/penilaian/rekap-nilai/print-rekap-nilai',compact('auth_data','data_kelas', 'list_data','list_siswa', 'id_kelas_mp'));
    }
}
