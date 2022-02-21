<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapKesehatanSiswa implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

public function __construct($auth_data, $dates, $data_bulan, $bulan, $data_kelas, $data_siswa, $data_pengisian){
    $this->auth_data = $auth_data;
    $this->dates = $dates;
    $this->data_bulan = $data_bulan;
    $this->bulan = $bulan;
    $this->data_kelas = $data_kelas;
    $this->data_siswa = $data_siswa;
    $this->data_pengisian = $data_pengisian;
}

public function view(): View 
{
    return view('guru/guru-piket/rekap-kesehatan/download-rekap-kesehatan-siswa', array(
            'auth_data' => $this->auth_data,
            'dates' => $this->dates,
            'data_bulan' => $this->data_bulan,
            'bulan' => $this->bulan,
            'data_kelas' => $this->data_kelas,
            'data_siswa' => $this->data_siswa,
            'data_pengisian' => $this->data_pengisian
        )
    );
}

    // public function collection()
    // {
    //     return [];
    // }
}
