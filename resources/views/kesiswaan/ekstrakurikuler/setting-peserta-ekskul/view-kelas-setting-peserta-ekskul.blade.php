<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ekstrakurikuler/setting-peserta-ekskul/view-ekskul/'.$id_ekskul)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-indigo">
                    <h2>
                        Tambah Peserta Ekstrakurikuler {{$ekskul->nm_ekskul}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-add-setting-peserta-ekskul')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if($id_kelas != null)
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach($data_kelas as $data)
                                    <option value="{{$data->id_kelas}}" @if($id_kelas == $data->id_kelas) selected @endif>{{$data->nm_kelas}}</option>
                                    @endforeach
                                </select>
                                @else
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach($data_kelas as $data)
                                    <option value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <input type="hidden" name="id_ekskul" value="{{$id_ekskul}}">
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">search</i><span>Cari</span></button>
                            </div>
                        </div>
                    </form>
                </div>

                @if($id_kelas != null)
                <div class="container-fluid">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="body">
                                    <div class="table-responsive">
                                        <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setting-peserta-ekskul/add/'.$id_ekskul)}}">
                                {{csrf_field()}}

                                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                                <thead>
                                                    <tr>
                                                        <th>No. </th>
                                                        <th>
                                                            <input id="checkbox_select_all" type="checkbox" name="select_all" class="filled-in">
                                                            <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                                        </th>
                                                        <th>NIS</th>
                                                        <th>Nama Siswa</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <button class="btn btn-block bg-blue waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                            </div>
                                    </form>

                                        </div>
                                       
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kelas = {!! json_encode($id_kelas) !!};

    var modul_url       = 'ekstrakurikuler';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-peserta-ekskul/datatables-siswa/' + id_kelas;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false,
        render: function (data, type, full, meta){
            return '<input id="checkbox-' + data.id + '" type="checkbox" name="id_siswa[]" class="filled-in" value="' + data.id + '">'+
            '<label for="checkbox-' + data.id + '"></label>'; 

        }
    },
    { data: 'nis_siswa', name: 'nis_siswa' },
    { data: 'nm_pengguna', name: 'pengguna.nm_pengguna' }
    ]
});

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
<script type="text/javascript">
    $(document).ready(function() {        
        /* Select All Checkbox */
        $('input[name="select_all"]').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({ 'search': 'applied' }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
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