<?php

namespace App\Http\Controllers\Humas\Alumni;



use Error;
use Exception;
use Carbon\Carbon;
use App\Exports\ExportAlumni;
use App\Exports\ExportAlumni2;
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
use App\Models\AlumniSmp;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TracerAlumniImport;
use Illuminate\Routing\Controller as BaseController;

class TracerAlumniController extends BaseController
{

    const PATH = 'alumni/tracer-alumni';
    const PATH2 = 'tracer-alumni';
    const PATHGURU = 'wali-kelas/tracer-alumni';
    const FETCH_WORK_ATTRIBUTE = ['nm_instansi', 'alamat_instansi', 'kontak_instansi', 'bidang_usaha_instansi', 'tahun_masuk_instansi', 'kapan_mulai_bekerja', 'lama_bekerja'];
    const FETCH_COLLEGE_ATTRIBUTE = ['nm_perguruan', 'alamat_perguruan', 'fakultas', 'prodi', 'jenjang', 'tahun_masuk_perguruan'];
    const FETCH_ENTERPRENEUR_ATTRIBUTE = ['nm_usaha', 'alamat_usaha', 'kontak_usaha', 'bidang_usaha', 'jumlah_karyawan', 'tahun_rintis'];
    const FETCH_IDLE_ATTRIBUTE = ['status_menunggu'];
    const FETCH_SMP = ['nm_sekolah', 'alamat_sekolah', 'jurusan', 'jenis_sekolah', 'tahun_masuk_sekolah'];

    public function viewTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            return view('humas.alumni.tracer-alumni.view-tracer-alumni-smp');
        } else {
            return view('humas.alumni.tracer-alumni.view-tracer-alumni');
        }
    }

    public function excelTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1') {
        //     return view('humas.alumni.tracer-alumni.view-tracer-alumni-smp');
        // } else {
        return view('humas.alumni.tracer-alumni.add-excel-tracer-alumni');
        // }
    }

    public function uploadFileExcel(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        if ($request->hasFile('file-excel')) {
            // $path = $request->file('file-excel')->getRealPath();
            // $data = Excel::load($path)->get();
            Excel::import(new TracerAlumniImport($auth_data, $now), $request->file('file-excel'));
            return [
                'status'     => 200, // FAILED
                'message'     => "Upload Sukses"
            ];;
        } else {
            return [
                'status'     => 300, // FAILED
                'message'     => "File Excel tidak ditemukan"
            ];
        }
    }

    public function downloadFileExcel()
    {
        $file = public_path() . "/excel/ContohFileExelUploadTracerAlumniSmp.xlsx";
        $headers = [
            'Content-Type' => 'application/xlsx',
        ];

        return response()->download($file, 'ContohFileExelUploadTracerAlumniSmp.xlsx', $headers);
    }


    public function datatablesTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1'  || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $alumnis    = LibAlumni::getAlumnisSmp();
        } else {
            $alumnis    = LibAlumni::getAlumnis();
        }

        return Datatables::of($alumnis)
            ->addColumn('status_verifikasi', function ($item) {
                if ($item->status_verifikasi == 0) {
                    $data['status'] = 'Belum Diverikasi';
                    $data['color'] = 'pink';
                } else {
                    $data['status'] = 'Sudah Diverikasi';
                    $data['color'] = 'teal';
                }
                return $data;
            })
            ->editColumn('status', function ($item) {
                return ucfirst($item->status);
            })
            ->addColumn('action', function ($item) {
                return ['id' => $item->id_alumni];
            })
            ->make(true);
    }

    public function addTracerAlumni(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = Jurusan::all();
        $data_kelas = Kelas::where('tingkat', 12)->orWhere('tingkat', 9)->orWhere('tingkat', 3)->get();
        $alumni = null;

        return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
    }

    public function editTracerAlumni($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = Jurusan::all();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $data_kelas = Kelas::where('tingkat', 9)->get();
            $alumni = Alumni::where('id_alumni', $id)->with('smp', 'calon_siswa')->first();
            // return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni-smp', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
        } else {
            $data_kelas = Kelas::where('tingkat', 12)->orWhere('tingkat', 3)->get();
            $alumni = Alumni::where('id_alumni', $id)->with('calon_siswa')->first();
        }
        return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
    }

    public function actionTracerAlumni(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($mode == 'add') {

            DB::beginTransaction();

            try {

                $data['nm_c_siswa']        = $request->nama_siswa;
                $data['nomor_hp']          = $request->nomor_hp;
                $data['id_jurusan']        = $request->jurusan;
                $data['alamat_jalan']      = $request->alamat_siswa;
                $data['id_c_siswa']        = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
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
                $data3['id_pengguna']           = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                $data3['created_by']            = $auth_data->pengguna->id_pengguna;
                $data3['created_at']            = Carbon::now(env('APP_TIMEZONE', ''));

                Pengguna::insert($data3);

                Siswa::insert([
                    'id_siswa'      => $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid(),
                    'id_pengguna'   => $data3['id_pengguna'],
                    'id_c_siswa'    => $data['id_c_siswa'],
                    'created_by'    => $auth_data->pengguna->id_pengguna,
                    'created_at'    => Carbon::now(env('APP_TIMEZONE', ''))
                ]);

                $data4['id_kelas']      = $request->id_kelas;
                $data4['email']         = $request->email;
                $data4['tahun_lulus']   = $request->tahun_lulus;
                $data4['url_medsos']    = $request->url_medsos;
                $data4['status']        = $request->status;
                $data4['id_alumni']     = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                $data4['id_c_siswa']    = $data['id_c_siswa'];
                $data4['created_by']    = $auth_data->pengguna->id_pengguna;
                $data4['created_at']    = Carbon::now(env('APP_TIMEZONE', ''));

                Alumni::insert($data4);

                switch ($request->status) {
                    case 'bekerja':
                        $data5 = $request->only(self::FETCH_WORK_ATTRIBUTE);
                        $data5['id_alumni_bekerja']    = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'usaha':
                        $data5 = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                        $data5['id_alumni_wirausaha']  = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'kuliah':
                        $data5 = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                        $data5['id_alumni_kuliah']     = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'menunggu':
                        $data5 = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                        $data5['id_alumni_menunggu']   = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'smp';
                        $data5 = $request->only(self::FETCH_SMP);
                        $data5['id_alumni_smp']   = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                }

                $data5['id_alumni']              = $data4['id_alumni'];
                $data5['created_by']             = $auth_data->pengguna->id_pengguna;
                $data5['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));

                if ($request->status == 'bekerja') {
                    LibAlumni::storeWorkplace($data5);
                } elseif ($request->status == 'usaha') {
                    LibAlumni::storeBusiness($data5);
                } elseif ($request->status == 'kuliah') {
                    LibAlumni::storeUniversity($data5);
                } elseif ($request->status == 'menunggu') {
                    LibAlumni::storeIdleAlumni($data5);
                } elseif ($request->status == 'smp') {
                    LibAlumni::storeSMP($data5);
                }

                DB::commit();

                return web_response(202, "Update Successfully", self::PATH);
            } catch (\Exception $e) {

                DB::rollback();

                return error_response($e);
            }
        } elseif ($mode == 'add2') {

            DB::beginTransaction();

            try {

                $jurusan = CalonSiswaBaru::where('id_c_siswa',  $request->id_c_siswa)->first();
                $jurusan->id_jurusan = $request->jurusan;
                $jurusan->alamat_jalan = $request->alamat_siswa;
                $jurusan->nomor_hp = $request->nomor_hp;
                $jurusan->save();

                $data4['id_kelas']      = $request->id_kelas;
                $data4['email']         = $request->email;
                $data4['tahun_lulus']   = $request->tahun_lulus;
                $data4['status']        = $request->status;
                $data4['url_medsos']    = $request->url_medsos;
                $data4['id_alumni']     = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                $data4['id_c_siswa']    = $request->id_c_siswa;
                $data4['created_by']    = $auth_data->pengguna->id_pengguna;
                $data4['created_at']    = Carbon::now(env('APP_TIMEZONE', ''));

                Alumni::insert($data4);

                switch ($request->status) {
                    case 'bekerja':
                        $data5 = $request->only(self::FETCH_WORK_ATTRIBUTE);
                        $data5['id_alumni_bekerja']    = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'usaha':
                        $data5 = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                        $data5['id_alumni_wirausaha']  = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'kuliah':
                        $data5 = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                        $data5['id_alumni_kuliah']     = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'menunggu':
                        $data5 = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                        $data5['id_alumni_menunggu']   = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'smp';
                        $data5 = $request->only(self::FETCH_SMP);
                        $data5['id_alumni_smp']   = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                }

                $data5['id_alumni']              = $data4['id_alumni'];
                $data5['created_by']             = $auth_data->pengguna->id_pengguna;
                $data5['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));

                if ($request->status == 'bekerja') {
                    LibAlumni::storeWorkplace($data5);
                } elseif ($request->status == 'usaha') {
                    LibAlumni::storeBusiness($data5);
                } elseif ($request->status == 'kuliah') {
                    LibAlumni::storeUniversity($data5);
                } elseif ($request->status == 'menunggu') {
                    LibAlumni::storeIdleAlumni($data5);
                } elseif ($request->status == 'smp') {
                    LibAlumni::storeSMP($data5);
                }

                DB::commit();


                if (Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->where('id_kelas', null)->first()) {
                    return web_response(202, "Update Successfully", self::PATH);
                } else {
                    return web_response(202, "Update Successfully", self::PATH2);
                }
            } catch (\Exception $e) {

                DB::rollback();

                return error_response($e);
            }
        } elseif ($mode == 'edit') {
            DB::beginTransaction();

            try {
                $jurusan = CalonSiswaBaru::where('id_c_siswa',  $request->id_c_siswa)->first();
                $jurusan->id_jurusan = $request->jurusan;
                $jurusan->alamat_jalan = $request->alamat_siswa;
                $jurusan->nomor_hp = $request->nomor_hp;
                $jurusan->save();

                $alumni =   Alumni::where('id_alumni', $request->id_alumni)->first();
                $alumni->id_kelas  = $request->id_kelas;
                $alumni->email  = $request->email;
                $alumni->tahun_lulus  = $request->tahun_lulus;
                $alumni->url_medsos = $request->url_medsos;
                $alumni->status  = $request->status;
                $alumni->updated_by = $auth_data->pengguna->id_pengguna;
                $alumni->save();

                switch ($request->status) {
                    case 'bekerja':
                        $data5 = $request->only(self::FETCH_WORK_ATTRIBUTE);
                        $data5['id_alumni_bekerja']    = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'usaha':
                        $data5 = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                        $data5['id_alumni_wirausaha']  = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'kuliah':
                        $data5 = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                        $data5['id_alumni_kuliah']     = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'menunggu':
                        $data5 = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                        $data5['id_alumni_menunggu']   = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'smp';
                        $data5 = $request->only(self::FETCH_SMP);
                        $data5['id_alumni_smp']   = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                }

                $data5['id_alumni']              = $request->id_alumni;
                $data5['updated_by']             = $auth_data->pengguna->id_pengguna;
                $data5['created_by']             = $auth_data->pengguna->id_pengguna;
                $data5['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));

                if ($request->old_status == 'bekerja') {
                    $hapus_old_status = AlumniBekerja::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                } elseif ($request->old_status == 'usaha') {
                    $hapus_old_status = AlumniWirausaha::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                } elseif ($request->old_status == 'kuliah') {
                    $hapus_old_status = AlumniKuliah::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                } elseif ($request->old_status == 'menunggu') {
                    $hapus_old_status = AlumniMenunggu::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                }
                if ($request->status == 'bekerja') {
                    LibAlumni::storeWorkplace($data5);
                } elseif ($request->status == 'usaha') {
                    LibAlumni::storeBusiness($data5);
                } elseif ($request->status == 'kuliah') {
                    LibAlumni::storeUniversity($data5);
                } elseif ($request->status == 'menunggu') {
                    LibAlumni::storeIdleAlumni($data5);
                } elseif ($request->status == 'smp') {
                    $hapus_old_status = AlumniSmp::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                    LibAlumni::storeSMP($data5);
                }

                DB::commit();
                if ($cek = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first()) {
                    if (Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->where('id_kelas', null)->first()) {
                        return web_response(202, "Update Successfully", self::PATH);
                    } else {
                        return web_response(202, "Update Successfully", self::PATH2);
                    }
                } else {
                    if ($auth_data->pengguna->role_pengguna->where('is_aktif', 1)->first()->id_role == '2') {
                        return web_response(202, "Update Successfully", self::PATHGURU);
                    }
                    return web_response(202, "Update Successfully", self::PATH);
                }
            } catch (\Exception $e) {

                DB::rollback();

                return error_response($e);
            }
        } elseif ($mode == 'delete') {

            $alumni               = Alumni::find($id);
            $alumni->deleted_by   = $input->auth_data->pengguna->id_pengguna;
            $alumni->save();

            $alumni->delete();

            if ($request->segment(1) == 'siswa') {
                return [
                    'status'    => 202, // SUCCESS AND LOAD TABLE
                    'path'      => 'tracer-alumni',
                    'message'   => 'Delete Alumni Successfully'
                ];
            } else {
                return [
                    'status'    => 202, // SUCCESS AND LOAD TABLE
                    'path'      => $request->segment(1) . '#alumni/tracer-alumni',
                    'message'   => 'Delete Alumni Successfully'
                ];
            }
        }
    }

    public function cetakTracerAlumni(Request $request, $id_kelas = null, $tahun_lulus = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_kelas = Kelas::where('tingkat', 9)->get();
        return view('humas.alumni.tracer-alumni.export-tracer-alumni', compact('auth_data', 'data_kelas', 'id_kelas', 'tahun_lulus'));
    }
    public function cetakTracerAlumni2(Request $request, $id_kelas = null, $tahun_lulus = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_kelas = Kelas::where('tingkat', 12)->orWhere('tingkat', 3)->get();
        return view('humas.alumni.tracer-alumni.export-tracer-alumni2', compact('auth_data', 'data_kelas', 'id_kelas', 'tahun_lulus'));
    }

    public function changeTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'alumni/tracer-alumni/cetak/' . $input->id_kelas . '/' . $input->tahun_lulus,
        ];
    }


    public function changeTracerAlumni2(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'alumni/tracer-alumni/cetak2/' . $input->id_kelas . '/' . $input->tahun_lulus,
        ];
    }



    public function exportAlumnni(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        $alumni = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->with('smp', 'calon_siswa', 'kelas')->get();
        return Excel::download(new ExportAlumni($alumni), 'download_alumni.xlsx');
    }


    public function exportAlumnni2(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        $alumni['alumni_bekerja'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'bekerja')->with('calon_siswa', 'kelas', 'bekerja')->get();
        $alumni['alumni_kuliah'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'kuliah')->with('calon_siswa', 'kelas', 'kuliah')->get();
        $alumni['alumni_menunggu'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'menunggu')->with('calon_siswa', 'kelas', 'menunggu')->get();
        $alumni['alumni_wirausaha'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'usaha')->with('calon_siswa', 'kelas', 'usaha')->get();
        // $alumni = $data;
        // dd($alumni);
        return Excel::download(new ExportAlumni2($alumni), 'download_alumni.xlsx');
    }


    public function datatablesCetakTracerAlumni(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $alumnis    = LibAlumni::getAlumnisSearchSmp($id_kelas, $tahun_lulus);
        } else {
            $alumnis    = LibAlumni::getAlumnisSearch($id_kelas, $tahun_lulus);
        }

        return Datatables::of($alumnis)->make(true);
    }
}
