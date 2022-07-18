<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH REKAP ABSEN TANPA JADWAL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-kbm-rekap-absen-tanpa-jadwal')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas" onchange="changeKelas()" required="">
                                    <option value="" disabled selected >-- Pilih Kelas --</option>
                                    @foreach($data_kelas as $kelas)
                                    <option value="{{$kelas->id_kelas}}">{{$kelas->nm_kelas}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            KBM
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas_mp" required="">
                                    <option value="" disabled selected >-- Pilih KBM --</option>
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
function changeKelas(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/post-get-kbm-by-kelas')}}',
        type: 'POST',
        data: {
            id_kelas: $('select[name=id_kelas]').val()
        },
        success: function(result) {
            $('select[name=id_kelas_mp]').html('');
            var html = '<option value="">-- Pilih KBM --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_kelas_mp+'">'+item.nm_mata_pelajaran+' - ' + item.nm_kelas + '</option>';
            });
            $('select[name=id_kelas_mp]').html(html);
        }
    });
}
</script>