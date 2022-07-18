<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#ekstrakurikuler/setting-peserta-ekskul/view-ekskul/'.$id_ekskul)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        SETTING PENGAMBILAN EKSKUL ({{ $ekskul->nm_ekskul }})
                    </h2>
                </div>
                <div class="header">
                    <h2>
                        Jumlah Pengambilan Peserta Ekskul {{ $ekskul->nm_ekskul }} : {{$jumlah_pengambilan}}  
                        <br> Semester {{$semester->tahun_ajaran}} ({{$semester->nm_semester}}) 
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setting-peserta-ekskul/setting/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester Pengambilan
                            <small><strong>Semester Yang Diambil Oleh Peserta Ekskul</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester" required >
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->is_aktif_semester == 1)
                                            <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} ({{$data->nm_semester}}) (Aktif)</option>
                                        @else
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} ({{$data->nm_semester}})</option>    
                                        @endif
                                    @endforeach
                                </select>
                                <input type="hidden" class="form-control" name="id_ekskul" required="" aria-required="true" aria-invalid="true" value="{{$id_ekskul}}">
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