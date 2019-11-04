<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#aktivitas-semester/input-nilai/')}}">
                <i class="material-icons">backspace</i><span>Kembali</span>
            </a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-light-5green">
                        <h2>Input Nilai</h2>
                    </div>
                    <div class="body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#setting-komponen-nilai" data-toggle="tab" aria-expanded="true">
                                    <i class="material-icons">create</i> Setting Komponen Nilai
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#input-nilai" data-toggle="tab">
                                    <i class="material-icons">edit</i> Input Nilai
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="setting-komponen-nilai">
                               <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                                {{csrf_field()}}
                                                <div class="header bg-light-green">
                                                    <h2>KOMPONEN NILAI MAPEL {{$data_kelas->nm_mata_pelajaran . " (" . $data_kelas->kd_mata_pelajaran . ") Kelas " . $data_kelas->nm_kelas}}</h2>
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
                            <div role="tabpanel" class="tab-pane fade" id="input-nilai">
                                <form id="form-validation1" method="POST" action="">
                                {{csrf_field()}}
                                    
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <button class="btn btn-block bg-red waves-effect" type="submit" name="cari"><i class="material-icons">save</i><span>Save</span></button>
                                        </div>
                                    </div>
                                </form>
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
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kelas_mp = {!! json_encode($data_kelas->id_kelas_mp) !!};
    var id_pengguna = {!! json_encode($id_pengguna) !!}
    var id_semester = {!! json_encode($id_semester) !!}

    var modul_url       = 'aktivitas-semester';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-nilai/datatables/' + id_kelas_mp;
    var edit_url        = role_url + '#' + modul_url + '/' + 'input-nilai/edit/' + id_kelas_mp + '/' + id_pengguna + '/' + id_semester;
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-komponen-nilai/delete';

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
            { data: 'nm_komponen_mp', name: 'nm_komponen_mp' },
            { data: 'persentase_komponen_mp', name: 'persentase_komponen_mp' },
            { data: 'urutan_komponen_mp', name: 'urutan_komponen_mp'},
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