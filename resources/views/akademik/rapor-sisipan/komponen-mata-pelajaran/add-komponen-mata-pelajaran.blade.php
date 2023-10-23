<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/detail/' . $id_kelas) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH KOMPONEN MATA PELAJARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/komponen-mata-pelajaran/action-komponen-mata-pelajaran/add/0') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Kelompok Sisipan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelompok_sisipan" required="">
                                    @foreach ($kelompok_sisipan as $kel_sisipan)
                                        @if ($kel_sisipan->sub_kelompok_sisipan->count() > 0)
                                            @foreach ($kel_sisipan->sub_kelompok_sisipan as $sub_kelompok_sisipan)
                                                <option value="{{ $sub_kelompok_sisipan->id_sub_kelompok_sisipan }}">
                                                    {{ $kel_sisipan->nm_kelompok_sisipan . ' - ' . $sub_kelompok_sisipan->nm_sub_kelompok_sisipan }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="{{ $kel_sisipan->id_kelompok_sisipan }}">
                                                {{ $kel_sisipan->nm_kelompok_sisipan }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Mata Pelajaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_mata_pelajaran" required="">
                                    @foreach ($mata_pelajaran as $m)
                                        <option value="{{ $m->id_mata_pelajaran }}">{{ $m->nm_mata_pelajaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas" required="">
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($k->id_kelas == $id_kelas) selected @endif>{{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Urutan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="urutan" required=""
                                    aria-required="true" aria-invalid="true" value="">
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
