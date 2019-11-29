<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>PETUGAS PENERIMAAN</h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/post-view-petugas-penerimaan')}}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control" name="id_penerimaan" id="select-penerimaan">
                                        <option value="">- Pilih Penerimaan -</option>
                                        @foreach($grup_penerimaan_tahun as $tahun => $grup_penerimaan)
                                            @foreach($grup_penerimaan as $semester => $datapergrup)
                                                <optgroup label="{{$tahun}} {{$semester}}">
                                                    @foreach($datapergrup as $data)
                                                    <option value="{{$data->id_penerimaan}}">{{"Gelombang " . $data->gelombang_penerimaan . " " . $data->nm_penerimaan}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endforeach                                    
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect" ><i class="material-icons">save</i><span>Edit Petugas Penerimaan</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
