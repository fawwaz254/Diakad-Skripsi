<?php

namespace App\Http\Controllers\Humas\Alumni;

use Error;
use Exception;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Alumni;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Pengguna;
use App\Models\AlumniKuliah;
use Illuminate\Http\Request;
use App\Models\AlumniBekerja;
use App\Models\AlumniMenunggu;
use App\Models\CalonSiswaBaru;
use App\Models\CalonSiswaOrtu;
use App\Models\StatusPengguna;
use App\Models\AlumniWirausaha;
use App\Models\CalonSiswaFisik;
use Yajra\Datatables\Datatables;
use App\Models\CalonSiswaSekolah;
use App\Libraries\Humas\LibAlumni;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibSiswa;

class TracerAlumniController extends Controller
{

    const PATH = 'alumni/tracer-alumni';
    const FETCH_WORK_ATTRIBUTE = ['nm_instansi', 'alamat_instansi', 'kontak_instansi', 'bidang_usaha_instansi', 'tahun_masuk_instansi','kapan_mulai_bekerja','lama_bekerja'];
    const FETCH_COLLEGE_ATTRIBUTE = ['nm_perguruan', 'alamat_perguruan', 'fakultas', 'prodi', 'jenjang', 'tahun_masuk_perguruan'];
    const FETCH_ENTERPRENEUR_ATTRIBUTE = ['nm_usaha', 'alamat_usaha', 'kontak_usaha', 'bidang_usaha', 'jumlah_karyawan', 'tahun_rintis'];
    const FETCH_IDLE_ATTRIBUTE = ['status_menunggu'];

	public function viewTracerAlumni(Request $request){

    	return view('humas.alumni.tracer-alumni.view-tracer-alumni');

    }

    public function datatablesTracerAlumni(Request $request)
    {
        $alumnis    = LibAlumni::getAlumnis();

        return Datatables::of($alumnis)
        ->addColumn('status_verifikasi',function($item){
            if($item->status_verifikasi == 0){
               $data['status'] = 'Belum Diverikasi';
               $data['color'] = 'pink';
            }
            else{
                $data['status'] = 'Sudah Diverikasi';
                $data['color'] = 'teal';
            }
            return $data;
        })
        ->editColumn('status', function($item){
            return ucfirst($item->status);
        })
        ->addColumn('action', function($item){
            return [ 'id' => $item->id_alumni];
        })
        ->make(true);
    }

    public function addTracerAlumni(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = Jurusan::all();
        $data_kelas = Kelas::where('tingkat',9)->get();
        $alumni = null;

        return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni',compact('auth_data','data_jurusan','alumni','data_kelas'));

    }

    public function editTracerAlumni($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = Jurusan::all();
        $data_kelas = Kelas::where('tingkat',9)->get();
        $alumni = Alumni::find($id);

        return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni',compact('auth_data','data_jurusan','alumni','data_kelas'));

    }

    public function actionTracerAlumni(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if($mode == 'add'){

            DB::beginTransaction();

            try {

                $data['nm_c_siswa']        = $request->nama_siswa;
                $data['nomor_hp']          = $request->nomor_hp;
                $data['id_jurusan']        = $request->jurusan;
                $data['alamat_jalan']      = $request->alamat_siswa;
                $data['id_c_siswa']        = $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();        
                $data['id_penerimaan']     = 0;
                $data['status_verifikasi'] = 0;
                $data['created_by']        = $auth_data->pengguna->id_pengguna;
                $data['created_at']        = Carbon::now(env('APP_TIMEZONE', ''));

                CalonSiswaBaru::insert($data);

                $data2 = [
                    'id_c_siswa'    => $data['id_c_siswa'],
                    'created_by'    => $auth_data->pengguna->id_pengguna,
                    'created_at'    => Carbon::now(env('APP_TIMEZONE', ''))
                ];

                CalonSiswaOrtu::insert($data2);
                CalonSiswaFisik::insert($data2);
                CalonSiswaSekolah::insert($data2);

                $data3['nm_pengguna']           = $request->nama_siswa;
                $data3['email_pengguna']        = $request->email;
                $data3['nomor_hp_pengguna']     = $request->nomor_hp;
                $data3['username']              = str_replace(' ', '_', $request->nama_siswa);
                $data3['id_status_pengguna']    = StatusPengguna::where('nm_status_pengguna', 'Lulus')->first()->id_status_pengguna;
                $data3['id_sekolah']            = $auth_data->pengguna->id_sekolah;
                $data3['id_pengguna']           = $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
                $data3['created_by']            = $auth_data->pengguna->id_pengguna;
                $data3['created_at']            = Carbon::now(env('APP_TIMEZONE', ''));

                Pengguna::insert($data3);

                Siswa::insert([
                    'id_siswa'      => $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid(), 
                    'id_pengguna'   => $data3['id_pengguna'],
                    'id_c_siswa'    => $data['id_c_siswa'],
                    'created_by'    => $auth_data->pengguna->id_pengguna,
                    'created_at'    => Carbon::now(env('APP_TIMEZONE', ''))
                ]);

                $data4['id_kelas']      = $request->id_kelas;
                $data4['email']         = $request->email;
                $data4['tahun_lulus']   = $request->tahun_lulus;
                $data4['status']        = $request->status;
                $data4['id_alumni']     = $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
                $data4['id_c_siswa']    = $data['id_c_siswa'];
                $data4['created_by']    = $auth_data->pengguna->id_pengguna;
                $data4['created_at']    = Carbon::now(env('APP_TIMEZONE', ''));

                Alumni::insert($data4);

                switch ($request->status) {
                    case 'bekerja':
                        $data5 = $request->only(self::FETCH_WORK_ATTRIBUTE);
                        $data5['id_alumni_bekerja']    = $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
                    break;
                    case 'usaha':
                        $data5 = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                        $data5['id_alumni_wirausaha']  = $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
                    break;
                    case 'kuliah':
                        $data5 = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                        $data5['id_alumni_kuliah']     = $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
                    break;
                    case 'menunggu':
                        $data5 = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                        $data5['id_alumni_menunggu']   = $auth_data->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
                    break;
                }

                $data5['id_alumni']              = $data4['id_alumni'];
                $data5['created_by']             = $auth_data->pengguna->id_pengguna;
                $data5['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));

                if($request->status == 'bekerja'){
                    LibAlumni::storeWorkplace($data5);
                }

                elseif($request->status == 'usaha'){
                    LibAlumni::storeBusiness($data5); 
                }

                elseif($request->status == 'kuliah'){
                    LibAlumni::storeUniversity($data5);
                }

                elseif($request->status == 'menunggu'){
                    LibAlumni::storeIdleAlumni($data5);
                }

                DB::commit();

                return web_response(202, "Update successfully", self::PATH);

            }

            catch (\Exception $e) {

               DB::rollback();

               return error_response($e);

            }

        }

        elseif($mode == 'delete'){

            $alumni               = Alumni::find($id);
            $alumni->deleted_by   = $input->auth_data->pengguna->id_pengguna;
            $alumni->save();

            $alumni->delete();

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Alumni successfully'
            ];

        }

    }

}