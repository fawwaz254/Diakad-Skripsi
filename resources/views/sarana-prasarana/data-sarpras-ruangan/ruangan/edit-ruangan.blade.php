<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#data-sarpras-ruangan/ruangan') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT RUANGAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-ruangan/edit/' . $data_ruangan->id_ruangan) }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Jenis Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_ruangan">
                                    @foreach ($data_jenis_ruangan as $data)
                                        @if ($data->id_jenis_ruangan == $data_ruangan->id_jenis_ruangan)
                                            <option value="{{ $data->id_jenis_ruangan }}" selected>
                                                {{ $data->nm_jenis_ruangan }}</option>
                                        @else
                                            <option value="{{ $data->id_jenis_ruangan }}">{{ $data->nm_jenis_ruangan }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Kelas <sup style="color: red">*Pilih Jika Ruang Kelas</sup>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="">Bukan Kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($data_ruangan->id_kelas == $k->id_kelas) selected @endif>{{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Gedung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_gedung">
                                    @foreach ($data_gedung as $data)
                                        @if ($data->id_gedung == $data_ruangan->id_gedung)
                                            <option value="{{ $data->id_gedung }}" selected>{{ $data->nm_gedung }}
                                            </option>
                                        @else
                                            <option value="{{ $data->id_gedung }}">{{ $data->nm_gedung }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Pemilik Sarpras
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_pemilik_sarpras">
                                    @foreach ($data_pemilik_sarpras as $data)
                                        @if ($data->id_pemilik_sarpras == $data_ruangan->id_pemilik_sarpras)
                                            <option value="{{ $data->id_pemilik_sarpras }}" selected>
                                                {{ $data->nm_pemilik_sarpras }}</option>
                                        @else
                                            <option value="{{ $data->id_pemilik_sarpras }}">
                                                {{ $data->nm_pemilik_sarpras }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_ruangan" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $data_ruangan->nm_ruangan }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kapasitas Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="kapasitas_ruangan" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_ruangan->kapasitas_ruangan }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kapasitas Ujian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="kapasitas_ujian" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_ruangan->kapasitas_ujian }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea rows="4" cols="50" class="form-control" name="deskripsi_ruangan" required=""
                                    aria-required="true" aria-invalid="true">{{ $data_ruangan->deskripsi_ruangan }}</textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">
                                    @if ($data_ruangan->is_aktif == 1)
                                        <option value="1" selected>Aktif</option>
                                        <option value="0">Non-Aktif</option>
                                    @else
                                        <option value="1">Aktif</option>
                                        <option value="0" selected>Non-Aktif</option>
                                    @endif
                                </select>
                            </div>
                        </div>
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
@include('scriptjs')
