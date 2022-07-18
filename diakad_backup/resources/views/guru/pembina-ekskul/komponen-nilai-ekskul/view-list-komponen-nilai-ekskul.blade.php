<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pembina-ekskul/komponen-nilai-ekskul')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pembina-ekskul/komponen-nilai-ekskul/add/'.$semester->id_semester.'/'.$data_ekskul->id_ekskul)}}"><i class="material-icons">note_add</i><span>Tambah Komponen Nilai</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>KOMPONEN NILAI EKSKUL {{ $data_ekskul->nm_ekskul }} - Tahun Ajaran {{ $semester->tahun_ajaran .'-'. $semester->nm_semester }}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Komponen</th>
                                        <th>Persentase Komponen</th>
                                        <th>Urutan Komponen</th>
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
<script>
    var id_ekskul = {!! json_encode($data_ekskul->id_ekskul) !!};
    var id_semester = {!! json_encode($semester->id_semester) !!};

    var modul_url       = 'pembina-ekskul';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'komponen-nilai-ekskul/datatables/' + id_semester + '/' + id_ekskul;
    var edit_url        = role_url + '#' + modul_url + '/' + 'komponen-nilai-ekskul/edit/' + id_semester + '/' + id_ekskul;
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-komponen-nilai-ekskul/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_komponen_ekskul', name: 'nm_komponen_ekskul' },
            { data: 'persentase_komponen_ekskul', name: 'persentase_komponen_ekskul' },
            { data: 'urutan_komponen_ekskul', name: 'urutan_komponen_ekskul'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
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
</script>