<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateNilaiRapor implements FromView, ShouldAutoSize
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
		return view('template-nilai-rapor', [
			'data' => $this->data
		]);
	}
}
