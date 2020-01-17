<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#jadwal/input-jadwal')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Input Jadwal Mata Ajar
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-jadwal/edit/'.$kelas_mp->id_kelas_mp)}}">
                        {{csrf_field()}}
                        <div class="demo-color-box bg-success">
                            Informasi Kelas dan Mata Pelajaran
                        </div>
                        <h2 class="card-inside-title">
                            Mata Pelajaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_mata_pelajaran" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->nm_mata_pelajaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Mapel
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_mata_pelajaran" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->nm_jenis_mata_pelajaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kelas" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->nm_kelas}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="semester" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->nm_semester}}  {{$kelas_mp->tahun_ajaran}}">
                                <input type="hidden" name="id_semester" value="{{$kelas_mp->id_semester}}">
                                <input type="hidden" name="id_mata_pelajaran" value="{{$kelas_mp->id_mata_pelajaran}}">
                            </div>
                        </div>
                         
                        <h2 class="card-inside-title">
                            Rencana Pertemuan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="jml_pertemuan_kelas_mp" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->jml_pertemuan_kelas_mp}}">
                            </div>
                        </div>
                       <!--  <h2 class="card-inside-title">
                            Kapasitas Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kapasitas_ruangan" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->kapasitas_ruangan}}">
                            </div>
                        </div>  -->
                        <div class="demo-color-box bg-success">
                            Informasi Jadwal dan Ruangan
                        </div>
                        <br><br>
                        @php
                            $i = 1
                        @endphp
                        @foreach($jadwal as $data)
                            <input type="hidden" value="{{$data->id_jadwal_kelas_mp}}" class="form-control" name="id_jadwal_kelas_mp_{{$i}}" aria-required="true">

                            @php
                                $i++
                            @endphp
                        @endforeach

                        @if(isset($pengampu_mp_pj))
                            <input type="hidden"  value="{{$pengampu_mp_pj->id_pengampu_mp}}" class="form-control" name="id_pengampu_mp_pj" aria-required="true">
                        @endif
                        

                        <div class="panel">
                            <div class="panel-heading demo-color-box bg-success">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#panel_jadwal1">
                                        <i class="material-icons">date_range</i> Jadwal 1
                                    </a>
                                </div>
                            </div>
                            <div id="panel_jadwal1" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <h3 class="card-inside-title">
                                        Hari
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="hari_jadwal1" required >
                                                <option value="">-- Pilih Hari --</option>
                                                @foreach($hari as $data)
                                                    @if($jml_jadwal < 1)
                                                        <option value="{{$data->id_jadwal_hari}}">
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_hari}}" @if($jadwal[0]->id_jadwal_hari == $data->id_jadwal_hari) selected @endif>
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Mulai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal1" required >
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 1)
                                                        <option value="{{$data->id_jadwal_jam}}">
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[0]->id_jadwal_jam == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Selesai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal_selesai1" required >
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 1)
                                                        <option value="{{$data->id_jadwal_jam}}">
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[0]->id_jadwal_jam_selesai == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Ruangan Kelas
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick select2" name="ruangan1" required  style="width:100%;">
                                                <option value="">-- Pilih Ruangan Kelas --</option>
                                                @foreach($ruangan as $data)
                                                    @if($jml_jadwal < 1)
                                                        <option value="{{$data->id_ruangan}}">
                                                            {{$data->nm_ruangan}}({{$data->nm_gedung}})
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_ruangan}}" @if($jadwal[0]->id_ruangan == $data->id_ruangan) selected @endif>
                                                            {{$data->nm_ruangan}}({{$data->nm_gedung}})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading demo-color-box bg-success">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#panel_jadwal2">
                                        <i class="material-icons">date_range</i> Jadwal 2
                                    </a>
                                </div>
                            </div>
                            <div id="panel_jadwal2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h3 class="card-inside-title">
                                        Hari
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="hari_jadwal2">
                                                <option value="">-- Pilih Hari --</option>
                                                @foreach($hari as $data)
                                                    @if($jml_jadwal < 2)
                                                        <option value="{{$data->id_jadwal_hari}}">
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_hari}}" @if($jadwal[1]->id_jadwal_hari == $data->id_jadwal_hari) selected @endif>
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Mulai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal2">
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 2)
                                                        <option value="{{$data->id_jadwal_jam}}">{{$data->nm_jadwal_jam}}</option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[1]->id_jadwal_jam == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Selesai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal_selesai2" >
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 2)
                                                        <option value="{{$data->id_jadwal_jam}}">
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[1]->id_jadwal_jam_selesai == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Ruangan Kelas
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick select2" name="ruangan2" style="width:100%;">
                                                <option value="">-- Pilih Ruangan Kelas --</option>
                                                @foreach($ruangan as $data)
                                                    @if($jml_jadwal < 2)
                                                        <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}}({{$data->nm_gedung}})</option>
                                                    @else
                                                        <option value="{{$data->id_ruangan}}" @if($jadwal[1]->id_ruangan == $data->id_ruangan) selected @endif>
                                                            {{$data->nm_ruangan}}({{$data->nm_gedung}})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading demo-color-box bg-success">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#panel_jadwal3">
                                        <i class="material-icons">date_range</i> Jadwal 3
                                    </a>
                                </div>
                            </div>
                            <div id="panel_jadwal3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h3 class="card-inside-title">
                                        Hari
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="hari_jadwal3">
                                                <option value="">-- Pilih Hari --</option>
                                                @foreach($hari as $data)
                                                    @if($jml_jadwal < 3)
                                                        <option value="{{$data->id_jadwal_hari}}">
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_hari}}" @if($jadwal[2]->id_jadwal_hari == $data->id_jadwal_hari) selected @endif>
                                                            {{$data->nm_jadwal_hari}} 
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Mulai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal3">
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 3)
                                                        <option value="{{$data->id_jadwal_jam}}">{{$data->nm_jadwal_jam}}</option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[2]->id_jadwal_jam == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Selesai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal_selesai3" >
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 3)
                                                        <option value="{{$data->id_jadwal_jam}}">
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[2]->id_jadwal_jam_selesai == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Ruangan Kelas
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick select2" name="ruangan3" style="width:100%;">
                                                <option value="">-- Pilih Ruangan Kelas --</option>
                                                @foreach($ruangan as $data)
                                                    @if($jml_jadwal < 3)
                                                        <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}}({{$data->nm_gedung}})</option>
                                                    @else
                                                        <option value="{{$data->id_ruangan}}" @if($jadwal[2]->id_ruangan == $data->id_ruangan) selected @endif>
                                                            {{$data->nm_ruangan}}({{$data->nm_gedung}})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading demo-color-box bg-success">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#panel_jadwal4">
                                        <i class="material-icons">date_range</i> Jadwal 4
                                    </a>
                                </div>
                            </div>
                            <div id="panel_jadwal4" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h3 class="card-inside-title">
                                        Hari
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="hari_jadwal4">
                                                <option value="">-- Pilih Hari --</option>
                                                @foreach($hari as $data)
                                                    @if($jml_jadwal < 4)
                                                        <option value="{{$data->id_jadwal_hari}}">
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_hari}}" @if($jadwal[3]->id_jadwal_hari == $data->id_jadwal_hari) selected @endif>
                                                            {{$data->nm_jadwal_hari}} 
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Mulai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal4">
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 4)
                                                        <option value="{{$data->id_jadwal_jam}}">{{$data->nm_jadwal_jam}}</option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[3]->id_jadwal_jam == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Selesai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal_selesai4" >
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 4)
                                                        <option value="{{$data->id_jadwal_jam}}">
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[3]->id_jadwal_jam_selesai == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Ruangan Kelas
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick select2" name="ruangan4" style="width:100%;">
                                                <option value="">-- Pilih Ruangan Kelas --</option>
                                                @foreach($ruangan as $data)
                                                    @if($jml_jadwal < 4)
                                                        <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}}({{$data->nm_gedung}})</option>
                                                    @else
                                                        <option value="{{$data->id_ruangan}}" @if($jadwal[3]->id_ruangan == $data->id_ruangan) selected @endif>
                                                            {{$data->nm_ruangan}}({{$data->nm_gedung}})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading demo-color-box bg-success">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#panel_jadwal5">
                                        <i class="material-icons">date_range</i> Jadwal 5
                                    </a>
                                </div>
                            </div>
                            <div id="panel_jadwal5" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h3 class="card-inside-title">
                                        Hari
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="hari_jadwal5">
                                                <option value="">-- Pilih Hari --</option>
                                                @foreach($hari as $data)
                                                    @if($jml_jadwal < 5)
                                                        <option value="{{$data->id_jadwal_hari}}">
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_hari}}" @if($jadwal[4]->id_jadwal_hari == $data->id_jadwal_hari) selected @endif>
                                                            {{$data->nm_jadwal_hari}} 
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Mulai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal5">
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 5)
                                                        <option value="{{$data->id_jadwal_jam}}">{{$data->nm_jadwal_jam}}</option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[4]->id_jadwal_jam == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Selesai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal_selesai5" >
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 5)
                                                        <option value="{{$data->id_jadwal_jam}}">
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[4]->id_jadwal_jam_selesai == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Ruangan Kelas
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick select2" name="ruangan5" style="width:100%;">
                                                <option value="">-- Pilih Ruangan Kelas --</option>
                                                @foreach($ruangan as $data)
                                                    @if($jml_jadwal < 5)
                                                        <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}}({{$data->nm_gedung}})</option>
                                                    @else
                                                        <option value="{{$data->id_ruangan}}" @if($jadwal[4]->id_ruangan == $data->id_ruangan) selected @endif>
                                                            {{$data->nm_ruangan}}({{$data->nm_gedung}})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel-heading demo-color-box bg-success">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#panel_jadwal6">
                                        <i class="material-icons">date_range</i> Jadwal 6
                                    </a>
                                </div>
                            </div>
                            <div id="panel_jadwal6" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <h3 class="card-inside-title">
                                        Hari
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="hari_jadwal6">
                                                <option value="">-- Pilih Hari --</option>
                                                @foreach($hari as $data)
                                                    @if($jml_jadwal < 6)
                                                        <option value="{{$data->id_jadwal_hari}}">
                                                            {{$data->nm_jadwal_hari}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_hari}}" @if($jadwal[5]->id_jadwal_hari == $data->id_jadwal_hari) selected @endif>
                                                            {{$data->nm_jadwal_hari}} 
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Mulai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal6">
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 6)
                                                        <option value="{{$data->id_jadwal_jam}}">{{$data->nm_jadwal_jam}}</option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[5]->id_jadwal_jam == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Jam Selesai
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jam_jadwal_selesai6" >
                                                <option value="">-- Pilih Jam --</option>
                                                @foreach($jam as $data)
                                                    @if($jml_jadwal < 6)
                                                        <option value="{{$data->id_jadwal_jam}}">
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @else
                                                        <option value="{{$data->id_jadwal_jam}}" @if($jadwal[5]->id_jadwal_jam_selesai == $data->id_jadwal_jam) selected @endif>
                                                            {{$data->nm_jadwal_jam}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <h3 class="card-inside-title">
                                        Ruangan Kelas
                                    </h3>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick select2" name="ruangan6" style="width:100%;">
                                                <option value="">-- Pilih Ruangan Kelas --</option>
                                                @foreach($ruangan as $data)
                                                    @if($jml_jadwal < 6)
                                                        <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}}({{$data->nm_gedung}})</option>
                                                    @else
                                                        <option value="{{$data->id_ruangan}}" @if($jadwal[5]->id_ruangan == $data->id_ruangan) selected @endif>
                                                            {{$data->nm_ruangan}}({{$data->nm_gedung}})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')

<script>
    $('.select2').select2();
</script>