<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class PengembanganDiri extends StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
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

        return view('pengembangan-diri', [
            'data' => $this->data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'C' => '0',
        ];
    }
}
