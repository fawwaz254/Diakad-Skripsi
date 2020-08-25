<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
            href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3))}}">
                <i class="material-icons">backspace</i><span>Kembali</span></a>
            <a class="btn bg-blue waves-effect target-link"
                href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/'.Request::segment(4).'/'.$item->id_kegiatan_harian_pertanyaan.'/add')}}">
                <i class="material-icons">note_add</i><span>Tambah Jawaban</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA JAWABAN DARI PERTANYAAN <br><b>{{$item->isi_pertanyaan}}</b></h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jawaban</th>
                                    <th>Bobot</th>
                                    <th>Warna</th>
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
    var menu_url        = '{{Request::segment(3)}}';
    var page_1_url      = '{{Request::segment(4)}}';
    var id_1            = '{{$item->id_kegiatan_harian_pertanyaan}}';

    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/' + page_1_url + '/' + id_1 + '/datatables';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/' + page_1_url + '/' + id_1 + '/action/delete';
    var edit_url        = role_url + '#' + modul_url + '/' + menu_url + '/' + page_1_url + '/' + id_1 + '/edit';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: 'show_order', searchable: false },
            { data: 'isi_jawaban' },
            { data: 'bobot_jawaban' },
            { data: 'warna_keadaan' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteItemAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            }
        ],
        order: [[0, 'asc']]
    });

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
                        id_kegiatan_harian_jawaban : item.attr('data-id')
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
</script>