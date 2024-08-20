<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        ABSENSI TANPA JADWAL
                    </h2>
                </div>
                <div class="body">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="kbm_with_icon_title">
                            <form id="form-validation" method="POST"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/view-kbm') }}">
                                {{ csrf_field() }}
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input class="form-control" type="text" name=""
                                            value="TA {{ $semester_aktif->tahun_ajaran }} - {{ $semester_aktif->nm_semester }}"
                                            readonly>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Tanggal
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name=""
                                            value="{{ $tanggal_id }}" readonly>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Mata Pelajaran
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_jadwal_kelas_mp">
                                            <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                                            @foreach ($jadwal_kelas_mp as $kelas_mp)
                                                <option value="{{ $kelas_mp->id_jadwal_kelas_mp }}">
                                                    {{ $kelas_mp->nm_kelas_mp }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_kelas">
                                            <option value="" disabled selected>-- Pilih Kelas --</option>
                                            @foreach ($kelas as $k)
                                                <option value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                {{-- <h2 class="card-inside-title">
                                    Pertemuan pekan ke
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="pertemuan_ke">
                                            <option value="" disabled selected>-- Pilih Pertemuan pekan ke --
                                            </option>
                                        </select>
                                    </div>
                                </div> --}}
                                {{-- <h2 class="card-inside-title">
                                    Opsi Hadir
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="opsi">
                                            <option value="0">-- Semua --</option>
                                            <option value="1">Ganjil</option>
                                            <option value="2">Genap</option>
                                            <option value="3">Setengah Awal</option>
                                            <option value="4">Setengah Akhir</option>
                                            <option value="5">Laki-laki</option>
                                            <option value="6">Perempuan</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <input type="hidden" name="opsi" value="0">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Save</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
