<div class="container-fluid">
    <div class="row-clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-light-green">
                    <h2>
                        Plotting Mapel Siswa
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-daftar-plotting-mapel-siswa')}}">
                            {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester" disabled="">
                                    @foreach($data_semester as $data)
                                    <option value="{{$data->id_semester}}" @if($id_semester === $data->id_semester) selected @endif>
                                        {{$data->tahun_ajaran}}
                                        {{$data->nm_semester}} 
                                        @if($data->is_aktif_semester == 1)
                                            (Aktif)
                                        @endif
                                    </option>
                                    @endforeach
                                    <input type="hidden" name="id_semester1SS" value="{{$id_semester}}">
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Angkatan
                                </h2>
                                <select class="form-control show-tick" name="angkatan" disabled="">
                                    @foreach($thn_masuk_siswa as $data)
                                    <option value="{{$data->thn_masuk_siswa}}"  @if($semester_aktif->thn_akademik_semester == $data->thn_masuk_siswa) selected  @endif>
                                        {{$data->thn_masuk_siswa}}
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="angkatan" value="{{$angkatan}}">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach($kelas as $data)
                                    <option value="{{$data->id_kelas}}" @if($data->id_kelas == $id_kelas) selected @endif>
                                        {{$data->nm_kelas}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-blue waves-effect" type="submit"><i class="material-icons">search</i><span>Cari</span></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="body">
                    <div class="header bg-light-green" id="daftar-mata-pelajaran">
                        <h2>
                            Daftar Mata Pelajaran
                        </h2>
                    </div>
                    <div class="header bg-light-green" id="daftar-siswa" style="display: none">
                        <h2>
                            Daftar Siswa
                        </h2>
                    </div>
                    <div class="row clearfix">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-plotting-mapel-siswa/add-krs')}}">
                    {{csrf_field()}}
                        <div class="table-responsive" id="table-mapel">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input id="checkbox_select_all_primary_table" type="checkbox" name="select_all" class="filled-in">
                                            <label for="checkbox_select_all_primary_table" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>Kode Mapel</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jam KBM</th>
                                        <th>Tingkat Semester</th>
                                        <th>Kelas</th>
                                        <th>Guru PJMP</th>
                                        <th>Kredit Semester</th>
                                        <th>Approve</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <input type="hidden" class="form-control" name="id_semester" value="{{$id_semester}}" aria-required="true" aria-invalid="true">
                        <div class="row clearfix" id="spaceButtonLanjut">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <a class="btn btn-block bg-red waves-effect" onclick="myFunction()" id="buttonLanjut"><i class="material-icons">arrow_forward</i><span>Lanjut</span></a>
                            </div>
                        </div>
                        <div class="table-responsive" style="display: none" id="myDIV">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_siswa" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input id="checkbox_select_all_primary_table_siswa" type="checkbox" name="select_all" class="filled-in">
                                            <label for="checkbox_select_all_primary_table_siswa" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>NISN</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Angkatan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix" style="display: none" id="buttonDIV">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <a class="btn btn-block bg-blue waves-effect" onclick="backFunction()" id="buttonKembali"><i class="material-icons">arrow_back</i><span>Kembali ke Daftar Mapel</span></a>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <a class="btn btn-block bg-blue waves-effect" onclick="reviewFunction()" id="buttonTinjau"><i class="material-icons">rate_review</i><span>Tinjau Kembali</span></a>
                            </div>
                        </div>
                        <div class="row clearfix" style="display: none" id="ajukan">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <a class="btn btn-block bg-blue waves-effect" onclick="backFunction()" id="buttonKembali"><i class="material-icons">arrow_back</i><span>Kembali ke Daftar Mapel</span></a>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Ajukan Plotting Siswa</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script type="text/javascript">
    var id_semester = {!! json_encode($id_semester) !!};
    var angkatan    = {!! json_encode($angkatan) !!};
    var id_kelas    = {!! json_encode($id_kelas)!!};

    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'aktivitas-semester';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'plotting-mapel-siswa/datatables-mapel/' + id_semester + '/' + angkatan +'/'+ id_kelas;

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
                    return '<input id="checkbox-' + data.id_kelas_mp + '" type="checkbox" name="id_kelas_mp[]" class="filled-in" value="' + data.id_kelas_mp + '">'+
                    '<label for="checkbox-' + data.id_kelas_mp + '"></label>'; 

                }
            },
            { data: 'kd_mata_pelajaran', name: 'kd_mata_pelajaran' },
            { data: 'nm_mata_pelajaran', name: 'nm_mata_pelajaran' },
            { data: 'kredit_semester', name: 'kredit_semester' },
            { data: 'tingkat_semester', name: 'tingkat_semester' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'pjma', name: 'pjma' },
            { data: 'kredit_semester', name: 'kredit_semester' },
            { data: 'tingkat_semester', name: 'tingkat_semester' }       
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    var datatable_url_siswa   = base_url + '/' + role_url + '/' + modul_url + '/' + 'plotting-mapel-siswa/datatables-siswa/' + angkatan +'/'+ id_kelas;

    var primary_table_siswa = $('#primary_table_siswa').DataTable({
        processing: true,
        // serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url_siswa,
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
            { data: 'nisn_siswa', name: 'nisn_siswa' },
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'thn_masuk_siswa', name: 'thn_masuk_siswa' }
        ]
    });

    primary_table_siswa.on( 'draw', function () {
        primary_table_siswa.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    function myFunction() {
      var x = document.getElementById("myDIV");

      if (x.style.display === "none") {
        x.style.display = "block";
        document.getElementById("buttonDIV").style.display = "block";
        document.getElementById("daftar-siswa").style.display = "block";
        document.getElementById("buttonLanjut").style.display = "none";
        document.getElementById("table-mapel").style.display = "none";
        document.getElementById("spaceButtonLanjut").style.display = "none";
        document.getElementById("daftar-mata-pelajaran").style.display = "none";
      } else {
        x.style.display = "none";
      }
    }
    function backFunction() {
      if (document.getElementById("table-mapel").style.display === "none") {
        document.getElementById("myDIV").style.display = "block";
        document.getElementById("buttonDIV").style.display = "none";
        document.getElementById("daftar-siswa").style.display = "block";
        document.getElementById("buttonLanjut").style.display = "block";
        document.getElementById("table-mapel").style.display = "block";
        document.getElementById("daftar-mata-pelajaran").style.display = "block";
        document.getElementById("spaceButtonLanjut").style.display = "block";
      } else {
        document.getElementById("table-mapel").style.display = "block";
      }
    }
    function reviewFunction() {
        document.getElementById("table-mapel").style.display === "block";
        document.getElementById("myDIV").style.display = "block";
        document.getElementById("buttonDIV").style.display = "none";
        document.getElementById("ajukan").style.display = "block";
        document.getElementById("buttonReview").style.display = "none";
        document.getElementById("daftar-siswa").style.display = "block";
        document.getElementById("buttonLanjut").style.display = "none";
        document.getElementById("daftar-mata-pelajaran").style.display = "block";
        document.getElementById("spaceButtonLanjut").style.display = "block";
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {        
        /* Select All Checkbox */
        $('#checkbox_select_all_primary_table').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({ 'search': 'applied' }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        $('#checkbox_select_all_primary_table_siswa').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table_siswa.rows({ 'search': 'applied' }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>