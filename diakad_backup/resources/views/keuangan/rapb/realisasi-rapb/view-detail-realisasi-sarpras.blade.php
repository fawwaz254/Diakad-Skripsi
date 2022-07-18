 <div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/view-detail-rapb/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali Ke Data RAPB</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/view-detail-realisasi/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester.'/'.$data_rapb->id_rapb)}}"><i class="material-icons">backspace</i><span>Kembali Ke Detail Realisasi</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>REALISASI RPB SARPRAS</h2> 
                    </div>
                <div class="body">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    INFO RAPB
                                </h2>
                                <hr style="border: 3px solid black;">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester Mulai
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $semester_mulai->tahun_ajaran }} {{ $semester_mulai->nm_semester }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester Selesai
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $semester_selesai->tahun_ajaran }} {{ $semester_selesai->nm_semester }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jenis Kategori
                                </h2>
                                @if($data_rapb->tipe_kategori_rapb == 1)
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                            aria-invalid="true" value="Penerimaan">
                                        </div>
                                    </div>
                                @elseif($data_rapb->tipe_kategori_rapb == 2)
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                            aria-invalid="true" value="Pengeluaran">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Unit Kerja
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rapb->nm_unit_kerja }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kode Sub-Kategori
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rapb->kode_subkategori_rapb }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Nama Sub-Kategori
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rapb->nm_subkategori_rapb }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Target Perkiraan
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="Rp{{ number_format($data_rapb->dana_perkiraan_rapb, 0) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Realisasi
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="Rp{{ number_format($data_rapb->jml_realisasi, 0) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tanggal RAPB
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ strftime("%A, %d %B %Y", strtotime($data_rapb->tgl_rapb)) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Prioritas
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @if($data_rapb->prioritas_rapb == 1)
                                            <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="Rendah">
                                        @elseif($data_rapb->prioritas_rapb == 2)
                                            <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="Sedang">
                                        @elseif($data_rapb->prioritas_rapb == 3)
                                            <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="Tinggi">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <hr style="border: 3px solid black;">
                        </div>
                    </div>
                    <div class="block-header">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Semester</th>
                                    <th>Unit Kerja</th>
                                    <th>Jenis Buku/Alat</th>
                                    <th>Buku/Alat</th>
                                    <th>Ruangan</th>
                                    <th>Inventaris</th>
                                    <th>Harga Satuan</th>
                                    <th>Qty</th>
                                    <th>Termin</th>
                                    <th>Rencana Realisasi</th>
                                    <th>Realisasi</th>
                                    <th>Sisa Realisasi</th>
                                    <th>Tgl Pengadaan</th>
                                    <th>Kepala Unit</th>
                                    <th>Kepala Sarpras</th>
                                    <th>Supplier</th>
                                    <th>Kepala Sarpras Apv Supplier</th>
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
@include('scriptjs')
<script>

    var id_rapb                 = {!! json_encode($data_rapb->id_rapb) !!};
    var id_semester_mulai       = {!! json_encode($semester_mulai->id_semester) !!};
    var id_semester_selesai     = {!! json_encode($semester_selesai->id_semester) !!};

    var modul_url               = 'rapb';
    var datatable_url           = base_url + '/' + role_url + '/' + modul_url + '/' + 'realisasi-rapb/datatables-sarpras';
    var add_url                 = role_url + '#' + modul_url + '/' + 'realisasi-rapb/add-realisasi-sarpras/' + id_semester_mulai + '/' + id_semester_selesai + '/' + id_rapb;

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
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'nm_jenis_buku_alat', name: 'jenis_buku_alat.nm_jenis_buku_alat' },
            { data: 'nm_buku_alat', name: 'buku_alat.nm_buku_alat' },
            { data: 'nm_ruangan', name: 'ruangan.nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'inventaris_ruangan.nm_inventaris_ruangan' },
            { data: 'harga_approve_supplier', name: 'harga_approve_supplier' },
            { data: 'qty_approve_supplier', name: 'rpb_sarpras_supplier.qty_approve_supplier' },
            { data: 'termin_approve_supplier', name: 'rpb_sarpras_supplier.termin_approve_supplier' },
            { data: 'rencana_realisasi', name: 'rencana_realisasi' },
            { data: 'jml_realisasi_sarpras', name: 'jml_realisasi_sarpras' },
            { data: 'sisa_realisasi', name: 'sisa_realisasi' },
            { data: 'tgl_rpb_sarpras', name: 'tgl_rpb_sarpras' },
            { data: 'nm_kepala_unit', name: 'nm_kepala_unit' },
            { data: 'nm_kepala_sarpras', name: 'nm_kepala_sarpras' },
            { data: 'nm_supplier', name: 'supplier.nm_supplier' },
            { data: 'nm_kepala_sarpras_approve', name: 'nm_kepala_sarpras_approve' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ add_url + '/' + data.id +'">'+
                        '    <i class="material-icons">post_add</i>'+
                        '</a> ';
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