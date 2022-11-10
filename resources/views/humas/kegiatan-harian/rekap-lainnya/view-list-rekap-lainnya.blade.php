<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA REKAP KEGIATAN HARIAN</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Aktif/Non-Aktif</th>
                                    <th>Jumlah Data</th>
                                    <th  style="text-align: center;">Guru/Tendik</th>
                                    <th  style="text-align: center;">Siswa</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var modul_url       = '{{Request::segment(2)}}';
    var menu_url       = '{{Request::segment(3)}}';

    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables';
    // var edit_url        = role_url + '#' + modul_url + '/' + menu_url + '/edit';
    var gurutendik_url      = role_url + '#' + modul_url + '/' + menu_url + '/rekap-guru-tendik';
    var siswa_url           = role_url + '#' + modul_url + '/' + menu_url + '/rekap-siswa'
    // var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/action/delete';
// alert(gurutendik_url);
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_kegiatan_harian' },
            { data: 'is_aktif' },
           {data: 'jumlah'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<center><a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ gurutendik_url + '/' + data.id +'">'+
                    '    <i class="material-icons">visibility</i>'+
                    '</a> </center>';
                }
            },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<center><a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ siswa_url + '/' + data.id +'">'+
                    '    <i class="material-icons">visibility</i>'+
                    '</a></center> ';
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