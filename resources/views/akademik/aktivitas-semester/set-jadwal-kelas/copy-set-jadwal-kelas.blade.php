<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#aktivitas-semester/set-jadwal-kelas') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        COPY SET JADWAL KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-copy-jadwal-kelas') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Semester Copy
                            <small><strong>Semester Asal Data</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_copy" required>
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    @foreach ($data_semester as $data)
                                        <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                            ({{ $data->nm_semester }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester Paste
                            <small><strong>Semester Tujuan Data</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_paste" required>
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    @foreach ($data_semester as $data)
                                        @if ($data->is_aktif_semester == 1)
                                            <option value="{{ $data->id_semester }}" selected>{{ $data->tahun_ajaran }}
                                                ({{ $data->nm_semester }})
                                                (Aktif)</option>
                                        @else
                                            <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                ({{ $data->nm_semester }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Elemen Copy
                            <small><strong>Pilihan Data yang Akan di-Copy</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input id="checkbox_select_all_kelas" type="checkbox" name="select_all"
                                    data-kelas="kelas" class="filled-in">
                                <label for="checkbox_select_all_kelas" style="margin-bottom: -10px;"></label>
                                <label><b>Pilih Semua Kelas</b></label>

                                <br>

                                @foreach ($data_kelas as $key => $k)
                                    <input type="checkbox" id="{{ $k->id_kelas }}" name="kelas[{{ $key }}]"
                                        class="filled-in" value="{{ $k->id_kelas }}" data-kelas="kelas">
                                    <label for="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</label> <br>
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div> --}}
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
<script>
    $(document).ready(function() {
        /* Select All Checkbox */
        $('input[name="select_all"]').change(function() {
            var select_all_checked = this.checked;
            // var data_kelas = $(this).attr('data-kelas');

            // console.log(data_jurusan);
            $('input[data-kelas="kelas"]').prop('checked', this.checked);
        });
    });
</script>
