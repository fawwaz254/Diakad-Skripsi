<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pelanggaran-siswa/input-pelanggaran-mp')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT PELANGGARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-pelanggaran-mp/add/'.$id_presensi_mp_pelanggaran)}}">
                        {{csrf_field()}}
                        <div class="row clearfix">

                            <div class="col-md-4">
                                <label> Data Siswa</label>
                                <input type="hidden" name="id_presensi_mp" required="" value="{{$presensi_mp_aktif->id_presensi_mp}}">
                                <input type="hidden" name="id_siswa" required="" value="{{$data_siswa->id_siswa}}">
                                <input type="hidden" name="id_kelas" required="" value="{{$data_siswa->id_kelas}}">
                                <input type="text" class="form-control" disabled="" value="{{$data_siswa->nm_pengguna}} - {{$data_siswa->nis_siswa}}">
                            </div>

                            <div class="col-md-4">
                                <label>Terjadi di</label>
                                <input type="text" class="form-control" disabled="" value="KELAS {{$data_kelas->nm_kelas}} MAPEL {{$data_kelas->nm_mata_pelajaran}}">
                            </div>

                            <div class="col-md-4">
                                <label>Pada pekan ke</label>
                                <input type="text" class="form-control" disabled="" value="{{$presensi_mp_aktif->pertemuan_ke}}">
                            </div>

                        </div>

                        <h2 class="card-inside-title">
                            Sub Kategori Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="id_subkategori_pelanggaran">
                                    <option value="" disabled selected >-- Pilih --</option>
                                    @foreach($data_kategori as $kategori)
                                    <optgroup label="{{$kategori->nm_kategori_pelanggaran}}">
                                        @foreach($kategori->subkategori_pelanggaran as $data)
                                            <option value="{{$data->id_subkategori_pelanggaran}}">{{$kategori->tingkat_kategori_pelanggaran}}.{{$data->tingkat_subkategori_pelanggaran}} {!!$data->nm_subkategori_pelanggaran!!}</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan_pelanggaran" required="" aria-required="true" aria-invalid="true">
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
    $('.select2').select2();
</script>