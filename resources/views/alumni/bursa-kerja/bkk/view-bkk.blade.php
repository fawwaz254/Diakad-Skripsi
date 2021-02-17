<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA LOWONGAN KERJA</h2>
                </div>
                {{csrf_field()}}
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Poster</th>
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
    var modul_url       = '{{Request::segment(2)}}';
    var menu_url       = '{{Request::segment(3)}}';

    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables';
    var detail_url        = role_url + '#' + modul_url + '/' + menu_url + '/detail';

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
            { data: 'judul_lowongan_kerja' },
            { data: 'action', name: 'poster_lowongan_kerja', searchable: false, orderable: false,
                render:function(data){
                    if(data.poster){
                        if(data.note=='image'){
                            return `<a href=`+data.poster+` target="_blank"><img src=`+data.poster+` width=150></a>`;
                        }
                        else{
                            return '<a class="btn btn-success btn-circle waves-effect waves-circle waves-float" target="_blank" href="'+data.poster+'">'+
                                    '    <i class="material-icons">insert_drive_file</i>'+
                                    '</a> '
                        }
                    }
                    else{
                        return ``;
                    }
                    
                }
            },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '
                }
            }
        ],
        columnDefs: [
            { className: 'text-center', targets: [2] },
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
