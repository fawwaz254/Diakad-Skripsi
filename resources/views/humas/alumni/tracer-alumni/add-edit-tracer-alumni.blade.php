<style>
    .status-header {
        /* margin: 0 1.5rem; */
        font-size: 18px;
        font-weight: normal;
        color: #111;
    }

</style>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/tracer-alumni') }}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ !empty($alumni) ? 'EDIT' : 'TAMBAH' }} ALUMNI
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" class="row"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2)) }}/{{ !empty($alumni) ? 'tracer-alumni/action/edit/' . $alumni->id_alumni : 'tracer-alumni/action/add/0' }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_alumni" value="{{ !empty($alumni) ? $alumni->id_alumni : '' }}">
                        <div class="col-md-8">
                            <h2 class="card-inside-title"> Nama Siswa </h2>
                            <input type="text" class="form-control" name="nama_siswa" aria-required="true"
                                aria-invalid="true"
                                value="{{ !empty($alumni) ? $alumni->calon_siswa->nm_c_siswa : '' }}"
                                {{ !empty($alumni) ? 'readonly' : '' }}>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Jurusan </h2>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="jurusan"
                                        {{ !empty($alumni) ? 'readonly' : '' }}>
                                        <option value="" selected disabled> Pilih Jurusan </option>
                                        @foreach ($data_jurusan as $jurusan)
                                            <option value="{{ $jurusan->id_jurusan }}"
                                                {{ isset($alumni) && $alumni->calon_siswa->jurusan->id_jurusan == $jurusan->id_jurusan ? 'selected' : '' }}>
                                                {{ $jurusan->nm_jurusan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Tahun Lulus </h2>
                            <input type="number" class="form-control" name="tahun_lulus" required=""
                                aria-required="true" aria-invalid="true"
                                value="{{ !empty($alumni) ? $alumni->tahun_lulus : '' }}">
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Kelas </h2>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="id_kelas"
                                        {{ !empty($alumni) ? 'readonly' : '' }}>
                                        <option value="" selected disabled> Pilih Kelas </option>
                                        @foreach ($data_kelas as $kelas)
                                            <option value="{{ $kelas->id_kelas }}"
                                                {{ isset($alumni) && $alumni->id_kelas == $kelas->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->nm_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Nomor Telepon/HP/WA </h2>
                            <input type="text" class="form-control" name="nomor_hp" required="" aria-required="true"
                                aria-invalid="true"
                                value="{{ !empty($alumni) ? $alumni->calon_siswa->nomor_hp : '' }}">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Email </h2>
                            <input type="text" class="form-control" name="email" required="" aria-required="true"
                                aria-invalid="true" value="{{ !empty($alumni) ? $alumni->email : '' }}">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Alamat </h2>
                            <textarea class="form-control" name="alamat_siswa" required="" aria-required="true"
                                aria-invalid="true"> {{ !empty($alumni) ? $alumni->calon_siswa->alamat_jalan : '' }} </textarea>
                        </div>
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1')
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title"> Status </h2>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="sma" id="sma_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'bekerja' ? 'checked' : '' }}>
                                <label for="work_status"> SMA </label>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="smk" id="smk_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'usaha' ? 'checked' : '' }}>
                                <label for="enterpreneur_status"> SMK </label>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="ma" id="college_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'kuliah' ? 'checked' : '' }}>
                                <label for="college_status"> MA </label>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="berhenti" id="berhenti_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'menunggu' ? 'checked' : '' }}>
                                <label for="idle_status"> Tidak Lanjut Sekolah </label>
                            </div>
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title"> Status </h2>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="bekerja" id="work_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'bekerja' ? 'checked' : '' }}>
                                <label for="work_status"> Bekerja </label>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="usaha" id="enterpreneur_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'usaha' ? 'checked' : '' }}>
                                <label for="enterpreneur_status"> Wirausaha </label>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="kuliah" id="college_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'kuliah' ? 'checked' : '' }}>
                                <label for="college_status"> Kuliah </label>
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status" value="menunggu" id="idle_status" required="required"
                                    {{ isset($alumni) && $alumni->status == 'menunggu' ? 'checked' : '' }}>
                                <label for="idle_status"> Belum Bekerja </label>
                            </div>
                        @endif
                        {{-- handle work data --}}
                        <div class="form_layout" id="work_state">
                            @include('./humas.alumni.forms.work_state')
                        </div>
                        {{-- handle Enterpreneur data --}}
                        <div class="form_layout" id="enterpreneur_state">
                            @include(
                                './humas.alumni.forms.enterpreneur_state'
                            )
                        </div>
                        {{-- handle College data --}}
                        <div class="form_layout" id="college_state">
                            @include('./humas.alumni.forms.college_state')
                        </div>
                        {{-- handle idle data --}}
                        <div class="form_layout" id="idle_state">
                            @include('./humas.alumni.forms.idle_state')
                        </div>
                        {{-- handle senior high school data --}}
                        <div class="form_layout" id="senior_high_school">
                            @include(
                                './humas.alumni.forms.senior_high_school'
                            )
                        </div>
                        {{-- handle vocational high school data --}}
                        <div class="form_layout" id="vocational_high_school">
                            @include(
                                './humas.alumni.forms.vocational_high_school'
                            )
                        </div>
                        {{-- handle vocational high school data --}}
                        <div class="form_layout" id="state_madrasah">
                            @include('./humas.alumni.forms.state_madrasah')
                        </div>
                        {{-- handle drop out data --}}
                        <div class="form_layout" id="drop_out">
                            @include('./humas.alumni.forms.drop_out')
                        </div>


                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button id="submit" disabled class="btn btn-block bg-red waves-effect" type="submit">
                                <i class="material-icons">save</i><span>
                                    {{ !empty($alumni) ? 'Update' : 'Save' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // hadle first load of page
    $(document).ready(function() {
        var alumni = {!! $alumni != null ? json_encode($alumni->toArray(), JSON_HEX_TAG) : "''" !!};
        var status = $("input[name='status']").value || alumni.status;
        toggleAlumniForm(status)
    })

    $("input[name='status']").change(function() {
        toggleAlumniForm(this.value)
    })

    function toggleAlumniForm(status) {
        $('.form_layout').hide();

        switch (status) {
            case 'bekerja':
                $('.form_layout#work_state').show();
                $('button#submit').attr('disabled', false);
                break;
            case 'usaha':
                $('.form_layout#enterpreneur_state').show();
                $('button#submit').attr('disabled', false);
                break;
            case 'kuliah':
                $('.form_layout#college_state').show();
                $('button#submit').attr('disabled', false);
                break;
            case 'menunggu':
                $('.form_layout#idle_state').show();
                $('button#submit').attr('disabled', false);
                break;
            case 'sma':
                $('.form_layout#senior_high_school').show();
                $('button#submit').attr('disabled', false);
                break;
        }
    }
</script>
@include('scriptjs')
