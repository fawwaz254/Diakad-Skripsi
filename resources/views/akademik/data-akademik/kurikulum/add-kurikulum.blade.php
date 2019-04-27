    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/kurikulum')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>
                            TAMBAH KURIKULUM
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-kurikulum/add/'.$id_kurikulum)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Jurusan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jurusan">
                                        @foreach($data_jurusan as $data)
                                        <option value="{{$data->id_jurusan}}">{{$data->nm_jurusan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Semester Mulai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_semester_mulai">
                                        @foreach($data_semester as $data)
                                        <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Nama Kurikulum
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_kurikulum" required="" aria-required="true" aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tahun Kurikulum
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="tahun_kurikulum" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nomor SK
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nomor_sk_kurikulum" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Keterangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan_kurikulum" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Berlaku Mulai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker form-control" name="berlaku_mulai" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Berlaku Sampai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker form-control" name="berlaku_sampai" required="" aria-required="true"
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
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
    </script>
