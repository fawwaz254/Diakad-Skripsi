<table class="table table-bordered">
    <thead >
        <tr>
            <td style="text-align: center; background-color: #d8d8d8">No</td>
            <td style="background-color: #d8d8d8">Nama</td>
            <td style="background-color: #d8d8d8">Kelas</td>
            <td style="background-color: #d8d8d8">Tahun Lulus</td>
            <td style="background-color: #d8d8d8">Tahun Masuk</td>
            <td style="background-color: #d8d8d8">Nama Sekolah</td>
            <td style="background-color: #d8d8d8">Alamat Sekolah</td>
            <td style="background-color: #d8d8d8">Jenis Sekolah</td>
            <td style="background-color: #d8d8d8">Jurusan</td>
            <td style="background-color: #d8d8d8">Email Siswa</td>
        </tr>
    </thead>
    <tbody>
        @foreach ( $alumni as $index =>  $detail_alumni )
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail_alumni->calon_siswa->nm_c_siswa }}</td>
            <td>{{ $detail_alumni->kelas->nm_kelas }}</td>
            <td>{{ $detail_alumni->tahun_lulus }}</td>
            <td>{{ $detail_alumni->smp->tahun_masuk_sekolah}}</td>
            {{-- <td>{{ $detail_alumni['smp->tahun_masuk_sekolah'] }}</td> --}}
            <td>{{ $detail_alumni->smp->nm_sekolah }}</td>
            <td>{{ $detail_alumni->smp->alamat_sekolah }}</td>
            <td>{{ $detail_alumni->smp->jenis_sekolah }}</td>
            <td>{{ $detail_alumni->smp->jurusan }}</td>
            <td>{{ $detail_alumni->email }}</td>

        </tr>
        @endforeach
    </tbody>
</table>