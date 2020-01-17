<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        @if($id == '0')
                            <h2>Daftar Ujian Tengah Semester (UTS) - {{$semester->tahun_ajaran}} {{$semester->nm_semester}}</h2>
                        @elseif($id == '1')
                            <h2>Daftar Ujian Akhir Semester (UAS) -{{$semester->tahun_ajaran}} {{$semester->nm_semester}}</h2>
                        @else
                            <h2>Daftar Try Out - {{$semester->tahun_ajaran}} {{$semester->nm_semester}}</h2>
                        @endif
                    </div>
                    <div class="body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#ujian-reguler" data-toggle="tab" aria-expanded="true">
                                    <i class="material-icons">create</i> Ujian Reguler
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#ujian-online" data-toggle="tab">
                                    <i class="material-icons">computer</i> Ujian Online
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="ujian-reguler">
                                @if($id == '0')
                                    <div class="block-header">
                                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/ujian-uts-reguler-online/add/0')}}"><i class="material-icons">note_add</i><span>Tambah UTS Reguler</span></a></h2>
                                    </div>
                                @elseif($id == '1')
                                    <div class="block-header">
                                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/ujian-uas-reguler-online/add/0')}}"><i class="material-icons">note_add</i><span>Tambah UAS Reguler</span></a></h2>
                                    </div>
                                @else
                                    <div class="block-header">
                                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/try-out-reguler-online/add/0')}}"><i class="material-icons">note_add</i><span>Tambah TryOut Reguler</span></a></h2>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_reguler">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Ujian</th>
                                                <th>Jenis Ujian</th>
                                                <th>Kode  -  Nama Mata Ajar</th>
                                                <th>Kelas</th>
                                                <th>Semester</th>
                                                <th>Tanggal Ujian</th>
                                                <th>Jam Mulai</th>
                                                <th>Jam Selesai</th>
                                                <th>Ruangan</th>
                                                <th>Kapasitas</th>
                                                <th>Keterangan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="ujian-online">
                                @if($id == '0')
                                    <div class="block-header">
                                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/ujian-uts-reguler-online/add/1')}}"><i class="material-icons">note_add</i><span>Tambah UTS Online</span></a></h2>
                                    </div>
                                @elseif($id == '1')
                                    <div class="block-header">
                                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/ujian-uas-reguler-online/add/1')}}"><i class="material-icons">note_add</i><span>Tambah UAS Online</span></a></h2>
                                    </div>
                                @else
                                    <div class="block-header">
                                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/try-out-reguler-online/add/1')}}"><i class="material-icons">note_add</i><span>Tambah TryOut Online</span></a></h2>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_online">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Ujian</th>
                                                <th>Jenis Ujian</th>
                                                <th>Kode  -  Nama Mata Ajar</th>
                                                <th>Kelas</th>
                                                <th>Semester</th>
                                                <th>Tanggal Ujian</th>
                                                <th>Jam Mulai</th>
                                                <th>Jam Selesai</th>
                                                <th>Ruangan</th>
                                                <th>Kapasitas</th>
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
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    // var ids = {!! json_encode($id) !!};

    var modul_url       = 'ujian';
    var datatable_online_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'ujian-uts-reguler-online/datatables/1';
    var datatable_reguler_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'ujian-uts-reguler-online/datatables/0';

    var add_url        = role_url + '#' + modul_url + '/' + 'ujian-uts-reguler-online/add';
    var assign_url        = role_url + '#' + modul_url + '/' + 'ujian-uts-reguler-online/assign';
    var edit_url        = role_url + '#' + modul_url + '/' + 'ujian-uts-reguler-online/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-ujian-uts/delete';

    var primary_table_online = $('#primary_table_online').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_online_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_ujian_mp', name: 'nm_ujian_mp' },
            { data: 'jenis_ujian', name: 'jenis_ujian' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'nm_kelas_mp', name: 'nm_kelas_mp' },
            { data: 'semester', name: 'semester' },
            { data: 'tgl_ujian_mp', name: 'tgl_ujian_mp' },
            { data: 'jam_mulai', name: 'jam_mulai' },
            { data: 'jam_selesai', name: 'jam_selesai' },
            { data: 'ruangan_ujian', name: 'ruangan_ujian' },
            { data: 'kapasitas_ujian', name: 'kapasitas_ujian' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ assign_url + '/' + data.id_ujian +'">'+
                    '    <i class="material-icons">assignment_ind</i>'+
                    '</a>'+'<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id_ujian +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a>'+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id_ujian +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            }
        ]
    });

    primary_table_online.on( 'draw', function () {
        primary_table_online.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    var primary_table_reguler = $('#primary_table_reguler').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_reguler_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_ujian_mp', name: 'nm_ujian_mp' },
            { data: 'jenis_ujian', name: 'jenis_ujian' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'nm_kelas_mp', name: 'nm_kelas_mp' },
            { data: 'semester', name: 'semester' },
            { data: 'tgl_ujian_mp', name: 'tgl_ujian_mp' },
            { data: 'jam_mulai', name: 'jam_mulai' },
            { data: 'jam_selesai', name: 'jam_selesai' },
            { data: 'ruangan_ujian', name: 'ruangan_ujian' },
            { data: 'kapasitas_ujian', name: 'kapasitas_ujian' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ assign_url + '/' + data.id_ujian +'">'+
                    '    <i class="material-icons">assignment_ind</i>'+
                    '</a>'+
                    '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id_ujian +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a>'+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id_ujian +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            },
        ]
    });

    primary_table_reguler.on( 'draw', function () {
        primary_table_reguler.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
<script>    
    
    $('#form-validation1').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if(response.status == 200){
                        vex.dialog.alert(response.message);
                    }else if(response.status == 201){
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    }else if(response.status == 202){
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    }else if(response.status == 203){
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    }else if(response.status == 204){
                        loadURI(response.path);
                    }else if(response.status == 300){
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>
