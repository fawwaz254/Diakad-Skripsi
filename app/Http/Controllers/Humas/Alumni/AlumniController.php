<?php

namespace App\Http\Controllers\Humas\Alumni;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlumniController extends Controller
{
    const RESOURCE_PATH = 'humas/alumni/';
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

    	return view(self::RESOURCE_PATH . 'add-alumni',compact('auth_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $alumniData     = $request->only(self::FETCH_ALUMNI_ATTRIBUTE);
        $partialData    = $this->fetchPartialAttribute($request);

        
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

    private function fetchPartialAttribute(Request $request)
    {
        switch ($request->status) {
            case 'bekerja':
                return $request->only(self::FETCH_WORK_ATTRIBUTE);
                break;
            case 'usaha':
                return $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                break;
            case 'kuliah':
                return $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                break;
            case 'menunggu':
                return $request->only(self::FETCH_IDLE_ATTRIBUTE);
                break;
        }
    }
}
