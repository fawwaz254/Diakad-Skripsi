<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ekstrakurikuler/setting-pelatih-ekskul/view-pelatih')}}"><i class="material-icons">people</i><span>Data Pelatih Ekskul</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Pelatih Ekskul</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pelatih</th>
                                        <th>Ekstrakurikuler</th>
                                        <th>Status</th>
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url               = 'ekstrakurikuler';
    var datatable_url           = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-pelatih-ekskul/datatables';
    var edit_url                = role_url + '#' + modul_url + '/' + 'setting-pelatih-ekskul/edit-status';
    var assign_url              = role_url + '#' + modul_url + '/' + 'setting-pelatih-ekskul/assign';
    // var unassign_url            = role_url + '#' + modul_url + '/' + 'setting-pelatih-ekskul/assign';
    var delete_url              = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-setting-pelatih-ekskul/unassign';

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
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nm_ekskul', name: 'nm_ekskul' },
            { data: 'is_aktif', name: 'is_aktif' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id_pelatih_ekskul_set +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a>'+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id_pelatih_ekskul_set +'">'+
                    '    <i class="material-icons">highlight_off</i>'+
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
