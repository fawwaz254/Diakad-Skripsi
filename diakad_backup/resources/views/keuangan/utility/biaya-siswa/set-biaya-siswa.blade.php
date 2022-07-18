<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#utility/biaya-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        SET BIAYA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-biaya-siswa/set/'.$data_biaya_siswa->id_siswa)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            NIS Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_biaya_siswa->nis_siswa}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NISN Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_biaya_siswa->nisn_siswa}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_biaya_siswa->nm_pengguna}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_biaya_siswa->nm_kelas}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelompok Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelompok_biaya">
                                    <option value="" disabled selected >-- Pilih Kelompok Biaya --</option>
                                    @foreach($data_kelompok_biaya as $data)
                                        @if($data->status_kelompok_biaya == 1)
                                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} (Reguler)</option>
                                        @else
                                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} (Khusus)</option>
                                        @endif
                                    @endforeach
                                </select>
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
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });
});
</script>