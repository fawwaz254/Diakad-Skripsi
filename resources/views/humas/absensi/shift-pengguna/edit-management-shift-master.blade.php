<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
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
                <div class="header">
                    <h2>Edit Management Shift Master</h2>

                </div>
                <div class="body">
                    <div class="col-sm-6">
                        <h4>Tambah Shift Baru</h4>
                        <form method="POST" id="form-validation"
                            action="/humas/absensi/shift_pengguna/editManagementShift/{{ $shift->id_shift_master }}">
                            {{ csrf_field() }}
                            {{-- /humas/absensi/shift_pengguna/addShiftMaster/{{ $shift->id_shift_master }} --}}
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">Nama Shift</h2>
                                <input type="text" name="name" class="form-control" value="{{ $shift->code }}"
                                    disabled>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">Jam masuk</h2>

                                <input type="time" class="timepicker form-control" name="check_in" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $shift->start_time }}">

                                {{-- <input type="time" name="check_in" class="form-control"> --}}
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">Jam keluar</h2>
                                <input type="time" class="timepicker form-control" name="check_out" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $shift->end_time }}">
                                {{-- <input type="time" name="check_out" class="form-control"> --}}
                            </div>

                            <button id="btn-submit" class="btn btn-block bg-red waves-effect" style="display: inline;">
                                <i class="material-icons">save</i>
                                <span>Save</span>
                            </button>

                        </form>

                        {{-- <br>
                        <table class="table table-bordered">
                            <tr>
                                <h4>List Shift :</h4>
                            </tr>
                            <tr>
                                <td style="text-align: center;">No</td>
                                <td>Nama</td>
                                <td>Jam Masuk</td>
                                <td>Jam Keluar</td>
                                <td>Aksi</td>
                            </tr>

                            @foreach ($shifts as $key => $shift)
                                <tr>

                                    <td style="text-align: center;">{{ $key + 1 }}</td>
                                    <td>{{ $shift['code'] }}</td>
                                    <td>{{ $shift['start_time'] }}</td>
                                    <td>{{ $shift['end_time'] }}</td>
                                    <td>
                                        <button id="button" data-id={{ $shift['id_shift_master'] }}
                                            style="margin-left:3px;" class="btn bg-blue waves-effect edit-record">
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button id="button" data-id={{ $shift['id_shift_master'] }}
                                            style="margin-left:3px;" class="btn bg-red waves-effect delete-record">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </table> --}}

                        {{-- <button class="btn btn-block bg-green waves-effect" id="btn-submit"><i class="material-icons">save</i><span>Save</span></button>
                                        </div>
                         </form> --}}
                    </div>

                </div>



            </div>
        </div>
    </div>

</div>

@include('scriptjs')
<script>
    function back() {
        window.location = '/humas#absensi/shift_pengguna/managementShift'
    }

    $(function() {
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            clearButton: true,
            date: false
        });
    });
</script>
