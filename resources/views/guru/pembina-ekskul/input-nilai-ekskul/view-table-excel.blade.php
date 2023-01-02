<table class="table table-bordered">
    <thead>
        <tr>
            <th style="text-align: center;font-weight: bold;">ID_SISWA</th>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">NAMA SISWA</td>
            @foreach ($data['list_komponen'] as $nilai)
                <td style="text-align: center;font-weight: bold;">{{ $nilai->nm_komponen_ekskul }}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data['list_siswa'] as $siswa)
            <tr>
                <td style="text-align: center">{{ $siswa->id_siswa }}</td>
                <td style="text-align: center">{{ $siswa->nis_siswa }}</td>
                <td>{{ $siswa->nm_pengguna }}</td>
                @foreach ($data['list_komponen'] as $nilai)
                    <td style="text-align: center">0</td>
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
