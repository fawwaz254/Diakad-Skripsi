<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#siswa/setting-wali-murid/view-kelas/'.$siswa->id_kelas)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT WALI MURID {{$siswa->nm_pengguna}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setting-wali-murid/edit/'.$siswa->id_siswa)}}" autocomplete="off">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    No. HP Wali Murid
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" id="realtxt" onkeyup="searchSel()" class="form-control">
                                        <select class="form-control show-tick" id="nomor_hp_wali_murid" name="nomor_hp_wali_murid">
                                            @foreach($data_wali_murid as $data)
                                                <option value="{{$data->nomor_hp_wali_murid}}">{{$data->nomor_hp_wali_murid}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Nama Wali Murid
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div style="width:90%; position: relative; display: inline-block;">
                                            <input type="text" class="form-control" name="nm_wali_murid" aria-required="true" aria-invalid="true" value="@if($siswa->id_wali_murid != null) {{$wali_murid->nm_wali_murid}} @endif">
                                        </div>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Sebagai Orangtua atau Wali
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @if($siswa->id_wali_murid != null)
                                            <select class="form-control show-tick" name="is_orang_tua">
                                                @if($wali_murid->is_orang_tua == 1)
                                                    <option value="1" selected="">Orangtua Kandung</option>
                                                    <option value="0">Wali (Bukan Orangtua Kandung)</option>
                                                @else
                                                    <option value="1">Orangtua Kandung</option>
                                                    <option value="0" selected="">Wali (Bukan Orangtua Kandung)</option>
                                                @endif
                                            </select>
                                        @else
                                            <select class="form-control show-tick" name="is_orang_tua">
                                                <option value="1">Orangtua Kandung</option>
                                                <option value="0">Wali (Bukan Orangtua Kandung)</option>
                                            </select>
                                        @endif
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
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script type="text/javascript">
    function searchSel() {
  var input=document.getElementById('realtxt').value.toLowerCase();
  var output=document.getElementById('nomor_hp_wali_murid').options;
  for(var i=0;i<output.length;i++) {
    if(output[i].value.indexOf(input)==0){
      output[i].selected=true;
    }
    if(document.getElementById('realtxt').value==''){
      output[0].selected=true;
    }
  }
}
</script>