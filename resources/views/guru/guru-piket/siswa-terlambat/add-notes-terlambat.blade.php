<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="guru#guru-piket/catat-siswa-terlambat/detail/{{ $id_kelas }}/{{ $date }}">
                <i class="material-icons">backspace</i><span>Kembali</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Tambah Alasan Keterlambatan</h2>
                </div>
                <div class="body">
                    <form method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/addnotes/' . $id_pengguna) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name='id_kelas' value={{ $id_kelas }}>
                        <input type="hidden" name='date' value={{ $date }}>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <h2 class="card-inside-title d-inline">Status </h2>
                            <select class="form-control show-tick" name="status">
                                <option value="masuk">Masuk</option>
                            </select>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <h2 class="card-inside-title">Check In</h2>
                            <input type="time" name="check_in" class="form-control"
                                value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <h2 class="card-inside-title">Check Out</h2>
                            <input type="time" name="check_out" class="form-control">
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Notes</h2>
                            <textarea name="notes" class="form-control" cols="30" rows="10"></textarea>
                        </div>

                        <button id="btn-submit" name="button_type" value="save"
                            class="btn btn-block bg-green waves-effect">
                            <i class="material-icons">save</i>
                            <span>Save</span>
                        </button>
                        <button id="btn-submit" name="button_type" value="saveandprint"
                            class="btn btn-block bg-blue waves-effect">
                            <i class="material-icons">local_printshop</i>
                            <span>Save & Print</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
