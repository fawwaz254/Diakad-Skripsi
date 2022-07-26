<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UploadToInsertUpdateGuru implements WithMapping, WithStartRow
{
    use Importable;
    // protected $auth_data;
    // protected $now;

    public function __construct()
    {
        // $this->auth_data = $auth_data;
        // $this->now = $now;

    }

    public function map($row): array
    {
        return [
            "nip" => $row[0],
            "nama_lengkap" => $row[1],
            "jenis_kelamin" => $row[2],
            "status_guru" => $row[3],
            "unit_kerja" => $row[4],
        ];
    }

    public function startRow(): int
    {
        return 2;
    }
}
