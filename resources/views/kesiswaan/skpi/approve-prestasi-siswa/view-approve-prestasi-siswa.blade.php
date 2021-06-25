<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" id="button0" onclick="datatable(0)" class="btn btn-primary">
                Approve / Reject
            </button>
             <button type="button" id="button1" onclick="datatable(1)" class="btn btn-default">
                Belum di approve
            </button>

            <input type="hidden" id="param" value="0">
            <input type="hidden" name="role" id="role" value="{{Request::segment(1)}}">

            <p></p>

            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Approved SKPI Siswa</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Prestasi</th>
                                        <th>Kegiatan</th>
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
    var modul_url       = '{{Request::segment(2)}}';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-prestasi-siswa/datatables';
    var detail_url        = role_url + '#' + modul_url + '/' + 'approve-prestasi-siswa';

    function datatable(id){

        if(id==0){
            $('#param').val(0);
            $('#button1').removeClass('btn btn-primary').addClass('btn btn-default');
            $('#button0').removeClass('btn btn-default').addClass('btn btn-primary');
            primary_table.draw();
        }

        else{
            $('#param').val(1);
            $('#button0').removeClass('btn btn-primary').addClass('btn btn-default');
            $('#button1').removeClass('btn btn-default').addClass('btn btn-primary');
            primary_table.draw();
        }

    }

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data:function(d){
                d.param = $('#param').val(),
                d.role = $('#role').val()
            }
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_c_siswa', name: 'nm_c_siswa' },
            { data: 'prestasi', name: 'prestasi', searchable: false, orderable: false},
            { data: 'kegiatan', name: 'kegiatan', searchable: false, orderable: false},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="'+ detail_url + '/' + data.id + '/' + $('#param').val() + '">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '}
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
