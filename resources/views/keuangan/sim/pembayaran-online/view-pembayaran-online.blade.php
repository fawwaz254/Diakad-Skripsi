<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/add')}}"><i class="material-icons">note_add</i><span>Request kode bayar</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>Daftar Kode Bayar</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <labe>Tanggal Mulai</label>
                                    <input type="text" class="datepicker form-control" name="start_date" value="{{\Carbon\Carbon::today(env('APP_TIMEZONE', 'Asia/jakarta'))->startOfMonth()->format('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <labe>Tanggal Selesai</label>
                                    <input type="text" class="datepicker form-control" name="end_date" value="{{\Carbon\Carbon::today(env('APP_TIMEZONE', 'Asia/jakarta'))->endOfMonth()->format('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Filter</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Transaksi</th>
                                    <th>NIS Siswa</th>
                                    <th>Nama Siswa</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Biaya Admin</th>
                                    <th>Bayar melalui</th>
                                    <th>Status</th>
                                    <th>Tanggal Bayar</th>
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
    var modul_url               = '/sim/pembayaran-online/';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: base_url + '/' + role_url + modul_url + 'datatables',
            type: 'POST',
            data: function(params){
                params.start_date = encodeURIComponent($('input[name=start_date]').val());
                params.end_date = encodeURIComponent($('input[name=end_date]').val());
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'nomor_transaksi' },
            { data: 'siswa.nis_siswa' },
            { data: 'siswa.pengguna.nm_pengguna' },
            { data: 'keterangan', searchable: false, orderable: false },
            { data: 'besar_pembayaran', searchable: false, orderable: false,
                render: function(data){
                    return 'Rp' +numeral(data).format('0,0');
                }
            },
            { data: 'fee_admin', searchable: false, orderable: false,
                render: function(data){
                    return 'Rp' +numeral(data).format('0,0');
                }
            },
            { data: 'payment_code' },
            { data: 'status', searchable: false, orderable: false },
            { data: 'tanggal_bayar', searchable: false, orderable: false },
            { data: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data == null){
                        return '';
                    }else{
                        return '<a class="btn btn-warning btn-circle waves-effect waves-circle waves-float" onclick="copyToClipboard(\'' +data+ '\')">'+
                            '    <i class="material-icons">info_outline</i>'+
                            '</a> '+
                            '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float" target="_blank" href="'+ data + '">'+
                            '    <i class="material-icons">attach_money</i>'+
                            '</a> ';
                    }
                }
            },
        ],
        
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

    function copyToClipboard(link) {
        var $input = $("<input>");
        $input.val(link).appendTo('body').select();
        document.execCommand('copy');
        $input.remove();

        vex.dialog.alert('Link copied!');
    }
</script>