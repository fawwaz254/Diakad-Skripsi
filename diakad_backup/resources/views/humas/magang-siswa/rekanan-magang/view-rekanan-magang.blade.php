<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#magang-siswa/rekanan-magang/add')}}"><i class="material-icons">note_add</i><span>Tambah Rekanan Magang</span></a>
            <a class="btn bg-green waves-effect" href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/import-excel')}}"><i class="material-icons">attach_file</i><span>Import Rekanan Magang</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>DATA REKANAN MAGANG</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Rekanan Magang</th>
                                        <th>No Telepon</th>
                                        <th>No. HP</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Awal Kerjasama</th>
                                        <th>Tanggal Akhir Kerjasama</th>
                                        <th>Kuota Magang</th>
                                        <th>Contact Person</th>
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
</div>
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'magang-siswa';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'rekanan-magang/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'rekanan-magang/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-rekanan-magang/delete';

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
            { data: 'nm_rekanan_magang', name: 'nm_rekanan_magang'},
            { data: 'nomor_telp_rekanan_magang', name: 'nomor_telp_rekanan_magang'},
            { data: 'nomor_hp_rekanan_magang', name: 'nomor_hp_rekanan_magang' },
            { data: 'alamat_rekanan_magang', name: 'alamat_rekanan_magang'},
            { data: 'tgl_mulai', name: 'tgl_mulai' },
            { data: 'tgl_selesai', name: 'tgl_selesai' },
            { data: 'kuota_rekanan_magang', name: 'kuota_rekanan_magang' },
            { data: 'contact_person_rekanan_magang', name: 'contact_person_rekanan_magang' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
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
</script>
