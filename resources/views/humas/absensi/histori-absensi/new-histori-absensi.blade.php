<div class="container-fluid">
  <div class="block-header">
      <h2><a class="btn bg-blue waves-effect target-link " href="/humas#absensi/histori-absensi/{{  $id_pengguna }}/{{ $start_date }}/{{ $end_date }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
  </div>
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
              <div class="header">
        <div class="header">
          <h2>
              Izin
          </h2>
        </div>
        <div class="body">
          

<form action="/humas/absensi/histori-absensi/tambah/{{  $id_pengguna }}/{{ $date }}/{{ $start_date }}/{{ $end_date }}" method="POST" class="d-inline">
  {{ csrf_field() }}

     
    
  <div class="row clearfix ">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    
        <div class="input-field col s12">
          <h4 class="card-inside-title">
            Status :
        </h4>
        <select class="form-control show-tick" name="status">

         
          <option value="izin">Izin</option>
          <option value="sakit">Sakit</option>
 
        </select>
      
        </div>
      </div>
  </div>

  <h4 class="card-inside-title">
    Notes :
</h4>
  <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <input type="text" class="form-control" name="notes" required="true" aria-required="true" aria-invalid="true">
    </div>
</div>
{{-- <input type="text" class="form-control" name="date" required="true" aria-required="true" aria-invalid="true" value="{{ $date }}"> --}}





  <button type="submit" class="btn btn-block bg-red waves-effect"><i class="material-icons">update</i> Submit </button>
</form>



</div>
</div>
    </div>
</div>


