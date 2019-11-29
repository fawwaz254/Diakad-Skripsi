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
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
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
                                    <i class="material-icons">money_off</i> TAGIHAN
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
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_tagihan">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Biaya Sekolah</th>
                                                <th>Jalur</th>
                                                <th>Nama Biaya</th>
                                                <th>Jenis Biaya</th>
                                                <th>Besar Tagihan</th>
                                                <th>Denda Tagihan</th>
                                                <th>Besar Pembayaran</th>
                                                <th>Sisa Tagihan</th>
                                                <th>Keterangan</th>
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
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_riwayat_bayar">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Biaya Sekolah</th>
                                                <th>Jalur</th>
                                                <th>Nama Biaya</th>
                                                <th>Jenis Biaya</th>
                                                <th>Besar Tagihan</th>
                                                <th>Besar Denda</th>
                                                <th>Besar Pembayaran</th>
                                                <th>Staff Keuangan</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Semester Bayar</th>
                                                <th>Via Bank</th>
                                                <th>Nomor Ref Bank</th>
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
@include('scriptjs')
<script>

    var id_pengguna = {!! json_encode($siswa->id_pengguna) !!};
    var nis_nama_siswa = {!! json_encode($nis_nama_siswa_asli) !!};

    var modul_url                   = 'utility';
    var datatable_tagihan_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembayaran-siswa/datatables-tagihan/' + id_pengguna + '/' + nis_nama_siswa;
    var datatable_riwayat_bayar_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembayaran-siswa/datatables-riwayat-bayar/' + id_pengguna;
    var detail_tagihan_siswa_url    = role_url + '#' + modul_url + '/' + 'pembayaran-siswa/view-detail-tagihan-siswa';
    var delete_pembayaran_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/delete';
    var lunas_url                   = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/lunas';


    // datatable jadwal UTS
    var primary_table_tagihan = $('#primary_table_tagihan').DataTable({
        processing: true,
        serverSide: true,
responsive: true,
        ajax: {
            url: datatable_tagihan_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'biaya_sekolah', name: 'biaya_sekolah' },
            { data: 'nm_jalur', name: 'nm_jalur'},
            { data: 'nm_biaya', name: 'nm_biaya' },
            { data: 'jenis_biaya', name: 'jenis_biaya'},
            { data: 'besar_biaya', name: 'besar_biaya'},
            { data: 'denda_biaya', name: 'denda_biaya'},
            { data: 'besar_pembayaran', name: 'besar_pembayaran'},
            { data: 'sisa_tagihan', name: 'sisa_tagihan'},
            { data: 'keterangan', name: 'keterangan'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_tagihan_siswa_url + '/' + data.id +'/' + data.id_asli + '">'+
                    '    <i class="material-icons">attach_money</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="lunasAction(\''+ lunas_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">money</i>'+
                    '</button>';
                }
            }
        ]
    });

    primary_table_tagihan.on( 'draw', function () {
        primary_table_tagihan.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    // datatable jadwal UAS
    var primary_table_riwayat_bayar = $('#primary_table_riwayat_bayar').DataTable({
        processing: true,
        serverSide: true,
responsive: true,
        ajax: {
            url: datatable_riwayat_bayar_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'biaya_sekolah', name: 'biaya_sekolah' },
            { data: 'nm_jalur', name: 'nm_jalur'},
            { data: 'nm_biaya', name: 'nm_biaya' },
            { data: 'jenis_biaya', name: 'jenis_biaya'},
            { data: 'besar_biaya', name: 'besar_biaya'},
            { data: 'denda_biaya', name: 'denda_biaya'},
            { data: 'besar_pembayaran', name: 'besar_pembayaran'},
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'tgl_pembayaran', name: 'tgl_pembayaran'},
            { data: 'semester_bayar', name: 'semester_bayar'},
            { data: 'nm_bank', name: 'nm_bank'},
            { data: 'nomor_transaksi', name: 'nomor_transaksi'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionKhusus(\''+ delete_pembayaran_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">money_off</i>'+
                    '</button>';
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
</script>
