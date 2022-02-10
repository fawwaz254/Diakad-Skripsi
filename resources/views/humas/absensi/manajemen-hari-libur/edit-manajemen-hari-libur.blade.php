<div class="container-fluid">
    <div class="block-header">
        <h2> <a class="btn bg-blue waves-effect target-link" href="/humas#absensi/manajemen-hari-libur">
                <i class="material-icons">backspace</i><span>Kembali</span></a>
        </h2>
    </div>

    <form method="POST" id="edit-form" action="{{url()->current()}}">
    {{csrf_field()}}

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>EDIT MANAJEMEN HARI LIBUR</h2>
                </div>

                <div class="body">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Date</h2>
                            <input type="date" value="{{$holiday['date']}}" name="date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Explanation</h2>
                            <input type="text" value="{{$holiday['explanation']}}" name="explanation" class="form-control" required>
                        </div>
                    </div>

                    <button id="btn-submit" class="btn bg-red waves-effect">
                        <i class="material-icons">save</i>
                        <span>Save</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

    </form>

</div>
@include('scriptjs')
<script>
$( "#edit-form" ).submit(function() {
    $('#btn-submit').attr("disabled", true);
    $('#btn-submit i').text('autorenew')
    $('#btn-submit span').text('Loading')
});
</script>
