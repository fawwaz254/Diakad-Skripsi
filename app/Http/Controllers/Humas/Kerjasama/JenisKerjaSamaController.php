<?php

namespace App\Http\Controllers\Humas\Kerjasama;

use Illuminate\Http\Request;
use App\Models\JenisKerjasama;
use App\Http\Controllers\Controller;
use App\Libraries\Humas\LibKerjasama;
use Yajra\Datatables\Datatables;

class JenisKerjaSamaController extends Controller
{
    const RESOURCE_PATH = 'humas/kerjasama/jenis_kerjasama/';
    const FETCH_ATTRIBUTE = ['nm_jenis_kerjasama'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(self::RESOURCE_PATH . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(self::RESOURCE_PATH . 'form_jenis_kerjasama');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(self::FETCH_ATTRIBUTE);
        LibKerjasama::storeJenisKerjasama($data);
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'kerjasama/jenis',
            'message' => 'Jenis Kerjasama Created'
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JenisKerjasama  $jenisKerjasama
     * @return \Illuminate\Http\Response
     */
    public function edit(JenisKerjasama $jenisKerjasama)
    {
        return view(self::RESOURCE_PATH . 'form_jenis_kerjasama', compact('jenisKerjasama'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JenisKerjasama  $jenisKerjasama
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JenisKerjasama $jenisKerjasama)
    {
        $data = $request->only(self::FETCH_ATTRIBUTE);
        $jenisKerjasama->update($data);
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'kerjasama/jenis',
            'message' => 'Jenis Kerjasama Updated'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JenisKerjasama  $jenisKerjasama
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisKerjasama $jenisKerjasama)
    {
        $jenisKerjasama->delete();
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'kerjasama/jenis',
            'message' => 'Jenis Kerjasama Deleted'
        ];
    }

    public function renderDatatables()
    {
        $data_jenisKerjasama = LibKerjasama::getJenisKerjasama() ;

        return Datatables::of($data_jenisKerjasama)
                ->addColumn('action', function($jenis_kerjasama){
                    return [
                        'id' => $jenis_kerjasama->id_jenis_kerjasama
                    ];
                })
                ->make(true);
    }
}
