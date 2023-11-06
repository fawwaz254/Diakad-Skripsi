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
                        COPY KOMPONEN MATA PELAJARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/komponen-mata-pelajaran/action-komponen-mata-pelajaran/copy/0') }}">
                        {{ csrf_field() }}

                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <input type="hidden" name="kelas" value="{{ $id_kelas }}">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas" required="">
                                    @foreach ($kelas as $k)
                                        @php
                                            $jumlah = $kelas_sisipan->where('id_kelas', $k->id_kelas)->count();
                                        @endphp
                                        @if ($jumlah != '0')
                                            <option value="{{ $k->id_kelas }}"
                                                @if ($k->id_kelas == $id_kelas) selected @endif>
                                                {{ $k->nm_kelas . ' ( ' . $jumlah . ' )' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
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
