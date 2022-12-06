    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#data-dokumen/input-dokumen/') }}"><i
                        class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            MENU UPLOAD DOKUMEN {{ $dokumen->nm_arsip_dokumen }} - {{ $dokumen->kode_katalog }}
                        </h2>
                    </div>
                    <div class="body">
                        <form id="formUpload" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-input-dokumen/upload/' . $dokumen->id_arsip_dokumen) }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3>Upload Dokumen</h3>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>
                                        <input type="file" name="file" />
                                    </label>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" id="upload" type="submit"><i
                                            class="material-icons">cloud_upload</i><span>Upload</span></button>
                                </div>
                            </div>
                        </form>

                        <div class="demo-color-box bg-success">
                            FILE DOKUMEN {{ $dokumen->nm_arsip_dokumen }}
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <form id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-input-dokumen/delete-file/' . $dokumen->id_arsip_dokumen) }}">
                            @forelse($arsip_dokumen_file as $file)
                                {{ csrf_field() }}
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <li>
                                            <a href="{{ Storage::disk('spaces')->url($file->nm_arsip_dokumen_file) }}"
                                                target="_blank">
                                                @if (in_array(pathinfo($file->nm_arsip_dokumen_file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'bmp', 'png']))
                                                    <img style="height:7rem; width:auto;"
                                                        src="{{ Storage::disk('spaces')->url($file->nm_arsip_dokumen_file) }}">
                                                @else
                                                    {{ \Illuminate\Support\Str::limit($file->nm_arsip_dokumen_file, $limit = 50, $end = '...') . pathinfo($file->nm_arsip_dokumen_file, PATHINFO_EXTENSION) }}
                                                @endif
                                            </a>
                                            <input type="hidden" class="form-control" name="id_arsip_dokumen_file"
                                                required="" aria-required="true" aria-invalid="true"
                                                value="{{ $file->id_arsip_dokumen_file }}">
                                        </li>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-danger bg-red waves-effect" id="delete"
                                            type="submit"><i
                                                class="material-icons">delete_forever</i><span>Delete</span></button>
                                    </div>
                                </div>
                            @empty
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <li><em>No files to display.</em></li>
                                    </div>
                                </div>
                            @endforelse
                        </form>

                        <div class="demo-color-box bg-success">
                            Detail Data Dokumen
                        </div>
                        <h2 class="card-inside-title">
                            Nama Arsip Dokumen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_arsip_dokumen" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $dokumen->nm_arsip_dokumen }}"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Katalog
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_katalog" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $dokumen->kode_katalog }}"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Unit Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="id_unit_kerja" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $dokumen->nm_arsip_dokumen }}"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Arsip Dokumen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_arsip_dokumen" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $dokumen->nomor_arsip_dokumen }}" readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jumlah Halaman
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="jumlah_halaman" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $dokumen->jumlah_halaman }}"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Penyusunan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="tgl_penyusunan" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $dokumen->tgl_penyusunan }}"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Contact Person <small>Nomor HP atau kontak dari penanggungjawab dokumen</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="contact_person" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $dokumen->contact_person }}"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Arsip Loker
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="id_arsip_loker" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $dokumen->nm_arsip_loker }}"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Arsip Pemilik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="id_arsip_pemilik" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $dokumen->nm_arsip_pemilik }}" readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            SubKategori Arsip
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kategori" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $dokumen->nm_arsip_subkategori }} ({{ $dokumen->nm_arsip_kategori }})"
                                    readonly>
                            </div>
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

        });
    </script>
