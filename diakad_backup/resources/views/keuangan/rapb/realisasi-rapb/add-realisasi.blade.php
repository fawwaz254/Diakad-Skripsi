<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/view-detail-rapb/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali Ke Data RAPB</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/realisasi-rapb/view-detail-realisasi/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester.'/'.$data_rapb->id_rapb)}}"><i class="material-icons">backspace</i><span>Kembali Ke Detail Realisasi</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH REALISASI
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-realisasi-rapb/add/'.$id_realisasi)}}">
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
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $semester_mulai->tahun_ajaran }} {{ $semester_mulai->nm_semester }}">
                                        <input type="hidden" class="form-control" name="id_rapb" aria-required="true" aria-invalid="true" value="{{ $data_rapb->id_rapb }}">
                                        <input type="hidden" class="form-control" name="id_semester_mulai" aria-required="true" aria-invalid="true" value="{{ $semester_mulai->id_semester }}">
                                        <input type="hidden" class="form-control" name="id_semester_selesai" aria-required="true" aria-invalid="true" value="{{ $semester_selesai->id_semester }}">

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
                                    Unit Kerja RAPB
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

                        <h2 class="card-inside-title">
                            Semester Realisasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_realisasi">
                                  <option value="" disabled selected >-- Pilih Semester Realisasi --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->is_aktif_semester == 1)
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                        @else
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Unit Kerja Realisasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_unit_kerja">
                                  <option value="" disabled selected >-- Pilih Unit Kerja --</option>
                                    @foreach($data_unit_kerja as $data)
                                        <option value="{{$data->id_unit_kerja}}">{{$data->nm_singkatan_unit}} - {{$data->nm_unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Realisasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_realisasi" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan Sub-Kategori RAPB <small>*Optional</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_ket_subkategori_rapb">
                                  <option value="" disabled selected >-- Pilih Keterangan Sub-Kategori --</option>
                                    @foreach($data_ket_subkategori_rapb as $data)
                                        <option value="{{$data->id_ket_subkategori_rapb}}">{{$data->kode_ket_subkategori_rapb}} - {{$data->nm_ket_subkategori_rapb}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Termin Realisasi <small>Apabila Tidak Ada Isikan 1</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="termin_dana_realisasi" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Cicilan Pembayaran Realisasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_cicilan">
                                    <option value="2">Tidak (Langsung Lunas)</option>
                                    <option value="1">Ya (Dibagi Termin)</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Dana Realisasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="dana_realisasi" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Realisasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_realisasi" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
@include('scriptjs')

<script>
$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'dddd, DD MMMM YYYY',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: false
    });
});
</script>