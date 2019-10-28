<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#presensi/absensi-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-orange">
                        <h2>UTS KELAS {{$data_kelas->nm_kelas}} SEMESTER {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}}</h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-absensi-siswa/add-uts/'.$id_ujian_mp)}}">
                            {{csrf_field()}}
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th></th>
                                            <th>Alasan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_ujian_mp = {!! json_encode($id_ujian_mp) !!};

    var modul_url       = 'presensi';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'absensi-siswa/datatables-uts/' + id_ujian_mp;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'nm_pengguna', name: 'pengguna.nm_pengguna' },
            { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false,
                render: function(data){
                    return '<input type="checkbox" class="mycheckbox" name="id_siswa[]" value="'+ data.id_siswa + '" checked >';

                    /*return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';*/
                }
            },
            { data: 'alasan', name: 'alasan', searchable: false, orderable: false,
                render: function(data){
                    var html = '';
                    $.each(data.options, function(index, item){
                        if(data.kehadiran == item.id){
                            html += '<option value="'+item.id+'" selected>'+ item.text + '</option>';
                        }else{
                            html += '<option value="'+item.id+'">'+ item.text + '</option>';
                        }
                    })
                    return '<select class="form-control show-tick" name="alasan[]">'+
                    html +
                    '</select>';
                    /*return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';*/
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