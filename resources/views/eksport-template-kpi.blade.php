<table class="table table-bordered">
    <thead>
        <tr>
            <th style="text-align: center;font-weight: bold;background-color :#e3e1e1">NOMOR</th>
            <th style="text-align: center;font-weight: bold;background-color :#e3e1e1">NIS</th>
            <th style="text-align: center;font-weight: bold;background-color :#e3e1e1">NAMA SISWA</th>
            @foreach ($data['kelompok_kpi']->sortBy('urutan') as $key => $kelompok_kpi)
                @foreach ($kelompok_kpi->point_kpi->sortBy('urutan') as $point_kpi)
                    <th style="font-weight: bold;background-color : {{ $data['color'][$key] }}">
                        {{ $point_kpi->nm_point_kpi }}
                    </th>
                @endforeach
            @endforeach

            @foreach ($data['mengaji']->sortBy('urutan') as $point_kpi_mengaji)
                <th style="font-weight: bold;">{{ $point_kpi_mengaji->nm_point_kpi }}</th>
            @endforeach

        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data['list_siswa']->sortBy('nis_siswa') as $siswa)
            @php
                $no++;
            @endphp
            <tr>
                <td
                    @if ($no % 2 == 0) style="text-align: center;background-color: #e3e1e1"  @else  style="text-align: center" @endif>
                    {{ $no }}</td>
                <td
                    @if ($no % 2 == 0) style="text-align: center;background-color: #e3e1e1" @else style="text-align: center" @endif>
                    {{ $siswa->nis_siswa }}</td>
                <td @if ($no % 2 == 0) style="background-color: #e3e1e1" @endif>
                    {{ $siswa->pengguna->nm_pengguna }} </td>

                @foreach ($data['kelompok_kpi']->sortBy('urutan') as $kelompok_kpi)
                    @foreach ($kelompok_kpi->point_kpi->sortBy('urutan') as $point_kpi)
                        <th @if ($no % 2 == 0) style="background-color: #e3e1e1" @endif>
                        </th>
                    @endforeach
                @endforeach

                @foreach ($data['mengaji']->sortBy('urutan') as $point_kpi_mengaji)
                    <th @if ($no % 2 == 0) style="background-color: #e3e1e1" @endif>
                    </th>
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
