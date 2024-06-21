<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateExcel implements FromView, ShouldAutoSize
{
    protected $headers;
    protected $bodies;

    public function __construct($headers, $bodies)
    {
        $this->headers = $headers;
        $this->bodies = $bodies;
    }

    public function view(): View
    {
        return view('template-excel', [
            'headers' => $this->headers,
            'bodies' => $this->bodies
        ]);
    }
}
