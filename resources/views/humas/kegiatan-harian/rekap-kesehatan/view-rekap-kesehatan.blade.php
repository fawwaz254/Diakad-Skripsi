<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA PENGISIAN FORM KESEHATAN TENDIK/GURU</h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">
                        Status
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select class="form-control show-tick" name="status" onchange="filterAction()">
                                <option value="">Semua Status</option>
                                <option value="1">Normal</option>
                                <option value="2">Warning</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Disubmit oleh</th>
                                    <th>Tanggal Mengisi</th>
                                    <th>Status</th>
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
    var modul_url       = '{{Request::segment(2)}}';
    var menu_url       = '{{Request::segment(3)}}';

    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables?is_tendik_guru=1';
    var detail_url      = role_url + '#' + modul_url + '/' + menu_url + '/detail/form';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/action/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            data: function(params){
                params.status = $('select[name=status]').val();
            },
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'pengguna_pengisi.nm_pengguna' },
            { data: 'tgl_pengisian' },
            { data: 'status', searchable: false, orderable: false,
                render: function(data){
                    return '<h4><span class="label" style="background-color: #'+data.warna_keadaan+';">'+data.status+'</span></h4>';
                }
            },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">remove_red_eye</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteItemAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    function deleteItemAction(delete_url, element){
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
                    url: delete_url,
                    data: {
                        id_pengisian_kegiatan_harian : item.attr('data-id')
                    },
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

    function filterAction(){
        primary_table.ajax.reload(null, false);
    }
</script>