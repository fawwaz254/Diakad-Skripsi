<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportAlumni implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($alumni)
    {
        $this->alumni = $alumni;
    }

    public function view(): View
    {

        return view('TracerAlumni', [
            'alumni' => $this->alumni
        ]);
    }

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
