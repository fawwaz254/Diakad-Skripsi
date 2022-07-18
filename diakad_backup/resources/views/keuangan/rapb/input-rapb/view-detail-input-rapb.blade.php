 <div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>INPUT RAPB</h2> 
                    </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-input-rapb')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester Mulai
                                </h2>
                                <select class="form-control show-tick" name="id_semester_mulai">
                                  <option value="" disabled selected >-- Pilih Semester Mulai --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->id_semester == $id_semester_mulai)
                                            @if($data->is_aktif_semester == 1)
                                                <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                            @endif
                                        @else
                                            @if($data->is_aktif_semester == 1)
                                                <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester Selesai
                                </h2>
                                <select class="form-control show-tick" name="id_semester_selesai">
                                  <option value="" disabled selected >-- Pilih Semester Selesai --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->id_semester == $id_semester_selesai)
                                            @if($data->is_aktif_semester == 1)
                                                <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                            @endif
                                        @else
                                            @if($data->is_aktif_semester == 1)
                                                <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                    <div class="block-header">
                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/input-rapb/add/'.$id_semester_mulai.'/'.$id_semester_selesai)}}"><i class="material-icons">note_add</i><span>INPUT RAPB</span></a></h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Mulai - Selesai</th>
                                    <th>Jenis Kategori</th>
                                    <th>Kode Sub-Kategori</th>
                                    <th>Nama Sub-Kategori</th>
                                    <th>Unit Kerja</th>
                                    <th>Target Perkiraan</th>
                                    <th>Tanggal</th>
                                    <th>Prioritas</th>
                                    <th>Apv Kepala Unit</th>
                                    <th>Apv Kepala Keuangan</th>
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];

    var id_semester_mulai     = {!! json_encode($id_semester_mulai) !!};
    var id_semester_selesai   = {!! json_encode($id_semester_selesai) !!};

    var modul_url       = 'rapb';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-rapb/datatables/' + id_semester_mulai + '/' + id_semester_selesai;
    var kepala_unit_url         = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-rapb/approve-kepala-unit';
    var kepala_keuangan_url     = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-rapb/approve-kepala-keuangan';
    var edit_url        = role_url + '#' + modul_url + '/' + 'input-rapb/edit/' + id_semester_mulai + '/' + id_semester_selesai;
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-rapb/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'semester', name: 'semester' },
            { data: 'tipe_kategori_rapb', name: 'tipe_kategori_rapb' },
            { data: 'kode_subkategori_rapb', name: 'subkategori_rapb.kode_subkategori_rapb' },
            { data: 'nm_subkategori_rapb', name: 'subkategori_rapb.nm_subkategori_rapb' },
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'dana_perkiraan_rapb', name: 'dana_perkiraan_rapb' },
            { data: 'tgl_rapb', name: 'tgl_rapb' },
            { data: 'prioritas_rapb', name: 'prioritas_rapb' },
            { data: 'nm_kepala_unit', name: 'nm_kepala_unit', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_unit == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaUnitAction(\''+ kepala_unit_url +'\', this)" data-idunitkerja="'+  data.id_unit_kerja +'" data-idrapb="'+  data.id_rapb +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_unit +'</a>';
                    }
                }
            },
            { data: 'nm_kepala_keuangan', name: 'nm_kepala_keuangan', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_keuangan == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaKeuanganAction(\''+ kepala_keuangan_url +'\', this)" data-idrapb="'+  data.id_rapb +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_keuangan +'</a>';
                    }
                }
            },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.jenis_jabatan == 2) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                        '    <i class="material-icons">edit</i>'+
                        '</a> '+
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                        '    <i class="material-icons">delete_forever</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>Hanya Kepala Keuangan yang Berhak Edit dan Hapus</a>';
                    }
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    function kepalaUnitAction(kepala_unit_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Aksi Ini Akan Otomatis Melakukan Approve Kepala Unit!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: kepala_unit_url + '/' + item.attr('data-idrapb') + '/' + item.attr('data-idunitkerja'),
                    success: function (response) {
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
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
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

    function kepalaKeuanganAction(kepala_keuangan_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Aksi Ini Akan Otomatis Melakukan Approve Kepala Keuangan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: kepala_keuangan_url + '/' + item.attr('data-idrapb') + '/1',
                    success: function (response) {
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
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
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