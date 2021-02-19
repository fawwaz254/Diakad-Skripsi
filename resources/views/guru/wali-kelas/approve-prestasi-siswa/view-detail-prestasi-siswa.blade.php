<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect" href="{{url(Request::segment(1).'/wali-kelas/approve-prestasi-siswa/print-skpi/'.Request::segment(4))}}" target="_blank"><i class="material-icons">print</i><span>Print SKPI</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Prestasi Siswa</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Prestasi</th>
                                        <th>Tingkat Prestasi</th>
                                        <th>Jenis Prestasi</th>
                                        <th>Peringkat</th>
                                        <th>Link Sertifikat</th>
                                        <th>Status</th>
                                        <th>Semester</th>
                                        <th>Kelas</th>
                                        <th>Lokasi</th>
                                        <th>Penyelenggara</th>
                                        <th>Tanggal</th>
                                        <th>Ekstrakurikuler</th>
                                        <th>Guru Pendamping</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <br>

                <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Kegiatan Siswa</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Lokasi</th>
                                        <th>Penyelenggara</th>
                                        <th>Tingkat Kegiatan</th>
                                        <th>Tanggal</th>
                                        <th>Link Sertifikat</th>
                                        <th>Status</th>
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
@include('scriptjs')
<script>

    var modul_url       = '{{Request::segment(2)}}';
    var id_siswa        = '{{Request::segment(4)}}';

    // datatable prestasi

    var datatable_url_prestasi   = base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-prestasi-siswa/prestasi/datatables/'+id_siswa;
    var approve_prestasi =  base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-prestasi-siswa/prestasi';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url_prestasi,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_prestasi_siswa', name: 'nm_prestasi_siswa' },
            { data: 'nm_tingkat_prestasi_siswa', name: 'nm_tingkat_prestasi_siswa' },
            { data: 'jenis_prestasi', name: 'jenis_prestasi' },
            { data: 'peringkat_prestasi_siswa', name: 'peringkat_prestasi_siswa' },
            { data: 'action', name: 'link_sertifikat', searchable: false, orderable: false,
                render:function(data){
                    return '<a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" target="_blank" href="'+ data.link_sertifikat +'">'+
                    '    <i class="material-icons">link</i>'+
                    '</a>';
                }
            },
            { data: 'keterangan', name: 'keterangan', searchable: false, orderable: false,
                render:function(data){
                    return `<span class="badge bg-`+data.color+`">`+data.status+`</span>`
                }
            },
            { data: 'semester', name: 'semester' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'lokasi_prestasi_siswa', name: 'lokasi_prestasi_siswa' },
            { data: 'penyelenggara_prestasi_siswa', name: 'penyelenggara_prestasi_siswa' },
            { data: 'tgl_prestasi_siswa', name: 'tgl_prestasi_siswa' },
            { data: 'nm_ekskul', name: 'nm_ekskul' },
            { data: 'nm_guru_pendamping', name: 'nm_guru_pendamping' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.status==0){
                         return '<button class="btn btn-info" onclick="approveAction(\''+ approve_prestasi +'\', this)" data-id="'+  data.id +'">'+
                        'Aprrove'+
                        '</button>';
                        }
                    else{
                        return '-';
                    }
                   
                }
            }
        ],
        columnDefs: [
            { className: 'text-center', targets: [5] },
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    // datatable kegiatan

    var datatable_url_kegiatan   = base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-prestasi-siswa/kegiatan/datatables/'+id_siswa;
    var approve_kegiatan =  base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-prestasi-siswa/kegiatan';

        var primary_table2 = $('#primary_table2').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url_kegiatan,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_kegiatan_siswa', name: 'nm_kegiatan_siswa' },
            { data: 'lokasi_kegiatan_siswa', name: 'lokasi_kegiatan_siswa' },
            { data: 'penyelenggara_kegiatan_siswa', name: 'penyelenggara_kegiatan_siswa' },
            { data: 'nm_tingkat_prestasi_siswa', name: 'nm_tingkat_prestasi_siswa' },
            { data: 'tgl_kegiatan_siswa', name: 'tgl_kegiatan_siswa'},
            { data: 'action', name: 'nm_kegiatan_siswa', searchable: false, orderable: false,
                render:function(data){
                    return '<a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" target="_blank" href="'+ data.link_sertifikat +'">'+
                    '    <i class="material-icons">link</i>'+
                    '</a>';
                }
            },
            { data: 'keterangan', name: 'keterangan', searchable: false, orderable: false,
                render:function(data){
                    return `<span class="badge bg-`+data.color+`">`+data.status+`</span>`
                }
            },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.status==0){
                        return '<button class="btn btn-info" onclick="approveAction(\''+ approve_kegiatan +'\', this)" data-id="'+  data.id +'">'+
                    'Aprrove'+
                    '</button>';
                    }
                    else{
                        return '-';
                    }
                }
            }
        ],
        columnDefs: [
            { className: 'text-center', targets: [4] },
        ]
    });

    primary_table2.on( 'draw', function () {
        primary_table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

</script>

<script type="text/javascript">
    
    function approveAction(approve_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "For Approve this",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, approve it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: approve_url + '/' + item.attr('data-id'),
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
                            if(response.from == 'prestasi'){
                                primary_table.ajax.reload(null, false);
                            }
                            else{
                                primary_table2.ajax.reload(null, false);
                            }
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
