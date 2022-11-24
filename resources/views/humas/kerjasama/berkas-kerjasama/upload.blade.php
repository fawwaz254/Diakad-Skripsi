<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#kerjasama/berkas/') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Detail Data Kerjasama <b> {{ $kerjasama->nm_kejasama }} </b>
                    </h2>
                </div>
                <div class="body">

                    <h2 class="card-inside-title">
                        Nama Kerjasama
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="nm_kerjasama" required=""
                                aria-required="true" aria-invalid="true" value="{{ $kerjasama->nm_kerjasama }}"
                                readonly>
                        </div>
                    </div>
                    <h2 class="card-inside-title">
                        Jenis Kerjasama
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="jenisKerjasama" required=""
                                aria-required="true" aria-invalid="true"
                                value="{{ $kerjasama->jenisKerjasama->nm_jenis_kerjasama }}" readonly>
                        </div>
                    </div>
                    <h2 class="card-inside-title">
                        Instansi
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="id_unit_kerja" required=""
                                aria-required="true" aria-invalid="true" value="{{ $kerjasama->instansi->nm_instansi }}"
                                readonly>
                        </div>
                    </div>
                    <h2 class="card-inside-title">
                        Tanggal Kerjasama
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="tanggal_kerjasama" required=""
                                aria-required="true" aria-invalid="true" value="{{ $kerjasama->tanggal_kerjasama }}"
                                readonly>
                        </div>
                    </div>
                    <h2 class="card-inside-title">
                        Status Kerjasama
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="status" required=""
                                aria-required="true" aria-invalid="true" value="{{ $kerjasama->status }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Menu Upload Berkas Kerjasama<b> {{ $kerjasama->nm_kejasama }} </b>
                    </h2>
                </div>
                <div class="body">
                    <form id="formUpload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/store') }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_kerjasama" value="{{ $kerjasama->id_kerjasama }}">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h3>Upload Berkas</h3>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input id="input_file_field" type="file" name="file" />
                                </label>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" disabled id="upload"
                                    type="submit"><i
                                        class="material-icons">cloud_upload</i><span>Upload</span></button>
                            </div>
                        </div>
                    </form>

                    <div class="demo-color-box bg-success">
                        FILE DOKUMEN {{ $kerjasama->nm_kerjasama }}
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                    <div class="row clearfix">
                        @forelse($kerjasama->berkasKerjasama as $berkas)
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <form style="display: flex; flex-direction: column" id="form-validation" method="POST"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . "/delete/$berkas->id_berkas_kerjasama") }}">
                                    {{ csrf_field() }}
                                    <a href="{{ $berkas->path }}" target="_blank">
                                        @if (in_array(pathinfo($berkas->nama_file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'bmp', 'png']))
                                            <img style="height:7rem; width:auto;" src="{{ $berkas->path }}">
                                        @else
                                            {{ str_limit($berkas->nama_file, $limit = 50, $end = '...') . pathinfo($berkas->nama_file, PATHINFO_EXTENSION) }}
                                        @endif
                                    </a>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-danger bg-red waves-effect" id="delete"
                                            type="submit"><i
                                                class="material-icons">delete_forever</i><span>Delete</span></button>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <li><em>No files to display.</em></li>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    $(document).ready(function() {

        $("#formUpload").submit(function(e) {

            //disable the submit button
            $('button').attr('disabled', 'disabled');

            return true;

        });

        $('#input_file_field').change(function() {
            if ($('#input_file_field').get(0).files.length > 0) {
                $('#upload').attr('disabled', false);
            }
        })

    });
</script>
