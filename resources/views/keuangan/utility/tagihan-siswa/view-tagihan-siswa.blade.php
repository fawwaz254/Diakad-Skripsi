<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        GENERATE TAGIHAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-tagihan-siswa') }}">
                        {{ csrf_field() }}
                        <input type="hidden" value="0" name="id_jalur">
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="" disabled selected>-- Pilih Kelas --</option>
                                    <option value="0">-- Semua Kelas --</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tahun Ajaran
                                </h2>
                                <select class="form-control show-tick" name="thn_akademik_semester">
                                    {{-- <option value="" disabled selected>-- Tahun Ajaran --</option> --}}
                                    @foreach ($data_semester as $data)
                                        @if ($data->thn_akademik_semester == $data_semester_aktif->thn_akademik_semester)
                                            <option value="{{ $data->thn_akademik_semester }}" selected>
                                                {{ $data->tahun_ajaran }}
                                                (Aktif)
                                            </option>
                                        @else
                                            <option value="{{ $data->thn_akademik_semester }}">{{ $data->tahun_ajaran }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelompok Biaya
                                </h2>
                                <select class="form-control show-tick" name="id_kelompok_biaya">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($data_kelompok_biaya as $data)
                                        @if ($data->status_kelompok_biaya == 1)
                                            <option value="{{ $data->id_kelompok_biaya }}">
                                                {{ $data->nm_kelompok_biaya }}</option>
                                        @else
                                            <option value="{{ $data->id_kelompok_biaya }}">
                                                {{ $data->nm_kelompok_biaya }} (Khusus)</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jalur
                                </h2>
                                <select class="form-control show-tick" name="id_jalur">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($data_jalur as $data)
                                        <option value="{{ $data->id_jalur }}">{{ $data->nm_jalur }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            {{-- </div>
                        <div class="row clearfix"> --}}
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Insert / Sync Tagihan
                                    {{-- <small>* REPLACE digunakan untuk menghapus Tagihan Lama dan mengganti dengan Tagihan
                                        Baru <br>
                                        * Sync digunakan untuk memperbarui Nilai Tagihan</small> --}}
                                </h2>
                                <select class="form-control show-tick" name="is_insert_replace">
                                    <option value="1">Insert Tagihan</option>
                                    <!-- <option value="2">Replace Tagihan</option> -->
                                    {{-- <option value="3">Update Tagihan</option> --}}
                                    <option value="4">Sync Tagihan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
@include('scriptjs')
