<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{url(Request::segment(1). '#' . Request::segment(2) . '/histori-absensi-siswa/detail/' . $kelas . '/' . $date . '/0')}}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>ADD HISTORI ABSENSI</h2>
                </div>
                <div class="body">
                    <form method="POST" id="add-form" action="{{url()->current()}}">
                        {{csrf_field()}}
                        {{-- <input type="hidden" value="{{Request::segment(4)}}" disabled="" class="form-control"> --}}
                        <h2 class="card-inside-title">Status</h2>
                        <div class="row clearfix">
                          
                            <div class="col-md-6">
                                 <input type="date" value="{{Request::segment(6)}}" disabled="" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <select class="form-control show-tick" name="status">
                                    <option value="izin">izin</option>
                                    <option value="sakit">sakit</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">Notes</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" name="notes" class="form-control">
                            </div>
                        </div>
                        <button id="btn-submit" class="btn btn-block bg-red waves-effect">
                            <i class="material-icons">save</i>
                            <span>Save</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$( "#add-form" ).submit(function() {
    $('#btn-submit').attr("disabled", true);
    $('#btn-submit i').text('autorenew')
    $('#btn-submit span').text('Loading')
});
</script>