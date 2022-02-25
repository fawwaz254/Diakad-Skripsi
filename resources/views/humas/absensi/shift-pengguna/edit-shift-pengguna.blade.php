<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="/humas#absensi/shift_pengguna/{{$date}}">
                <i class="material-icons">backspace</i><span>Kembali</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>EDIT SHIFT PENGGUNA</h2>
                </div>
                <div class="body">
                    <form method="POST" id="edit-form" action="{{url()->current()}}">
                        {{csrf_field()}}
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="shift">Taggal {{ $date }}</label></td><td>
                                    <select name="shift"  class="form-control form-control-lg">
                                        <option value="" selected>Libur</option>
                                        @foreach($shifts as $shift)
                                        <option value="{{ $shift['code'] }}" >({{ minimalisTime($shift['start_time']) }} - {{ minimalisTime($shift['end_time']) }}) - {{ $shift['code'] }}</option>
                                        @endforeach
                                </select>
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
    </div>
</div>
@include('scriptjs')
<script>
$( "#edit-form" ).submit(function() {
    $('#btn-submit').attr("disabled", true);
    $('#btn-submit i').text('autorenew')
    $('#btn-submit span').text('Loading')
});
</script>
