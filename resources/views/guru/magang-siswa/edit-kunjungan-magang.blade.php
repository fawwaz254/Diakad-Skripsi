<div class="container" style="padding-bottom: 50px;">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <h2>
                    <a class="btn bg-blue waves-effect target-link "
                        href="{{ url(Request::segment(1) . '#' . Request::segment(2)) . '/list-kunjungan-magang' }}">
                        <i class="material-icons">backspace</i>
                        Kembali
                    </a>
                </h2>
            </div>
            <div class="card">
                {{-- <div id="alert-message"></div> --}}
                <div class="header d-flex justify-content-between">
                    <h2>
                        EDIT KUNJUNGAN MAGANG
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST" enctype="multipart/form-data">
                        {{-- {{ csrf_field() }} --}}
                        @csrf
                        @method('PUT')
                        <h2 class="card-inside-title">
                            Periode Magang <span class="text-danger">*</span>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select id="select-periode-magang" class="form-control" name="periode_magang">
                                    <option value="">-- Pilih Periode Magang --</option>
                                    @foreach ($periode_magang as $periode)
                                        <option value="{{ $periode->id_periode_magang }}"
                                            @if ($periode->id_periode_magang == $kunjungan_magang->id_periode_magang) selected @endif>
                                            {{ $periode->nm_periode_magang . ' ' }}{{ $periode->nomor_sk_periode_magang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Rekanan Magang <span class="text-danger">*</span>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select id="select-rekanan-magang" class="form-control" name="rekanan_magang">
                                    <option value="">-- Pilih Rekanan Magang --</option>
                                    @foreach ($rekanan_magang as $rekanan)
                                        <option value="{{ $rekanan->id_rekanan_magang }}"
                                            @if ($rekanan->id_rekanan_magang == $kunjungan_magang->id_rekanan_magang) selected @endif>
                                            {{ $rekanan->nm_rekanan_magang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            keterangan Kunjungan <span class="text-danger">*</span>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="keterangan_kunjungan" id="" class="form-control" cols="30" rows="10">{{ $kunjungan_magang->keterangan_kunjungan }}</textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Foto Kunjungan <span class="text-danger">*</span>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <img class="img-thumbnail" style="margin-bottom: 10px;"
                                    src="{{ Storage::disk('spaces')->url($kunjungan_magang->foto_kunjungan) }}"
                                    alt="{{ $kunjungan_magang->foto_kunjungan }}">
                                <input type="file" class="form-control" name="foto" aria-required="true"
                                    value="{{ $kunjungan_magang->foto_kunjungan }}" aria-invalid="true"
                                    accept="image/*">

                            </div>
                        </div>

                        <p>Nb: <span class="text-danger">*</span> Wajib diisi</p>

                        <div id="to-large-image"></div>
                        <div id="alert-message"></div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button id="btn-submit" class="btn btn-block bg-red waves-effect" type="submit">
                                    <i class="material-icons">save</i>
                                    <span>Save</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#select-periode-magang').select2();
        $('#select-rekanan-magang').select2();

        $('#form-upload').submit(function(e) {
            e.preventDefault();
            $('#btn-submit').prop('disabled', true);
            let formData = new FormData(this);
            console.log([...formData.entries()]);
            $.ajax({
                url: "{{ route('update.kunjungan-magang', $kunjungan_magang->id_kunjungan_magang) }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                enctype: 'multipart/form-data',
                success: function(response) {
                    if (response.code === 400) {
                        $('#btn-submit').prop('disabled', false);
                        vex.dialog.alert(response.message);
                        $('#alert-message').html(
                            `<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <strong>Gagal!</strong> ${response.message}.
                    </div>`);
                    } else {
                        $('#form-upload')[0].reset();

                        vex.dialog.alert(response.message);
                        setTimeout(() => {
                            window.location.href = response.path;
                        }, 1000)
                    }
                },
                error: function(response) {
                    $('#btn-submit').prop('disabled', false);
                    $('#to-large-image').html(
                        `<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <strong>Error!</strong> Ukuran foto maksimal 2 MB
                    </div>`
                    )
                }
            })
        })
    });
</script>
