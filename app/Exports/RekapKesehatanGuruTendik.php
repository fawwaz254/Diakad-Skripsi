<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapKesehatanGuruTendik implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

public function __construct($auth_data, $dates, $data_bulan, $bulan, $data_pengguna, $data_pengisian, $tahun){
    $this->auth_data = $auth_data;
    $this->dates = $dates;
    $this->data_bulan = $data_bulan;
    $this->bulan = $bulan;
    $this->data_pengguna = $data_pengguna;
    $this->data_pengisian = $data_pengisian;
    $this->tahun = $tahun;
}

public function view(): View 
{
    return view('humas/kegiatan-harian/rekap-kesehatan/download-rekap-kesehatan-guru-tendik', array(
            'auth_data' => $this->auth_data,
            'dates' => $this->dates,
            'data_bulan' => $this->data_bulan,
            'bulan' => $this->bulan,
            'data_pengguna' => $this->data_pengguna,
            'data_pengisian' => $this->data_pengisian,
            'tahun' => $this->tahun
        )
    );
}

    // public function collection()
    // {
    //     return [];
    // }
}
