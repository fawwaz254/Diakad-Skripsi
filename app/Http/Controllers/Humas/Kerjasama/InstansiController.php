<?php

namespace App\Http\Controllers\Humas\Kerjasama;

use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Humas\LibKerjasama;

class InstansiController extends Controller
{
    const RESOURCE_PATH = 'humas/kerjasama/';
    const FETCH_ATTRIBUTE = ['nm_instansi', 'bidang_usaha', 'kontak', 'website', 'alamat'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view( self::RESOURCE_PATH . 'instansi');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(self::RESOURCE_PATH . 'createOrUpdate');
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
        $instansi = LibKerjasama::storeInstansi($data);
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'kerjasama/instansi',
            'message' => 'Instansi Created'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  Instansi  $instansi
     * @return \Illuminate\Http\Response
     */
    public function show(Instansi $instansi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Instansi  $instansi
     * @return \Illuminate\Http\Response
     */
    public function edit(Instansi $instansi)
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
    public function update(Request $request, Instansi $instansi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instansi $instansi)
    {
        //
    }

    public function renderDatatables()
    {

    }
}
