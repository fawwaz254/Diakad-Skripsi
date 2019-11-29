<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/usulan-mata-ajar/tambah-mata-ajar/'.$id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH KELAS UNTUK USULAN MATA AJAR Mata Pelajaran : {{$mapel->nm_mata_pelajaran}}
                    </h2>
                    <br>
                    <h2 style="font-size: 18px">Semester : {{$semester->tahun_ajaran}} ({{$semester->nm_semester}})</h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-usulan-mata-ajar/add/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Mata Pelajaran
                        </h2>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>{{$mapel->kd_mata_pelajaran}} - {{$mapel->nm_mata_pelajaran}}</label>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Mapel
                        </h2>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>{{$mapel->nm_jenis_mata_pelajaran}}</label>
                        </div>
                        <h2 class="card-inside-title">
                            Jurusan
                        </h2>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>{{$mapel->nm_jurusan}}</label>
                        </div>
                        <h2 class="card-inside-title">
                            Tingkat Semester
                        </h2>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>{{$mapel->tingkat_semester}}</label>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach($kelas as $data)
                                    <option value="{{$data->id_kelas}}">
                                        {{$data->nm_kelas}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jumlah Pertemuan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="jml_pertemuan_kelas_mp" required="" aria-required="true"
                                aria-invalid="true">
                                <input type="hidden"  value="{{$id_mata_pelajaran}}" class="form-control" name="id_mata_pelajaran" aria-required="true">
                                <input type="hidden"  value="{{$id_semester}}" class="form-control" name="id_semester" aria-required="true">
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
