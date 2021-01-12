<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Dokumen</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Loker</th>
                                        <th>Pemilik</th>
                                        <th>SubKategori</th>
                                        <th>Jumlah File</th>
                                        <th>Kode Katalog</th>
                                        <th>Nama Arsip Dokumen</th>
                                        <th>Nomor Arsip Dokumen</th>
                                        <th>Unit Kerja</th>
                                        <th>Jumlah Halaman</th>
                                        <th>Tanggal Penyusunan</th>
                                        <th>Contact Person</th>
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
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'kesekretariatan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'dokumen/datatables';
    var detail_url        = role_url + '#' + modul_url + '/' + 'dokumen/detail';
    var edit_url        = role_url + '#' + modul_url + '/' + 'upload-dokumen/edit';
    var upload_url        = role_url + '#' + modul_url + '/' + 'upload-dokumen/upload';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-upload-dokumen/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_arsip_loker', name: 'arsip_loker.nm_arsip_loker' },
            { data: 'nm_arsip_pemilik', name: 'arsip_pemilik.nm_arsip_pemilik' },
            { data: 'nm_arsip_subkategori', name: 'arsip_kategori.nm_arsip_subkategori' },
            { data: 'jml_file', name: 'jml_file', searchable: false, orderable: false },
            { data: 'kode_katalog', name: 'arsip_dokumen.kode_katalog' },
            { data: 'nm_arsip_dokumen', name: 'arsip_dokumen.nm_arsip_dokumen' },
            { data: 'nomor_arsip_dokumen', name: 'arsip_dokumen.nomor_arsip_dokumen' },
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'jumlah_halaman', name: 'arsip_dokumen.jumlah_halaman' },
            { data: 'tgl_penyusunan', name: 'arsip_dokumen.tgl_penyusunan' },
            { data: 'contact_person', name: 'arsip_dokumen.contact_person' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-warning btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">remove_red_eye</i>'+
                    '</a> '+
                    '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<a class="target-link btn btn-success btn-circle waves-effect waves-circle waves-float" href="'+ upload_url + '/' + data.id +'">'+
                    '    <i class="material-icons">cloud_upload</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button> ';
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
