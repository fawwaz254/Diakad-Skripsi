<?php

namespace App\Imports;

use App\Models\PesertaEkskulSet;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PesertaEkskulImport implements ToCollection
{
    protected $id_semester, $id_ekskul;

    public function __construct($id_semester, $id_ekskul)
    {
        $this->id_semester = $id_semester;
        $this->id_ekskul = $id_ekskul;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // skip header

            $id_siswa = $row[0];
            if (!$id_siswa) continue;

            $exists = PesertaEkskulSet::where('id_siswa', $id_siswa)
                ->where('id_ekskul', $this->id_ekskul)
                ->where('id_semester', $this->id_semester)
                ->exists();

            if ($exists) continue;

            $newId = DB::table('peserta_ekskul_set')->max('id_peserta_ekskul_set') + 1;

            PesertaEkskulSet::create([
                'id_peserta_ekskul_set' => $newId,
                'id_siswa' => $id_siswa,
                'id_ekskul' => $this->id_ekskul,
                'id_semester' => $this->id_semester,
                'is_aktif' => 1,
                'created_by' => auth()->id() ?? 1, // atau sesuaikan default
            ]);
        }
    }
}