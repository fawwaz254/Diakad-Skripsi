<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Filter Input Capaian Karakter
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-kbm-rekap-absen') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Kelas KBM
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="" disabled selected>-- Pilih Kelas --</option>
                                    @foreach ($data_kelas as $data)
                                        <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Aktivitas Reward Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="" disabled selected>-- Plih Aktivitas Reward Siswa --</option>
                                    @foreach ($data_kelas as $data)
                                        <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="id_aktivitas_reward">
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($data_aktivitas as $data)
                                        <optgroup label="{{ $data->jenisAktivitasReward->nm_jenis_aktivitas_reward }}">
                                            @foreach ($data->jenisAktivitasReward as $data)
                                                <option value="{{ $data->id_subkategori_pelanggaran }}">
                                                    {{ $kategori->tingkat_kategori_pelanggaran }}.{{ $data->tingkat_subkategori_pelanggaran }}
                                                    {!! $data->nm_subkategori_pelanggaran !!}</option>
                                            @endforeach
                                        </optgroup>
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
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@include('scriptjs')
<script></script>
