<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pembina-ekskul/komponen-nilai-ekskul/list/'.$semester->id_semester.'/'.$data_ekskul->id_ekskul)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH KOMPONEN NILAI EKSKUL {{strtoupper($data_ekskul->nm_ekskul) . " - TA " . $semester->tahun_ajaran . ' ' . $semester->nm_semester}}
                    </h2>
                </div>
                <div class="body">
                    @if($id_komponen_ekskul !== null)
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-komponen-nilai-ekskul/edit/'.$id_komponen_ekskul)}}">
                    @else
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-komponen-nilai-ekskul/add/'.$id_komponen_ekskul)}}">
                    @endif
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Komponen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_komponen_ekskul" required="" aria-required="true"
                                    aria-invalid="true" value="{{ !empty($komponen_ekskul) ? $komponen_ekskul->nm_komponen_ekskul : null }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Persentase Komponen <small><b>* Cukup Angka Saja Tanpa Tanda %</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="persentase_komponen_ekskul" required="" aria-required="true" aria-invalid="true" value="{{ !empty($komponen_ekskul) ? $komponen_ekskul->persentase_komponen_ekskul : null }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Urutan Komponen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="urutan_komponen_ekskul" required="" aria-required="true" aria-invalid="true" value="{{ !empty($komponen_ekskul) ? $komponen_ekskul->urutan_komponen_ekskul : null }}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" class="form-control" name="id_ekskul" required="" aria-required="true" aria-invalid="true" value="{{$data_ekskul->id_ekskul}}">
                                <input type="hidden" class="form-control" name="id_semester" required="" aria-required="true" aria-invalid="true" value="{{$semester->id_semester}}">
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