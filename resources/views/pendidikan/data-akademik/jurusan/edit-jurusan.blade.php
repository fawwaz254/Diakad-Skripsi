<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-akademik/jurusan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT JURUSAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-jurusan/edit/'.$data_jurusan->id_jurusan)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Jurusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_jurusan" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_jurusan->nm_jurusan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Jurusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_jurusan" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_jurusan->kode_jurusan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Bidang Keahlian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="bidang_keahlian"  aria-required="true" aria-invalid="true"
                                value="{{$data_jurusan->bidang_keahlian}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Program Keahlian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="program_keahlian"  aria-required="true" aria-invalid="true"
                                value="{{$data_jurusan->program_keahlian}}">
                            </div>
                        </div>
                        @if($selected_jurusan->tingkat == '1')
                        <h2 class="card-inside-title">
                            Konsentrasi Keahlian
                        </h2>
                        @else
                        <h2 class="card-inside-title">
                            Kompetensi Keahlian
                        </h2>
                        @endif
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kompetensi_keahlian"  aria-required="true" aria-invalid="true"
                                value="{{$data_jurusan->kompetensi_keahlian}}">
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