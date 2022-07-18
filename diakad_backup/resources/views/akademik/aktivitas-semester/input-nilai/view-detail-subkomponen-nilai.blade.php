<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/input-nilai/view-sub-komponen/'.$id_komponen_mp.'/'.$id_kelas_mp.'/'.$id_pengguna.'/'.$id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH SUB KOMPONEN NILAI ({{ $data_komponen->nm_komponen_mp }})
                    </h2>
                </div>
                <div class="body">
                @if(!empty($id_subkomponen_mp))
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-subkomponen-nilai/edit/'.$id_subkomponen_mp)}}">
                @else
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-subkomponen-nilai/add/'.$id_subkomponen_mp)}}">
                @endif
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            KD Sub Komponen <small><b>* Dapat diisi nomor/kode KD</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kd_subkomponen_mp" required="" aria-required="true"
                                    aria-invalid="true" value="{{ isset($data_subkomponen_mp) ? $data_subkomponen_mp->kd_subkomponen_mp : null }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tipe Sub Komponen <small><b>* Tipe Ujian untuk penilaian UTS,UAS, dll.</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name="type_subkomponen_mp" id="type_subkomponen_mp" class="form-control" required>
                                    <option value="" selected disabled>-- Pilih Tipe --</option>
                                    <option value="0" {{ isset($data_subkomponen_mp) && $data_subkomponen_mp->type_subkomponen_mp === 0 ? 'selected' : null }}>Kompetensi Dasar</option>
                                    <option value="1" {{ isset($data_subkomponen_mp) && $data_subkomponen_mp->type_subkomponen_mp == 1 ? 'selected' : null }}>Ujian</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Sub Komponen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_subkomponen_mp" required="" aria-required="true"
                                    aria-invalid="true" value="{{ isset($data_subkomponen_mp) ? $data_subkomponen_mp->nm_subkomponen_mp : null }}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" class="form-control" name="id_komponen_mp" required="" aria-required="true" aria-invalid="true" value="{{$id_komponen_mp}}">
                                <input type="hidden" class="form-control" name="id_subkomponen_mp" required="" aria-required="true" aria-invalid="true" value="{{$id_subkomponen_mp}}">
                                <input type="hidden" class="form-control" name="id_kelas_mp" required="" aria-required="true" aria-invalid="true" value="{{$id_kelas_mp}}">
                                <input type="hidden" class="form-control" name="id_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$id_pengguna}}">
                                <input type="hidden" class="form-control" name="id_semester" required="" aria-required="true" aria-invalid="true" value="{{$id_semester}}">
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