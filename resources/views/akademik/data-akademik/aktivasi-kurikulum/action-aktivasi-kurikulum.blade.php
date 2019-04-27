    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/aktivasi-kurikulum')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>
                            AKTIVASI KURIKULUM
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-aktivasi-kurikulum/aktivasi/'.$data_kurikulum->id_kurikulum)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Jurusan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jurusan">
                                        <option value="{{$data_jurusan->id_jurusan}}" readonly>{{$data_jurusan->nm_jurusan}}</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Semester Mulai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_semester_mulai">
                                        <option value="{{$data_semester->id_semester}}" readonly>{{$data_semester->tahun_ajaran}}
                                            {{$data_semester->nm_semester}}</option>
                                    </select>
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Nama Kurikulum
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_kurikulum" required="" aria-required="true" aria-invalid="true"
                                        value="{{$data_kurikulum->nm_kurikulum}}" readonly>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tahun Kurikulum
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="tahun_kurikulum" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_kurikulum->tahun_kurikulum}}" readonly>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nomor SK
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="no_sk_kurikulum" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_kurikulum->nomor_sk_kurikulum}}" readonly>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Keterangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan_kurikulum" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_kurikulum->keterangan_kurikulum}}" readonly>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Berlaku Mulai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="berlaku_mulai" required="" aria-required="true" aria-invalid="true"
                                        value="{{$berlaku_mulai}}" readonly>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Berlaku Sampai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="berlaku_sampai" required="" aria-required="true" aria-invalid="true"
                                        value="{{$berlaku_sampai}}" readonly>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Status Aktif
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="is_aktif">
                                        @if($data_kurikulum->is_aktif == 0)
                                        <option value="0" selected>Non-Aktif</option>
                                        @else
                                        <option value="0">Non-Aktif</option>
                                        @endif
                                        @if($data_kurikulum->is_aktif == 1)
                                        <option value="1" selected>Aktif</option>
                                        @else
                                        <option value="1">Aktif</option>
                                        @endif
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
