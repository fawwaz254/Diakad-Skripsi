<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#perawatan-sarpras/pengadaan-sarpras/add')}}"><i class="material-icons">note_add</i><span>Tambah Pengadaan Sarpras</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header">
                    <h2>DATA PENGADAAN BARANG/SARPRAS</h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="prioritas" class="active">
                            <a href="#tinggi" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">priority_high</i> TINGGI
                            </a>
                        </li>
                        <li role="prioritas">
                            <a href="#sedang" data-toggle="tab">
                                <i class="material-icons">vertical_align_center</i> SEDANG
                            </a>
                        </li>
                        <li role="prioritas">
                            <a href="#rendah" data-toggle="tab">
                                <i class="material-icons">low_priority</i> RENDAH
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tinggi">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_tinggi">
                                        <thead>
                                            <tr>
                                                <th>No. </th>
                                                <th>Semester</th>
                                                <th>Unit Kerja</th>
                                                <th>Jenis Buku/Alat</th>
                                                <th>Buku/Alat</th>
                                                <th>Ruangan</th>
                                                <th>Inventaris</th>
                                                <th>Harga Satuan</th>
                                                <th>Qty</th>
                                                <th>Tgl Pengadaan</th>
                                                <th>Kepala Unit</th>
                                                <th>Kepala Sarpras</th>
                                                <th>Supplier</th>
                                                <th>Kepala Sarpras Apv Supplier</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> 
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sedang">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_sedang">
                                        <thead>
                                            <tr>
                                                <th>No. </th>
                                                <th>Semester</th>
                                                <th>Unit Kerja</th>
                                                <th>Jenis Buku/Alat</th>
                                                <th>Buku/Alat</th>
                                                <th>Ruangan</th>
                                                <th>Inventaris</th>
                                                <th>Harga Satuan</th>
                                                <th>Qty</th>
                                                <th>Tgl Pengadaan</th>
                                                <th>Kepala Unit</th>
                                                <th>Kepala Sarpras</th>
                                                <th>Supplier</th>
                                                <th>Kepala Sarpras Apv Supplier</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div> 
                            </div>    
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="rendah">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_rendah">
                                        <thead>
                                            <tr>
                                                <th>No. </th>
                                                <th>Semester</th>
                                                <th>Unit Kerja</th>
                                                <th>Jenis Buku/Alat</th>
                                                <th>Buku/Alat</th>
                                                <th>Ruangan</th>
                                                <th>Inventaris</th>
                                                <th>Harga Satuan</th>
                                                <th>Qty</th>
                                                <th>Tgl Pengadaan</th>
                                                <th>Kepala Unit</th>
                                                <th>Kepala Sarpras</th>
                                                <th>Supplier</th>
                                                <th>Kepala Sarpras Apv Supplier</th>
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
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url               = 'perawatan-sarpras';
    var datatable_url_tinggi    = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengadaan-sarpras/datatables-tinggi';
    var datatable_url_sedang    = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengadaan-sarpras/datatables-sedang';
    var datatable_url_rendah    = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengadaan-sarpras/datatables-rendah';
    var kepala_unit_url         = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-pengadaan/approve-kepala-unit';
    var kepala_sarpas_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-pengadaan/approve-kepala-sarpras';
    var kepala_sarpas_fix_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-pengadaan/approve-kepala-sarpras-fix';
    var supplier_url            = role_url + '#' + modul_url + '/' + 'pengadaan-sarpras/view-detail-supplier';

    var primary_table_tinggi = $('#primary_table_tinggi').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url_tinggi,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'semester', name: 'semester' },
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'nm_jenis_buku_alat', name: 'jenis_buku_alat.nm_jenis_buku_alat' },
            { data: 'nm_buku_alat', name: 'buku_alat.nm_buku_alat' },
            { data: 'nm_ruangan', name: 'ruangan.nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'inventaris_ruangan.nm_inventaris_ruangan' },
            { data: 'harga_satuan_rpb_sarpras', name: 'harga_satuan_rpb_sarpras' },
            { data: 'qty_rpb_sarpras', name: 'rpb_sarpras.qty_rpb_sarpras' },
            { data: 'tgl_rpb_sarpras', name: 'tgl_rpb_sarpras' },
            { data: 'nm_kepala_unit', name: 'nm_kepala_unit', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_unit == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaUnitAction(\''+ kepala_unit_url +'\', this)" data-idunitkerja="'+  data.id_unit_kerja +'" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_unit +'</a>';
                    }
                }
            },
            { data: 'nm_kepala_sarpras', name: 'nm_kepala_sarpras', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_sarpras == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaSarprasAction(\''+ kepala_sarpas_url +'\', this)" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_sarpras +'</a>';
                    }
                }
            },
            { data: 'supplier', name: 'supplier', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ supplier_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> ';
                }
            },
            { data: 'nm_kepala_sarpras_approve', name: 'nm_kepala_sarpras_approve', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_sarpras_approve == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaSarprasFixAction(\''+ kepala_sarpas_fix_url +'\', this)" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_sarpras_approve +'</a>';
                    }
                }
            }
        ]
    });

    primary_table_tinggi.on( 'draw', function () {
        primary_table_tinggi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    var primary_table_sedang = $('#primary_table_sedang').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url_sedang,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'semester', name: 'semester' },
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'nm_jenis_buku_alat', name: 'jenis_buku_alat.nm_jenis_buku_alat' },
            { data: 'nm_buku_alat', name: 'buku_alat.nm_buku_alat' },
            { data: 'nm_ruangan', name: 'ruangan.nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'inventaris_ruangan.nm_inventaris_ruangan' },
            { data: 'harga_satuan_rpb_sarpras', name: 'harga_satuan_rpb_sarpras' },
            { data: 'qty_rpb_sarpras', name: 'rpb_sarpras.qty_rpb_sarpras' },
            { data: 'tgl_rpb_sarpras', name: 'tgl_rpb_sarpras' },
            { data: 'nm_kepala_unit', name: 'nm_kepala_unit', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_unit == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaUnitAction(\''+ kepala_unit_url +'\', this)" data-idunitkerja="'+  data.id_unit_kerja +'" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_unit +'</a>';
                    }
                }
            },
            { data: 'nm_kepala_sarpras', name: 'nm_kepala_sarpras', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_sarpras == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaSarprasAction(\''+ kepala_sarpas_url +'\', this)" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_sarpras +'</a>';
                    }
                }
            },
            { data: 'supplier', name: 'supplier', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ supplier_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> ';
                }
            },
            { data: 'nm_kepala_sarpras_approve', name: 'nm_kepala_sarpras_approve', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_sarpras_approve == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaSarprasFixAction(\''+ kepala_sarpas_fix_url +'\', this)" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_sarpras_approve +'</a>';
                    }
                }
            }
        ]
    });

    primary_table_sedang.on( 'draw', function () {
        primary_table_sedang.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    var primary_table_rendah = $('#primary_table_rendah').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url_rendah,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'semester', name: 'semester' },
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'nm_jenis_buku_alat', name: 'jenis_buku_alat.nm_jenis_buku_alat' },
            { data: 'nm_buku_alat', name: 'buku_alat.nm_buku_alat' },
            { data: 'nm_ruangan', name: 'ruangan.nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'inventaris_ruangan.nm_inventaris_ruangan' },
            { data: 'harga_satuan_rpb_sarpras', name: 'harga_satuan_rpb_sarpras' },
            { data: 'qty_rpb_sarpras', name: 'rpb_sarpras.qty_rpb_sarpras' },
            { data: 'tgl_rpb_sarpras', name: 'tgl_rpb_sarpras' },
            { data: 'nm_kepala_unit', name: 'nm_kepala_unit', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_unit == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaUnitAction(\''+ kepala_unit_url +'\', this)" data-idunitkerja="'+  data.id_unit_kerja +'" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_unit +'</a>';
                    }
                }
            },
            { data: 'nm_kepala_sarpras', name: 'nm_kepala_sarpras', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_sarpras == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaSarprasAction(\''+ kepala_sarpas_url +'\', this)" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_sarpras +'</a>';
                    }
                }
            },
            { data: 'supplier', name: 'supplier', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ supplier_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> ';
                }
            },
            { data: 'nm_kepala_sarpras_approve', name: 'nm_kepala_sarpras_approve', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_sarpras_approve == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaSarprasFixAction(\''+ kepala_sarpas_fix_url +'\', this)" data-idrpbsarpras="'+  data.id_rpb_sarpras +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_sarpras_approve +'</a>';
                    }
                }
            }
        ]
    });

    primary_table_rendah.on( 'draw', function () {
        primary_table_rendah.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    function kepalaUnitAction(kepala_unit_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Aksi Ini Akan Otomatis Melakukan Approve Kepala Unit!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: kepala_unit_url + '/' + item.attr('data-idrpbsarpras') + '/' + item.attr('data-idunitkerja'),
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
                            primary_table.ajax.reload(null, false);
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

    function kepalaSarprasAction(kepala_keuangan_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Aksi Ini Akan Otomatis Melakukan Approve Kepala Sarpras!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: kepala_sarpas_url + '/' + item.attr('data-idrpbsarpras') + '/1',
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
                            primary_table.ajax.reload(null, false);
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

    function kepalaSarprasFixAction(kepala_keuangan_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Aksi Ini Akan Otomatis Melakukan Approve Supplier Oleh Kepala Sarpras!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: kepala_sarpas_fix_url + '/' + item.attr('data-idrpbsarpras') + '/1',
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
                            primary_table.ajax.reload(null, false);
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