<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER LAPORAN KEUANGAN
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
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-orange waves-effect" onclick="printLaporan()"><i class="material-icons">print</i><span>Cetak Laporan Keuangan</span></button>
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
                    <h2>LAPORAN KEUANGAN</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Keterangan</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
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
    var modul_url        = 'laporan-keuangan';
    var datatable_url    = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-laporan/datatables';
    var print_laporan_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-laporan/print';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(params){
                params.start_date = encodeURIComponent($('input[name=start_date]').val());
                params.end_date = encodeURIComponent($('input[name=end_date]').val());
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'tanggal_bayar', name:'tanggal', searchable: false },
            { data: 'keterangan' },
            { data: 'debit',
                render: function(data){
                    if(data != null){
                        return 'Rp ' +numeral(data).format('0,0');
                    } else {
                        return null;
                    }
                }
            },
            { data: 'credit',
                render: function(data){
                    if(data != null){
                        return 'Rp ' +numeral(data).format('0,0');
                    } else {
                        return null;
                    }
                }
            },
        ],
        fnDrawCallback: function ( row, data, start, end, display ) {
            var api = this.api();
            var json = api.ajax.json();
            $( api.column( 3 ).footer() ).html(
                'Total Debit: <br>Rp '+numeral(json.total_debit).format('0,0')
            );
            $( api.column( 4 ).footer() ).html(
                'Total Kredit: <br>Rp '+numeral(json.total_kredit).format('0,0')
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

    function printLaporan(){
        $('button').attr('disabled', 'disabled');
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();
        console.log(start_date, end_date);
        
        if(start_date == null || end_date == null || start_date == '' || end_date == ''){
            vex.dialog.alert("Tanggal Mulai atau Tanggal Selesai yang dipilih tidak valid");
            $('button').removeAttr('disabled', 'disabled');
        } else {
            window.open(print_laporan_url + '/' + start_date + '/' + end_date, "_blank");
            $('button').removeAttr('disabled', 'disabled');
        }
    }
</script>