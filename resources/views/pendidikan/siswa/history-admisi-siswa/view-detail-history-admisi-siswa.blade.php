<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        HISTORY ADMISI SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-histori-admisi-siswa')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            NIS/NISN atau Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_nama_siswa" aria-invalid="true" value="{{$nis_siswa}}">
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
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th colspan="2" style="text-align: center;">BIODATA SISWA</th>
                            </tr>
                            <tr>
                                <td style="width: 35%">NIS</td>
                                <td style="width: 65%">{{$siswa->nis_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 35%">NISN</td>
                                <td style="width: 65%">{{$siswa->nisn_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 35%">Nama Siswa</td>
                                <td style="width: 65%">{{$siswa->nm_pengguna}}</td>
                            </tr>
                            <tr>
                                <td style="width: 35%">Kelas</td>
                                <td style="width: 65%">{{$siswa->nm_kelas}}</td>
                            </tr>
                            <tr>
                                <td style="width: 35%">Status Akademik Saat Ini</td>
                                <td style="width: 65%">{{$siswa->nm_status_pengguna}}</td>
                            </tr>
                        </table>
                    </div>
                    <br>
                        {{csrf_field()}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Jalur Masuk</th>
                                    <th>NO. SK</th>
                                    <th>Tanggal SK</th>
                                    <th>No. Ijazah</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
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
                            <!-- <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button> -->
                            <a class="btn btn-block bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-kesiswaan/admisi-siswa/view-detail/'.$nis_siswa)}}"><i class="material-icons">library_add</i><span>Tambah Admisi</span></a>
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

    var nis_siswa = {!! json_encode($nis_siswa) !!};

    var modul_url       = 'data-kesiswaan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'histori-admisi-siswa/datatables/' + nis_siswa;
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-histori-admisi-siswa/delete';


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
            { data: 'nm_semester', name: 'nm_semester' },
            { data: 'nm_status_pengguna', name: 'nm_status_pengguna' },
            { data: 'nm_jalur', name: 'nm_jalur' },
            { data: 'nomor_sk_kelulusan', name: 'nomor_sk_kelulusan' },
            { data: 'tgl_sk_kelulusan', name: 'tgl_sk_kelulusan' },
            { data: 'nomor_ijasah', name: 'nomor_ijasah' },
            { data: 'tgl_keluar', name: 'tgl_keluar' },
            { data: 'keterangan_admisi', name: 'keterangan_admisi' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.status_pengguna == "LULUS" || data.status_pengguna == "CALON_LULUS") {
                        return ' ';
                    }else{
                        return ' ';
                        /*return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';*/
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