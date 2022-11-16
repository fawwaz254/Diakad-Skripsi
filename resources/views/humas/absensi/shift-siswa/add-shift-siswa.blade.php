<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header" >
                    <h2>Filter Data</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <label>
                                Kelas
                            </label>
                            <select class="form-control show-tick" name="kelas" >
                                <option>Pilih kelas</option>
                                <option value="0" @if($id_kelas == '0') selected @endif>Semua</option>
                                @foreach ($kelas as $lk)
                                <option value="{{ $lk->id_kelas }}" @if($id_kelas == $lk->id_kelas) selected @endif>{{ $lk->nm_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;" onclick="filterAction()">Lihat </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 10px">
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
                        <form method="POST" id="form-validation" action="/humas/absensi/shift_pengguna/add">
                            {{ csrf_field() }}
                            <div class="header">
                                <h2>Tambah Shift Siswa</h2>
                            </div>
        
                            <div class="body">
                                <div class="col-sm-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <h4>Pilih Siswa :</h4>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">No</td>
                                            <td>kelas</td>
                                            <td>Name</td>
                                            @if ($shiftsPengguna)
                                                <td>Shift hari ini</td>
                                            @endif
                                        </tr>
                                        @php
                                        $no = 1;
                                        @endphp
                                        <tr>
                                            @foreach ($penggunas as $key => $pengguna)
                                                <td style="text-align: center;">{{ $no++ }}</td>
                                                <td>{{ $pengguna->siswa->kelas->nm_kelas}}</td>
                                                <td><input type="checkbox" name="pengguna[{{ $pengguna['nm_pengguna'] }}]"
                                                        value="{{ $pengguna['id_pengguna'] }}"
                                                        id="{{ $pengguna['id_pengguna'] }}"> <label
                                                        for="{{ $pengguna['id_pengguna'] }}">{{ $pengguna['nm_pengguna'] }}
                                                    </label></td>
                                                @foreach ($shiftsPengguna as $shift)
                                                    @if ($shift['id_pengguna'] == $pengguna['id_pengguna'])
                                                        <td>{{ $shift['id_shift_master'] }}</td>
                                                    @endif
                                                @endforeach
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
        
                                <div class="col-sm-4">
                                    <h4>Pilih Bulan :</h4>
                                    <table class="table">
                                        <tr>
                                            <td><label for="firstMount"> Bulan Awal :</label></td>
                                            <td>
                                                <select name="firstMount" class="form-control form-control-lg">
                                                    <option value="January" selected>Januari</option>
                                                    <option value="February">Februari</option>
                                                    <option value="March">Maret</option>
                                                    <option value="April">April</option>
                                                    <option value="May">Mei</option>
                                                    <option value="June">Juni</option>
                                                    <option value="July">Juli</option>
                                                    <option value="August">Agustus</option>
                                                    <option value="September">September</option>
                                                    <option value="October">Oktober</option>
                                                    <option value="November">November</option>
                                                    <option value="December">Desember</option>
                                                </select>
                                            <td>
        
                                        </tr>
                                        <tr>
                                            <td> <label for="endMount"> Bulan Akhir :</label></td>
                                            <td><select name="endMount" class="form-control form-control-lg">
                                                    <option value="January" selected>Januari</option>
                                                    <option value="February">Februari</option>
                                                    <option value="March">Maret</option>
                                                    <option value="April">April</option>
                                                    <option value="May">Mei</option>
                                                    <option value="June">Juni</option>
                                                    <option value="July">Juli</option>
                                                    <option value="August">Agustus</option>
                                                    <option value="September">September</option>
                                                    <option value="October">Oktober</option>
                                                    <option value="November">November</option>
                                                    <option value="December">Desember</option>
                                                </select>
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
                                                            {{ minimalisTime($shift['end_time']) }}) -
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
                                                            {{ minimalisTime($shift['end_time']) }}) -
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
                                                            {{ minimalisTime($shift['end_time']) }}) -
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
                                                            {{ minimalisTime($shift['end_time']) }}) -
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
                                                            {{ minimalisTime($shift['end_time']) }}) -
                                                            {{ $shift['code'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="dayName[Sunday]" >Ahad</label>
                                            </td>
                                            <td>
                                                <select name="dayName[Sunday]" class="form-control form-control-lg">
                                                    <option value="" selected>Libur</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift['code'] }}">
                                                            ({{ minimalisTime($shift['start_time']) }} -
                                                            {{ minimalisTime($shift['end_time']) }}) -
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
function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + 
            $('select[name=kelas]').val());
    }
    </script>



{{-- <div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <button class="btn btn-block bg-red waves-effect" onclick=back()><i
                    class="material-icons">arrow_back</i><span>Kembali</span></button>
        </div>
    </div>
    <br>
    
    <div class="card">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <form method="POST" id="form-validation" action="/humas/absensi/shift_pengguna/add">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>Tambah Shift Pengguna</h2>
                    </div>

                    <div class="body">
                        <div class="col-sm-6">
                            <table class="table table-bordered">
                                <tr>
                                    <h4>Pilih Pengguna :</h4>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">No</td>
                                    <td>Unit kerja</td>
                                    <td>Name</td>
                                    @if ($shiftsPengguna)
                                        <td>Shift hari ini</td>
                                    @endif
                                </tr>
                                <tr>
                                    @foreach ($penggunas as $key => $pengguna)
                                        <td style="text-align: center;">{{ $key + 1 }}</td>
                                        <td>{{ isset($pengguna->guru->unit_kerja)  ?  $pengguna->guru->unit_kerja->nm_unit_kerja : 'Pegawai'}}</td>
                                        <td><input type="checkbox" name="pengguna[{{ $pengguna['nm_pengguna'] }}]"
                                                value="{{ $pengguna['id_pengguna'] }}"
                                                id="{{ $pengguna['id_pengguna'] }}"> <label
                                                for="{{ $pengguna['id_pengguna'] }}">{{ $pengguna['nm_pengguna'] }}
                                            </label></td>
                                        @foreach ($shiftsPengguna as $shift)
                                            @if ($shift['id_pengguna'] == $pengguna['id_pengguna'])
                                                <td>{{ $shift['id_shift_master'] }}</td>
                                            @endif
                                        @endforeach
                                </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="col-sm-4">
                            <h4>Pilih Bulan :</h4>
                            <table class="table">
                                <tr>
                                    <td><label for="firstMount"> Bulan Awal :</label></td>
                                    <td>
                                        <select name="firstMount" class="form-control form-control-lg">
                                            <option value="January" selected>Januari</option>
                                            <option value="February">Februari</option>
                                            <option value="March">Maret</option>
                                            <option value="April">April</option>
                                            <option value="May">Mei</option>
                                            <option value="June">Juni</option>
                                            <option value="July">Juli</option>
                                            <option value="August">Agustus</option>
                                            <option value="September">September</option>
                                            <option value="October">Oktober</option>
                                            <option value="November">November</option>
                                            <option value="December">Desember</option>
                                        </select>
                                    <td>

                                </tr>
                                <tr>
                                    <td> <label for="endMount"> Bulan Akhir :</label></td>
                                    <td><select name="endMount" class="form-control form-control-lg">
                                            <option value="January" selected>Januari</option>
                                            <option value="February">Februari</option>
                                            <option value="March">Maret</option>
                                            <option value="April">April</option>
                                            <option value="May">Mei</option>
                                            <option value="June">Juni</option>
                                            <option value="July">Juli</option>
                                            <option value="August">Agustus</option>
                                            <option value="September">September</option>
                                            <option value="October">Oktober</option>
                                            <option value="November">November</option>
                                            <option value="December">Desember</option>
                                        </select>
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
                                                    {{ minimalisTime($shift['end_time']) }}) -
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
                                                    {{ minimalisTime($shift['end_time']) }}) -
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
                                                    {{ minimalisTime($shift['end_time']) }}) -
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
                                                    {{ minimalisTime($shift['end_time']) }}) -
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
                                                    {{ minimalisTime($shift['end_time']) }}) -
                                                    {{ $shift['code'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="dayName[Sunday]" >Ahad</label>
                                    </td>
                                    <td>
                                        <select name="dayName[Sunday]" class="form-control form-control-lg">
                                            <option value="" selected>Libur</option>
                                            @foreach ($shifts as $shift)
                                                <option value="{{ $shift['code'] }}">
                                                    ({{ minimalisTime($shift['start_time']) }} -
                                                    {{ minimalisTime($shift['end_time']) }}) -
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
    $("#add-form").submit(function() {
        $('#btn-submit').attr("disabled", true);
        $('#btn-submit i').text('autorenew')
        $('#btn-submit span').text('Loading')
    });

    function back() {
        window.location = '/humas#absensi/shift_pengguna'
    }
</script> --}}
