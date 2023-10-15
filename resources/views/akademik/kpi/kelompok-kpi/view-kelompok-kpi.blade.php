<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        Komponen KPI
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tingkat Kelas
                                </h2>
                                <select class="form-control show-tick" name="tingkat">
                                    <option selected="" disabled="">Pilih Tingkat Kelas</option>
                                    @foreach ($tingkat_kelas as $tingkat_kel)
                                        <option value="{{ $tingkat_kel }}">{{ $tingkat_kel }}</option>
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
<br>

@include('scriptjs')
