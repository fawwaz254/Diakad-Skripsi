<table class="table table-bordered">
    <thead>
        <tr>
            <td style="text-align: center;font-weight: bold;">NOMOR</td>
            <th style="text-align: center;font-weight: bold;">KELAS</th>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">NAMA SISWA</td>
            @foreach ($data['kelompok_tambahan_rapor'] as $kelompok_tambahan_rapor)
                @foreach ($kelompok_tambahan_rapor->tambahan_rapor as $tambahan_rapor)
                    <td style="text-align: center;font-weight: bold;">{{ $tambahan_rapor->nm_tambahan_rapor }}</td>
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
                <td style="text-align: center">{{ $data['kelas']->nm_kelas }} </td>
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
