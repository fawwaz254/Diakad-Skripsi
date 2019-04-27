<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/try-out-reguler-online')}}"><i class="material-icons">backspace</i><span>Kembali ke Daftar Ujian</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header bg-light-green">
                    <h2>Daftar Siswa Kelas {{$kelas->nm_kelas}} {{$kelas->nm_mata_pelajaran}}</h2><br>
                    <h2>{{$kelas->nm_ujian_mp}} @if($kelas->is_online == 0) Reguler  @else Online @endif</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-try-out/assign/'.$kelas->id_ujian_mp)}}">
                            {{csrf_field()}}
                            @if($peserta == null)
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Tambahkan ke Ujian</span></button>
                            </div>
                            @endif
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input id="checkbox_select_all" type="checkbox" name="select_all" class="filled-in" checked="">
                                            <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                    </tr>
                                </thead>
                            </table>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script type="text/javascript">
    var id = {!! json_encode($id) !!}

    var modul_url               = 'ujian';
    var datatable_reguler_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'try-out-reguler-online/datatablesSiswa/' + id;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_reguler_url,
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
    { data: 'nis_siswa', name: 'nis_siswa'},
    { data: 'nisn_siswa', name: 'nisn_siswa'},
    { data: 'nm_pengguna', name: 'nm_pengguna' },

    ]
});

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
<script type="text/javascript">
    $(document).ready(function() {        
        /* Select All Checkbox */
        $('input[name="select_all"]').change(function() {
            var select_all_checked = this.checked;
            $('input[name="id_siswa[]"]').each(function() {
                this.checked = select_all_checked;
            });
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
