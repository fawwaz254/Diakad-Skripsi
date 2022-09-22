<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;

class RaporSisipanSTS implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        return view('rapor-sisipan-sts', [
            'data' => $this->data
        ]);
    }

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
