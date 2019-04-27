<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#wali-kelas/home-visit/add')}}"><i class="material-icons">note_add</i><span>Tambah Home Visit</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-orange">
                        <h2>DATA HOME VISIT KELAS {{$wali_kelas->nm_kelas}}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Semester</th>
                                        <th>Nama Wali Kelas</th>
                                        <th>Nama Siswa</th>
                                        <th>Nomor HP Wali Murid</th>
                                        <th>Alamat Wali Murid</th>
                                        <th>Rangkuman Home Visit</th>
                                        <th>Validasi Kesiswaan</th>
                                        <th>Guru Kesiswaan</th>
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
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'wali-kelas';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'home-visit/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'home-visit/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-home-visit/delete';

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
            { data: 'semester', name: 'semester' },
            { data: 'nm_guru', name: 'nm_guru' },
            { data: 'nm_siswa', name: 'nm_siswa' },
            { data: 'nomor_hp_wali_murid', name: 'nomor_hp_wali_murid' },
            { data: 'alamat_wali_murid', name: 'alamat_wali_murid' },
            { data: 'rangkuman_home_visit', name: 'rangkuman_home_visit' },
            { data: 'is_berkas_lengkap', name: 'is_berkas_lengkap' },
            { data: 'nm_guru_kesiswaan', name: 'nm_guru_kesiswaan' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.is_berkas_lengkap == 1) {
                        return '<a>Sudah Validasi Kesiswaan</a>';
                    }
                    else {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                        '    <i class="material-icons">edit</i>'+
                        '</a>'+
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                        '    <i class="material-icons">delete_forever</i>'+
                        '</button>';
                    }
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>