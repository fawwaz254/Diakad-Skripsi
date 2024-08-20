<div class="container-fluid">
    {{-- <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#jadwal/input-jadwal')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div> --}}
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        SET JADWAL KELAS (KBM Tanpa Jadwal)
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    <option selected="" disabled="">Pilih Kelas</option>
                                    @foreach ($data_kelas as $kelas)
                                        <option value="{{ $kelas->id_kelas }}">{{ $kelas->nm_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach ($data_semester as $semester)
                                        @if ($semester->is_aktif_semester == 1)
                                            <option value="{{ $semester->id_semester }}" selected>
                                                {{ $semester->tahun_ajaran }} {{ $semester->nm_semester }} (Aktif)
                                            </option>
                                        @else
                                            <option value="{{ $semester->id_semester }}">{{ $semester->tahun_ajaran }}
                                                {{ $semester->nm_semester }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('scriptjs')
