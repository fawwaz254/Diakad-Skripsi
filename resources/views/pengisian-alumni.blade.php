<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<title>Pengisian Data Alumni</title>
</head>
<body>

<form action="/action-pengisian-alumni" method="post" novalidate>
{{csrf_field()}}

<div class="container" style="margin-top:50px;">
  <div class="card">
    <div class="card-body">
      <h4>PENELUSURAN LULUSAN SMK PEMUDA</h4>

      <br>

      <div class="form-group">
        <label for="exampleFormControlInput1">Nama Siswa</label>
        <input type="text" class="form-control" name="nama_siswa"  required>
      </div>

      <div class="form-group">
        <label for="exampleFormControlInput1">Jurusan</label>
        <select class="form-control" name="jurusan">
          <option selected="" disabled="">Pilih Jurusan</option>
          @foreach($data_jurusan as $jurusan)
          <option value="{{$jurusan->id_jurusan}}">
            {{$jurusan->nm_jurusan}}
          </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="exampleFormControlInput1">Tahun Lulus</label>
        <input type="number" class="form-control" name="tahun_lulus"  required>
      </div>

      <div class="form-group">
        <label for="exampleFormControlInput1">Kelas</label>
        <select class="form-control" name="id_kelas">
          <option selected="" disabled="">Pilih Kelas</option>
          @foreach($data_kelas as $kelas)
          <option value="{{$kelas->id_kelas}}">
            {{$kelas->nm_kelas}}
          </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label for="exampleFormControlInput1">Nomor HP</label>
        <input type="text" class="form-control" name="nomor_hp"  required>
      </div>

      <div class="form-group">
        <label for="exampleFormControlInput1">Email</label>
        <input type="email" class="form-control" name="email"  required>
      </div>

      <div class="form-group">
        <label for="exampleFormControlTextarea1">Alamat</label>
        <textarea class="form-control" name="alamat_siswa" required="" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label for="exampleFormControlTextarea1">Status</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="status" id="work_status" value="bekerja">
          <label class="form-check-label" for="exampleRadios1">
            Bekerja
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="status" id="enterpreneur_status" value="usaha">
          <label class="form-check-label" for="exampleRadios2">
            Wirausaha
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="status" id="college_status" value="kuliah">
          <label class="form-check-label" for="exampleRadios3">
            Kuliah
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="status" id="idle_status" value="menunggu">
          <label class="form-check-label" for="exampleRadios4">
            Belum Bekerja
          </label>
        </div>
      </div>

      <div class="form_layout" id="work_state">
        @include('form-pengisian-alumni.work_state')
      </div>

      <div class="form_layout" id="enterpreneur_state">
        @include('form-pengisian-alumni.enterpreneur_state')
      </div>

      <div class="form_layout" id="college_state">
        @include('form-pengisian-alumni.college_state')
      </div>

      <div class="form_layout" id="idle_state">
        @include('form-pengisian-alumni.idle_state')
      </div>

    </div>
  </div>

  <br>
  <button id="submit" disabled class="btn btn-success" type="submit">Submit</button>
  <p></p>

</div>

</form>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script type="text/javascript">

  $(document).ready(function(){
    var alumni = {!! $alumni != null ? json_encode($alumni->toArray(), JSON_HEX_TAG) : "''" !!};
    var status = $("input[name='status']").value || alumni.status;
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
</body>
</html>