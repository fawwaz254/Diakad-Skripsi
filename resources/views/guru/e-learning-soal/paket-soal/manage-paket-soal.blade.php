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
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Mata Pelajaran :</span>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="kategori" required="">
                                                @if ($item)
                                                    <option disabled>-- Pilih Mata Pelajaran --</option>
                                                    @foreach ($kategori as $r)
                                                        <option value="{{ $r->id_kategori_soal }}"
                                                            @if ($r->id_kategori_soal == $item->id_kategori_soal) selected @else disabled @endif>
                                                            {{ $r->nm_kategori_soal }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option selected disabled>-- Pilih Mata Pelajaran --</option>
                                                    @foreach ($kategori as $r)
                                                        <option value="{{ $r->id_kategori_soal }}">
                                                            {{ $r->nm_kategori_soal }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nama Paket Soal :</span>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
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
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Nilai Pilihan Ganda :</span>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="nilai" required="">
                                                @if ($item)
                                                    <option @if ($item->nilai == 20) selected @endif
                                                        value="20">20
                                                        Point per
                                                        Soal</option>
                                                    <option @if ($item->nilai == 10) selected @endif
                                                        value="10">10
                                                        Point per
                                                        Soal</option>
                                                    <option @if ($item->nilai == 5) selected @endif
                                                        value="5">
                                                        5 Point per
                                                        Soal</option>
                                                    <option @if ($item->nilai == 2.5) selected @endif
                                                        value="2.5">
                                                        2.5 Point per
                                                        Soal</option>
                                                    <option @if ($item->nilai == 2) selected @endif
                                                        value="2">
                                                        2 Point per
                                                        Soal</option>
                                                    <option @if ($item->nilai == 0) selected @endif
                                                        value="0">
                                                        Tidak Mengunakan Pilihan Ganda</option>
                                                @else
                                                    <option disabled selected value="">-- Pilih Nilai --</option>
                                                    <option value="20">20 Point per Soal</option>
                                                    <option value="10">10 Point per Soal</option>
                                                    <option value="5">5 Point per Soal</option>
                                                    <option value="2.5">2,5 Point per Soal</option>
                                                    <option value="2">2 Point per Soal</option>
                                                    <option value="0">Tidak Mengunakan Pilihan Ganda</option>
                                                @endif

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">kelas :</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="kelas[]" required=""
                                                required="">
                                                @if ($item)
                                                    @foreach ($kelas as $k)
                                                        <option @if ($item->paket_soal_kelas[0]->id_kelas == $k->id_kelas) selected @endif
                                                            value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                                    @endforeach
                                                @else
                                                    @if (isset($wali_kelas))
                                                        <option disabled value="">-- Pilih kelas --</option>
                                                        @foreach ($kelas as $k)
                                                            <option @if ($wali_kelas->id_kelas == $k->id_kelas) selected @endif
                                                                value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option disabled selected value="">-- Pilih kelas --
                                                        </option>
                                                        @foreach ($kelas as $k)
                                                            <option value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}
                                                            </option>
                                                        @endforeach

                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div id="tambah">
                                        <button class="btn btn-success btn-block" type="button"><i
                                                class="material-icons">add</i> Tambah</button>
                                    </div>
                                </div>

                            </div>
                            <div id="place">
                                @if ($item)
                                    @foreach ($item->paket_soal_kelas as $i => $paket_soal)
                                        @if ($i < 1)
                                            @continue
                                        @endif
                                        <div class="row clearfix">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <div class="input-group">
                                                    <span class="input-group-addon">kelas :</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="kelas[]"
                                                            required="" required="">

                                                            @foreach ($kelas as $k)
                                                                <option
                                                                    @if ($paket_soal->id_kelas == $k->id_kelas) selected @endif
                                                                    value="{{ $k->id_kelas }}">
                                                                    {{ $k->nm_kelas }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <button class="btn btn-danger btn-block delete_file" type="button"><i
                                                        class="material-icons">delete</i> Hapus</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Durasi :</span>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="waktu_pengerjaan"
                                                required="">
                                                @if ($item)
                                                    <option @if ($item->waktu_pengerjaan == '20160') selected @endif
                                                        value="20160">2
                                                        Minggu</option>
                                                    <option @if ($item->waktu_pengerjaan == '10080') selected @endif
                                                        value="10080">1
                                                        Minggu</option>
                                                    <option @if ($item->waktu_pengerjaan == '120') selected @endif
                                                        value="120">2
                                                        Jam</option>
                                                    <option @if ($item->waktu_pengerjaan == '90') selected @endif
                                                        value="90">1
                                                        Jam + 30 Menit</option>
                                                    <option @if ($item->waktu_pengerjaan == '75') selected @endif
                                                        value="75">1
                                                        Jam + 15 Menit</option>
                                                    <option @if ($item->waktu_pengerjaan == '60') selected @endif
                                                        value="60">1
                                                        Jam</option>
                                                    <option @if ($item->waktu_pengerjaan == '50') selected @endif
                                                        value="50">50
                                                        Menit</option>
                                                    <option @if ($item->waktu_pengerjaan == '40') selected @endif
                                                        value="40">40
                                                        Menit</option>
                                                    <option @if ($item->waktu_pengerjaan == '30') selected @endif
                                                        value="30">30
                                                        Menit</option>
                                                    <option @if ($item->waktu_pengerjaan == '20') selected @endif
                                                        value="20">
                                                        20
                                                        Menit</option>
                                                    <option @if ($item->waktu_pengerjaan == '10') selected @endif
                                                        value="10">
                                                        10
                                                        Menit</option>
                                                @else
                                                    <option disabled value="">-- Pilih Durasi Pengerjaan --
                                                    </option>
                                                    <option value="20160">2 Minggu</option>
                                                    <option value="10080">1 Minggu</option>
                                                    <option value="120">2 Jam</option>
                                                    <option value="90">1 Jam + 30 Menit</option>
                                                    <option value="75">1 Jam + 15 Menit</option>
                                                    <option value="60" selected>1 Jam</option>
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

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="input-group">
                                    <span class="input-group-addon">Waktu Mulai :</span>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group form-float">
                                    <div class="form-line">

                                        {{-- <label class="form-label">Title</label> --}}
                                        <input type="datetime-local" class="form-control" name="waktu_mulai"
                                            required="" aria-required="true" aria-invalid="true"
                                            @if ($item) value="{{ Carbon\Carbon::parse($item->waktu_mulai)->format('Y-m-d H:i') }}" @else value="{{ Carbon\Carbon::now()->format('Y-m-d H:i') }}" @endif>

                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="input-group">
                                    <span class="input-group-addon">Waktu Selesai :</span>
                                </div>
                            </div>

                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="datetime-local" class="form-control" name="waktu_selesai"
                                            required="" aria-required="true" aria-invalid="true"
                                            @if ($item) value="{{ Carbon\Carbon::parse($item->waktu_selesai)->format('Y-m-d H:i') }}" @else value="{{ Carbon\Carbon::now()->yesterday()->addDays(7)->format('Y-m-d H:i') }}" @endif>

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

<script>
    $('#tambah').click(function() {

        $('#place').append(`
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">kelas :</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control show-tick" name="kelas[]" required=""
                                                required="">
                                                        <option disabled selected value="">-- Pilih kelas --
                                                        </option>
                                                        @foreach ($kelas as $k)
                                                            <option value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}
                                                            </option>
                                                        @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <button class="btn btn-danger btn-block delete_file" type="button"><i class="material-icons">delete</i> Hapus</button>
                                </div>
                            </div>`);
    });
    $("#place").on("click", ".delete_file", function() {
        $(this).parent().parent().remove();
    })

    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY - HH:mm',
            lang: 'id',
            // clearButton: true,
            weekStart: 1,
            time: true
        });
    });
</script>
