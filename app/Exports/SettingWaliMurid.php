<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SettingWaliMurid implements FromView, ShouldAutoSize
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

        return view('setting-wali-murid', [
            'data' => $this->data
        ]);
    }
}
