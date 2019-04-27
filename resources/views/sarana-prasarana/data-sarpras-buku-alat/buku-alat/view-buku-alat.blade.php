<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-sarpras-buku-alat/buku-alat/add')}}"><i class="material-icons">note_add</i><span>Tambah Buku/Alat</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-purple">
                        <h2>DATA BUKU/ALAT</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Buku/Alat</th>
                                        <th>Nama Buku/Alat</th>
                                        <th>Tingkat Pendidikan</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kode Buku/Alat</th>
                                        <th>Tgl Pembelian</th>
                                        <th>Jumlah Buku/Alat</th>
                                        <th>Kondisi Baik</th>
                                        <th>Kondisi Rusak</th>
                                        <th>Keterangan</th>
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
    var modul_url       = 'data-sarpras-buku-alat';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'buku-alat/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'buku-alat/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-buku-alat/delete';

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
            { data: 'nm_jenis_buku_alat', name: 'nm_jenis_buku_alat' },
            { data: 'nm_buku_alat', name: 'nm_buku_alat' },
            { data: 'tingkat_pendidikan_buku_alat', name: 'tingkat_pendidikan_buku_alat' },
            { data: 'nm_mata_pelajaran', name: 'nm_mata_pelajaran' },
            { data: 'kode_buku_alat', name: 'kode_buku_alat' },
            { data: 'tgl_pembelian', name: 'tgl_pembelian' },
            { data: 'jumlah_buku_alat', name: 'jumlah_buku_alat' },
            { data: 'jumlah_kondisi_baik', name: 'jumlah_kondisi_baik' },
            { data: 'jumlah_kondisi_rusak', name: 'jumlah_kondisi_rusak' },
            { data: 'keterangan_buku_alat', name: 'keterangan_buku_alat' },
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
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>