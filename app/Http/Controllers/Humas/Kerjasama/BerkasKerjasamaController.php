<?php

namespace App\Http\Controllers\Humas\Kerjasama;

use App\Models\Kerjasama;
use Illuminate\Http\Request;
use App\Models\BerkasKerjasama;
use App\Http\Controllers\Controller;
use App\Libraries\Humas\LibKerjasama;
use Illuminate\Support\Facades\Storage;

class BerkasKerjasamaController extends Controller
{
    const RESOURCE_PATH = 'humas/kerjasama/berkas-kerjasama/';
    const FETCH_ATTRIBUTE = ['version', 'file'];

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
     * @param  \Illuminate\Http\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function create(Kerjasama $kerjasama)
    {
        return view(self::RESOURCE_PATH . 'upload', compact('kerjasama'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uploadedFile   = storeFileToCloud('berkas_kerjasama', $request->id_kerjasama, $request->file);
        
        $data = [
            'id_kerjasama'  => $request->id_kerjasama,
            'nama_file'     => $uploadedFile,
            'path'          => Storage::disk('spaces')->url($uploadedFile),
            'versi'         => 1, //need discussion
            'type'          => $request->file->extension()
        ];
        LibKerjasama::storeBerkasKerjasama($data);

        return redirect("humas#kerjasama/berkas/add/$request->id_kerjasama");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BerkasKerjasama  $berkasKerjasama
     * @return \Illuminate\Http\Response
     */
    public function show(BerkasKerjasama $berkasKerjasama)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BerkasKerjasama  $berkasKerjasama
     * @return \Illuminate\Http\Response
     */
    public function edit(BerkasKerjasama $berkasKerjasama)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BerkasKerjasama  $berkasKerjasama
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BerkasKerjasama $berkasKerjasama)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BerkasKerjasama  $berkasKerjasama
     * @return \Illuminate\Http\Response
     */
    public function destroy(BerkasKerjasama $berkasKerjasama)
    {
        //
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
                ->addColumn('jumlah_file', function($kerjasama){
                    return $kerjasama->berkasKerjasama->count();
                })
                ->addColumn('versi', function($kerjasama){
                    return 1;
                })
                ->addColumn('action', function($kerjasama){
                    return [
                        'id' => $kerjasama->id_kerjasama
                    ];
                })
                ->make(true);
    }

    private function fetchAndManipulateData($request)
    {
        $data = $request->only(self::FETCH_ATTRIBUTE);
        $data['type']   = $request->file->extension();
        $data['versi']  = 1;  // need discussion about versioning

        return $data;
    }
}
