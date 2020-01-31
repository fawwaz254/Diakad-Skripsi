<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header">
                    <h2>DATA KOMPLAIN SARPRAS</h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#belum" data-toggle="tab" aria-expanded="true">
                                    <i class="material-icons">cancel_presentation</i> BELUM DITANGGAPI
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#sudah" data-toggle="tab">
                                    <i class="material-icons">done_all</i> SUDAH DITANGGAPI
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
                                                <th>User Komplain</th>
                                                <th>Keterangan Komplain</th>
                                                <th>Tgl Komplain</th>
                                                <th>Status Urgent</th>
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
                                                <th>User Komplain</th>
                                                <th>Keterangan Komplain</th>
                                                <th>Tgl Komplain</th>
                                                <th>Status Urgent</th>
                                                <th>User Perbaikan</th>
                                                <th>Tgl Perbaikan</th>
                                                <th>Keterangan Perbaikan</th>
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
    var modul_url               = 'komplain-sarpras';
    var datatable_url_belum     = base_url + '/' + role_url + '/' + modul_url + '/' + 'tanggapi-komplain/datatables-belum';
    var datatable_url_sudah     = base_url + '/' + role_url + '/' + modul_url + '/' + 'tanggapi-komplain/datatables-sudah';
    var edit_url                = role_url + '#' + modul_url + '/' + 'tanggapi-komplain/edit';
    var delete_url              = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-tanggapi-komplain/delete';

    var primary_table_belum = $('#primary_table_belum').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url_belum,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_ruangan', name: 'nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'nm_inventaris_ruangan' },
            { data: 'nm_buku_alat', name: 'nm_buku_alat' },
            { data: 'user_komplain', name: 'user_komplain'},
            { data: 'keterangan_komplain', name: 'keterangan_komplain'},
            { data: 'tgl_komplain', name: 'created_at' },
            { data: 'is_urgent', name: 'is_urgent'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">done_all</i>'+
                    '</a>'+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionKomplain(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
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
            { data: 'nm_ruangan', name: 'nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'nm_inventaris_ruangan' },
            { data: 'nm_buku_alat', name: 'nm_buku_alat' },
            { data: 'user_komplain', name: 'user_komplain'},
            { data: 'keterangan_komplain', name: 'keterangan_komplain'},
            { data: 'tgl_komplain', name: 'created_at' },
            { data: 'is_urgent', name: 'is_urgent'},
            { data: 'nm_pengguna_guru_sarpras', name: 'nm_pengguna_guru_sarpras'},
            { data: 'tgl_perbaikan', name: 'tgl_perbaikan'},
            { data: 'keterangan_perbaikan', name: 'keterangan_perbaikan'}
        ]
    });

    primary_table_sudah.on( 'draw', function () {
        primary_table_sudah.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    function deleteActionKomplain(delete_url, element){
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