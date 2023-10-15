<table class="table table-bordered">
    <thead>
        <tr>
            <th style="text-align: center;font-weight: bold;">NOMOR</th>
            <th style="text-align: center;font-weight: bold;">NIS</th>
            <th style="text-align: center;font-weight: bold;">NAMA SISWA</th>
            @foreach ($data['kelompok_kpi']->sortBy('urutan') as $key => $kelompok_kpi)
                @foreach ($kelompok_kpi->point_kpi->sortBy('urutan') as $point_kpi)
                    @if ($point_kpi->jenis == '1')
                        <th style="font-weight: bold;background-color : {{ $data['color'][$key] }}">
                            {{-- {{ preg_match('/\d/', $point_kpi->nm_point_kpi) ? preg_replace('/[0-9\.]/', '', $point_kpi->nm_point_kpi) : $point_kpi->nm_point_kpi }} --}}
                            {{ $point_kpi->nm_point_kpi }}
                        </th>
                    @endif
                @endforeach
            @endforeach
            <th style="font-weight: bold;">Sertifikasi</th>
            <th style="font-weight: bold;">Nilai Sertifikasi</th>
            <th style="font-weight: bold;">Tingkat/Jilid</th>
            <th style="font-weight: bold;">Nilai</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data['list_siswa']->sortBy('nis_siswa') as $siswa)
            <tr>
                <td style="text-align: center">{{ ++$no }}</td>
                <td style="text-align: center">{{ $siswa->nis_siswa }}</td>
                <td>{{ $siswa->pengguna->nm_pengguna }}</td>

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
