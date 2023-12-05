<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportRekapPerTanggalSiswa implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($hasil, $bulan, $tahun, $dates, $pengguna, $nama_kelas)
    {
        $this->hasil = $hasil;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->dates = $dates;
        $this->pengguna = $pengguna;
        $this->nama_kelas = $nama_kelas;
    }

    public function view(): View
    {

        return view('ExportRekapPerTanggalSiswa', [
            'hasil' => $this->hasil,
            'dates' => $this->dates,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'pengguna' => $this->pengguna,
            'nama_kelas' => $this->nama_kelas
        ]);
    }

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
