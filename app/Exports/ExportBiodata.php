<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBiodata implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($siswa)
    {
        $this->siswa = $siswa;
    }

    public function view(): View
    {

        return view('excel-biodata-siswa-kelas', [
            'siswa1' => $this->siswa
        ]);
    }

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
