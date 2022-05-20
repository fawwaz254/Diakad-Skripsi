{{-- <body class="theme-red">
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>DASHBOARD | {{Carbon\Carbon::now('Asia/Jakarta')->format('d M Y')}}</h2>
        </div> --}}
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            List Kategori
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="{{url('guru#e-learning-soal/kategori-soal/manage')}}">Tambah Kategori Baru</a></li>
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
                                        <th>Nama Kategori</th>
                                        <th>Benar</th>
                                        <th>Salah</th>
                                        <th>Kosong</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                {{-- <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Benar</th>
                                        <th>Salah</th>
                                        <th>Kosong</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot> --}}
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Examples -->
    {{-- </div>
</section>
</body> --}}
@include('scriptjs')
<script>
  var modul_url       = '{{Request::segment(2)}}';
  var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'kategori-soal/table';
  var detail_url      =  role_url + '#' + modul_url + '/' + 'kategori-soal/manage';
//   var delete_url      = role_url + '#' + modul_url + '/' + 'kategori-soal/delete';

    $(function(){
        var primary_table = $('#primary_table').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: datatable_url,
                type: 'POST'
            },
            columns: [
                { data: null, searchable: false, orderable: false },
                { data: 'nama', name: 'nama'},
                { data: 'nilai_benar', name: 'nilai_benar', orderable: false, searchable: false },
                { data: 'nilai_salah', name: 'nilai_salah', orderable: false, searchable: false },
                { data: 'nilai_kosong', name: 'nilai_kosong', orderable: false, searchable: false },
                { data: 'action', name: 'action', searchable: false, orderable: false,
                    render: function(data) {
                        return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' +  data.id +'">'+
                            '    <i class="material-icons">mode_edit</i>'+
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
    });

    function actionDelete(element){
        var item = $(element);
        item.prop('disabled', true);
        var url = '{{url('e-learning-soal/kategori-soal/delete')}}';
        vex.dialog.confirm({
            message: 'Apakah yakin mau menghapus kategori ini?',
            callback: function (value) {
                if(value){
                    $.ajax({
                        type: "POST",
                        url: url,
                        data:{
                            id_kategori_soal: item.attr('data-id')
                        },
                        success: function (data) {
                          alert(data)
                            // location.reload();
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