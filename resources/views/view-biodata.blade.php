<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{ url(Request::segment(1)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>
            {{--  <a href="{{url(Request::segment(1).'/siswa/insert-update-siswa/view-print-siswa/'.$siswa->nis_siswa)}}" target="_blank" class="btn bg-red waves-effect">
            <i class="material-icons">print</i>
            <span>Print Data Siswa</span>
        </a></h2> --}}
    </div>
    {{-- <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                {{csrf_field()}}
                <div class="header">
                    <h2> --}}
    {{-- BIODATA SISWA NIS/NISN : {{$nis_siswa}} --}}
    </h2>
    {{-- </div>
</div>
</div> --}}
    {{-- </div> --}}
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="body">
                    <center>
                        <h4>Biodata</h4>
                        @if (!empty($pengguna->pengguna->path_foto_pengguna))
                            <img src="{{ Storage::disk('spaces')->url($pengguna->pengguna->path_foto_pengguna) }}"
                                style="height: 270px; width: 180px">
                        @else
                            <img src="{{ asset('media/blank-user.png') }}" style="height: 270px; width: 180px">
                        @endif
                    </center>
                    <br>
                    {{-- <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <td style="width: 50%">Nama Lengkap</td>
                                <td style="width: 50%">{{$siswa->nm_pengguna}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nomor Pendaftaran</td>
                                <td style="width: 50%">{{$siswa->kode_voucher}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NIS</td>
                                <td style="width: 50%">{{$siswa->nis_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Email</td>
                                <td style="width: 50%">{{$email_pengguna}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NISN</td>
                                <td style="width: 50%">{{$siswa->nisn_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jenis Kelamin</td>
                                <td style="width: 50%">{{$jenis_kelamin}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Tempat, Tanggal Lahir</td>
                                <td style="width: 50%">{{$kota_lahir}},{{$siswa->tgl_lahir}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Tahun Masuk</td>
                                <td style="width: 50%">{{$siswa->thn_masuk_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jalur Masuk</td>
                                <td style="width: 50%">{{$siswa->nm_jalur}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Sekolah Asal</td>
                                <td style="width: 50%">{{$siswa->asal_sekolah}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jurusan</td>
                                <td style="width: 50%">{{$siswa->nm_jurusan}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Kelas</td>
                                <td style="width: 50%">{{$siswa->nm_kelas}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Status Siswa</td>
                                <td style="width: 50%">{{$siswa->nm_status_pengguna}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Ayah</td>
                                <td style="width: 50%">{{$siswa->nm_ayah}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Ibu</td>
                                <td style="width: 50%">{{$siswa->nm_ibu}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Alamat Siswa</td>
                                <td style="width: 50%">{{$alamat_jalan_siswa}} {{$alamat_dusun_siswa}} {{$alamat_kelurahan_siswa}} {{$alamat_rt_siswa}} {{$alamat_rw_siswa}} {{$alamat_kecamatan_siswa}} {{$alamat_kodepos_siswa}} {{$siswa->nm_kota}} {{$siswa->nm_provinsi}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Alamat Ortu</td>
                                <td style="width: 50%">{{$alamat_jalan_ortu}} {{$alamat_dusun_ortu}} {{$alamat_kelurahan_ortu}} {{$alamat_rt_ortu}} {{$alamat_rw_ortu}} {{$alamat_kecamatan_ortu}} {{$alamat_kodepos_ortu}} {{$alamat_kota_ortu}} {{$alamat_provinsi_ortu}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Siswa</td>
                                <td style="width: 50%">{{$siswa->nomor_hp}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. Telepon Ortu</td>
                                <td style="width: 50%">{{$siswa->nomor_telp_ortu}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Ortu</td>
                                <td style="width: 50%">{{$siswa->nomor_hp_ortu}}</td>
                            </tr>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>


        <div class="row">
            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('report.pimpinan') }}">
                        <div class="card">
                            <div class="body bg-red" style="text-align: -webkit-center;">
                                <img class="media-object" src="{{ url('media/flaticon/man.png') }}" width="64" height="64">
                                <h5>
                                    Report Pimpinan
                                </h5>
                                <small>Data Penggunaan Diakad Untuk Setiap Role Semester
                                    {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</small>
                            </div>
                        </div>
                    </a>
                </div> --}}
            @foreach ($role_pengguna as $role)
                {{-- @php
                        dd($role);
                    @endphp --}}
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-top: 10px">
                    <form action='' method='post'>
                        {{-- <h5>
                               
                            </h5> --}}
                        <button type='submit'
                            style="background-color: transparent; border: none; font-weight: bold; cursor: pointer; margin-top:10px">
                            Role : {{ $role->role->nm_role }}</button>
                    </form>
                    {{-- <a href="{{ url(Request::segment(0) . Request::segment(1) . '#device/fingerprint' . $role->role->id_role) }}"
                            style=" display: inline-block;"> --}}

                    </a>
                    <div class="card">
                        <div class="body " style="text-align: -webkit-center;">
                            {{-- <img class="media-object" src="{{ url('media/flaticon/clipboard.png') }}"
                                        width="64" height="64"> --}}


                            @foreach ($role->role->modul as $modul)
                                <h5>
                                    {{ $modul->nm_modul }}
                                </h5>
                                @foreach ($modul->menus as $menu)
                                    <a href="{{ url(Request::segment(0) . Request::segment(1) . '#device/fingerprint') }}"
                                        style=" display: inline-block;">
                                        <div class="card" style="margin-top: 5px">
                                            <div class="body bg-green" style="text-align: -webkit-center;">

                                                <h5>
                                                    {{ $menu->nm_menu }}
                                                </h5>
                                                {{-- <small>Informasi alat Fingerprint</small> --}}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endforeach
                            {{-- <small>Informasi alat Fingerprint</small> --}}
                        </div>
                    </div>


                    {{-- <a href="{{ url(Request::segment(0) . Request::segment(1) . '#device/fingerprint') }}"
                            style=" display: inline-block;">
                            <div class="card">
                                <div class="body bg-green" style="text-align: -webkit-center;">

                                    <h5>
                                        {{ $role->role->nm_role }}
                                    </h5> --}}
                    {{-- <small>Informasi alat Fingerprint</small> --}}
                    {{-- </div>
                            </div>
                        </a> --}}
                </div>
            @endforeach



            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('report.walikelas') }}">
                        <div class="card">
                            <div class="body bg-red" style="text-align: -webkit-center;">
                                <img class="media-object" src="{{ url('media/flaticon/man.png') }}" width="64"
                                    height="64">
                                <h5>
                                    Report Wali Kelas
                                </h5>
                                <small>Data Menu Wali Kelas
                                    {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</small>
                            </div>
                        </div>
                    </a>
                </div> --}}
        </div>









        {{-- <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        @foreach ($grup_semester_kelas as $tahun_ajaran => $grup_kelas)
                            @foreach ($grup_kelas as $nm_semester => $datapergrup)
                            <h2 class="card-inside-title">Mapel yang Diambil Semester {{$nm_semester}} ({{$tahun_ajaran}})</h2>
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <th>No.</th>
                                    <th>Kode</th>
                                    <th>Mata<br> Pelajaran</th>
                                    <th>Jam <br>Pelajaran</th>
                                    <th>KKM</th>
                                    <th>Nilai<br> Angka</th>
                                    <th>Nilai <br>Huruf</th>
                                </tr>
                                @foreach ($datapergrup as $key => $data)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$data->kd_mata_pelajaran}}</td>
                                    <td>{{$data->nm_mata_pelajaran}}</td>
                                    <td>{{$data->kredit_semester}}</td>
                                    <td>{{$data->nilai_kkm}}</td>
                                    <td>{{$data->nilai_angka}}</td>
                                    <td>{{$data->nilai_huruf}}</td>
                                </tr>
                                @endforeach
                            </table>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div> --}}

        <br>

        {{-- <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <h2 class="card-inside-title">Aktivitas</h2>
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th>Semester</th>
                                <th>Status Siswa</th>
                            </tr>
                            @foreach ($aktivitas as $aktivitas)
                            <tr>
                                <td>{{$aktivitas->nm_semester}} {{$aktivitas->tahun_ajaran}}</td>
                                <td>{{$aktivitas->nm_status_pengguna}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div> --}}

        {{-- <div class="row clearfix" style="margin-top:20px">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <button class="btn btn-block bg-blue waves-effect" id="btn-reset-password" onclick="resetPasswordSiswa('{{$siswa->id_pengguna}}')"><i class="material-icons">update</i><span>Reset Password Siswa</span></button>
                </div>
            </div> --}}

    </div>
</div>

{{-- <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Prestasi Siswa</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Prestasi</th>
                                        <th>Tingkat Prestasi</th>
                                        <th>Jenis Prestasi</th>
                                        <th>Jenis Lomba</th>
                                        <th>Peringkat</th>
                                        <th>Link Sertifikat</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Semester</th>
                                        <th>Kelas</th>
                                        <th>Lokasi</th>
                                        <th>Penyelenggara</th>
                                        <th>Tanggal</th>
                                        <th>Ekstrakurikuler</th>
                                        <th>Guru Pendamping</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div> --}}

<br>

{{-- <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Kegiatan Siswa</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tingkat Kegiatan</th>
                                        <th>Tanggal</th>
                                        <th>Lokasi</th>
                                        <th>Penyelenggara</th>
                                        <th>Link Sertifikat</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

</div>

</div>
@include('scriptjs')
{{-- <script>
    function resetPasswordSiswa(id) {
        swal(
            { title: 'Are you sure?', showCancelButton: true },
            function (isConfirm) {
                if (isConfirm) {
                    $('#btn-reset-password').attr("disabled", true);
                    $.ajax({
                        url:
                            base_url + '/{{Request::segment(1)}}/{{Request::segment(2)}}/reset-password',
                        type: 'POST',
                        data: {
                            id_pengguna: id,
                        },
                        success: function (response) {
                            if (response.status_code == 200) {
                                vex.dialog.alert(response.message);
                            } else if (response.status_code == 201) {
                                vex.dialog.alert(response.message);
                                window.location.href = response.link;
                            } else if (response.status_code == 202) {
                                vex.dialog.alert(response.message);
                                loadURI(response.path);
                            } else if (response.status_code == 203) {
                                vex.dialog.alert(response.message);
                                primary_table.ajax.reload(null, false);
                            } else if (response.status_code == 204) {
                                loadURI(response.path);
                            } else if (response.status_code == 300) {
                                vex.dialog.alert(response.message);
                            }
                        },
                        complete: function () {
                            $('#btn-reset-password').removeAttr('disabled', 'disabled');
                        },
                    });
                }
                return;
            }
        );
    }
</script> --}}
