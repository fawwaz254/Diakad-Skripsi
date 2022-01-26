<div class="container-fluid">
    <div class="block-header">
        <h2> <a class="btn bg-blue waves-effect target-link" href="/humas#absensi/manajemen-hari-libur">
                <i class="material-icons">backspace</i><span>Kembali</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>ADD MANAJEMEN HARI LIBUR</h2>
                </div>
                <div class="body">
                    <form method="POST" id="add-form" action="{{url()->current()}}">
                        {{csrf_field()}}
                        <div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">Extra Money</h2>
                                <input type="number" name="extraMoney" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Explanation</h2>
                            <textarea name="explanation" class="form-control" cols="30"
                                rows="10"></textarea>
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
@include('scriptjs')
<script>
$( "#add-form" ).submit(function() {
    $('#btn-submit').attr("disabled", true);
    $('#btn-submit i').text('autorenew')
    $('#btn-submit span').text('Loading')
});
</script>
