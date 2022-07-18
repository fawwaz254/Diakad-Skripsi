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
                            <select class="form-control show-tick" name="id_kelas" onchange="changeKelas(this)">
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
                            <button class="btn btn-block bg-red waves-effect" onclick="printJurnalTindakan()"><i class="material-icons">print</i><span>Cetak</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')

<script>
    function changeKelas(el){
        $.ajax({
            url: '{{url(Request::segment(1).'/'.Request::segment(2).'/siswa-bykelas')}}',
            type: 'POST',
            data: {
                kelas: $('select[name=id_kelas]').val()
            },
            success: function(result) {
                $('select[name=id_siswa]').html('');
                var html = '<option value="">-- Pilih Siswa --</option>';
                $.each(result, function( key, item ) {
                    html += '<option value="'+item.id_siswa+'">'+item.nm_pengguna+' ('+item.nis_siswa+')</option>'
                });
                $('select[name=id_siswa]').html(html);
            }
        });
    }

    function printJurnalTindakan(){
        window.open(base_url + '/{{Request::segment(1)}}/penanganan-siswa/jurnal-tindakan/print/'+$('select[name=id_semester]').val()+'/'+$('select[name=id_kelas]').val()+'/'+$('select[name=id_siswa]').val(), '_blank');
    }
</script>