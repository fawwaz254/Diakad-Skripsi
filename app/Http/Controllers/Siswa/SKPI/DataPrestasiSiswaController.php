<?php

namespace App\Http\Controllers\Siswa\SKPI;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Kelas as Kelas;
use App\Models\Semester as Semester;
use App\Models\Siswa as Siswa;
use App\Models\Guru as Guru;
use App\Models\Ekskul as Ekskul;
use App\Models\PrestasiSiswa as PrestasiSiswa;
use App\Models\TingkatPrestasiSiswa as TingkatPrestasiSiswa;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class DataPrestasiSiswaController extends BaseController
{
    public function viewDataPrestasiSiswa(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('siswa/skpi/data-prestasi-siswa/view-data-prestasi-siswa', compact('auth_data'));
    }

    public function viewAddDataPrestasiSiswa(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        // $jenis_prestasi = [[1,'Sains'],[2,'Seni'],[3,'Olahraga'],[4,'Lain-lain']];
        // $ekskul = Ekskul::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        $guru = Guru::select(
                'id_guru',
                'p2.nm_pengguna as nm_guru_pendamping',
                'p2.gelar_depan',
                'p2.gelar_belakang')
                ->Join('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
                ->get();

        return view('siswa/skpi/data-prestasi-siswa/add-data-prestasi-siswa',compact('auth_data','tingkat','guru'));

    }

    public function viewEditDataPrestasiSiswa(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        // $jenis_prestasi = [[1,'Sains'],[2,'Seni'],[3,'Olahraga'],[4,'Lain-lain']];
        $ekskul = Ekskul::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        $guru = Guru::select(
                'id_guru',
                'p2.nm_pengguna as nm_guru_pendamping',
                'p2.gelar_depan',
                'p2.gelar_belakang')
                ->Join('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
                ->get();

        $prestasi = PrestasiSiswa::findOrFail($id);
        return view('siswa/skpi/data-prestasi-siswa/edit-data-prestasi-siswa',compact('auth_data','tingkat','ekskul','guru','prestasi'));

    }

    public function actionDataPrestasiSiswa(Request $request, $mode , $id=null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

         $validator = Validator::make($request->all(), [
            'nm_prestasi_siswa' => 'required',
            'peringkat_prestasi_siswa' => 'required',
            'lokasi_prestasi_siswa' => 'required',
            'penyelenggara_prestasi_siswa' => 'required',
            'jenis_lomba_siswa'=>'required',
            'id_tingkat_prestasi_siswa' => 'required',
            'tgl_prestasi_siswa' => 'required',
            'link_sertifikat' => 'required|url'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        else{

            if($mode == 'add'){

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $prestasi = new PrestasiSiswa;
                $prestasi->id_prestasi_siswa = $id;
                $prestasi->id_siswa = $siswa->id_siswa;
                $prestasi->id_kelas = $siswa->id_kelas;
                $prestasi->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
                $prestasi->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
                // $prestasi->jenis_prestasi_siswa = $input->jenis_prestasi_siswa;
                $prestasi->jenis_lomba_siswa = $input->jenis_lomba_siswa;
                $prestasi->nm_prestasi_siswa = $input->nm_prestasi_siswa;
                $prestasi->lokasi_prestasi_siswa = $input->lokasi_prestasi_siswa;
                $prestasi->penyelenggara_prestasi_siswa = $input->penyelenggara_prestasi_siswa;
                $prestasi->peringkat_prestasi_siswa = $input->peringkat_prestasi_siswa;
                $prestasi->tgl_prestasi_siswa =date("Y-m-d", strtotime($input->tgl_prestasi_siswa));
                $prestasi->created_by = $input->auth_data->pengguna->id_pengguna;

                if(!empty($input->id_guru_pendamping)) {
                    $prestasi->id_guru_pendamping = $input->id_guru_pendamping;
                }

                if(!empty($input->id_ekskul)) {
                    $prestasi->id_ekskul = $input->id_ekskul;
                }

                $prestasi->link_sertif_prestasi_siswa = $input->link_sertifikat;

                $prestasi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'skpi/data-prestasi-siswa',
                    'message' => 'Save Prestasi Successfully'
                ];

            }

            elseif ($mode == 'edit') {
               $prestasi = PrestasiSiswa::findOrFail($id);

               $prestasi->id_siswa = $siswa->id_siswa;
               $prestasi->id_kelas = $siswa->id_kelas;
               $prestasi->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
               $prestasi->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
            //    $prestasi->jenis_prestasi_siswa = $input->jenis_prestasi_siswa;
               $prestasi->jenis_lomba_siswa = $input->jenis_lomba_siswa;
               $prestasi->nm_prestasi_siswa = $input->nm_prestasi_siswa;
               $prestasi->lokasi_prestasi_siswa = $input->lokasi_prestasi_siswa;
               $prestasi->penyelenggara_prestasi_siswa = $input->penyelenggara_prestasi_siswa;
               $prestasi->peringkat_prestasi_siswa = $input->peringkat_prestasi_siswa;
               $prestasi->tgl_prestasi_siswa =date("Y-m-d", strtotime($input->tgl_prestasi_siswa));
               $prestasi->updated_at = $now;
               $prestasi->updated_by = $input->auth_data->pengguna->id_pengguna;

               if(!empty($input->id_guru_pendamping)) {
                    $prestasi->id_guru_pendamping = $input->id_guru_pendamping;
               }

                if(!empty($input->id_ekskul)) {
                    $prestasi->id_ekskul = $input->id_ekskul;
                }

                $prestasi->link_sertif_prestasi_siswa = $input->link_sertifikat;

               $prestasi->save();

               return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'skpi/data-prestasi-siswa',
                        'message' => 'Edit Prestasi Successfully'
            ];

            }

            elseif ($mode == 'delete') {
                $prestasi = PrestasiSiswa::findOrFail($id);
                $prestasi->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                $prestasi->deleted_at  = $now;
                $prestasi->save();

                $prestasi->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Prestasi Successfully'
                ];

            }

        }

    }
    public function datatablesDataPrestasiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = PrestasiSiswa::select(
            'prestasi_siswa.nm_prestasi_siswa',
            'prestasi_siswa.keterangan',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            // 'prestasi_siswa.jenis_prestasi_siswa',
            'prestasi_siswa.peringkat_prestasi_siswa',
            'prestasi_siswa.link_sertif_prestasi_siswa',
            'prestasi_siswa.status',
            'prestasi_siswa.jenis_lomba_siswa',
            'p1.nm_pengguna as nm_siswa',
            'siswa.nisn_siswa',
            'siswa.nis_siswa',
            'semester.nm_semester',
            'semester.tahun_ajaran',
            'kelas.nm_kelas',
            'prestasi_siswa.lokasi_prestasi_siswa',
            'prestasi_siswa.penyelenggara_prestasi_siswa',
            'prestasi_siswa.tgl_prestasi_siswa',
            // 'ekskul.nm_ekskul',
            'prestasi_siswa.id_prestasi_siswa',
            'prestasi_siswa.id_guru_pendamping',
            'p2.nm_pengguna as nm_guru_pendamping',
            'p2.gelar_depan',
            'p2.gelar_belakang'
        )
        ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'prestasi_siswa.id_tingkat_prestasi_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')
        ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
        ->join('semester', 'semester.id_semester', '=', 'prestasi_siswa.id_semester')
        ->join('kelas', 'kelas.id_kelas', '=', 'prestasi_siswa.id_kelas')
        // ->leftJoin('ekskul', 'ekskul.id_ekskul', '=', 'prestasi_siswa.id_ekskul')
        ->leftJoin('guru', 'guru.id_guru', '=', 'prestasi_siswa.id_guru_pendamping')
        ->leftJoin('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
        ->orderBy('prestasi_siswa.created_at', 'desc')
        ->orderBy('semester.thn_akademik_semester', 'desc')
        ->orderBy('semester.nm_semester', 'desc')
        ->where('p1.id_pengguna', '=', $auth_data->pengguna->id_pengguna)
        ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->get();

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->nm_semester.' ('.$item->tahun_ajaran.')';
                })
                ->addColumn('keterangan_status', function ($item) {
                            if ($item->status == 0) {
                                $status = 'Belum Diapprove';
                                $color = 'pink';
                            } elseif ($item->status== 1) {
                                $status = 'Sudah Diapprove';
                                $color = 'teal';
                            }
                            elseif ($item->status== 10) {
                                $status = 'Ditolak';
                                $color = 'red';
                            }
                            $data = array(
                                'status' => $status,
                                'color'  => $color
                            );
                            return $data;
                        })
                ->addColumn('peringkat_prestasi_siswa', function ($item) {
                    if ($item->peringkat_prestasi_siswa == 1) {
                        return "Peringkat 1";
                    } elseif ($item->peringkat_prestasi_siswa == 2) {
                        return "Peringkat 2";
                    } elseif ($item->peringkat_prestasi_siswa == 3) {
                        return "Peringkat 3";
                    } elseif ($item->peringkat_prestasi_siswa == 4) {
                        return "Juara Harapan 1";
                    }elseif ($item->peringkat_prestasi_siswa == 5) {
                        return "Juara Harapan 2";
                    }elseif ($item->peringkat_prestasi_siswa == 6) {
                        return "Juara Harapan 3";
                    }elseif ($item->peringkat_prestasi_siswa == 7) {
                        return "Peserta";
                    }
                })
              ->addColumn('tgl_prestasi_siswa', function ($item) {
                  return strftime("%d %B %Y", strtotime($item->tgl_prestasi_siswa));
              })
              ->addColumn('nm_guru_pendamping', function ($item) {
                  if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                      return $item->gelar_depan." ".$item->nm_guru_pendamping.", ".$item->gelar_belakang;
                  } elseif (! empty($item->gelar_depan)) {
                      return $item->gelar_depan." ".$item->nm_guru_pendamping;
                  } elseif (! empty($item->gelar_belakang)) {
                      return $item->nm_guru_pendamping.", ".$item->gelar_belakang;
                  } else {
                      return $item->nm_guru_pendamping;
                  }
              })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_prestasi_siswa,
                        'link_sertifikat'=>$item->link_sertif_prestasi_siswa,
                        'status'=>$item->status
                    );
                    return $data;
                })
                ->make(true);
    }
}
