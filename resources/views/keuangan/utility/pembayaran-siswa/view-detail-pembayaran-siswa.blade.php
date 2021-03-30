<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#utility/pembayaran-siswa/view-detail/'.$nis_nama_siswa_asli)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header">
                    <h2>
                        PEMBAYARAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display">
                            <tr>
                                <th colspan="2" style="text-align: center;">BIODATA SISWA</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align: center;">
                                    <img src="" style="height: 270px; width: 180px">
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Lengkap</td>
                                <td style="width: 50%">{{strtoupper($siswa->nm_pengguna)}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NIS</td>
                                <td style="width: 50%">{{$nis_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Kelas</td>
                                <td style="width: 50%">{{$siswa->nm_kelas}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Status Siswa</td>
                                <td style="width: 50%">{{$siswa->nm_status_pengguna}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Siswa</td>
                                <td style="width: 50%">{{$siswa->nomor_hp}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. Telepon Ortu</td>
                                <td style="width: 50%">{{$siswa->nomor_telp_ortu}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Ortu</td>
                                <td style="width: 50%">{{$siswa->nomor_hp_ortu}}</td>
                            </tr>
                        </table>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tagihan" data-toggle="tab" aria-expanded="true">
                                    <i class="material-icons">money_off</i> TAGIHAN SISWA
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#riwayat_bayar" data-toggle="tab">
                                    <i class="material-icons">attach_money</i> RIWAYAT BAYAR
                                </a>
                            </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tagihan">
                            <div class="block-header">
                                <h2>
                                    <!-- <a class="btn btn-info waves-effect" target="_blank" href="{{url(Request::segment(1).'/utility/pembayaran-siswa/print-pembayaran/'.$siswa->id_pengguna.'/'.now()->format('Y-m-d'))}}">
                                        <i class="material-icons">print</i><span>Cetak Pembayaran Hari ini</span>
                                    </a> -->
                                    <button class="btn btn-info waves-effect" id="print-button" data-toggle="modal" data-target="#modal-print"><i class="material-icons">print</i><span>Cetak Pembayaran Hari ini</span></button>
                                    <!-- <a class="btn btn-danger waves-effect" target="_blank" href="{{ url(Request::segment(1).'/utility/pembayaran-siswa/print-belum-terbayar/'.$siswa->id_pengguna) }}">
                                        <i class="material-icons">print</i><span>Cetak Tagihan Belum Terbayar</span>
                                    </a> -->
                                    <button class="btn btn-success waves-effect float-right" id="unpaid-bills-button" data-toggle="modal" data-target="#modal-print-unpaid-bills">
                                        <i class="material-icons">print</i><span>Cetak Tagihan Belum Terbayar</span>
                                    </button>
                                    <button class="btn btn-success waves-effect float-right" id="pay-button" onclick="paySelected()">
                                        <i class="material-icons">point_of_sale</i><span>Bayar tagihan terpilih</span>
                                    </button>
                                </h2>
                            </div>
                            <div class="body">
                                <div>
                                    <h4 class="">Total Tagihan terpilih: Rp <span id="show-total"></span></h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table_tagihan">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Biaya</th>
                                                <th>Besar Tagihan</th>
                                                <th>Besar Pembayaran</th>
                                                <th>Sisa Tagihan</th>
                                                <th>
                                                    <input id="checkbox_select_all_primary_table" type="checkbox" name="select_all" class="filled-in">
                                                    <label for="checkbox_select_all_primary_table" style="margin-bottom: -10px;"></label>
                                                </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="riwayat_bayar">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table_riwayat_bayar">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Biaya</th>
                                                <th>Besar Pembayaran</th>
                                                <th>Verifikasi Oleh</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Semester Bayar</th>
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
    </div>
</div>

<!-- START modal print -->
<div class="modal fade" id="modal-print" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">CETAK PEMBAYARAN HARI INI</h4>
            </div>
            <form id="print-unpaid" action="{{ url(Request::segment(1).'/utility/pembayaran-siswa/print-pembayaran/'.$siswa->id_pengguna.'/'.now()->format('Y-m-d')) }}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col">
                            <label for="catatan" class="form-control">Pilih Jenis</label>
                            <select class="form-control" name="type" id="jenis">
                                <option value="default">Default</option>
                                <option value="struk">Struk/Printer Thermal</option>
                            </select>
                        </div>
                        <div class="col" id="ukuran-struk" style="display: none">
                            <label for="" class="form-control">Pilih Ukuran Kertas</label>
                            <select class="form-control" name="lebar" id="lebar">
                                <option value="" selected disabled>-- Pilih ukuran --</option>
                                <option value="57">57 mm</option>
                                <option value="58">58 mm</option>
                                <option value="60">60 mm</option>
                                <option value="70">70 mm</option>
                                <option value="75">75 mm</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Cetak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END modal print -->
<!-- START modal print UNPAID BILLS -->
<div class="modal fade" id="modal-print-unpaid-bills" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">CETAK TAGIHAN BELUM TERBAYAR</h4>
            </div>
            <form id="print" action="{{ url(Request::segment(1).'/utility/pembayaran-siswa/print-belum-terbayar/'.$siswa->id_pengguna) }}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col">
                            <label for="catatan" class="form-control">Pilih Jenis</label>
                            <select class="form-control" name="type">
                                <option value="1">Seluruh tagihan selama 1 tahun ajaran</option>
                                <option value="2">Hanya tagihan hingga bulan ini</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Cetak Tagihan Belum Terbayar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END modal print -->
@include('scriptjs')
<script>

    var id_pengguna = {!! json_encode($siswa->id_pengguna) !!};
    var nis_nama_siswa = {!! json_encode($nis_nama_siswa_asli) !!};

    var modul_url                   = 'utility';
    var datatable_tagihan_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembayaran-siswa/datatables-tagihan/' + id_pengguna + '/' + nis_nama_siswa;
    var datatable_riwayat_bayar_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembayaran-siswa/datatables-riwayat-bayar/' + id_pengguna;
    var detail_tagihan_siswa_url    = role_url + '#' + modul_url + '/' + 'pembayaran-siswa/view-detail-tagihan-siswa';
    var delete_tagihan_siswa        = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-tagihan-siswa/delete';
    var delete_pembayaran_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/delete';
    var lunas_url                   = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/lunas';
    var mass_payment_url            = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa-massal';
    var print_riwayat_pembayaran_url = base_url + '/' + role_url + '/' + modul_url + '/' + "pembayaran-siswa/print-pembayaran";


    // datatable jadwal UTS
    var primary_table_tagihan = $('#primary_table_tagihan').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        ajax: {
            url: datatable_tagihan_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_biaya', name: 'nm_biaya' },
            { data: 'besar_biaya', name: 'besar_biaya'},
            { data: 'besar_pembayaran', name: 'besar_pembayaran'},
            { data: 'sisa_tagihan', name: 'sisa_tagihan'},
            { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false,
                render: function (data){
                    if(data.sisa_tagihan > 0){
                        return '<input id="checkbox-' + data.id_tagihan + '"type="checkbox" name="besar_biaya[]" value="' + data.sisa_tagihan + '" class="filled-in checkbox" onclick="checkedCb();" data-id="' + data.id_tagihan + '">' +
                        '<label for="checkbox-' + data.id_tagihan + '"></label>';
                    } else {
                        return '';
                    }
                }
            },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.sisa_tagihan > 0){
                        return '<button class="btn btn-warning waves-effect waves-float" onclick="lunasAction(\''+ lunas_url +'\', this)" data-id="'+  data.id +'">'+
                        '    <span>Lunas</span>'+
                        '</button>'+
                        '<a class="target-link btn btn-info waves-effect waves-float" href="'+ detail_tagihan_siswa_url + '/' + data.id +'/' + data.id_asli + '">'+
                        '    <span>Cicilan</span>'+
                        '</a> ';
                        // '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionTagihan(this)" data-id="'+data.id+'">'+
                        // '    <span>Hapus Tagihan</span>'+
                        // '</button>';
                    }else{
                        return '';
                    }
                }
            }
        ],
        createdRow: function( row, data, dataIndex){
            if( data.sisa_tagihan == 'Rp0'){
                $(row).css('background-color', 'hsl(171, 100%, 41%)');
            }
        }
    });

    primary_table_tagihan.on( 'draw', function () {
        primary_table_tagihan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    var primary_table_riwayat_bayar = $('#primary_table_riwayat_bayar').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        ajax: {
            url: datatable_riwayat_bayar_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_biaya', name: 'nm_biaya' },
            { data: 'besar_pembayaran', name: 'besar_pembayaran'},
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'tgl_pembayaran', name: 'tgl_pembayaran'},
            { data: 'semester_bayar', name: 'semester_bayar'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" id="delete-khusus-button" onclick="deleteActionKhusus(\''+ delete_pembayaran_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">close</i>'+
                    '</button>' +
                    '<a class="btn btn-info waves-effect" target="_blank" href="' + print_riwayat_pembayaran_url + '/' + data.id_pengguna + '/' + data.tgl_pembayaran 
                    + '"><i class="material-icons">print</i></a>';
                }
            }
        ]
    });

    primary_table_riwayat_bayar.on( 'draw', function () {
        primary_table_riwayat_bayar.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>

<script>
    function deleteActionKhusus(delete_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url + '/' + item.attr('data-id'),
                    success: function (response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                        }else if(response.status == 201){
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        }else if(response.status == 202){
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        }else if(response.status == 203){
                            vex.dialog.alert(response.message);
                            primary_table_tagihan.ajax.reload(null, false);
                            primary_table_riwayat_bayar.ajax.reload(null, false);
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    function deleteActionTagihan(delete_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_tagihan_siswa + '/' + item.attr('data-id'),
                    success: function (response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                        }else if(response.status == 201){
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        }else if(response.status == 202){
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        }else if(response.status == 203){
                            vex.dialog.alert(response.message);
                            primary_table_tagihan.ajax.reload(null, false);
                            primary_table_riwayat_bayar.ajax.reload(null, false);
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    function lunasAction(lunas_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "Pembayaran Tagihan Langsung LUNAS!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, LUNAS!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: lunas_url + '/' + item.attr('data-id'),
                    success: function (response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                        }else if(response.status == 201){
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        }else if(response.status == 202){
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        }else if(response.status == 203){
                            vex.dialog.alert(response.message);
                            primary_table_tagihan.ajax.reload(null, false);
                            primary_table_riwayat_bayar.ajax.reload(null, false);
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    $('#checkbox_select_all_primary_table').change(function() {
        var select_all_checked = this.checked;
        var rows = primary_table_tagihan.rows({ 'search': 'applied' }).nodes();

        $('input[type="checkbox"]', rows).prop('checked', this.checked);
        
        var sum = calc();
        if(sum > 0){
            var sumFormatted = String(sum).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('#show-total').text(sumFormatted);
            $('#pay-button').removeAttr('disabled', 'disabled');
        } else {
            $('#pay-button').attr('disabled', 'disabled');
            $('#show-total').text('0');
        }
    });

    $('document').ready(function(){
        var sum = 0;
        $('#show-total').text(sum);
        $('#pay-button').attr('disabled', 'disabled');
    });

    function calc(){
        // Query for only the checked checkboxes and put the result in an array
        var checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked.checkbox"));

        var arrayChecked = checked.map(function(a){
            return a.value;
        });
        var sum = 0;
        if(arrayChecked.length > 0){
            sum = arrayChecked.reduce(function(a, b){
                return parseFloat(a) + parseFloat(b);
            });
        }
        
        return sum;
    };
    
    function checkedCb(){
        var sum = calc();
        var sumFormatted = String(sum).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $('#show-total').text(sumFormatted);
        
        if(sum > 0){
            $('#pay-button').removeAttr('disabled', 'disabled');
        } else {
            $('#pay-button').attr('disabled', 'disabled');
        }
    }

    function paySelected(){
        var checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked.checkbox"));

        var valueObj = checked.map(function(a){
            var dataSet = {'nilai' : a.value, 'id': a.dataset.id};
            return dataSet;
        });

        var sum = calc();
        var sumFormatted = String(sum).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        $('#pay-button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "Total Pembayaran Tagihan = Rp " + sumFormatted,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, BAYAR!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    method: "POST",
                    url: mass_payment_url,
                    data: { data_pembayaran: valueObj},
                    success: function (response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                        }else if(response.status == 201){
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        }else if(response.status == 202){
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        }else if(response.status == 203){
                            vex.dialog.alert(response.message);
                            primary_table_tagihan.ajax.reload(null, false);
                            primary_table_riwayat_bayar.ajax.reload(null, false);
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        var sum = 0;
                        $('#show-total').text(sum);
                    }
                });
            } else {
                $('#pay-button').removeAttr('disabled', 'disabled');
            }
        });
    }

    $(document).ready(function () {
        $("#jenis").change(function () {
            if($("#jenis option:selected").val() == 'struk') {
                $('#ukuran-struk').show();
                $('#lebar').attr('required', true);
            } else {
                $('#lebar').attr('required', false);
                $('#ukuran-struk').hide();
            }
        });
    });
        
    function printAction(){
        console.log();
    }

</script>
