<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if($pembayaran_aktif->first())
            <div class="card is-gap">
                <div class="header">
                    <h2>KODE BAYAR AKTIF</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nomor Transaksi</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Bayar melalui</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($pembayaran_aktif as $pembayaran_trs)
                                <tr>
                                    <td>{{$pembayaran_trs->nomor_transaksi}}</td>
                                    <td>{{$pembayaran_trs->keterangan}}</td>
                                    <td>Rp{{number_format($pembayaran_trs->besar_pembayaran)}}</td>
                                    <td>{{$pembayaran_trs->payment_code}}</td>
                                    <td>
                                        <a class="btn btn-warning btn-circle waves-effect waves-circle waves-float" onclick="copyToClipboard('{{url('payment/detail/'.$pembayaran_trs->id_pembayaran_trs)}}')">
                                            <i class="material-icons">info_outline</i>
                                        </a> 
                                        <a class="btn btn-info btn-circle waves-effect waves-circle waves-float" target="_blank" href="{{url('payment/detail/'.$pembayaran_trs->id_pembayaran_trs)}}">
                                            <i class="material-icons">attach_money</i>
                                        </a> 
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <div class="card">
                <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/generate')}}">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>TAGIHAN SISWA</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input id="checkbox_select_all_primary_table" type="checkbox" name="select_all" class="filled-in">
                                            <label for="checkbox_select_all_primary_table" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>Biaya</th>
                                        <th>-</th>
                                        <th>Semester</th>
                                        <th>Besar Tagihan</th>
                                        <th>Denda Tagihan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <h2 class="card-inside-title">
                            Akan dibayar melalui
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="payment_channel" required="">
                                    <option value="" disabled selected >-- Pilih Metode Pembayaran --</option>
                                    @foreach($grup_payment_channel as $name => $data_payment_channel)
                                    <optgroup label="{{$name}}">
                                        @foreach($data_payment_channel as $data)
                                            <option value="{{$data->payment_code}}">{{$data->payment_name}} ({{$data->payment_description}})</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn bg-blue waves-effect" type="submit"><span>Bayarkan yang dicentang</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>

    var modul_url       = 'keuangan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'tagihan/datatables';

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
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function (data, type, full, meta){
                    return '<input id="checkbox-' + data.id + '" type="checkbox" name="id_tagihan_biaya[]" class="filled-in" value="' + data.id + '">'+
                    '<label for="checkbox-' + data.id + '"></label>'; 

                }
            },
            { data: 'nm_biaya', name: 'nm_biaya' },
            { data: 'jenis_biaya', name: 'jenis_biaya'},
            { data: 'semester', name: 'semester', searchable: false, orderable: false },
            { data: 'besar_biaya', name: 'besar_biaya'},
            { data: 'denda_biaya', name: 'denda_biaya'},
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
<script type="text/javascript">
    $(document).ready(function() {        
        /* Select All Checkbox */
        $('#checkbox_select_all_primary_table').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({ 'search': 'applied' }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });

    function copyToClipboard(link) {
        var $input = $("<input>");
        $input.val(link).appendTo('body').select();
        document.execCommand('copy');
        $input.remove();

        vex.dialog.alert('Link copied!');
    }
</script>