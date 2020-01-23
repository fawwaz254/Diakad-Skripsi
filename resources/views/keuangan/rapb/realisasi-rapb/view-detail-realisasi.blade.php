 <div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/view-detail-rapb/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>REALISASI RAPB</h2> 
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
                    <div class="block-header">
                        <h2>
                            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/add/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester.'/'.$data_rapb->id_rapb)}}"><i class="material-icons">note_add</i><span>INPUT REALISASI</span></a> &nbsp; &nbsp;
                            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/view-detail-realisasi-sarpras/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester.'/'.$data_rapb->id_rapb)}}"><i class="material-icons">note_add</i><span>REALISASI RPB SARPRAS</span></a>
                        </h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Semester Realisasi</th>
                                    <th>Unit Kerja</th>
                                    <th>Pengadaan</th>
                                    <th>Nama Realisasi</th>
                                    <th>Keterangan</th>
                                    <th>Termin</th>
                                    <th>Hutang</th>
                                    <th>Dana Realisasi</th>
                                    <th>Tgl Realisasi</th>
                                    <th>Cek Staf Keuangan</th>
                                    <th>Apv Kepala Keuangan</th>
                                    <th>Cicilan</th>
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
    var datatable_url           = base_url + '/' + role_url + '/' + modul_url + '/' + 'realisasi-rapb/datatables/' + id_rapb;
    var cek_keuangan_url        = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-realisasi/approve-cek-keuangan';
    var kepala_keuangan_url     = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-realisasi/approve-kepala-keuangan';
    var cicilan_url                = role_url + '#' + modul_url + '/' + 'realisasi-rapb/view-detail-realisasi-termin/' + id_semester_mulai + '/' + id_semester_selesai + '/' + id_rapb;

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
            { data: 'semester', name: 'semester' },
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'pengadaan', name: 'pengadaan' },
            { data: 'nm_realisasi', name: 'realisasi.nm_realisasi' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'termin_dana_realisasi', name: 'realisasi.termin_dana_realisasi' },
            { data: 'is_hutang', name: 'is_hutang' },
            { data: 'dana_realisasi', name: 'realisasi.dana_realisasi' },
            { data: 'tgl_realisasi', name: 'tgl_realisasi' },
            { data: 'nm_cek_keuangan', name: 'nm_cek_keuangan', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_cek_keuangan == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="cekKeuanganAction(\''+ cek_keuangan_url +'\', this)" data-idrealisasi="'+  data.id_realisasi +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_cek_keuangan +'</a>';
                    }
                }
            },
            { data: 'nm_kepala_keuangan', name: 'nm_kepala_keuangan', searchable: false, orderable: false,
                render: function(data){
                    if(data.nm_kepala_keuangan == null) {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="kepalaKeuanganAction(\''+ kepala_keuangan_url +'\', this)" data-idrealisasi="'+  data.id_realisasi +'">'+
                        '    <i class="material-icons">verified_user</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>'+ data.nm_kepala_keuangan +'</a>';
                    }
                }
            },
            { data: 'cicilan', name: 'cicilan', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ cicilan_url + '/' + data.id +'">'+
                        '    <i class="material-icons">attach_money</i>'+
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


    function cekKeuanganAction(kepala_unit_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Aksi Ini Akan Otomatis Melakukan Cek Staf Keuangan!",
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
                    url: cek_keuangan_url + '/' + item.attr('data-idrealisasi'),
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
                    url: kepala_keuangan_url + '/' + item.attr('data-idrealisasi'),
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