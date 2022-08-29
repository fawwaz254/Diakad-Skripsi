<h2>Alumni yang bekerja</h2>
<table class="table table-bordered">

    <thead >
        <tr >
            <td style="text-align: center; background-color: #d8d8d8;">No</td>
            <td style="background-color: #d8d8d8">Nama</td>
            <td style="background-color: #d8d8d8">Kelas</td>
            <td style="background-color: #d8d8d8">No Telp</td>
            <td style="background-color: #d8d8d8">Alamat</td>
            <td style="background-color: #d8d8d8">Tahun Lulus</td>
            <td style="background-color: #d8d8d8">Status</td>
            <td style="background-color: #d8d8d8">Email Siswa</td>
            <td style="background-color: #d8d8d8">Url Medsos</td>
            <td style="background-color: #d8d8d8">Nama Instansi</td>
            <td style="background-color: #d8d8d8">Alamat Instansi</td>
            <td style="background-color: #d8d8d8">Kontak Instansi</td>
            <td style="background-color: #d8d8d8">Bidang Usaha</td>
           

         

        </tr>
    </thead>
    <tbody>
        @foreach ( $alumni['alumni_bekerja'] as $index =>  $detail_alumni )
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail_alumni->calon_siswa->nm_c_siswa }}</td>
            <td>{{ $detail_alumni->kelas->nm_kelas }}</td>
            <td>{{ $detail_alumni->calon_siswa->nomor_hp }}</td>
            <td>{{ $detail_alumni->calon_siswa->alamat_jalan }}</td>
            <td>{{ $detail_alumni->tahun_lulus }}</td>
            <td>{{ $detail_alumni->status }}</td>
            <td>{{ $detail_alumni->email }}</td>
            <td>{{ $detail_alumni->url_medsos }}</td>
            <td>{{ $detail_alumni->bekerja->nm_instansi }}</td>
            <td>{{ $detail_alumni->bekerja->alamat_instansi }}</td>
            <td>{{ $detail_alumni->bekerja->kontak_instansi }}</td>
            <td>{{ $detail_alumni->bekerja->bidang_usaha_instansi }}</td>
            

        </tr>
        @endforeach
    </tbody>
</table>
<br>
<h2>Alumni yang kuliah</h2>
<table class="table table-bordered">
    <thead >
        <tr>
            <td style="text-align: center; background-color: #d8d8d8">No</td>
            <td style="background-color: #d8d8d8">Nama</td>
            <td style="background-color: #d8d8d8">Kelas</td>
            <td style="background-color: #d8d8d8">No Telp</td>
            <td style="background-color: #d8d8d8">Alamat</td>
            <td style="background-color: #d8d8d8">Tahun Lulus</td>
            <td style="background-color: #d8d8d8">Status</td>
            <td style="background-color: #d8d8d8">Email Siswa</td>
            <td style="background-color: #d8d8d8">Url Medsos</td>
            <td style="background-color: #d8d8d8">Nama Perguruan Tinggi</td>
            <td style="background-color: #d8d8d8">Alamat Perguruan</td>
            <td style="background-color: #d8d8d8">Fakultas</td>
            <td style="background-color: #d8d8d8">Prodi</td>
            <td style="background-color: #d8d8d8">Jenjang</td>
            <td style="background-color: #d8d8d8">Tahun masuk Perguruan</td>
          
        </tr>
    </thead>
    <tbody>
        @foreach ( $alumni['alumni_kuliah'] as $index =>  $detail_alumni )
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail_alumni->calon_siswa->nm_c_siswa }}</td>
            <td>{{ $detail_alumni->kelas->nm_kelas }}</td>
            <td>{{ $detail_alumni->calon_siswa->nomor_hp }}</td>
            <td>{{ $detail_alumni->calon_siswa->alamat_jalan }}</td>
            <td>{{ $detail_alumni->tahun_lulus }}</td>
            <td>{{ $detail_alumni->status }}</td> 
            <td>{{ $detail_alumni->email }}</td>
            <td>{{ $detail_alumni->url_medsos }}</td>
            <td>{{ $detail_alumni->kuliah->nm_perguruan }}</td>
            <td>{{ $detail_alumni->kuliah->alamat_perguruan }}</td>
            <td>{{ $detail_alumni->kuliah->fakultas }}</td>
            <td>{{ $detail_alumni->kuliah->prodi }}</td>
            <td>{{ $detail_alumni->kuliah->jenjang }}</td>
            <td>{{ $detail_alumni->kuliah->tahun_masuk_perguruan}}</td>
          

        </tr>
        @endforeach
    </tbody>
</table>
<br>
<h2>Alumni yang menunggu</h2>
<table class="table table-bordered">

    <thead >
        <tr>
            <td style="text-align: center; background-color: #d8d8d8">No</td>
            <td style="background-color: #d8d8d8">Nama</td>
            <td style="background-color: #d8d8d8">Kelas</td>
            <td style="background-color: #d8d8d8">No Telp</td>
            <td style="background-color: #d8d8d8">Alamat</td>
            <td style="background-color: #d8d8d8">Tahun Lulus</td>
            <td style="background-color: #d8d8d8">Status</td>
            <td style="background-color: #d8d8d8">Email Siswa</td>
            <td style="background-color: #d8d8d8">Url Medsos</td>
            <td style="background-color: #d8d8d8">Status Menunggu</td>
            

        </tr>
    </thead>
    <tbody>
        @foreach ( $alumni['alumni_menunggu'] as $index =>  $detail_alumni )
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail_alumni->calon_siswa->nm_c_siswa }}</td>
            <td>{{ $detail_alumni->kelas->nm_kelas }}</td>
            <td>{{ $detail_alumni->calon_siswa->nomor_hp }}</td>
            <td>{{ $detail_alumni->calon_siswa->alamat_jalan }}</td>
            <td>{{ $detail_alumni->tahun_lulus }}</td>
            <td>{{ $detail_alumni->status }}</td> 
            <td>{{ $detail_alumni->email }}</td>
            <td>{{ $detail_alumni->url_medsos }}</td>
            <td>{{ $detail_alumni->menunggu->status_menunggu }}</td>
            

        </tr>
        @endforeach
    </tbody>
</table>
<br>
<h2>Alumni yang wirausaha</h2>
<table class="table table-bordered">
 
    <thead >
        <tr>
            <td style="text-align: center; background-color: #d8d8d8">No</td>
            <td style="background-color: #d8d8d8">Nama</td>
            <td style="background-color: #d8d8d8">Kelas</td>
            <td style="background-color: #d8d8d8">No Telp</td>
            <td style="background-color: #d8d8d8">Alamat</td>
            <td style="background-color: #d8d8d8">Tahun Lulus</td>
            <td style="background-color: #d8d8d8">Status</td>
            <td style="background-color: #d8d8d8">Email Siswa</td>
            <td style="background-color: #d8d8d8">Url Medsos</td>
            <td style="background-color: #d8d8d8">Nama usaha</td>
            <td style="background-color: #d8d8d8">Alamat usaha</td>
            <td style="background-color: #d8d8d8">Kontak usaha</td>
            <td style="background-color: #d8d8d8">Bidang usaha</td>
            <td style="background-color: #d8d8d8">Jumlah karyawan</td>
            <td style="background-color: #d8d8d8">Tahun rintis</td>
           
        </tr>
    </thead>
    <tbody>
        @foreach ( $alumni['alumni_wirausaha'] as $index =>  $detail_alumni )
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail_alumni->calon_siswa->nm_c_siswa }}</td>
            <td>{{ $detail_alumni->kelas->nm_kelas }}</td>
            <td>{{ $detail_alumni->calon_siswa->nomor_hp }}</td>
            <td>{{ $detail_alumni->calon_siswa->alamat_jalan }}</td>
            <td>{{ $detail_alumni->tahun_lulus }}</td>
            <td>{{ $detail_alumni->status }}</td> 
            <td>{{ $detail_alumni->email }}</td>
            <td>{{ $detail_alumni->url_medsos }}</td>
            <td>{{ $detail_alumni->usaha->nm_usaha }}</td>
            <td>{{ $detail_alumni->usaha->alamat_usaha }}</td>
            <td>{{ $detail_alumni->usaha->kontak_usaha }}</td>
            <td>{{ $detail_alumni->usaha->bidang_usaha }}</td>
            <td>{{ $detail_alumni->usaha->jumlah_karyawan }}</td>
            <td>{{ $detail_alumni->usaha->tahun_rintis }}</td>
            

        </tr>
        @endforeach
    </tbody>
</table>
<br>