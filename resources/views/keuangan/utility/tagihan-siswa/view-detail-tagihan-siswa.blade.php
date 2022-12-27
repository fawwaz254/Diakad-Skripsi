 <div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>GENERATE TAGIHAN SISWA</h2> 
                    </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-tagihan-siswa')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tahun Masuk Siswa
                                </h2>
                                <select class="form-control show-tick" name="thn_masuk_siswa" >
                                  <option value="" disabled selected >-- Pilih Tahun Masuk --</option>
                                    @foreach($data_thn_masuk_siswa as $data)
                                        @if($data->thn_masuk_siswa == $thn_masuk_siswa)
                                            <option value="{{$data->thn_masuk_siswa}}" selected >{{$data->thn_masuk_siswa}}</option>
                                        @else
                                            <option value="{{$data->thn_masuk_siswa}}" >{{$data->thn_masuk_siswa}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester">
                                  <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->id_semester == $id_semester)
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
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelompok Biaya
                                </h2>
                                <select class="form-control show-tick" name="id_kelompok_biaya" >
                                @if($id_kelompok_biaya == "0")
                                    <option value="0" selected >-- Semua --</option>
                                    @foreach($data_kelompok_biaya as $data)
                                        @if($data->status_kelompok_biaya == 1)
                                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}}</option>
                                        @else
                                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} (Khusus)</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="0" >-- Semua --</option>
                                    @foreach($data_kelompok_biaya as $data)
                                        @if($data->id_kelompok_biaya == $id_kelompok_biaya)
                                            @if($data->status_kelompok_biaya == 1)
                                                <option value="{{$data->id_kelompok_biaya}}" selected >{{$data->nm_kelompok_biaya}} (Reguler)</option>
                                            @else
                                                <option value="{{$data->id_kelompok_biaya}}" selected >{{$data->nm_kelompok_biaya}} (Khusus)</option>
                                            @endif
                                        @else
                                            @if($data->status_kelompok_biaya == 1)
                                                <option value="{{$data->id_kelompok_biaya}}" >{{$data->nm_kelompok_biaya}} (Reguler)</option>
                                            @else
                                                <option value="{{$data->id_kelompok_biaya}}" >{{$data->nm_kelompok_biaya}} (Khusus)</option>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jalur
                                </h2>
                                <select class="form-control show-tick" name="id_jalur" >
                                @if($id_jalur == "0")
                                    <option value="0" selected >-- Semua --</option>
                                    @foreach($data_jalur as $data)
                                        <option value="{{$data->id_jalur}}">{{$data->nm_jalur}}</option>
                                    @endforeach
                                @else
                                    <option value="0" >-- Semua --</option>
                                    @foreach($data_jalur as $data)
                                        @if($data->id_jalur == $id_jalur)
                                            <option value="{{$data->id_jalur}}" selected >{{$data->nm_jalur}}</option>
                                        @else
                                            <option value="{{$data->id_jalur}}">{{$data->nm_jalur}}</option>
                                        @endif
                                    @endforeach
                                @endif
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Insert/Replace Tagihan <small>* REPLACE digunakan untuk menghapus Tagihan Lama dan mengganti dengan Tagihan Baru <br>
                                    * UPDATE digunakan untuk memperbarui tagihan yg belum terbayar</small>
                                </h2>
                                <select class="form-control show-tick" name="is_insert_replace" >
                                @if($is_insert_replace == "1")
                                    <option value="1" selected >Insert Tagihan</option>
                                    <!-- <option value="2">Replace Tagihan</option> -->
                                    <option value="3">Update Tagihan</option>
                                @elseif($is_insert_replace == "2")
                                    <option value="1">Insert Tagihan</option>
                                    <!-- <option value="2" selected >Replace Tagihan</option> -->
                                    <option value="3">Update Tagihan</option>
                                @elseif($is_insert_replace == "3")
                                    <option value="1">Insert Tagihan</option>
                                    <!-- <option value="2" >Replace Tagihan</option> -->
                                    <option value="3" selected>Update Tagihan</option>
                                @endif
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

                    <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-tagihan-siswa/add')}}">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>
                                        <input id="checkbox_select_all" type="checkbox" name="select_all" class="filled-in">
                                        <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                    </th>
                                    <th>Nomor Pendaftaran</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Tahun Masuk</th>
                                    <th>Kelas</th>
                                    <th>Kelompok Biaya</th>
                                    <th>Jalur</th>
                                    <!-- <th>Jumlah Tagihan</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" class="form-control" name="thn_masuk_siswa" required="" aria-required="true" aria-invalid="true" value="{{$thn_masuk_siswa}}">
                            <input type="hidden" class="form-control" name="id_semester" required="" aria-required="true" aria-invalid="true" value="{{$id_semester}}">
                            <input type="hidden" class="form-control" name="id_kelompok_biaya" required="" aria-required="true" aria-invalid="true" value="{{$id_kelompok_biaya}}">
                            <input type="hidden" class="form-control" name="id_jalur" required="" aria-required="true" aria-invalid="true" value="{{$id_jalur}}">
                            <input type="hidden" class="form-control" name="is_insert_replace" required="" aria-required="true" aria-invalid="true" value="{{$is_insert_replace}}">
                            <button class="btn btn-block bg-blue waves-effect" type="submit"><i class="material-icons">save</i><span>Save Tagihan</span></button>
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

    var thn_masuk_siswa     = {!! json_encode($thn_masuk_siswa) !!};
    var id_semester         = {!! json_encode($id_semester) !!};
    var id_kelompok_biaya   = {!! json_encode($id_kelompok_biaya) !!};
    var id_jalur            = {!! json_encode($id_jalur) !!};
    var is_insert_replace   = {!! json_encode($is_insert_replace) !!};

    var modul_url       = 'utility';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'tagihan-siswa/datatables/' + thn_masuk_siswa + '/' + id_semester +'/' + id_kelompok_biaya +'/' + id_jalur +'/' + is_insert_replace;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false,
                render: function (data, type, full, meta){
                    return '<input id="checkbox-' + data.id_siswa + '" type="checkbox" name="id_siswa[]" class="filled-in" value="' + data.id_siswa + '">'+
                    '<label for="checkbox-' + data.id_siswa + '"></label>'; 

                }
            },
            { data: 'kode_voucher', name: 'calon_siswa_baru.kode_voucher' },
            { data: 'nis_siswa', name: 'siswa.nis_siswa' },
            { data: 'nisn_siswa', name: 'siswa.nisn_siswa' },
            { data: 'nm_pengguna', name: 'pengguna.nm_pengguna' },
            { data: 'thn_masuk_siswa', name: 'siswa.thn_masuk_siswa' },
            { data: 'nm_kelas', name: 'kelas.nm_kelas' },
            { data: 'kelompok_biaya', name: 'kelompok_biaya.nm_kelompok_biaya' },
            { data: 'nm_jalur', name: 'jalur.nm_jalur' }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1;
            primary_table.cell(cell).invalidate('dom');
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