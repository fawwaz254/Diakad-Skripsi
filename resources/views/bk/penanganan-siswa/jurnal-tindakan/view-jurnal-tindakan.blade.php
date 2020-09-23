<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        JURNAL TINDAKAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-jurnal-tindakan')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $semester)
                                    <option value="{{$semester->id_semester}}" @if($semester_aktif->id_semester == $semester->id_semester) selected @endif>{{$semester->tahun_ajaran}} {{$semester->nm_semester}} @if($semester_aktif->id_semester == $semester->id_semester) (Aktif) @endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="" disabled selected >-- Pilih Kelas --</option>
                                    @foreach($data_kelas as $kelas)
                                    <option value="{{$kelas->id_kelas}}">{{$kelas->nm_kelas}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_siswa">
                                    <option value="" disabled selected >-- Pilih Siswa --</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">print</i><span>Cetak</span></button>
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
    function subKategori(){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/sub-kategori')}}',
        type: 'POST',
        data: {
            kategori: $('select[name=kategori]').val()
        },
        success: function(result) {
            $('select[name=id_arsip_subkategori]').html('');
            var html = '<option value="">-- Pilih SubKategori --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_arsip_subkategori+'">'+item.nm_arsip_subkategori+'</option>'
            });
            $('select[name=id_arsip_subkategori]').html(html);
        }
    });
}
</script>