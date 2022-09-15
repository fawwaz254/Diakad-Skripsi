<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        PLOTTING MAPEL SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-plotting-mapel-siswa')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach($data_semester as $data)
                                    <option value="{{$data->id_semester}}" @if($id_semester === $data->id_semester) selected @endif>
                                        {{$data->tahun_ajaran}}
                                        {{$data->nm_semester}} 
                                        @if($data->is_aktif_semester == 1)
                                            (Aktif)
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                         <h2 class="card-inside-title">
                            Angkatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="angkatan">
                                    @foreach($thn_masuk_siswa as $data)
                                    <option value="{{$data->thn_masuk_siswa}}"  @if($semester_aktif->thn_akademik_semester == $data->thn_masuk_siswa) selected  @endif>
                                        {{$data->thn_masuk_siswa}}
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
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                {{csrf_field()}}
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Jurusan</th>
                                    <th>Sudah Diplotting</th>
                                    <th>Jumlah Siswa</th>
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
    var id_semester= {!! json_encode($id_semester) !!};
    var angkatan= {!! json_encode($angkatan) !!};

    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'aktivitas-semester';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'plotting-mapel-siswa/datatables/' + id_semester + '/' + angkatan;
    var detail_url        = role_url + '#' + modul_url + '/' + 'plotting-mapel-siswa/view-mapel-plotting/'+ id_semester + '/' + angkatan;
    var auto_ploting      = role_url + '#' + modul_url + '/' + 'plotting-mapel-siswa/action-auto-plotting-mapel-siswa/'+ id_semester + '/' + angkatan;

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
            { data: 'nm_jurusan', name: 'nm_jurusan' },
            { data: 'jml_siswa_krs', name: 'jml_siswa_krs' },
            { data: 'jml_siswa', name: 'jml_siswa' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn bg-blue waves-effect" href="'+ detail_url +'/' + data.id + '">Manual Plotting</a>'
                    +'      <a class="target-link btn bg-red waves-effect" href="'+ auto_ploting +'/' + data.id + '">Auto Plotting</a>';
                }
            }]})

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
