<?php

namespace App\Http\Controllers\Humas\Alumni;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Alumni;
use App\Models\Jurusan;
use App\Models\Pengguna;
use App\Models\AlumniIdle;
use Illuminate\Http\Request;
use App\Models\AlumniBusiness;
use App\Models\CalonSiswaBaru;
use App\Models\CalonSiswaOrtu;
use App\Models\StatusPengguna;
use App\Models\AlumniWorkplace;
use App\Models\CalonSiswaFisik;
use App\Models\AlumniUniversity;
use App\Models\CalonSiswaSekolah;
use App\Http\Controllers\Controller;
use App\Libraries\Keuangan\LibAlumni;
use App\Libraries\Pendidikan\LibSiswa;
use Yajra\Datatables\Datatables;

class AlumniController extends Controller
{
    const RESOURCE_PATH = 'humas/alumni/';
    const FETCH_PENGGUNA = ['nama_siswa', 'nomor_hp', 'email'];
    const FETCH_STUDENT_APPLICANT = ['nama_siswa', 'nomor_hp'];
    const FETCH_WORK_ATTRIBUTE = ['nm_instansi', 'alamat_instansi', 'kontak_instansi', 'bidang_usaha_instansi', 'tahun_masuk_instansi'];
    const FETCH_COLLEGE_ATTRIBUTE = ['nm_perguruan', 'alamat_perguruan', 'fakultas', 'prodi', 'jenjang', 'tahun_masuk_perguruan'];
    const FETCH_ENTERPRENEUR_ATTRIBUTE = ['nm_usaha', 'alamat_usaha', 'kontak_usaha', 'bidang_usaha', 'jumlah_karyawan', 'tahun_rintis'];
    const FETCH_IDLE_ATTRIBUTE = ['idle_status'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;
    	return view(self::RESOURCE_PATH . 'tracer-alumni',compact('auth_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_jurusan = Jurusan::all();
        $alumni = null;
    	return view(self::RESOURCE_PATH . 'add-alumni',compact('auth_data', 'data_jurusan', 'alumni'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idAlumni  = $this->storeAlumniAndReturnId($request);
        $this->storePartialData($request, $idAlumni);

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'alumni/',
            'message' => 'Save successfully'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  Alumni  $alumni
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Alumni $alumni)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $alumni->load(['calon_siswa', 'calon_siswa.jurusan', $alumni->status]);
        $data_jurusan = Jurusan::all();
    	return view(self::RESOURCE_PATH . 'add-alumni',compact('auth_data', 'data_jurusan', 'alumni'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alumni $alumni)
    {
        $auth       = (object) $request->input()['auth_data'];
        $userId     = $auth->pengguna->id_pengguna;
        $calonSiswa = $alumni->calon_siswa;
        $pengguna   = $calonSiswa->siswa->pengguna;

        $this->updateStudentApplicant($calonSiswa, $request);
        $this->updateUser($pengguna, $request);

        if($this->isAlumniStatusChanged($alumni, $request)){
            $status = $alumni->status;

            $alumni->$status->update(['deleted_by' => $userId]);
            $alumni->$status->delete();

            $this->storePartialData($request, $alumni->id_alumni);
        } else {
            $this->updatePartialData($alumni, $request);
        }

        $this->updateAlumni($alumni, $request);
        
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'alumni/',
            'message' => 'Save successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Alumni $alumni)
    {
        $auth       = (object) $request->input()['auth_data'];
        $userId     = $auth->pengguna->id_pengguna;

        $alumni->update(['deleted_by' => $userId]);
        $alumni->delete();

        return [
            'status' => 203, // SUCCESS AND LOAD TABLE
            'message' => 'Success Delete Alumni '
        ];        
    }

    public function renderDatatables(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $alumnis    = LibAlumni::getAlumnis();
        return Datatables::of($alumnis)
        ->editColumn('status', function($item){
            return ucfirst($item->status);
        })
        ->addColumn('action', function($item){
            return [ 'id' => $item->id_alumni];
        })
        ->make(true);
    }

    private function storeAlumniAndReturnId($request)
    {
        $idPengguna     = $this->storeUserAndReturnId($request);
        $idCalonSiswa   = $this->storeAllDataThatRelatedToCalonSiswaAndReturnId($request);
        $data           = $this->fetchAlumniData($request);
        $data           = $this->handleAlumniCreationData($data, $idCalonSiswa, $request);
        
        $this->storeStudent($request, $idPengguna, $idCalonSiswa);
        LibAlumni::store($data);

        return $data['id_alumni'];
    }

    private function storePartialData($request, $idAlumni)
    {
        $data = $this->fetchPartialData($request);
        $data = $this->handlePartialCreationData($data, $request);

        $data['id_alumni'] = $idAlumni;
        switch ($request->status) {
            case 'bekerja':
                return LibAlumni::storeWorkplace($data);
            case 'usaha':
                return LibAlumni::storeBusiness($data); 
            case 'kuliah':
                return LibAlumni::storeUniversity($data);
            case 'menunggu':
                return LibAlumni::storeIdleAlumni($data);
        }
    }

    private function updatePartialData($alumni, $request)
    {
        $data = $this->fetchPartialData($request);
        $status = $alumni->status;
        $alumni->$status->update($data);
    }

    private function updateAlumni($alumni, $request)
    {
        $data = $this->fetchAlumniData($request);
        return $alumni->update($data);
    }

    private function updateStudentApplicant($studentApplicant, $request)
    {
        $data = $this-> fetchStudentApplicant($request);
        return $studentApplicant->update($data);
    }

    private function fetchPartialData(Request $request)
    {
        switch ($request->status) {
            case 'bekerja':
                return $request->only(self::FETCH_WORK_ATTRIBUTE);
            case 'usaha':
                return $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
            case 'kuliah':
                return $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
            case 'menunggu':
                return $request->only(self::FETCH_IDLE_ATTRIBUTE);
        }
    }

    private function handlePartialCreationData($data, $request){
        $auth       = (object) $request->input()['auth_data'];
        $id         = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId     = $auth->pengguna->id_pengguna;

        switch ($request->status) {
            case 'bekerja':
                $data['id_alumni_workplace']    = $id;
            break;
            case 'usaha':
                $data['id_alumni_business']     = $id;
            break;
            case 'kuliah':
                $data['id_alumni_university']   = $id;
            break;
            case 'menunggu':
                $data['id_alumni_idle']         = $id;
            break;
        }

        $data['created_by']             = $userId;
        $data['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));

        return $data;
    }

    private function storeStudent($request, $idPengguna, $idCalonSiswa)
    {
        $auth       = (object) $request->input()['auth_data'];
        $id         = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId     = $auth->pengguna->id_pengguna;

        Siswa::insert([
            'id_siswa'      => $id, 
            'id_pengguna'   => $idPengguna,
            'id_c_siswa'    => $idCalonSiswa,
            'created_by'    => $userId,
            'created_at'    => Carbon::now(env('APP_TIMEZONE', ''))
        ]);
    }

    private function storeUserAndReturnId($request)
    {
        $data = $this->fetchUserData($request);
        $data = $this->handleUserCreationData($data, $request);
        Pengguna::insert($data);

        return $data['id_pengguna'];
    }

    private function updateUser($user, $request)
    {
        $data = $this->fetchUserData($request);
        return $user->update($data);
    }

    private function storeAllDataThatRelatedToCalonSiswaAndReturnId($request)
    {
        $auth   = (object) $request->input()['auth_data'];
        $id     = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId = $auth->pengguna->id_pengguna;

        $data = [
            'id_c_siswa'    => $id,
            'created_by'    => $userId,
            'created_at'    => Carbon::now(env('APP_TIMEZONE', ''))
        ];
        $this->storeCalonSiswa($request, $id);
        CalonSiswaOrtu::insert($data);
        CalonSiswaFisik::insert($data);
        CalonSiswaSekolah::insert($data);

        return $id;
    }

    private function storeCalonSiswa($request, $id)
    {
        $data = $this->fetchStudentApplicant($request);
        $data = $this->handleStudentApplicantCreationData($data, $id, $request);
        CalonSiswaBaru::insert($data);
    }

    private function fetchUserData($request)
    {
        return [
            'nm_pengguna'           => $request->nama_siswa,
            'email_pengguna'        => $request->email,
            'nomor_hp_pengguna'     => $request->nomor_hp,
        ];
    }
    
    private function handleUserCreationData($data, $request)
    {
        $auth   = (object) $request->input()['auth_data'];
        $id     = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId = $auth->pengguna->id_pengguna;
        
        $data['username']               = str_replace(' ', '_', $request->nama_siswa);
        $data['id_status_pengguna']     = StatusPengguna::where('nm_status_pengguna', 'Lulus')->first()->id_status_pengguna;
        $data['id_sekolah']             = $auth->pengguna->id_sekolah;
        $data['id_pengguna']            = $id;
        $data['created_by']             = $userId;
        $data['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));

        return $data;
    }

    private function fetchStudentApplicant($request)
    {
        return [
            'nm_c_siswa'        => $request->nama_siswa,
            'nomor_hp'          => $request->nomor_hp,
            'id_jurusan'        => $request->jurusan,
            'alamat_jalan'      => $request->alamat_siswa,
        ];
    }

    private function handleStudentApplicantCreationData($data, $id, $request)
    {
        $auth   = (object) $request->input()['auth_data'];
        $userId = $auth->pengguna->id_pengguna;

        $data['id_c_siswa']        = $id;        
        $data['id_penerimaan']     = 0;
        $data['status_verifikasi'] = 0;
        $data['created_by']        = $userId;
        $data['created_at']        = Carbon::now(env('APP_TIMEZONE', ''));

        return $data;
    }

    private function fetchAlumniData($request)
    {
        return [
            'email'         => $request->email,
            'tahun_lulus'   => $request->tahun_lulus,
            'status'        => $request->status, 
        ];
    }

    private function handleAlumniCreationData($data, $idCalonSiswa, $request)
    {
        $auth       = (object) $request->input()['auth_data'];
        $id         = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId     = $auth->pengguna->id_pengguna;

        $data['id_alumni']  = $id;
        $data['id_c_siswa'] = $idCalonSiswa;
        $data['created_by'] = $userId;
        $data['created_at'] = Carbon::now(env('APP_TIMEZONE', ''));
        
        return $data;
    }

    private function isAlumniStatusChanged($request, $alumni)
    {
        return $request->status != $alumni->status;
    }

}
