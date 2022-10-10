<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-update-foto')}}">
                            {{csrf_field()}}
                            {{-- {{url(Request::segment(1).'/'.Request::segment(2).'/post-view-update-foto')}} --}}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Unit Kerja
                                </h2>
                                <select class="form-control show-tick" name="unit_kerja" id="unit_kerja">
                                    <option value="0">-- Semua --</option>
                                    @foreach($list_unit_kerja as $unit_kerja)
                                        <option value="{{$unit_kerja->id_unit_kerja}}">{{$unit_kerja->nm_unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Role
                                </h2>
                                <select class="form-control show-tick" name="status_join_table" id="status_join_table">
                                    <option value="0">-- Semua --</option>
                                    <option value="1">Tendik</option>
                                    <option value="2">Guru</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
