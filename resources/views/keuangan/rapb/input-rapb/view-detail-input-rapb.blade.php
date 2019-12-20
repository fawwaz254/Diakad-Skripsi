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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Kode Sub-Kategori</th>
                                    <th>Nama Sub-Kategori</th>
                                    <th>Unit Kerja</th>
                                    <th>Dana Perkiraan</th>
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
            { data: 'semester_mulai', name: 'semester_mulai' },
            { data: 'semester_selesai', name: 'semester_selesai' },
            { data: 'kode_subkategori_rapb', name: 'subkategori_rapb.kode_subkategori_rapb' },
            { data: 'nm_subkategori_rapb', name: 'subkategori_rapb.nm_subkategori_rapb' },
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'dana_perkiraan_rapb', name: 'rapb.dana_perkiraan_rapb' },
            { data: 'tgl_rapb', name: 'rapb.tgl_rapb' },
            { data: 'prioritas_rapb', name: 'prioritas_rapb' },
            { data: 'nm_kepala_unit', name: 'nm_kepala_unit' },
            { data: 'nm_kepala_keuangan', name: 'nm_kepala_keuangan' },
            { data: 'prioritas_rapb', name: 'prioritas_rapb' }
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