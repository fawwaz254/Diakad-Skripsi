<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#perawatan-sarpras/input-perawatan-rutin/add')}}"><i class="material-icons">note_add</i><span>Tambah Perawatan Rutin</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header">
                    <h2>DATA PERAWATAN RUTIN</h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#belum" data-toggle="tab" aria-expanded="true">
                                    <i class="material-icons">cancel_presentation</i> RENCANA PERAWATAN
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#sudah" data-toggle="tab">
                                    <i class="material-icons">done_all</i> SUDAH PERAWATAN
                                </a>
                            </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table_belum">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Ruangan</th>
                                                <th>Nama Inventaris</th>
                                                <th>Nama Buku/Alat</th>
                                                <th>Tanggal Perawatan</th>
                                                <th>Keterangan Perawatan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sudah">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table_sudah">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Ruangan</th>
                                                <th>Nama Inventaris</th>
                                                <th>Nama Buku/Alat</th>
                                                <th>Tanggal Perawatan</th>
                                                <th>Keterangan Perawatan</th>
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
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url               = 'perawatan-sarpras';
    var datatable_url_belum     = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-perawatan-rutin/datatables-belum';
    var datatable_url_sudah     = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-perawatan-rutin/datatables-sudah';
    var edit_url                = role_url + '#' + modul_url + '/' + 'input-perawatan-rutin/edit';
    var delete_url              = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-perawatan-rutin/delete';

    var primary_table_belum = $('#primary_table_belum').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url_belum,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_ruangan', name: 'ruangan.nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'inventaris_ruangan.nm_inventaris_ruangan' },
            { data: 'nm_buku_alat', name: 'buku_alat.nm_buku_alat' },
            { data: 'tgl_perawatan', name: 'perawatan_sarpras.tgl_perawatan'},
            { data: 'keterangan_perawatan', name: 'perawatan_sarpras.keterangan_perawatan'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionPerawatan(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            }
        ]
    });

    primary_table_belum.on( 'draw', function () {
        primary_table_belum.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    var primary_table_sudah = $('#primary_table_sudah').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url_sudah,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_ruangan', name: 'ruangan.nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'inventaris_ruangan.nm_inventaris_ruangan' },
            { data: 'nm_buku_alat', name: 'buku_alat.nm_buku_alat' },
            { data: 'tgl_perawatan', name: 'perawatan_sarpras.tgl_perawatan'},
            { data: 'keterangan_perawatan', name: 'perawatan_sarpras.keterangan_perawatan'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionPerawatan(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            }
        ]
    });

    primary_table_sudah.on( 'draw', function () {
        primary_table_sudah.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    function deleteActionPerawatan(delete_url, element){
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
                            primary_table_belum.ajax.reload(null, false);
                            primary_table_sudah.ajax.reload(null, false);
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