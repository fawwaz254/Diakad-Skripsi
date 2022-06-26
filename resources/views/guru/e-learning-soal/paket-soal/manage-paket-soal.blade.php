<div class="container-fluid">
    <div class="block-header">
        <h2><a type="button" class="btn bg-grey waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal') }}">
                <i class="material-icons">keyboard_backspace</i>
                <span>Kembali</span>
            </a>
    </div>
    <div class="row clearfix">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        Paket Soal
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <form class="form-validation" id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_paket_soal"
                                @if ($item) value="{{ $item->id_paket_soal }}" @endif>
                            <div class="row clearfix">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Mapel :</span>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <select class="form-control show-tick" name="kategori">
                                        <option selected disabled>-- Pilih Mata Pelajaran --</option>
                                        @foreach ($kategori as $r)
                                        <option value="{{ $r->id_kategori_soal }}" @if($r->id_kategori_soal == $item->id_kategori_soal) selected @endif>{{ $r->nm_kategori_soal }}</option>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                   
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <div class="input-group">
                                <span class="input-group-addon">Nama :</span>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="title" required=""
                                        aria-required="true" aria-invalid="true"
                                        @if ($item) value="{{ $item->text }}" @endif>
                                    {{-- <label class="form-label">Title</label> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <div class="input-group">
                                <span class="input-group-addon">Nilai :</span>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="nilai" required="">
                                        @if ($item)
                                            <option @if ($item->nilai == 20) selected @endif value="20">20
                                                Point per
                                                Soal</option>
                                            <option @if ($item->nilai == 10) selected @endif value="10">10
                                                Point per
                                                Soal</option>
                                            <option @if ($item->nilai == 5) selected @endif value="5">
                                                5 Point per
                                                Soal</option>
                                        @else
                                            <option disabled selected value="">-- Pilih Nilai --</option>
                                            <option value="20">20 Point per Soal</option>
                                            <option value="10">10 Point per Soal</option>
                                            <option value="5">5 Point per Soal</option>
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <div class="input-group">
                                <span class="input-group-addon">kelas :</span>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="kelas" required="" required="">
                                        @if ($item)
                                            @foreach ($kelas as $k)
                                                <option @if ($item->id_kelas == $k->id_kelas) selected @endif
                                                    value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                            @endforeach
                                        @else
                                            <option disabled selected value="">-- Pilih kelas --</option>
                                            @foreach ($kelas as $k)
                                                <option value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <div class="input-group">
                                <span class="input-group-addon">Durasi :</span>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="waktu_pengerjaan" required="">
                                        @if ($item)
                                            <option @if ($item->waktu_pengerjaan == 60) selected @endif value="60">1
                                                Jam</option>
                                            <option @if ($item->waktu_pengerjaan == 50) selected @endif value="50">50
                                                Menit</option>
                                            <option @if ($item->waktu_pengerjaan == 40) selected @endif value="40">40
                                                Menit</option>
                                            <option @if ($item->waktu_pengerjaan == 30) selected @endif value="30">30
                                                Menit</option>
                                            <option @if ($item->waktu_pengerjaan == 20) selected @endif value="20">
                                                20
                                                Menit</option>
                                            <option @if ($item->waktu_pengerjaan == 10) selected @endif value="10">
                                                10
                                                Menit</option>
                                        @else
                                            <option disabled selected value="">-- Pilih Durasi Pengerjaan --
                                            </option>
                                            <option value="60">1 Jam</option>
                                            <option value="50">50 Menit</option>
                                            <option value="40">40 Menit</option>
                                            <option value="30">30 Menit</option>
                                            <option value="20">20 Menit</option>
                                            <option value="10">10 Menit</option>
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <div class="input-group">
                            <span class="input-group-addon">Waktu Mulai :</span>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                        <div class="form-group form-float">
                            <div class="form-line">

                                {{-- <label class="form-label">Title</label> --}}
                                <input type="datetime-local" class="form-control" name="waktu_mulai" required=""
                                    aria-required="true" aria-invalid="true"
                                    @if ($item) value="{{ $item->waktu_mulai }}" @endif>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <div class="input-group">
                            <span class="input-group-addon">Waktu Selesai :</span>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="datetime-local" class="form-control" name="waktu_selesai"
                                    required="" aria-required="true" aria-invalid="true"
                                    @if ($item) value="{{ $item->waktu_selesai }}" @endif>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <button class="btn btn-block bg-pink waves-effect" id="btn-submit"
                            type="submit">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('scriptjs')
