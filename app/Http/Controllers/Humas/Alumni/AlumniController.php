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

class AlumniController extends Controller
{
    const RESOURCE_PATH = 'humas/alumni/';
    const FETCH_PENGGUNA = ['nama_siswa', 'nomor_hp', 'email'];
    const FETCH_STUDENT_APPLICANT = ['nama_siswa', 'nomor_hp'];
    const FETCH_WORK_ATTRIBUTE = ['nm_instansi', 'alamat', 'kontak', 'bidang_usaha', 'tahun_masuk'];
    const FETCH_COLLEGE_ATTRIBUTE = ['nm_perguruan', 'alamat', 'fakultas', 'prodi', 'jenjang', 'tahun_masuk'];
    const FETCH_ENTERPRENEUR_ATTRIBUTE = ['nm_usaha', 'alamat', 'kontak', 'bidang_usaha', 'jumlah_karyawan', 'tahun_rintis'];
    const FETCH_IDLE_ATTRIBUTE = ['idle_status'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = LibAlumni::getAlumnis();

        dd($data);

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
    	return view(self::RESOURCE_PATH . 'add-alumni',compact('auth_data', 'data_jurusan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idAlumni       = $this->storeAlumniAndReturnId($request);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function storeAlumniAndReturnId($request)
    {
        $idPengguna     = $this->storeUserAndReturnId($request);
        $idCalonSiswa   = $this->storeAllDataThatRelatedToCalonSiswaAndReturnId($request);
        $data           = $this->fetchAlumniDataAndSetIdCalonSiswa($request, $idCalonSiswa);
        
        $this->storeStudent($request, $idPengguna, $idCalonSiswa);
        LibAlumni::store($data);

        return $data['id_alumni'];
    }

    private function storePartialData($request, $idAlumni)
    {
        $data = $this->fetchPartialData($request);
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

    private function fetchPartialData(Request $request)
    {
        $auth       = (object) $request->input()['auth_data'];
        $id         = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId     = $auth->pengguna->id_pengguna;

        switch ($request->status) {
            case 'bekerja':
                $data = $request->only(self::FETCH_WORK_ATTRIBUTE);
                $data['id_alumni_workplace']    = $id;
                $data['created_by']             = $userId;
                $data['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));
                return $data;
            case 'usaha':
                $data = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                $data['id_alumni_business'] = $id;
                $data['created_by']         = $userId;
                $data['created_at']         = Carbon::now(env('APP_TIMEZONE', ''));
                return $data;
            case 'kuliah':
                $data = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                $data['id_alumni_university']   = $id;
                $data['created_by']             = $userId;
                $data['created_at']             = Carbon::now(env('APP_TIMEZONE', ''));
                return $data;
            case 'menunggu':
                $data = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                $data['id_alumni_idle'] = $id;
                $data['created_by']     = $userId;
                $data['created_at']     = Carbon::now(env('APP_TIMEZONE', ''));
                return $data;
        }
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
        Pengguna::insert($data);

        return $data['id_pengguna'];
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
        $data = $this->fetchStudentApplicant($request, $id);
        CalonSiswaBaru::insert($data);
    }

    private function fetchUserData($request)
    {
        $auth   = (object) $request->input()['auth_data'];
        $id     = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId = $auth->pengguna->id_pengguna;

        return [
            'id_pengguna'           => $id,
            'nm_pengguna'           => $request->nama_siswa,
            'email_pengguna'        => $request->email,
            'username'              => str_replace(' ', '_', $request->nama_siswa),
            'nomor_hp_pengguna'     => $request->nomor_hp,
            'id_status_pengguna'    => StatusPengguna::where('nm_status_pengguna', 'Lulus')->first()->id_status_pengguna,
            'id_sekolah'            => $auth->pengguna->id_sekolah, 
            'created_by'            => $userId,
            'created_at'            => Carbon::now(env('APP_TIMEZONE', ''))
        ];
    }

    private function fetchStudentApplicant($request, $id)
    {
        $auth   = (object) $request->input()['auth_data'];
        $userId = $auth->pengguna->id_pengguna;

        return [
            'id_c_siswa'        => $id,
            'id_penerimaan'     => 0,
            'nm_c_siswa'        => $request->nama_siswa,
            'nomor_hp'          => $request->nomor_hp,
            'id_jurusan'        => $request->jurusan,
            'status_verifikasi' => 0,
            'created_by'        => $userId,
            'created_at'        => Carbon::now(env('APP_TIMEZONE', ''))
        ];
    }

    private function fetchAlumniDataAndSetIdCalonSiswa($request, $idCalonSiswa)
    {
        $auth       = (object) $request->input()['auth_data'];
        $id         = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();
        $userId     = $auth->pengguna->id_pengguna;

        return [
            'id_alumni'     => $id,
            'id_c_siswa'    => $idCalonSiswa,
            'email'         => $request->email,
            'tahun_lulus'   => $request->tahun_lulus,
            'status'        => $request->status, 
            'created_by'    => $userId
        ];
    }

}
