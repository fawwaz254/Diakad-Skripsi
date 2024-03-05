<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/pertanyaan/' . $id_form) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH PERTANYAAN @if ($jenis_pertanyaan == '3')
                            SATU OPSI
                        @else
                            BANYAK OPSI
                        @endif
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/list-form/pertanyaan/action-pertanyaan-form/add/0') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_form" value="{{ $id_form }}">
                        <input type="hidden" name="jenis_pertanyaan" value="{{ $jenis_pertanyaan }}">

                        <h2 class="card-inside-title">
                            Pertanyaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pertanyaan_form" required=""
                                    aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>

                        @if ($jenis_pertanyaan == '3')
                            <h2 class="card-inside-title">
                                Opsi
                                <small>Masukkan Opsi Jawaban disertai warna label</small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <input type="text" class="form-control" name="options[]" required=""
                                        aria-required="true" aria-invalid="true" value="">
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    <input type="color" class="form-control form-control-color" name="color[]"
                                        required="" aria-required="true" value="#563d7c">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div id="tambah">
                                        <button class="btn btn-success btn-block" type="button"><i
                                                class="material-icons">add</i> Tambah Opsi</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <h2 class="card-inside-title">
                                Opsi
                                <small>Masukkan Opsi Jawaban</small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="options[]" required=""
                                        aria-required="true" aria-invalid="true" value="">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <div id="tambah">
                                        <button class="btn btn-success btn-block" type="button"><i
                                                class="material-icons">add</i> Tambah Opsi</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div id="place"></div>

                        <div class="row clearfix">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <h2 class="card-inside-title">
                                    Opsi Lainnya
                                    <small>Aktifkan jika ingin menambahkan opsi jawaban "Lainnya"</small>
                                </h2>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="demo-switch">
                                    <div class="switch">
                                        <label><input type="checkbox" name="others" value="1"><span
                                                class="lever switch-col-green"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="place-lainnya"></div>

                        <h2 class="card-inside-title">
                            Urutan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="urutan" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $urutan }}">
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
<script>
    $('#tambah').click(function() {
        $('#place').append(`
					<div class="row clearfix">
                        @if ($jenis_pertanyaan == '3')
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" name="options[]" required="" aria-required="true" aria-invalid="true" value="">
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                <input type="color" class="form-control form-control-color" name="color[]" required="" aria-required="true" value="#563d7c">
                            </div>
                            @else
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="options[]" required="" aria-required="true" aria-invalid="true" value="">
                            </div>

                            @endif
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
							<button class="btn btn-danger btn-block delete_file" type="button"><i class="material-icons">delete</i> Hapus</button>
						</div>
					</div>`);
    });
    $("#place").on("click", ".delete_file", function() {
        $(this).parent().parent().remove();
    })
    $("#place-lainnya").on("click", ".delete_file", function() {
        $(this).parent().parent().remove();
        $('#tambah-lainnya').show();
    })
    $(function() {
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            lang: 'id',
            time: true,
            date: false,
            shortTime: false
        });
    });
</script>
