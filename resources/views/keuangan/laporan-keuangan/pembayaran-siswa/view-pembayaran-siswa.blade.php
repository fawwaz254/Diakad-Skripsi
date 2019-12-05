<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER PEMBAYARAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <labe>Tanggal Mulai</label>
                                    <input type="text" class="datepicker form-control" name="start_date" value="{{\Carbon\Carbon::today(env('APP_TIMEZONE', 'Asia/jakarta'))->format('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <labe>Tanggal Selesai</label>
                                    <input type="text" class="datepicker form-control" name="end_date" value="{{\Carbon\Carbon::today(env('APP_TIMEZONE', 'Asia/jakarta'))->format('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Filter</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>LAPORAN PEMBAYARAN</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bayar</th>
                                    <th>NIS Siswa</th>
                                    <th>Nama Siswa</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah Bayar</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>
<script>
    var modul_url               = 'laporan-keuangan';
    var datatable_url_belum     = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembayaran-siswa/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url_belum,
            type: 'GET',
            data: function(params){
                params.start_date = encodeURIComponent($('input[name=start_date]').val());
                params.end_date = encodeURIComponent($('input[name=end_date]').val());
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'tanggal_bayar', name:'tgl_pembayaran', searchable: false },
            { data: 'tagihan_biaya.siswa.nis_siswa' },
            { data: 'tagihan_biaya.siswa.pengguna.nm_pengguna' },
            { data: 'keterangan_bayar', searchable: false, orderable: false },
            { data: 'besar_pembayaran',
                render: function(data){
                    return 'Rp' +numeral(data).format('0,0');
                }
            },
        ],
        fnDrawCallback: function ( row, data, start, end, display ) {
            var api = this.api();
            var json = api.ajax.json();
            $( api.column( 4 ).footer() ).html(
                'Total Pembayaran'
            );
            $( api.column( 5 ).footer() ).html(
                'Rp'+numeral(json.total).format('0,0')
            );
        }
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table.cell(cell).invalidate('dom');
        } );
    } ).draw();


    function filterAction(){
        primary_table.ajax.reload(null, false);
    }
</script>