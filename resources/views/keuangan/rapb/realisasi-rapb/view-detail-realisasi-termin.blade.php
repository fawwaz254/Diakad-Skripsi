 <div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/view-detail-realisasi/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester.'/'.$data_rapb->id_rapb)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>CICILAN TERMIN REALISASI RAPB</h2> 
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
                        </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <hr style="border: 3px solid black;">
                        </div>
                    </div>

                    <div class="row clearfix">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    INFO REALISASI RAPB
                                </h2>
                                <hr style="border: 3px solid black;">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester Realisasi
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->tahun_ajaran }} {{ $data_realisasi->nm_semester }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Unit Kerja
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->nm_unit_kerja }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (! empty ($data_realisasi->id_rpb_sarpras))
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Semester)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->tahun_ajaran_sarpras }} {{ $data_realisasi->nm_semester_sarpras }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Unit Kerja)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->nm_unit_kerja_sarpras }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Buku/Alat)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->nm_buku_alat }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Inventaris Ruangan)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->nm_inventaris_ruangan }} - {{ $data_realisasi->nm_ruangan }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Harga Satuan - Qty)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->harga_satuan_rpb_sarpras }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Qty)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->qty_rpb_sarpras }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Tgl Pengadaan)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->tgl_rpb_sarpras }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Pengadaan Sarpras (Prioritas)
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->prioritas_rpb_sarpras }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Nama Realisasi
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->nm_realisasi }}">
                                    </div>
                                </div>
                            </div>
                            @if (! empty ($data_realisasi->kode_ket_subkategori_rapb))
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Keterangan Sub-Kategori RAPB
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->kode_ket_subkategori_rapb }} - {{ $data_realisasi->nm_ket_subkategori_rapb }}">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Termin
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_realisasi->termin_dana_realisasi }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Hutang
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @if ($data_realisasi->is_hutang_realisasi == 1)
                                            <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="Ya">
                                        @elseif ($data_realisasi->is_hutang_realisasi == 0)
                                            <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="Tidak">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Dana Realisasi
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="Rp{{ number_format($data_realisasi->dana_realisasi, 0) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tanggal Realisasi
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ strftime("%A, %d %B %Y", strtotime($data_realisasi->tgl_realisasi)) }}">
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
                        <h2>
                            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/add-realisasi-termin/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester.'/'.$data_rapb->id_rapb.'/'.$data_realisasi->id_realisasi)}}"><i class="material-icons">note_add</i><span>INPUT CICILAN</span></a>
                        </h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Termin Ke</th>
                                    <th>Tgl Pembayaran</th>
                                    <th>Dana Cicilan Termin</th>
                                    <th>Apv Kepala Keuangan</th>
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

    var id_realisasi            = {!! json_encode($data_realisasi->id_realisasi) !!};

    var modul_url               = 'rapb';
    var datatable_url           = base_url + '/' + role_url + '/' + modul_url + '/' + 'realisasi-rapb/datatables-termin/' + id_realisasi;
    var kepala_keuangan_url     = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-realisasi/approve-kepala-keuangan-termin';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'termin_ke', name: 'termin_ke' },
            { data: 'tgl_pembayaran', name: 'tgl_pembayaran' },
            { data: 'dana_realisasi_pembayaran', name: 'dana_realisasi_pembayaran' },
            { data: 'nm_kepala_keuangan', name: 'nm_kepala_keuangan', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_keuangan == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaKeuanganAction(\''+ kepala_keuangan_url +'\', this)" data-idrealisasipembayaran="'+  data.id_realisasi_pembayaran +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_keuangan +'</a>';
                    }
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

    function kepalaKeuanganAction(kepala_keuangan_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Aksi Ini Akan Otomatis Melakukan Approve Kepala Keuangan!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, Saya Yakin!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: kepala_keuangan_url + '/' + item.attr('data-idrealisasipembayaran'),
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