<style>
  .status-header{
    margin: 0 1.5rem;
    font-size: 18px;
    font-weight: normal;
    color: #111;
  }
</style>

<div class="container-fluid">
  <div class="block-header">
      <h2><a class="btn bg-blue waves-effect target-link"
              href="{{url(Request::segment(1).'#'.Request::segment(2).'/')}}">
              <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
  </div>
  <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card">
              <div class="header">
                  <h2>
                      {{!empty($item)? 'EDIT' : 'TAMBAH'}} ALUMNI
                  </h2>
              </div>
              <div class="body">
                  <form id="form-validation" method="POST" class="row"
                      action="{{url(Request::segment(1).'/'.Request::segment(2))}}/{{!empty($item)? 'update' : 'store'}}">
                      {{csrf_field()}}
                      <input type="hidden" name="id_alumni" value="{{ !empty($item) ? $item->id_alumni : ''}}">
                      <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                        <h2 class="card-inside-title"> Nama Siswa </h2>
                        <input type="text" class="form-control" name="nama_siswa" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->nama_siswa : ''}}">
                      </div>
                      <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <h2 class="card-inside-title"> Jurusan </h2>
                        <div class="form-group">
                          <div class="form-line">
                              <select class="form-control show-tick" name="jurusan">
                                  <option value="" selected disabled> Pilih Jurusan </option>
                                  @foreach($data_jurusan as $jurusan)
                                    <option value="{{$jurusan->id_jurusan}}">{{$jurusan->nm_jurusan}}</option>
                                  @endforeach
                              </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <h2 class="card-inside-title"> Tahun Lulus </h2>
                        <input type="text" class="form-control" name="tahun_lulus" required="" aria-required="true" aria-invalid="true" value="">
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <h2 class="card-inside-title"> Nomor Telepon/HP/WA </h2>
                        <input type="text" class="form-control" name="" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->nm_kegiatan_harian : ''}}">
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <h2 class="card-inside-title"> Email </h2>
                        <input type="text" class="form-control" name="email" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->nm_kegiatan_harian : ''}}">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 class="card-inside-title"> Alamat </h2>
                        {{-- this data is not used of referred to siswa table --}}
                        <textarea class="form-control" name="alamat" disabled required="" aria-required="true" aria-invalid="true"> {{(!empty($item))? $item->nm_kegiatan_harian : ''}} </textarea>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 class="card-inside-title"> Status </h2>
                        <input class="with-gap radio-col-light-green form-control validate" type="radio" name="status" value="bekerja" id="work_status" required="required" data-error="Error msg here">
                        <label for="work_status"> Bekerja </label>
                        <input class="with-gap radio-col-light-green form-control validate" type="radio" name="status" value="usaha" id="enterpreneur_status" required="required" data-error="Error msg here">
                        <label for="enterpreneur_status"> Wirausaha </label>
                        <input class="with-gap radio-col-light-green form-control validate" type="radio" name="status" value="kuliah" id="college_status" required="required" data-error="Error msg here">
                        <label for="college_status"> Kuliah </label>
                        <input class="with-gap radio-col-light-green form-control validate" type="radio" name="status" value="menunggu" id="idle_status" required="required" data-error="Error msg here">
                        <label for="idle_status"> Belum Bekerja </label>
                      </div>

                      {{-- handle work data --}}
                      <div class="form_layout" id="work_state">
                        @include('./humas.alumni.forms.work_state')
                      </div>
                      {{-- handle Enterpreneur data --}}
                      <div class="form_layout" id="enterpreneur_state">
                        @include('./humas.alumni.forms.enterpreneur_state')
                      </div>
                      {{-- handle College data --}}
                      <div class="form_layout" id="college_state">
                        @include('./humas.alumni.forms.college_state')
                      </div>
                      {{-- handle idle data --}}
                      <div class="form_layout" id="idle_state">
                        @include('./humas.alumni.forms.idle_state')
                      </div>
                      

                      <div class="row clearfix">
                          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                              <button id="submit" disabled class="btn btn-block bg-red waves-effect" type="submit">
                                <i class="material-icons">save</i><span>Save</span>
                              </button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css"></script> --}}
<script>
  // hadle first load of page
  $(document).ready(function(){
    var status = $("input[name='status']").value;
    toggleAlumniForm(status)
  })

  $("input[name='status']").change(function(){
    toggleAlumniForm(this.value)
  })

  function toggleAlumniForm(status){
    $('.form_layout').hide();
    
    switch (status) {
      case 'bekerja':
        $('.form_layout#work_state').show();
        $('button#submit').attr('disabled', false);
        break;
      case 'usaha':
        $('.form_layout#enterpreneur_state').show();    
        $('button#submit').attr('disabled', false);
      break;
      case 'kuliah':
        $('.form_layout#college_state').show();
        $('button#submit').attr('disabled', false);
      break;
      case 'menunggu':
        $('.form_layout#idle_state').show();
        $('button#submit').attr('disabled', false);
      break;
    }
  }

</script>
@include('scriptjs')