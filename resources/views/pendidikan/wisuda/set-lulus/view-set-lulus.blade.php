<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>SET LULUS SISWA</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Periode Wisuda</th>
                                        <th>Semester</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tgl Pengajuan Wisuda</th>
                                        <th>Biodata</th>
                                        <th>Status Lab</th>
                                        <th>Status Perpus</th>
                                        <th>Status Ijasah</th>
                                        <th>Nomor SK Kelulusan</th>
                                        <th>Tgl SK Kelulusan</th>
                                        <th>Nomor Ijasah</th>
                                        <th>Tgl Kelulusan</th>
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

    var modul_url       = 'wisuda';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'set-lulus/datatables';
    var set_lulus_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-set-lulus/set-lulus';


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
            { data: 'nm_periode_wisuda', name: 'nm_periode_wisuda' },
            { data: 'semester', name: 'semester' },
            { data: 'nis_siswa', name: 'nis_siswa'},
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'nm_kelas', name: 'nm_kelas'},
            { data: 'tgl_pengajuan_wisuda', name: 'tgl_pengajuan_wisuda'},
            { data: 'status_biodata', name: 'status_biodata'},
            { data: 'status_lab', name: 'status_lab'},
            { data: 'status_perpus', name: 'status_perpus'},
            { data: 'status_ijasah', name: 'status_ijasah'},
            { data: 'nomor_sk_kelulusan', name: 'nomor_sk_kelulusan'},
            { data: 'tgl_sk_kelulusan', name: 'tgl_sk_kelulusan'},
            { data: 'nomor_ijasah', name: 'nomor_ijasah'},
            { data: 'tgl_kelulusan', name: 'tgl_kelulusan'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="setLulusAction(\''+ set_lulus_url +'\', this)" data-id="'+  data.id +'" >'+
                        '    <i class="material-icons">verified_user</i>'+
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

    function setLulusAction(set_lulus_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Set Lulus Untuk Siswa Ini (Menjadi Alumni)!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, Set Lulus!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: set_lulus_url + '/' + item.attr('data-id'),
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