<?php

namespace App\Http\Controllers\Humas\Kerjasama;

use App\Models\Kerjasama;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Humas\LibKerjasama;
use App\Http\Requests\Humas\StoreKerjasama;
use App\Http\Requests\Humas\UpdateKerjasama;
use Yajra\Datatables\Datatables;

class KerjasamaController extends Controller
{
    const PATH = "kerjasama/list";
    const RESOURCE_PATH = 'humas/kerjasama/kerjasama/';
    const FETCH_ATTRIBUTE = ['nm_kerjasama', 'id_instansi', 'id_jenis_kerjasama', 'tanggal_kerjasama','tanggal_akhir_kerjasama', 'status'];

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
        $data_instansi = LibKerjasama::getInstansi();
        $data_jenis_kerjasama = LibKerjasama::getJenisKerjasama();
        return view(self::RESOURCE_PATH . 'form_kerjasama', compact('data_instansi', 'data_jenis_kerjasama'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKerjasama $request)
    {
        $data = $request->only(self::FETCH_ATTRIBUTE);
        LibKerjasama::storeKerjasama($data);
        return web_response(202, "Kerjasama Created", self::PATH);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function edit(Kerjasama $kerjasama)
    {
        $data_instansi = LibKerjasama::getInstansi();
        $data_jenis_kerjasama = LibKerjasama::getJenisKerjasama();
        return view(self::RESOURCE_PATH . 'form_kerjasama', compact('data_instansi', 'data_jenis_kerjasama', 'kerjasama'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKerjasama $request, Kerjasama $kerjasama)
    {
        $data = $request->only(self::FETCH_ATTRIBUTE);
        $kerjasama->update($data);
        return web_response(202, "Kerjasama Updated", self::PATH);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kerjasama $kerjasama)
    {
        $kerjasama->delete();
        return web_response(203, "Kerjasama Deleted");
    }

    public function renderDatatables()
    {
        $data_kerjasama = LibKerjasama::getKerjasama() ;

        return Datatables::of($data_kerjasama)
                ->editColumn('status', function($kerjasama){
                    return $kerjasama->status ? "Aktif" : "Tidak Aktif";
                })
                ->editColumn('instansi', function($kerjasama){
                    return $kerjasama->instansi->nm_instansi;
                })
                ->editColumn('jenis_kerjasama', function($kerjasama){
                    return $kerjasama->jenisKerjasama->nm_jenis_kerjasama;
                })
                ->addColumn('action', function($kerjasama){
                    return [
                        'id' => $kerjasama->id_kerjasama
                    ];
                })
                ->make(true);
    }
}
