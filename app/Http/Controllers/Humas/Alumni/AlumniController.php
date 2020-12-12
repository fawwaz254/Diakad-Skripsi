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
    const FETCH_ALUMNI_ATTRIBUTE = ['id_siswa', 'tahun_lulus', 'status'];
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
        $idSiswa        = $this->storeStudentAndReturnId($request, $idPengguna, $idCalonSiswa);
        $data           = $this->fetchAlumniDataAndSetIdSiswa($request, $idSiswa);

        $this->storeAlumni($data);

        return $data['id_alumni'];
    }

    private function storeAlumni($data)
    {
        return Alumni::insert($data);
    }

    private function storePartialData($request, $idAlumni)
    {
        $data = $this->fetchPartialData($request);
        $data['id_alumni'] = $idAlumni;

        switch ($request->status) {
            case 'bekerja':
                return AlumniWorkplace::insert($data);
            case 'usaha':
                return AlumniBusiness::insert($data); 
            case 'kuliah':
                return AlumniUniversity::insert($data);
            case 'menunggu':
                return AlumniIdle::insert($data);
        }
    }

    private function fetchPartialData(Request $request)
    {
        $auth = (object) $request->input()['auth_data'];
        $id = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();

        switch ($request->status) {
            case 'bekerja':
                $data = $request->only(self::FETCH_WORK_ATTRIBUTE);
                $data['id_alumni_workplace'] = $id;
                return $data;
            case 'usaha':
                $data = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                $data['id_alumni_business'] = $id;
                return $data;
            case 'kuliah':
                $data = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                $data['id_alumni_university'] = $id;
                return $data;
            case 'menunggu':
                $data = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                $data['id_alumni_idle'] = $id;
                return $data;
        }
    }

    private function storeStudentAndReturnId($request, $idPengguna, $idCalonSiswa)
    {
        $auth = (object) $request->input()['auth_data'];
        $id = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();

        Siswa::insert([
            'id_siswa'      => $id, 
            'id_pengguna'   => $idPengguna,
            'id_c_siswa'    => $idCalonSiswa
        ]);

        return $id;
    }

    private function storeUserAndReturnId($request)
    {
        $data = $this->fetchUserData($request);
        Pengguna::insert($data);

        return $data['id_pengguna'];
    }

    private function storeAllDataThatRelatedToCalonSiswaAndReturnId($request)
    {
        $auth = (object) $request->input()['auth_data'];
        $id = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();

        $this->storeCalonSiswa($request, $id);
        CalonSiswaOrtu::insert(['id_c_siswa' => $id]);
        CalonSiswaFisik::insert(['id_c_siswa' => $id]);
        CalonSiswaSekolah::insert(['id_c_siswa' => $id]);

        return $id;
    }

    private function storeCalonSiswa($request, $id)
    {
        $data = $this->fetchStudentApplicant($request, $id);
        CalonSiswaBaru::insert($data);
    }

    private function fetchUserData($request)
    {
        $auth = (object) $request->input()['auth_data'];
        $id = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();

        return [
            'id_pengguna'           => $id,
            'nm_pengguna'           => $request->nama_siswa,
            'email_pengguna'                 => $request->email,
            'username'              => str_replace(' ', '_', $request->nama_siswa),
            'nomor_hp_pengguna'     => $request->nomor_hp,
            'id_status_pengguna'    => StatusPengguna::where('nm_status_pengguna', 'Lulus')->first()->id_status_pengguna,
            'id_sekolah'            => $auth->pengguna->id_sekolah
        ];
    }

    private function fetchStudentApplicant($request, $id)
    {
        return [
            'id_c_siswa'        => $id,
            'id_penerimaan'     => 0,
            'nm_c_siswa'        => $request->nama_siswa,
            'nomor_hp'          => $request->nomor_hp,
            'id_jurusan'        => $request->jurusan,
            'status_verifikasi' => 0
        ];
    }

    private function fetchAlumniDataAndSetIdSiswa($request, $idSiswa)
    {
        $auth = (object) $request->input()['auth_data'];
        $id = $auth->sekolah_data->prefix.strtotime(Carbon::now()).uniqid();

        return [
            'id_alumni'     => $id,
            'id_siswa'      => $idSiswa,
            'email'         => $request->email,
            'tahun_lulus'   => $request->tahun_lulus,
            'status'        => $request->status
        ];
    }

}
