<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-akademik/rentang-nilai-mutu')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH RENTANG NILAI MUTU
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-rentang-nilai-mutu/add/'.$id_peraturan_nilai)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_mata_pelajaran">
                                    <option value="0">Umum (Ekskul, Magang, dll)</option>
                                    <option value="1">Mapel</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nilai KKM <small><b>* Diisi Hanya Ketika Status "Mapel"</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nilai_kkm" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nilai Huruf
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_standar_nilai">
                                    @foreach($data_nilai_mutu as $data)
                                    <option value="{{$data->id_standar_nilai}}">{{$data->nm_standar_nilai}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Rentang Nilai Minimal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nilai_min_peraturan_nilai" required="" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Rentang Nilai Maksimal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nilai_max_peraturan_nilai" required="" aria-required="true"
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