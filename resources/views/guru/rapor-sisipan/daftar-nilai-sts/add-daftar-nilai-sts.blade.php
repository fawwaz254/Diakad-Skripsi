<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/daftar-nilai-sts') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        TAMBAH NILAI
                    </h2>
                </div>
                <div class="body">

                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/daftar-nilai-sts/action-daftar-nilai-sts/add/0') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6">
                                <label>Mata Pelajaran</label>
                                <select class="form-control show-tick" name="id_mata_pelajaran">
                                    <option selected disabled>-- Pilih Mata Pelajaran --</option>
                                    @foreach ($list_mapel as $r)
                                        <option value="{{ $r->id_mata_pelajaran }}">{{ $r->nm_mata_pelajaran }} ({{ $r->kd_mata_pelajaran }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Kelas</label>
                                <select class="form-control show-tick" name="id_kelas">
                                    <option selected disabled>-- Pilih Kelas --</option>
                                    @foreach ($list_kelas as $r)
                                    <option value="{{ $r->id_kelas }}">{{ $r->nm_kelas }}
                                    </option>
                                @endforeach
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
