<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>REKAP MONITORING KELAS KOSONG</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pada Tanggal
                                </h2>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="on_date" required="" aria-required="true" aria-invalid="true" value="{{\Carbon\Carbon::today('Asia/Jakarta')->format('Y-m-d')}}">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" onclick="onFilterAction()"><i class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kelas</th>
                                        <th>Guru Pengampu</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jam</th>
                                        <th>Ruangan</th>
                                        <th>Status</th>
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

    var modul_url       = 'guru-piket';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'rekap-monitoring-kelas-kosong/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(params){
                params.on_date = encodeURIComponent($('input[name=on_date]').val());
            },
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_kelas'},
            { data: 'nm_pengguna', searchable: false, orderable: false },
            { data: 'nm_mata_pelajaran'},
            { data: 'jam', searchable: false, orderable: false },
            { data: 'nm_ruangan'},
            { data: 'status', searchable: false, orderable: false}
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    $(function(){  
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });

    function onFilterAction(){
        primary_table.ajax.reload(null, false);
    }
</script>