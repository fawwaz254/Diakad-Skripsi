    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-kesiswaan/beasiswa-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-indigo">
                        <h2>
                            TAMBAH BEASISWA SISWA
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-beasiswa-siswa/add/0')}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Kelas
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="kelas" onchange="changeKelas(this)">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($data_kelas as $data)
                                            <option value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_siswa">
                                        <option value="">-- Pilih Siswa --</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jenis Beasiswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="jenis_beasiswa_siswa">
                                        <option value="">-- Pilih Jenis Beasiswa --</option>
                                        <option value="1">Anak Berprestasi</option>
                                        <option value="2">Anak Miskin</option>
                                        <option value="3">Pendidikan</option>
                                        <option value="4">Unggulan</option>
                                        <option value="99">Lain-Lain</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tahun Mulai Beasiswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="tahun_mulai_beasiswa_siswa" required="" aria-required="true"
                                    aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tahun Selesai Beasiswa <small><b>Jika waktu beasiswa hanya satu tahun atau kurang. Tahun Selesai diisi sama dengan tahun mulai</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="tahun_selesai_beasiswa_siswa" required="" aria-required="true"
                                    aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Keterangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="keterangan" class="form-control" name="keterangan_beasiswa_siswa" aria-required="true"
                                    aria-invalid="true">
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
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/siswa-bykelas')}}',
        type: 'POST',
        data: {
            kelas: $('select[name=kelas]').val()
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
</script>