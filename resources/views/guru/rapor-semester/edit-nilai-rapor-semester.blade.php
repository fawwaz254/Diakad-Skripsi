<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-semester/tambah-nilai-rapor-semester') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        Edit NILAI
                    </h2>
                </div>
                <div class="body">

                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/tambah-nilai-rapor-semester/action/edit/' . $rapor->id_rapor) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <label>Kelas</label>
                                <select class="form-control show-tick" disabled>
                                    <option>{{ $rapor->kelas->nm_kelas }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label>Mata Pelajaran</label>
                                <select class="form-control show-tick" disabled>
                                    <option>{{ $rapor->mata_pelajaran->nm_mata_pelajaran }}
                                    </option>
                                </select>

                            </div>
                            <div class="col-md-12">
                                <label>Semester</label>
                                <select class="form-control show-tick" disabled>
                                    <option>{{ $rapor->semester->tahun_ajaran . '-' . $rapor->semester->nm_semester }}
                                    </option>
                                </select>

                            </div>
                        </div>

                        @if ($rapor->kelas->type_rapor == '1')
                            @foreach ($rapor->keterangan_rapor as $keterangan_rapor)
                                <div class="col-md-12">
                                    <label>Keterangan
                                        {{ $keterangan_rapor->komponen_jenis_rapor->nm_komponen_jenis_rapor }}
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <pre>nilai A</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_a]" aria-required="true"
                                        aria-invalid="true">{{ $keterangan_rapor->keterangan_a }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <pre>nilai B</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_b]" aria-required="true"
                                        aria-invalid="true ">{{ $keterangan_rapor->keterangan_b }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <pre> nilai C</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_c]" aria-required="true"
                                        aria-invalid="true">{{ $keterangan_rapor->keterangan_c }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <pre>nilai D</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_d]" aria-required="true"
                                        aria-invalid="true">{{ $keterangan_rapor->keterangan_d }}</textarea>
                                </div>
                            @endforeach
                        @elseif($rapor->kelas->type_rapor == '3')
                            @foreach ($rapor->keterangan_rapor as $keterangan_rapor)
                                <div class="col-md-12">
                                    <label>Keterangan
                                        {{ $keterangan_rapor->komponen_jenis_rapor->nm_komponen_jenis_rapor }}
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <pre>nilai A</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_a]" aria-required="true"
                                        aria-invalid="true">{{ $keterangan_rapor->keterangan_a }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <pre>nilai B</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_b]" aria-required="true"
                                        aria-invalid="true ">{{ $keterangan_rapor->keterangan_b }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <pre> nilai C</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_c]" aria-required="true"
                                        aria-invalid="true">{{ $keterangan_rapor->keterangan_c }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <pre>nilai D</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan_rapor[{{ $keterangan_rapor->id_komponen_jenis_rapor }}][keterangan_d]" aria-required="true"
                                        aria-invalid="true">{{ $keterangan_rapor->keterangan_d }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <pre>keterangan bawah</pre>
                                    <textarea rows="1" cols="50" class="form-control"
                                        name="keterangan2[{{ $keterangan_rapor->id_komponen_jenis_rapor }}]" aria-required="true" aria-invalid="true">{{ $keterangan_rapor->keterangan2 }}</textarea>
                                </div>
                            @endforeach

                        @endif

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
