<table class="table table-bordered">
    <thead>
        <tr>
            <td colspan='20' style="font-weight: bold;">
                {{ 'Kelas : ' . $data['rapor']['kelas']['nm_kelas'] }}
            </td>
        </tr>
        <tr>
            <td colspan='20' style="font-weight: bold;">
                {{ 'Tahun ajaran : ' . $data['rapor']['semester']['tahun_ajaran'] }}
            </td>
        </tr>
        <tr>
            <td colspan='20' style="font-weight: bold;">
                {{ 'Semester : ' . $data['rapor']['semester']['nm_semester'] }}
            </td>
        </tr>
        <tr>
            <td rowspan="4" style="text-align: center;font-weight: bold; vertical-align: middle;">NOMOR</td>
            <td rowspan="4" style="text-align: center;font-weight: bold; vertical-align: middle;">NIS</td>
            <td rowspan="4" style="text-align: center;font-weight: bold; vertical-align: middle;">NAMA</td>
            <td colspan="{{ $data['kelas_rapor']->count() * $data['list_komponen']->count() * 2 }}"
                style="text-align: center;font-weight: bold;">MATA
                PELAJARAN</td>
        </tr>
        <tr>
            @foreach ($data['kelompok_mapel_rapor'] as $k)
                @foreach ($k->mata_pelajaran_rapor as $item)
                    <td colspan="{{ $data['list_komponen']->count() * 2 }}">
                        {{ $item->mata_pelajaran->nm_mata_pelajaran }}
                    </td>
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach ($data['kelompok_mapel_rapor'] as $k)
                @foreach ($k->mata_pelajaran_rapor as $item)
                    @foreach ($data['list_komponen'] as $list_komponen)
                        <td colspan="2">
                            {{ $list_komponen->nm_komponen_jenis_rapor }}
                        </td>
                    @endforeach
                @endforeach
            @endforeach
        </tr>

        <tr>
            @foreach ($data['kelompok_mapel_rapor'] as $k)
                @foreach ($k->mata_pelajaran_rapor as $item)
                    @foreach ($data['list_komponen'] as $list_komponen)
                        <td>N</td>
                        <td>P</td>
                    @endforeach
                @endforeach
            @endforeach
        </tr>

    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data['list_siswa'] as $siswa)
            <tr>
                <td style="text-align: center">{{ ++$no }}</td>
                <td style="text-align: center">{{ $siswa->nis_siswa }}</td>
                <td>{{ $siswa->pengguna->nm_pengguna }}</td>

                @foreach ($data['kelompok_mapel_rapor'] as $k)
                    @foreach ($k->mata_pelajaran_rapor as $item)
                        @foreach ($data['list_komponen'] as $list_komponen)
                            <td>{{ isset($data['nilai_siswa'][$siswa->id_siswa . $item['id_mata_pelajaran'] . $list_komponen['id_komponen_jenis_rapor'] . 'nilai']) ? $data['nilai_siswa'][$siswa->id_siswa . $item['id_mata_pelajaran'] . $list_komponen['id_komponen_jenis_rapor'] . 'nilai'] : null }}
                            </td>
                            <td>{{ isset($data['nilai_siswa'][$siswa->id_siswa . $item['id_mata_pelajaran'] . $list_komponen['id_komponen_jenis_rapor'] . 'predikat']) ? $data['nilai_siswa'][$siswa->id_siswa . $item['id_mata_pelajaran'] . $list_komponen['id_komponen_jenis_rapor'] . 'predikat'] : null }}
                            </td>
                        @endforeach
                    @endforeach
                @endforeach

            </tr>
        @endforeach
    </tbody>

</table>

<style>
    .center {
        text-align: center;
        vertical-align: middle;
        background-color: red;
    }
</style>
