<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{Carbon\Carbon::now('Asia/Jakarta')->format('d M Y')}}</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        List Question
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{url('guru#e-learning-soal/soal/new')}}">Adding new question</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pembuat</th>
                                    <th>Pertanyaan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
</div>
@include('scriptjs')
<script>
  var modul_url       = '{{Request::segment(2)}}';
  var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'soal/table';
  var detail_url      =  role_url + '#' + modul_url + '/' + 'soal';
  var delete_url      =  role_url + '/' + modul_url + '/' + 'soal';


    
        var primary_table = $('#primary_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: datatable_url,
                type: 'POST'
            },
            columns: [
                { data: null, searchable: false, orderable: false },
                { data: 'pengguna.nm_pengguna' },
                { data: 'text', name: 'text', orderable: false },
                { data: 'action', name: 'action', searchable: false, orderable: false,
                    render: function(data) {
                        return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/edit/' +  data.id +'">'+
                            '    <i class="material-icons">mode_edit</i>'+
                            '</a>'+
                            '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/detail/' +  data.id +'">'+
                            '    <i class="material-icons">reorder</i>'+
                            '</a>'+
                            '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="'+data.id+'" onclick="actionDelete(this)">'+
                            '    <i class="material-icons">delete</i>'+
                            '</button>';
                    }
                }
            ]
        });

        primary_table.on( 'draw', function () {
            primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                var start = this.page.info().page * this.page.info().length;
                cell.innerHTML = i + 1;
            } );
        } ).draw();
   

    
//     function actionDelete(element){
//        var item = $(element);
//        $('button').attr('disabled', 'disabled');

//        swal({
//            title: "Are you sure?",
//            text: "For Reject this",
//            showCancelButton: true,
//            confirmButtonColor: "#DD6B55",
//            confirmButtonText: "Yes, reject it!",
//            cancelButtonText: "No, cancel!",
//            closeOnConfirm: true,
//            closeOnCancel: true,
//         //    idnya:item.attr('data-id'),
//        }, function (result) {
//            if (result) {
//         //   alert(detail_url + '/delete/' + item.attr('data-id'))
//                $.ajax({
//                    type: "POST",
//                    url: delete_url + '/delete/' + item.attr('data-id'),
//                    data : {keterangan:item.attr('data-id')},
//                    success: function (response) {
//                        if(response.status == 200){
//                            vex.dialog.alert(response.message);
//                        }else if(response.status == 201){
//                            vex.dialog.alert(response.message);
//                            window.location.href = response.link;
//                        }else if(response.status == 202){
//                            vex.dialog.alert(response.message);
//                            loadURI(response.path);
//                        }else if(response.status == 203){
//                            vex.dialog.alert(response.message);
//                            if(response.from == 'prestasi'){
//                                primary_table.ajax.reload(null, false);
//                            }
//                            else if(respone.from == 'kegiatan'){
//                             primary_table2.ajax.reload(null, false);
//                            }
//                            else{
//                                primary_table3.ajax.reload(null, false);
//                            }
//                        }else if(response.status == 300){
//                            vex.dialog.alert(response.message);
//                        }
//                    },
//                    complete: function() {
//                        $('button').removeAttr('disabled', 'disabled');
//                    }
//                });
//            }else{
//                alert("Alasan Ditolak Harus Diisi");
//                $('button').removeAttr('disabled', 'disabled');
//                return false
//            }
//        });
//    }




    function actionDelete(element){
        var item = $(element);
        item.prop('disabled', true);
        
        var url = delete_url + '/delete' ;
        // var url = '{{url('organizer/question/delete')}}';
        vex.dialog.confirm({
            message: 'Are you sure to delete this item?',
            callback: function (value) {
                // alert(url)
                if(value){
                    // alert(url);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data:{
                            id_soal: item.attr('data-id')
                        },
                        success: function (data) {
                            vex.dialog.alert(data.message);
                        setTimeout(() => {
                            // $('.primary_table').DataTable().ajax.reload(null, false);
                            // primary_table.ajax.reload(null, false);
                            primary_table.ajax.reload(null, false);
                            //    location.reload();
                        }, 2000);
                        
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                }else{
                    item.prop('disabled', false);
                }
            }
        })
    }
</script>