<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH MATA PELAJARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-komponen-nilai')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Mata Pelajaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas_mp">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($grup_semester_kelas as $tahun_ajaran => $grup_kelas)
                                        @foreach($grup_kelas as $nm_semester => $datapergrup)
                                            <optgroup label="{{$nm_semester}} ({{$tahun_ajaran}})">
                                                @foreach($datapergrup as $data)
                                                    @if($data->pjmp_pengampu_mp == 1)
                                                        <option value="{{$data->id_kelas_mp}}">{{$data->nm_mata_pelajaran . " (" . $data->kd_mata_pelajaran . ") Kelas " . $data->nm_kelas . " (PJMP)"}}</option>
                                                    @else
                                                        <option value="{{$data->id_kelas_mp}}">{{$data->nm_mata_pelajaran . " (" . $data->kd_mata_pelajaran . ") Kelas " . $data->nm_kelas . " (Anggota)"}}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endforeach 
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