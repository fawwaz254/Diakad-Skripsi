<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="/humas#absensi/histori-absensi/{{$presences["id_pengguna"]}}/{{$start_date}}/{{$end_date}}">
                <i class="material-icons">backspace</i><span>Kembali</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>EDIT HISTORI ABSENSI</h2>
                </div>
                <div class="body">
                    <form method="POST" action="{{url()->current()}}">
                        {{csrf_field()}}
                        <div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title d-inline">Status </h2>
                                <select class="form-control show-tick" name="status">
                                    @switch($presences['status'])
                                    @case('masuk')
                                    <option value="{{$presences['status']}}">{{$presences['status']}}</option>
                                    <option value="sakit">sakit</option>
                                    <option value="izin">izin</option>
                                    @break
                                    @case('sakit')
                                    <option value="{{$presences['status']}}">{{$presences['status']}}</option>
                                    <option value="masuk">masuk</option>
                                    <option value="izin">izin</option>
                                    @break
                                    @case('izin')
                                    <option value="{{$presences['status']}}">{{$presences['status']}}</option>
                                    <option value="masuk">masuk</option>
                                    <option value="sakit">sakit</option>
                                    @break
                                    @default
                                    <h1>error</h1>
                                    @endswitch
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">Check In</h2>
                                <input type="time" name="check_in" class="form-control"
                                    value="{{$presences['check_in']}}">
                            </div>
                        </div>
                        <div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">Check Out</h2>
                                <input type="time" name="check_out" class="form-control"
                                    value="{{$presences['check_out']}}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Notes</h2>
                            <textarea name="notes" class="form-control" cols="30"
                                rows="10">{{$presences['notes']}}</textarea>
                        </div>


                        <button class="btn btn-block bg-red waves-effect" type="submit">
                            <i class="material-icons">save</i>
                            <span>Save</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')