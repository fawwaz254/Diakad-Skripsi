<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>LIST FORM KEGIATAN HARIAN</h2>
                </div>
                <div class="body">
                    {{-- <h2 class="card-inside-title">
                        Status
                    </h2> --}}
                    {{-- <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select class="form-control show-tick" name="status" onchange="filterAction()">
                                <option value="">Semua Status</option>
                                <option value="1">Normal</option>
                                <option value="2">Warning</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kegiatan Harian</th>
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
<script>
    // alert()
    var modul_url       = '{{Request::segment(2)}}';
    var menu_url       = '{{Request::segment(3)}}';

    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables';
    var detail_url      = role_url + '#' + modul_url + '/' + menu_url + '/form';

// alert(detail_url);
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_kegiatan_harian' },
            { data: 'is_aktif' },
            // { data: 'status', searchable: false, orderable: false,
            //     render: function(data){
            //         return '<h4><span class="label" style="background-color: #'+data.warna_keadaan+';">'+data.status+'</span></h4>';
            //     }
            // },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">remove_red_eye</i>'+
                    '</a> ';
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