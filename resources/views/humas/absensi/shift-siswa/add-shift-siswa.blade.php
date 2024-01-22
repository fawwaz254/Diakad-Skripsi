<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card" style="margin-top: 10px">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <form method="POST" id="form-validation" action="/humas/absensi/shift_siswa/add">
                            {{ csrf_field() }}
                            <div class="header">
                                <h2>Tambah Shift Siswa</h2>
                            </div>
                            <div class="body">
                                <div class="col-sm-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <h4>
                                                <input id="checkbox_select_all" type="checkbox" name="select_all"
                                                    data-tingkat="0" class="filled-in">
                                                <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                                <label><b>Pilih Semua Kelas</b></label>
                                            </h4>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">No</td>
                                            <td>Kelas</td>
                                            <td>Shift hari ini</td>
                                        </tr>
                                        @php
                                            $no = 1;
                                        @endphp

                                        @foreach ($kelas as $k)
                                            <tr>
                                                <td style="text-align: center;">{{ $no++ }}</td>
                                                <td>
                                                    <input type="checkbox" name="kelas[{{ $k['nm_kelas'] }}]"
                                                        value="{{ $k['id_kelas'] }}" id="{{ $k['id_kelas'] }}"
                                                        data-tingkat="0"> <label
                                                        for="{{ $k['id_kelas'] }}">{{ $k['nm_kelas'] }}
                                                    </label>
                                                </td>
                                                <td>{{ optional($shiftsPengguna->where('id_pengguna', $k->siswa_one->id_pengguna)->first())->id_shift_master }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <div class="col-sm-4">
                                    <h4>Pilih Tanggal :</h4>
                                    <table class="table">
                                        <tr>
                                            <td><label for="startDate">Tanggal Mulai :</label></td>
                                            <td>
                                                <input type="date" name="startDate">
                                            <td>
                                        </tr>
                                        <tr>
                                            <td><label for="endDate">Tanggal Akhir :</label></td>
                                            <td>
                                                <input type="date" name="endDate">
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <h4>Pilih Shift :</h4>
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <label for="dayName[Monday]"> Senin</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Monday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">
                                                            ({{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }})
                                                            -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            <td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="dayName[Tuesday]">Selasa</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Tuesday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">
                                                            ({{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }})
                                                            -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="dayName[Wednesday]">Rabu</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Wednesday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">(
                                                            {{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }}) -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="dayName[Thursday]">Kamis</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Thursday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">
                                                            ({{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }})
                                                            -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="dayName[Friday]">Jum'at</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Friday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">
                                                            ({{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }})
                                                            -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="dayName[Saturday]">Sabtu</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Saturday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">
                                                            ({{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }})
                                                            -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="dayName[Sunday]">Ahad</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Sunday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">
                                                            ({{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }})
                                                            -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <button class="btn btn-block bg-green waves-effect" id="btn-submit"><i
                                            class="material-icons">save</i><span>Save</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@include('scriptjs')
<script>
    // function filterAction() {
    //     loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' +
    //         $('select[name=tingkat]').val());
    // }

    $(document).ready(function() {
        $('input[name="select_all"]').change(function() {
            var select_all_checked = this.checked;
            // var data_jurusan = $(this).attr('data-jurusan');
            $('input[data-tingkat="0"]').prop('checked', this.checked);
        });
    });
</script>
