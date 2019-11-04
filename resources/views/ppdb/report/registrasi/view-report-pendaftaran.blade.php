<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-lime">
                        <h2>DATA REPORT PENDAFTARAN</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Penerimaan</th>
                                        <th>Gelombang</th>
                                        <th>Submit Form</th>
                                        <th>Antri Verifikasi</th>
                                        <th>Perbaikan</th>
                                        <th>Lolos Verifikasi</th>
                                        <th>Bayar</th>
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

    var modul_url       = 'report';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'report-pendaftaran/datatables';
    var rekap_url        = role_url + '#' + modul_url + '/' + 'report-pendaftaran/rekap';
    var detail_url        = role_url + '#' + modul_url + '/' + 'report-pendaftaran/detail';

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
            { data: 'tahun_penerimaan', name: 'tahun_penerimaan' },
            { data: 'nm_penerimaan', name: 'nm_penerimaan' },
            { data: 'gelombang_penerimaan', name: 'gelombang_penerimaan' },
            { data: 'jumlah_submit_form', name: 'jumlah_submit_form'},
            { data: 'jumlah_antri_verifikasi', name: 'jumlah_antri_verifikasi'},
            { data: 'jumlah_verifikasi_kembali', name: 'jumlah_verifikasi_kembali'},
            { data: 'jumlah_verifikasi', name: 'jumlah_verifikasi'},
            { data: 'jumlah_bayar', name: 'jumlah_bayar'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a href="'+ rekap_url + '/' + data.id +'"><i class="material-icons">folder_open</i></a> <a href="'+ detail_url + '/' + data.id +'"><i class="material-icons">details</i></a> ';
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