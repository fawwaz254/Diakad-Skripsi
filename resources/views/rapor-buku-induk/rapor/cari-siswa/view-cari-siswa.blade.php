<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        CARI DATA RAPOR SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-cari-siswa')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Masukkan NIS/NISN atau Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_nama_siswa" aria-invalid="true" value="{{($nis_nama_siswa==null?'':$nis_nama_siswa)}}" autofocus>
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

                @if($nis_nama_siswa != null)
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Jalur Masuk</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-rapor" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">PRINT RAPOR</h4>
            </div>
            <form action="{{ url(Request::segment(1).'/'.Request::segment(2).'/cari-siswa/print-rapor') }}" target="_blank" method="POST">
                <div class="modal-body">
                    {{ csrf_field() }}
                        <div class="row form-group">
                            <div class="col">
                                <label for="catatan">Catatan Wali Kelas</label><br>
                                <textarea id="catatan" class="form-control" name="deskripsi_catatan_wali_kelas" placeholder="Tulis catatan"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="">Pilih Kelas</label>
                            </div>
                        </div>
                        <div class="modal-print"></div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect">Kirim</button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Batal</button>
                </div> -->
            </form>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var nis_nama_siswa = {!! json_encode($nis_nama_siswa) !!};
    
    var modul_url       = 'rapor';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'cari-siswa/datatables/' + nis_nama_siswa;
    var preview_url     = base_url + '/' + role_url + '/' + modul_url + '/' + 'cari-siswa/preview-rapor';
    var print_url       = role_url + '#' + modul_url + '/' + 'cari-siswa/print-rapor';
    
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
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'nisn_siswa', name: 'nisn_siswa' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'nm_status_pengguna', name: 'nm_status_pengguna' },
            { data: 'nm_jalur' , name:'nm_jalur'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float btn-print" target="_blank">'
                            + '    <i class="material-icons">print</i>' + '</a>';
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

    var table = $('#primary_table').DataTable();

    $('#primary_table tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        console.log(data);
        
        $(".modal-print").empty();
        data.log_kelas.forEach(function (row) {
              $(".modal-print").append('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">');
              $(".modal-print").append('<input type="hidden" name="id_siswa" value="' + row.id_siswa + '">');
              $(".modal-print").append('<input type="hidden" name="id_kelas" value="' + row.id_kelas + '">');
              $(".modal-print").append('<input type="hidden" name="id_semester" value="' + row.id_semester + '">');
              $(".modal-print").append('<button type="submit" class="btn btn-primary waves-effect"> Kelas: ' + row.tingkat + ' Semester: ' + row.nm_semester + ' (' + row.nm_kelas + ')</button>');
              $(".modal-print").append('</div></div>');
            });
        $("#modal-rapor").modal('show');

    });
</script>