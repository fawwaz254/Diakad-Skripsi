<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#magang-siswa/pengajuan-siswa-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-green">
                        <h2>PENGAJUAN MAGANG SISWA  
                            @if(is_null($data_periode_magang))  
                            @else 
                                Periode : {{$data_periode_magang->nm_periode_magang}} 
                            @endif 
                            @if(is_null($data_rekanan_magang))  
                            @else 
                                Rekanan :  {{$data_rekanan_magang->nm_rekanan_magang}}
                            @endif
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Periode Magang</th>
                                        <th>Semester</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Rekanan Magang</th>
                                        <th>Kuota Rekanan</th>
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

    var id_periode_magang = {!! json_encode($id_periode_magang) !!};
    var id_rekanan_magang = {!! json_encode($id_rekanan_magang) !!};
    var nis_nama_siswa = {!! json_encode($nis_nama_siswa) !!};

    var modul_url       = 'magang-siswa';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengajuan-siswa-magang/datatables/' + id_periode_magang + '/' + id_rekanan_magang +'/' + nis_nama_siswa;
    var cancel_url        = role_url + '#' + modul_url + '/' + 'pengajuan-siswa-magang/cancel';
    var pengajuan_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pengajuan-siswa-magang/pengajuan';
    

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
            { data: 'nm_periode_magang', name: 'nm_periode_magang' },
            { data: 'semester', name: 'semester' },
            { data: 'nis_siswa', name: 'nis_siswa'},
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'nm_kelas', name: 'nm_kelas'},
            { data: 'nm_rekanan_magang', name: 'nm_rekanan_magang'},
            { data: 'kuota_rekanan_magang', name:'kuota_rekanan_magang'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.id != null) {
                        if(data.status_magang == 1) {
                            return '<a>Sedang Di Proses</a>';
                        }
                        else if(data.status_magang == 0) {
                            return '<a class="target-link btn btn-danger btn-circle waves-effect waves-circle waves-float" href="'+ cancel_url + '/' + data.id  +'">'+
                            '    <i class="material-icons">delete_forever</i>'+
                            '</a>';
                        }
                        
                    }
                    else {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\''+ pengajuan_url +'\', this)" data-id-siswa="'+  data.id_siswa +'" data-id-rekanan-magang="'+  id_rekanan_magang
                            +'" data-id-periode-magang="'+  id_periode_magang
                             +'">'+
                        '    <i class="material-icons">autorenew</i>'+
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

    function pengajuanAction(pengajuan_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Pengajuan Magang Untuk Siswa Ini!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, Ajukan Magang!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: pengajuan_url + '/0/' + item.attr('data-id-siswa') + '/' + item.attr('data-id-periode-magang')+ '/' + item.attr('data-id-rekanan-magang'),
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
