<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pengelolaan-akun/pencarian/view-detail/'.$username_nama_cari)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header bg-cyan">
                    <h2>
                        USERNAME : {{$pengguna->username}}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pencarian/edit/'.$id_pengguna)}}">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <th colspan="2" style="text-align: center;">DETAIL AKUN</th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: center;">
                                        <img src="" style="height: 270px; width: 180px">
                                    </th>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Username</td>
                                    <td style="width: 50%">{{$pengguna->username}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Nama Lengkap</td>
                                    <td style="width: 50%">{{strtoupper($pengguna->nm_pengguna)}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Email Afiliasi</td>
                                    <td style="width: 50%">{{$pengguna->email_afiliasi}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Email Pribadi</td>
                                    <td style="width: 50%">{{$pengguna->email_pengguna}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Nomor HP</td>
                                    <td style="width: 50%">{{$pengguna->nomor_hp_pengguna}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Tipe Akun</td>
                                    <td style="width: 50%">{{$tipe_akun}}</td>
                                </tr>
                                @if($pengguna->status_join_table == 1 or $pengguna->status_join_table == 2)
                                    <tr>
                                        <td style="width: 50%">Unit Kerja</td>
                                        <td style="width: 50%">
                                            <select class="form-control show-tick" name="id_unit_kerja">
                                                <option value="" disabled selected >-- Pilih Unit Kerja --</option>
                                                @foreach($unit_kerja_set as $data)
                                                    @if($data->id_unit_kerja == $id_unit_kerja)
                                                        <option value="{{$data->id_unit_kerja}}" selected >{{$data->nm_unit_kerja}} - {{$data->nm_singkatan_unit}}</option>
                                                    @else
                                                        <option value="{{$data->id_unit_kerja}}">{{$data->nm_unit_kerja}} - {{$data->nm_singkatan_unit}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="width: 50%">Role Aktif</td>
                                    <td style="width: 50%">
                                        @if($pengguna->id_pengguna != $id_pengguna_auth)
                                            <select class="form-control show-tick" name="id_role_pengguna">
                                                <option value="" disabled selected >-- Pilih Role --</option>
                                                @foreach($role_pengguna_set as $data)
                                                    @if($data->id_role == $pengguna->id_role)
                                                        <option value="{{$data->id_role_pengguna}}" selected >{{$data->nm_role}}</option>
                                                    @else
                                                        <option value="{{$data->id_role_pengguna}}">{{$data->nm_role}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @else
                                            Tidak Tersedia Ganti Role
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Terakhir Ganti Password</td>
                                    <td style="width: 50%">{{$last_time_password}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Terakhir Login</td>
                                    <td style="width: 50%">{{$last_time_login}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Status Login</td>
                                    <td style="width: 50%">{{$status_online}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="hidden" class="form-control" name="username_nama_cari" required="" aria-required="true" aria-invalid="true" value="{{$username_nama_cari}}">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th colspan="2" style="text-align: center;">Reset Password?</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pencarian/reset-password/'.$id_pengguna)}}">
                                        <input type="hidden" class="form-control" name="id_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$id_pengguna}}">
                                        <button class="btn btn-block bg-blue waves-effect" type="submit"><i class="material-icons">update</i><span>Reset</span></button>
                                    </form>
                                </td>
                                <td style="width: 50%">
                                    <button class="btn btn-block bg-blue waves-effect"><i class="material-icons">update</i><span>Reset Via SMS/WA</span></button>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="body">
                        <div class="table-responsive">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <strong>ROLE PENGGUNA : {{strtoupper($pengguna->nm_pengguna)}}</strong>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Role</th>
                                        <th>Deskripsi</th>
                                        <th>Path</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        @if($pengguna->status_join_table == 1 or $pengguna->status_join_table == 2)
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <a class="btn btn-block bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pengelolaan-akun/pencarian/add-role-pengguna/'.$id_pengguna.'/'.$username_nama_cari)}}"><i class="material-icons">library_add</i><span>Tambah Role</span></a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>

    var id_pengguna = {!! json_encode($pengguna->id_pengguna) !!};

    var modul_url        = 'pengelolaan-akun';
    var datatable_url    = base_url + '/' + role_url + '/' + modul_url + '/' + 'pencarian/datatables-role/' + id_pengguna;
    var delete_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pencarian/delete';

    // datatable jadwal UAS
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
            { data: 'nm_role', name: 'nm_role' },
            { data: 'deskripsi_role', name: 'deskripsi_role'},
            { data: 'path', name: 'path' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.is_mobile == 0) {
                        return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                        '    <i class="material-icons">delete_forever</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>Default Role</a>';
                    }
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
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