<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use App\Models\PresensiPengguna;
use Maatwebsite\Excel\Concerns\FromView;

class HistoriAbsensi implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

public function __construct($products){
    $this->products = $products;
}

public function view(): View 
{

    return view('HistoriAbsensi',[
        'products'=>$this->products
    ]);
}

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
