<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/usulan-mata-ajar/view-semester-usulan-mata-ajar/'.$kelas_mp->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Update Usulan Mata Ajar
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-usulan-mata-ajar/edit/'.$kelas_mp->id_kelas_mp)}}">
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
                       
                        <div class="row clearfix">

                            <div class="col-md-3">
                                <label>Jenis Mapel</label>
                                <input type="text" class="form-control" name="nm_mata_pelajaran" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->nm_jenis_mata_pelajaran}}">
                            </div>

                            <div class="col-md-3">
                                <label> Kelas</label>
                                <input type="text" class="form-control" name="nm_kelas" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->nm_kelas}}">
                            </div>

                            <div class="col-md-3">
                                <label>Semester</label>
                                <input type="text" class="form-control" name="semester" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$kelas_mp->nm_semester}}  {{$kelas_mp->tahun_ajaran}}">
                                <input type="hidden" name="id_semester" value="{{$kelas_mp->id_semester}}">
                                <input type="hidden" name="id_mata_pelajaran" value="{{$kelas_mp->id_mata_pelajaran}}">
                                <input type="hidden" name="id_kelas_mp" value="{{$kelas_mp->id_kelas_mp}}">
                            </div>

                             <div class="col-md-3">
                                <label> Rencana Pertemuan</label>
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
                        <br>
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

                        @php
                            $j = 1
                        @endphp
                        @foreach($anggota as $data1)
                            <input type="hidden"  value="{{$data1->id_pengampu_mp}}" class="form-control" name="id_pengampu_mp_{{$j}}" aria-required="true">

                            @php
                                $j++
                            @endphp
                        @endforeach

                        @for($k=1;$k<=6;$k++)

                        <div class="panel">
                            <div class="panel-heading demo-color-box bg-success">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#panel_jadwal{{$k}}">
                                        <i class="material-icons">date_range</i> Jadwal {{$k}}
                                    </a>
                                </div>
                            </div>

                            <div id="panel_jadwal{{$k}}" class="panel-collapse collapse {{$k==1 ? 'in' : ''}}">
                                <div class="panel-body">

                                    <div class="row">

                                        <div class="row clearfix">

                                            <div class="col-md-6">
                                                <label>Hari</label>
                                                <select class="form-control show-tick" name="hari_jadwal{{$k}}" required >
                                                    <option value="">-- Pilih Hari --</option>
                                                    @foreach($hari as $data)
                                                        @if($jml_jadwal < $k)
                                                            <option value="{{$data->id_jadwal_hari}}">
                                                                {{$data->nm_jadwal_hari}}
                                                            </option>
                                                        @else
                                                            <option value="{{$data->id_jadwal_hari}}" @if($jadwal[$k-1]->id_jadwal_hari == $data->id_jadwal_hari) selected @endif>
                                                                {{$data->nm_jadwal_hari}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Ruangan Kelas</label>
                                                <select class="form-control show-tick select2" name="ruangan{{$k}}" required  style="width:100%;">
                                                    <option value="">-- Pilih Ruangan Kelas --</option>
                                                    @foreach($ruangan as $data)
                                                        @if($jml_jadwal < $k)
                                                            <option value="{{$data->id_ruangan}}">
                                                                {{$data->nm_ruangan}} ({{$data->nm_gedung}})
                                                            </option>
                                                        @else
                                                            <option value="{{$data->id_ruangan}}" @if($jadwal[$k-1]->id_ruangan == $data->id_ruangan) selected @endif>
                                                                {{$data->nm_ruangan}} ({{$data->nm_gedung}})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="row clearfix">

                                            <div class="col-md-6">
                                                <label> Jam Mulai</label>
                                                <select class="form-control show-tick" name="jam_jadwal{{$k}}" required >
                                                    <option value="">-- Pilih Jam --</option>
                                                    @foreach($jam as $data)
                                                        @if($jml_jadwal < $k)
                                                            <option value="{{$data->id_jadwal_jam}}">
                                                                {{$data->nm_jadwal_jam}} ( {{$data->jam_mulai}} : {{$data->menit_mulai}} - {{$data->jam_selesai}}:{{$data->menit_selesai}} )
                                                            </option>
                                                        @else
                                                            <option value="{{$data->id_jadwal_jam}}" @if($jadwal[$k-1]->id_jadwal_jam == $data->id_jadwal_jam) selected @endif>
                                                               {{$data->nm_jadwal_jam}} ( {{$data->jam_mulai}} : {{$data->menit_mulai}} - {{$data->jam_selesai}}:{{$data->menit_selesai}} )
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Jam Selesai</label>
                                                <select class="form-control show-tick" name="jam_jadwal_selesai{{$k}}" required >
                                                    <option value="">-- Pilih Jam --</option>
                                                    @foreach($jam as $data)
                                                        @if($jml_jadwal < $k)
                                                            <option value="{{$data->id_jadwal_jam}}">
                                                               {{$data->nm_jadwal_jam}} ( {{$data->jam_mulai}} : {{$data->menit_mulai}} - {{$data->jam_selesai}} : {{$data->menit_selesai}} )
                                                            </option>
                                                        @else
                                                            <option value="{{$data->id_jadwal_jam}}" @if($jadwal[$k-1]->id_jadwal_jam_selesai == $data->id_jadwal_jam) selected @endif>
                                                                {{$data->nm_jadwal_jam}} ( {{$data->jam_mulai}} : {{$data->menit_mulai}} - {{$data->jam_selesai}} : {{$data->menit_selesai}} )
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                    </div>
 
                                </div>
                            </div>

                        </div>

                        @endfor
                        
                        <div class="demo-color-box bg-success">
                                Informasi Penanggungjawab Mata Pelajaran 
                        </div>
                        <h2 class="card-inside-title">
                            Penanggungjawab Mata Ajar
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="pjma" required  style="width:100%;">
                                    <option value="">-- Pilih Guru  --</option>
                                    @foreach($pjma as $data)
                                        @if($pengampu_mp_pj == null)
                                            <option value="{{$data->id_guru}}">
                                                {{$data->gelar_depan}}{{$data->nm_pengguna}},{{$data->gelar_belakang}}
                                            </option>
                                        @else
                                            <option value="{{$data->id_guru}}" @if($pengampu_mp_pj->id_guru == $data->id_guru) selected @endif>
                                                {{$data->gelar_depan}}{{$data->nm_pengguna}},{{$data->gelar_belakang}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tim PJMA 1
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="pjma_tim1" style="width:100%;">
                                    <option value="">-- Pilih Guru  --</option>
                                    @foreach($pjma as $data)
                                        @if($jml_anggota < 1)
                                            <option value="{{$data->id_guru}}">
                                                {{$data->gelar_depan}}{{$data->nm_pengguna}},{{$data->gelar_belakang}}
                                            </option>
                                        @else
                                            <option value="{{$data->id_guru}}" @if($anggota[0]->id_guru == $data->id_guru) selected @endif>
                                                {{$data->gelar_depan}}{{$data->nm_pengguna}},{{$data->gelar_belakang}}
                                            </option>
                                        @endif
                                     @endforeach
                                </select>
                            </div>
                        </div>
    
                        <h2 class="card-inside-title">
                            Tim PJMA 2
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="pjma_tim2" style="width:100%;">
                                    <option value="">-- Pilih Guru  --</option>
                                    @foreach($pjma as $data)
                                        @if($jml_anggota < 2)
                                            <option value="{{$data->id_guru}}">
                                                {{$data->gelar_depan}}{{$data->nm_pengguna}},{{$data->gelar_belakang}}
                                            </option>
                                        @else
                                            <option value="{{$data->id_guru}}" @if($anggota[1]->id_guru == $data->id_guru) selected @endif>
                                                {{$data->gelar_depan}}{{$data->nm_pengguna}},{{$data->gelar_belakang}}
                                            </option>
                                        @endif
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