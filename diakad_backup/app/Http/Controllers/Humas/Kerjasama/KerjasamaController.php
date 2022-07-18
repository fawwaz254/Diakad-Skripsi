<?php

namespace App\Http\Controllers\Humas\Kerjasama;

use App\Models\Kerjasama;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Humas\LibKerjasama;
use App\Http\Requests\Humas\StoreKerjasama;
use App\Http\Requests\Humas\UpdateKerjasama;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Validator;

class KerjasamaController extends Controller
{
    const PATH = "kerjasama/list";
    const RESOURCE_PATH = 'humas/kerjasama/kerjasama/';
    const FETCH_ATTRIBUTE = ['nm_kerjasama', 'id_instansi', 'id_jenis_kerjasama', 'tanggal_kerjasama', 'tanggal_akhir_kerjasama', 'status'];

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
        $validator = Validator::make($request->all(), [
            'nm_kerjasama' => 'required',
            'id_instansi' => 'required',
            'id_jenis_kerjasama' => 'required',
            'tanggal_kerjasama' => 'required',
            'tanggal_akhir_kerjasama' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        $data = $request->only(self::FETCH_ATTRIBUTE);

        LibKerjasama::storeKerjasama($data);
        return web_response(202, "Save Data Successfully", self::PATH);
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
        return web_response(202, "Save Data Successfully", self::PATH);
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
        return web_response(203, "Delete Data Successfully");
    }

    public function renderDatatables()
    {
        $data_kerjasama = LibKerjasama::getKerjasama();

        return Datatables::of($data_kerjasama)
            ->editColumn('status', function ($kerjasama) {
                return $kerjasama->status ? "Aktif" : "Tidak Aktif";
            })
            ->editColumn('instansi', function ($kerjasama) {
                return $kerjasama->instansi->nm_instansi;
            })
            ->editColumn('jenis_kerjasama', function ($kerjasama) {
                return $kerjasama->jenisKerjasama->nm_jenis_kerjasama;
            })
            ->addColumn('status_kadaluarsa', function ($kerjasama) {
                // Expired : 10; Akan Expired : 0; Belum Expired : >1 ;

                $tanggal_akhir_kerjasama = Carbon::parse($kerjasama->tanggal_akhir_kerjasama);
                $status_kadaluarsa = $tanggal_akhir_kerjasama->diffInMonths();

                // if under one month
                if ($status_kadaluarsa == 0) {
                    $status_kadaluarsa = Carbon::parse($kerjasama->tanggal_akhir_kerjasama) <= Carbon::now() ? 10 : 0;
                }

                return [
                    // compare two dates using carbon
                    'status_kadaluarsa' => $status_kadaluarsa,
                ];
            })
            ->addColumn('action', function ($kerjasama) {
                return [
                    'id' => $kerjasama->id_kerjasama
                ];
            })
            ->make(true);
    }
}
