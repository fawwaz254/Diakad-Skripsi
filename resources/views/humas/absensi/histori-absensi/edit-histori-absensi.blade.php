
<div class="container-fluid">
  <div class="block-header">
      <h2><a class="btn bg-blue waves-effect target-link " href="/humas#absensi/histori-absensi/{{  $presences->id_pengguna }}/{{ $start_date }}/{{ $end_date }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
  </div>
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
              <div class="header">
        <div class="header">
          <h2>
              EDIT 
          </h2>
        </div>
        <div class="body">
          

<form action="/humas/absensi/histori-absensi/update/{{  $presences->id_presensi_pengguna }}/{{ $start_date }}/{{ $end_date }}" method="POST" class="d-inline">
  {{ csrf_field() }}

      <h4 class="card-inside-title">
          Check-in :
      </h4>
      <div class="row clearfix">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="time" class="form-control" name="check_in" required="true" aria-required="true" aria-invalid="true"
            value="{{$presences->check_in}}">
          </div>
      </div>
    


      <h4 class="card-inside-title ">
        Check-out :
    </h4>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <input type="time" class="form-control" name="check_out" required="true" aria-required="true" aria-invalid="true"
          value="{{$presences->check_out}}">
        </div>
    </div>
    
    

<br>
  <div class="row clearfix ">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    
        <div class="input-field col s12">
          <h4 class="card-inside-title">
            Status :
        </h4>
        <select class="form-control show-tick" name="status">

          <option value="Masuk">Masuk</option>
          <option value="Izin">Izin</option>
          <option value="Tidak Masuk">Tidak Masuk</option>
        </select>
      
        </div>
      </div>
  </div>

  <h4 class="card-inside-title">
    Notes :
</h4>
  <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <input type="text" class="form-control" name="notes" required="true" aria-required="true" aria-invalid="true"
      value="{{$presences->notes}}">
    </div>
</div>






  <button type="submit" class="btn btn-block bg-red waves-effect"><i class="material-icons">update</i> Update </button>
</form>


</div>
</div>
    </div>
</div>
