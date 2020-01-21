<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/hapus-plotting-mapel-siswa/view-semester-hapus-plotting-mapel-siswa/'.$semester_aktif->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Daftar siswa {{$data_kelas->mata_pelajaran->nm_mata_pelajaran}} di kelas {{$data_kelas->kelas->nm_kelas}}<br>
                            Semester {{$semester_aktif->tahun_ajaran}} ({{$semester_aktif->nm_semester}})</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>Daftar Jadwal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_jadwal as $jadwal)
                                    <tr>
                                        <th>{{$jadwal->jadwal_hari->nm_jadwal_hari}}, 
                                            {{$jadwal->jadwal_jam_mulai->jam_mulai.':'.$jadwal->jadwal_jam_mulai->menit_mulai}} -
                                            {{$jadwal->jadwal_jam_selesai->jam_mulai.':'.$jadwal->jadwal_jam_selesai->menit_mulai}} 
                                            ({{$jadwal->ruangan->nm_ruangan}})
                                        </th>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1
                                    @endphp
                                    @foreach($data_siswa as $siswa)
                                    <tr>
                                        <th>{{$no++}}</th>
                                        <th>{{$siswa->siswa->nis_siswa}}</th>
                                        <th>{{$siswa->siswa->pengguna->nm_pengguna}}</th>
                                        <th>
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deletePlottingAction(this)" data-id="{{$siswa->siswa->id_siswa}}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
                                        </th>
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
    var modul_url       = 'aktivitas-semester';
    
    function deletePlottingAction(element){
        $('button').attr('disabled', 'disabled');

        var item = $(element);
        var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-hapus-plotting-mapel-siswa';

        swal({
            title: "Anda sudah yakin?",
            text: "Penghapusan ini akan menghapus juga presensi yang telah dilakukan oleh guru terkait",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url,
                    data: {
                        id_siswa: item.attr('data-id'),
                        id_semester: '{{$semester_aktif->id_semester}}',
                        id_kelas_mp: '{{$data_kelas->id_kelas_mp}}'
                    },
                    success: function (response) {
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
</script>