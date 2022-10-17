<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/komponen-nilai') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        Edit Komponen Nilai
                    </h2>
                </div>
                <div class="body">

                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/komponen-nilai/edit/' . $komponen_nilai->id_komponen_nilai) }}">
                        {{ csrf_field() }}

                        <div class="row clearfix">
                            <div class="col-md-4">
                                <label>Nama Komponen Nilai</label>
                                <input type="text" class="form-control" name="nm_nilai"
                                    value="{{ $komponen_nilai->nm_nilai }}" />
                            </div>

                            <div class="col-md-4">
                                <label>Type</label>
                                <select class="form-control show-tick" name="type">
                                    <option value="formatif" @if($komponen_nilai->type == "formatif") selected @endif>Formatif</option>
                                    <option value="sumatif" @if($komponen_nilai->type == "sumatif") selected @endif>Sumatif</option>
                                    <option value="uts" @if($komponen_nilai->type == "uts") selected @endif>UTS</option>
                                    <option value="uas" @if($komponen_nilai->type == "uas") selected @endif>UAS</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Status</label>
                                <select class="form-control show-tick" name="status">
                                    <option value="1" @if($komponen_nilai->status == "1") selected @endif>Aktif</option>
                                    <option value="0" @if($komponen_nilai->status == "0") selected @endif>Tidak Aktif
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-indigo waves-effect" type="submit"><i
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
