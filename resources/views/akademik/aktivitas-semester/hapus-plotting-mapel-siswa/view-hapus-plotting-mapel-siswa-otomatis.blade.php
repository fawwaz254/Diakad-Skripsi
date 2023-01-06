<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#aktivitas-semester/hapus-plotting-mapel-siswa/view-semester-hapus-plotting-mapel-siswa/' . $semester_aktif->id_semester) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                {{-- <div class="header">
                    <h2>Daftar siswa {{ $data_kelas->mata_pelajaran->nm_mata_pelajaran }} di kelas
                        {{ $data_kelas->kelas->nm_kelas }}<br>
                        Semester {{ $semester_aktif->tahun_ajaran }} ({{ $semester_aktif->nm_semester }})</h2>
                </div> --}}
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>Daftar Jadwal Siswa Duplikat ada {{ $data_siswa->count() }}</th>

                                </tr>
                                <tr>
                                    <th>Penghapusan Maximal 500 data per action</th>
                                </tr>
                            </thead>
                            {{-- <tbody>
                                @foreach ($data_jadwal as $jadwal)
                                    <tr>
                                        <th>{{ $jadwal->jadwal_hari->nm_jadwal_hari }},
                                            {{ $jadwal->jadwal_jam_mulai->jam_mulai . ':' . $jadwal->jadwal_jam_mulai->menit_mulai }}
                                            -
                                            {{ $jadwal->jadwal_jam_selesai->jam_mulai . ':' . $jadwal->jadwal_jam_selesai->menit_mulai }}
                                            ({{ $jadwal->ruangan->nm_ruangan }})
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody> --}}
                        </table>
                        <br>

                        <div style="margin-bottom: 15px">
                            <button type="submit" class="btn btn-danger waves-effect" onclick="deleteAll()">
                                <i class="material-icons">delete_forever</i><span>Delete 500 Data</span>
                            </button>
                        </div>

                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    {{-- <th>
                                        <input id="checkbox_select_all_primary_table" type="checkbox" name="select_all"
                                            class="filled-in" onclick="return checkboxAll()">
                                        <label for="checkbox_select_all_primary_table"
                                            style="margin-bottom: -10px;"></label>
                                    </th> --}}
                                    <th>Nama</th>
                                    <th>Jumlah Duplikat</th>
                                    <th>Nama Mata Pelajaran</th>
                                    <th>Jenis Mapel</th>
                                    <th>Kelas</th>
                                    {{-- <th>Jadwal</th>
                                    <th>Pengampu</th> --}}
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <th>{{ $no++ }}</th>
                                        {{-- <th>
                                            <input id="{{ $siswa->siswa->id_siswa }}" onchange="checkboxSingle()"
                                                type="checkbox" name="id_siswa[]" class="filled-in data-siswa"
                                                value="{{ $siswa->siswa->id_siswa }}">
                                            <label for="{{ $siswa->siswa->id_siswa }}"
                                                style="margin-bottom: -10px;"></label>
                                        </th> --}}
                                        <th>{{ $siswa->siswa->pengguna->nm_pengguna }}</th>
                                        <th>{{ $siswa->count }}</th>
                                        <th>
                                            {{ $siswa->kelas_mp->mata_pelajaran->nm_mata_pelajaran }}
                                        </th>
                                        <th>
                                            {{ $siswa->kelas_mp->mata_pelajaran->jenis_mata_pelajaran->nm_jenis_mata_pelajaran }}
                                        </th>
                                        <th>{{ $siswa->kelas_mp->kelas->nm_kelas }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    var modul_url = 'aktivitas-semester';

    // trigger button delete all
    function deleteAll() {
        var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
            'action-hapus-plotting-mapel-siswa-otomatis';
        // alert(delete_url);

        swal({
            title: "Anda sudah yakin?",
            text: "Penghapusan ini akan lama",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            $('button').attr('disabled', 'disabled');
            $('button').html(
                `<div class="spinner-border text-light" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>`
            );
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url,
                    success: function(response) {
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
    // }
</script>
