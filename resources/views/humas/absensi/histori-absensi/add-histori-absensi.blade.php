<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="/humas#absensi/histori-absensi/{{$id_pengguna}}/{{$start_date}}/{{$end_date}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>ADD HISTORI ABSENSI</h2>
                </div>
                <div class="body">
                  <form method="POST" action="{{url()->current()}}">
                    {{csrf_field()}}

                   <h2 class="card-inside-title">Status</h2>
                    <div class="row clearfix">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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