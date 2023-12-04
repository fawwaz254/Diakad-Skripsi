<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportRekapPerTanggal implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($hasil, $bulan, $tahun, $dates, $pengguna)
    {
        $this->hasil = $hasil;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->dates = $dates;
        $this->pengguna = $pengguna;
    }

    public function view(): View
    {

        return view('ExportRekapPerTanggal', [
            'hasil' => $this->hasil,
            'dates' => $this->dates,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'pengguna' => $this->pengguna
        ]);
    }

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
