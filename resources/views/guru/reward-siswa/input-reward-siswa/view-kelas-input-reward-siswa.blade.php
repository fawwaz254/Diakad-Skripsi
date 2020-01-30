<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#reward-siswa/input-reward-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>KELAS {{$data_kelas->nm_kelas}}<br>
                        SEMESTER {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" style="width:100%;" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Beri Reward</th>
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
    var id_kelas = {!! json_encode($data_kelas->id_kelas) !!};

    var modul_url       = 'reward-siswa';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-reward-siswa/datatables' + '/'+ id_kelas;
    var add_url        = role_url + '#' + modul_url + '/' + 'input-reward-siswa/add';

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
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ add_url + '/' + data.id+ '">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a>';
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