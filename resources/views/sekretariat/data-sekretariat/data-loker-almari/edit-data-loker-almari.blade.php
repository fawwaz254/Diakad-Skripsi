    <div class="container-fluid">
        <div class="block-header">
            <h2>
                <a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#data-sekretariat/data-loker-almari/') }}">
                    <i class="material-icons">backspace</i><span>Kembali</span>
                </a>
            </h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            EDIT DATA LOKER/ALMARI {{ $arsip->nm_arsip_loker }}
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-data-loker-almari/edit/' . $arsip->id_arsip_loker) }}">
                            {{ csrf_field() }}
                            <h2 class="card-inside-title">
                                Nama Arsip Loker
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_arsip_loker" required="" aria-required="true"
                                        aria-invalid="true" value="{{ $arsip->nm_arsip_loker }}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Unit Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_unit_kerja">
                                        <option value="">-- Pilih Unit Kerja--</option>
                                        @foreach ($unit as $data)
                                            <option value="{{ $data->id_unit_kerja }}" @if ($data->id_unit_kerja == $arsip->id_unit_kerja) selected @endif>
                                                {{ $data->nm_unit_kerja }}
                                            </option>
                                        @endforeach
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
