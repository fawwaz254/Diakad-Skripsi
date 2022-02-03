<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;

class HistoriAbsensiDay implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

public function __construct($products){
    $this->products = $products;
}

public function view(): View 
{

    return view('HistoriAbsensiDay',[
        'products'=>$this->products
    ]);
}

    // public function collection()
    // {
    //     return PresensiPengguna::all();
    // }
}
