<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#wisuda/entri-wisuda/view-detail/'.$id_periode_wisuda.'/'.$id_kelas)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        ENTRI DATA WISUDA {{$data_pengajuan_wisuda->nm_periode_wisuda}} SEMESTER {{$data_pengajuan_wisuda->tahun_ajaran}} {{$data_pengajuan_wisuda->nm_semester}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-entri-wisuda/input/'.$data_pengajuan_wisuda->id_pengajuan_wisuda.'/0/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            NIS
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_siswa" required="" aria-required="true" aria-invalid="true" value="{{$data_pengajuan_wisuda->nis_siswa}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$data_pengajuan_wisuda->nm_pengguna}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kelas" required="" aria-required="true" aria-invalid="true" value="{{$data_pengajuan_wisuda->nm_kelas}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pengajuan Wisuda
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="tgl_pengajuan_wisuda" required="" aria-required="true" aria-invalid="true" value="{{$tgl_pengajuan_wisuda}}" readonly >
                            </div>
                        </div>
                        {{-- <h2 class="card-inside-title">
                            Biodata
                        </h2> --}}
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none !important">
                                <select class="form-control show-tick" name="status_biodata" >
                                    @if($data_pengajuan_wisuda->status_biodata == 0)
                                        <option value="0" selected >Belum Lengkap</option>
                                        <option value="1">Lengkap</option>
                                    @else
                                        <option value="0">Belum Lengkap</option>
                                        <option value="1" selected >Lengkap</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        {{-- <h2 class="card-inside-title">
                            Status Lab
                        </h2> --}}
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none !important">
                                <select class="form-control show-tick" name="status_lab">
                                    @if($data_pengajuan_wisuda->status_lab == 0)
                                        <option value="0" selected >Ada Tanggungan</option>
                                        <option value="1">Bebas Tanggungan</option>
                                    @else
                                        <option value="0">Ada Tanggungan</option>
                                        <option value="1" selected >Bebas Tanggungan</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        {{-- <h2 class="card-inside-title">
                            Status Perpus
                        </h2> --}}
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none !important">
                                <select class="form-control show-tick" name="status_perpus">
                                    @if($data_pengajuan_wisuda->status_perpus == 0)
                                        <option value="0" selected >Ada Tanggungan</option>
                                        <option value="1">Bebas Tanggungan</option>
                                    @else
                                        <option value="0">Ada Tanggungan</option>
                                        <option value="1" selected >Bebas Tanggungan</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        {{-- <h2 class="card-inside-title">
                            Status Ijasah
                        </h2> --}}
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none !important">
                                <select class="form-control show-tick" name="status_ijasah">
                                    @if($data_pengajuan_wisuda->status_ijasah == 0)
                                        <option value="0" selected >Belum Cetak</option>
                                        <option value="1">Sudah Cetak</option>
                                    @else
                                        <option value="0">Belum Cetak</option>
                                        <option value="1" selected >Sudah Cetak</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor SK Kelulusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_kelulusan" aria-invalid="true" value="{{$data_pengajuan_wisuda->nomor_sk_kelulusan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal SK Kelulusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_sk_kelulusan" aria-invalid="true" value="{{$tgl_sk_kelulusan}}" >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Ijasah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_ijasah" aria-invalid="true" value="{{$data_pengajuan_wisuda->nomor_ijasah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Kelulusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_kelulusan" aria-invalid="true" value="{{$tgl_kelulusan}}" >
                                <input type="hidden" class="form-control" name="id_kelas" required="" aria-required="true" aria-invalid="true" value="{{$id_kelas}}">
                                <input type="hidden" class="form-control" name="id_periode_wisuda" required="" aria-required="true" aria-invalid="true" value="{{$id_periode_wisuda}}">
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
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>