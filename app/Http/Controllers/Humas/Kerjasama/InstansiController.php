<?php

namespace App\Http\Controllers\Humas\Kerjasama;

use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Humas\LibKerjasama;
use Yajra\Datatables\Datatables;

class InstansiController extends Controller
{
    const RESOURCE_PATH = 'humas/kerjasama/instansi/';
    const FETCH_ATTRIBUTE = ['nm_instansi', 'bidang_usaha', 'kontak', 'website', 'alamat'];
    const PATH = 'kerjasama/instansi';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view( self::RESOURCE_PATH . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(self::RESOURCE_PATH . 'form_instansi');
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
        return web_response(self::PATH);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Instansi  $instansi
     * @return \Illuminate\Http\Response
     */
    public function edit(Instansi $instansi)
    {
        return view(self::RESOURCE_PATH . 'form_instansi', compact('instansi'));
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
        $data = $request->only(self::FETCH_ATTRIBUTE);
        $instansi->update($data);
        return web_response(self::PATH);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instansi $instansi)
    {
        $instansi->delete();
        return web_response(self::PATH);
    }

    public function renderDatatables()
    {
        $data_instansi = libKerjasama::getInstansi() ;

        return Datatables::of($data_instansi)
                ->addColumn('action', function($instansi){
                    $data = array(
                        'id' => $instansi->id_instansi
                    );
                    return $data;
                })
                ->make(true);
    }
}
