<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <a type="button" class="btn btn-success" style="margin-bottom: 15px" href="{{url('guru#e-learning-soal/paket-soal/manage')}}">
                <i class="material-icons">add_box</i>
                <span>Tambah Paket Soal</span>
            </a> 
            <div class="card">
                <div class="header">
                    <h2>
                        List Paket Soal
                    </h2>
                    {{-- <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{url('guru#e-learning-soal/paket-soal/manage')}}">Adding new package</a></li>
                            </ul>
                        </li>
                    </ul> --}}
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Total Question</th>
                                    <th>Total Answer</th>
                                    <th>Nilai Jawaban</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Akhir</th>
                                    <th>Durasi Pengerjaan</th>
                                    <th>Status</th>
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
<script>
     var modul_url       = '{{Request::segment(2)}}';
     var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/table';
     var detail_url     =  role_url + '#' + modul_url + '/' + 'paket-soal';
     var delete_url     = role_url + '/' + modul_url + '/' + 'paket-soal/delete';

  
        var primary_table = $('#primary_table').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: datatable_url,
                type: 'POST'
            },
            columns: [
                { data: null, searchable: false, orderable: false },
                { data: 'text', name: 'text'},
                { data: 'kelas.nm_kelas' },
                { data: 'total_question', name: 'total_question', searchable: false, orderable: false },
                { data: 'total_answer', name: 'total_answer', searchable: false, orderable: false },
                { data: 'nilai'},{ data: 'waktu_mulai'},{ data: 'waktu_selesai'},{ data: 'waktu_pengerjaan'},{ data: 'waktu_pengerjaan'},
                { data: 'action', name: 'action', searchable: false, orderable: false,
                    render: function(data) {
                        return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' + detail_url +'/detail/' + data.id +'">' +
                            '    <i class="material-icons">library_add</i>'+
                            '</a>'+
                            '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="{{url('organizer/question/package/manage')}}/'+ data.id +'">'+
                            '    <i class="material-icons">mode_edit</i>'+
                            '</a>'+
                            '<a type="button" class="btn btn-orange btn-circle waves-effect waves-circle waves-float" href="' + detail_url +'/test/' + data.id +'">'+
                            '    T'+
                            '</a>'+
                            '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="'+data.id+'" onclick="actionDelete(this)">'+
                            '    <i class="material-icons">delete</i>'+
                            '</button>';
                    }
                }
            ],
            order: [[2, 'asc'], [1, 'asc']]
        });

        primary_table.on( 'draw', function () {
            primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                var start = this.page.info().page * this.page.info().length;
                cell.innerHTML = i + 1;
            } );
        } ).draw();
 

    function actionDelete(element){
        var item = $(element);
        item.prop('disabled', true);
        var url = delete_url;
        vex.dialog.confirm({
            message: 'Are you sure to delete this item?',
            callback: function (value) {
                if(value){
                    $.ajax({
                        type: "POST",
                        url: url,
                        data:{
                            question_package_id: item.attr('data-id')
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