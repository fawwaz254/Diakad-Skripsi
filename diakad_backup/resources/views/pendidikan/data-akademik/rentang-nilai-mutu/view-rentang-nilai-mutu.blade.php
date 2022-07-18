<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/rentang-nilai-mutu/add')}}"><i class="material-icons">note_add</i><span>Tambah Rentang Nilai Mutu</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>DATA RENTANG NILAI MUTU</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Status</th>
                                        <th>Nilai KKM (Mapel)</th>
                                        <th>Nilai Huruf</th>
                                        <th>Nilai Mutu</th>
                                        <th>Rentang Nilai Min</th>
                                        <th>Rentang Nilai Maks</th>
                                        <th>Keterangan</th>
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
    var modul_url       = 'data-akademik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'rentang-nilai-mutu/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'rentang-nilai-mutu/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-rentang-nilai-mutu/delete';

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
            { data: 'status', name: 'status'},
            { data: 'nilai_kkm', name: 'nilai_kkm'},
            { data: 'nm_standar_nilai', name: 'nm_standar_nilai' },
            { data: 'mutu_standar_nilai', name: 'mutu_standar_nilai' },
            { data: 'nilai_min_peraturan_nilai', name: 'nilai_min_peraturan_nilai' },
            { data: 'nilai_max_peraturan_nilai', name: 'nilai_max_peraturan_nilai' },
            { data: 'keterangan_standar_nilai', name: 'keterangan_standar_nilai' },
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