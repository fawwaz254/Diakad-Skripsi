<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;

class ExportAlumni2 implements FromView
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

        return view('TracerAlumni2', [
            'alumni' => $this->alumni
        ]);
    }

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
