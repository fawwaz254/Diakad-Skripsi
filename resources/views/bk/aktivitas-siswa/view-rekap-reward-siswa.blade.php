<div class="row clearfix">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="card is-gap">
      <div class="header">
        <h2>Filter Data</h2>
      </div>
      <div class="body">
        <div class="row clearfix">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <h4>
              Kelas
            </h4>
            <select class="form-control show-tick" name="kelas" required="">
              <option value="" selected disabled>-- Pilih Kelas --</option>
              @foreach ($data_kelas as $kelas)
          <option value="{{ $kelas->id_kelas }}" {{ Request::input('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
          {{ $kelas->nm_kelas }}
          </option>
        @endforeach
            </select>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <h4>
              Semester
            </h4>
            <select class="form-control show-tick" name="semester" required="" onchange="">
              <option value="" selected disabled>-- Pilih Semester --</option>
              @foreach ($data_semester as $semester)
          <option value="{{ $semester->id_semester }}" {{ Request::input('semester') == $semester->id_semester ? 'selected' : '' }}>
          {{ $semester->nm_semester . ' ' . $semester->tahun_ajaran }}
          </option>
        @endforeach
            </select>
          </div>
        </div>
        <div class="row clearfix">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <button class="btn bg-red waves-effect" onclick="filterAction()">
              <i class="material-icons">save</i>
              <span>Filter</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if (!empty(Request::input('kelas')) && !empty(Request::input('semester')))
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="header">
      <h2>Rekap Reward Siswa</h2>
      </div>
      <div class="body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
        id="table_rekap_reward">
        <thead>
          <tr>
          <th style="vertical-align : middle;text-align:center;">No</th>
          <th style="vertical-align : middle;text-align:center;">NIS</th>
          <th style="vertical-align : middle;text-align:center;">Nama Siswa</th>
          @foreach ($data_aktivitas_reward_siswa as $aktivitas)
        <th style="vertical-align : middle;text-align:center;">{{ $aktivitas->nilai_karakter }}</th>
      @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($data_siswa as $siswa)
          <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $siswa->nis_siswa }}</td>
          <td>{{ $siswa->nm_pengguna }}</td>
          @foreach ($data_aktivitas_reward_siswa as $aktivitas)
          @php
      $data = $data_reward
      ->where('id_siswa', $siswa->id_siswa)
      ->where('nilai_karakter', $aktivitas->nilai_karakter);
    @endphp
          @if ($data)
        <td style="text-align:center;">
        <a href="#" style="color: black; text-decoration: underline" data-toggle="modal"
        data-target="#modalDetailReward" data-id="{{ $siswa->id_siswa }}" data-nis="{{ $siswa->nis_siswa }}"
        data-nama="{{ $siswa->nm_pengguna }}" data-karakter="{{ $aktivitas->nilai_karakter }}">
        {{ $data->sum('total_point') }}
        </a>
        </td>
      @else
      <td style="text-align:center;">
      <a href="#" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalDetailReward"
      data-id="{{ $siswa->id_siswa }}" data-nama="{{ $siswa->nm_pengguna }}">
      Lihat Detail
      </a>
      </td>
    @endif
      @endforeach
          </tr>
      @endforeach
        </tbody>
        </table>
      </div>
      </div>
    </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="header">
      <h2>Rekap Presensi Siswa</h2>
      </div>
      <div class="body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
        id="table_rekap_reward">
        <thead>
          <tr>
          <th style="vertical-align : middle;text-align:center;">No</th>
          <th style="vertical-align : middle;text-align:center;">NIS</th>
          <th style="vertical-align : middle;text-align:center;">Nama Siswa</th>
          @foreach ($data_aktivitas_reward_siswa_presensi as $aktivitas)
        <th style="vertical-align : middle;text-align:center;">{{ $aktivitas }}</th>
      @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($data_siswa as $siswa)
          <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $siswa->nis_siswa }}</td>
          <td>{{ $siswa->nm_pengguna }}</td>
          @foreach ($data_aktivitas_reward_siswa_presensi as $aktivitas)
          @php
      $data = $data_presensi
      ->where('id_kelas', $siswa->id_siswa)
      ->where('nm_reward_siswa', $aktivitas)
      ->count('id_kelas_mp');
    @endphp
          @if ($data)
        <td style="text-align:center;">
        {{ $data }}
        </td>
      @else
      <td style="text-align:center;">0</td>
    @endif
      @endforeach
          </tr>
      @endforeach
        </tbody>
        </table>
      </div>
      </div>
    </div>
    </div>
  @endif
  <!-- Modal -->
  <div class="modal fade" id="modalDetailReward" tabindex="-1" role="dialog" aria-labelledby="modalLabelReward"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modalLabelReward">Detail Reward Siswa</h4>
        </div>
        <div class="modal-body">
          <p><strong>NIS:</strong> <span id="modalNISSiswa"></span></p>
          <p><strong>Nama:</strong> <span id="modalNamaSiswa"></span></p>
          <p><strong>Nilai Karakter:</strong> <span id="modalNilaiKarater"></span></p>
          <div class="table-responsive">
            <table class="table table-bordered" id="tableDetailAktivitasReward">
              <thead>
                <tr>
                  <th>Nama Aktivitas</th>
                  <th>Jenis Aktivitas</th>
                  <th>Frekuensi</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data akan di-inject dengan JavaScript -->
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let modul_url = 'reward-siswa';
  let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rekap-reward-siswa/datatables';
  // let delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivitas-reward-siswa/delete';
  // let edit_url = base_url + '/' + role_url + '#' + modul_url + '/' + 'aktivitas-reward-siswa/edit';

  function filterAction() {
    var kelas = $('select[name=kelas]').val();
    var semester = $('select[name=semester]').val();
    if (!kelas || !semester) {
      swal({
        title: 'Input kelas dan semester tidak boleh kosong',
      });
    } else {
      loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}?kelas=' + kelas + '&semester=' + semester);
    }
  }
</script>

<script>
  $('#modalDetailReward').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var nis = button.data('nis');
    var nama = button.data('nama');
    var karakter = button.data('karakter');
    var semester = $('select[name=semester]').val();

    var modal = $(this);
    modal.find('#modalNamaSiswa').text(nama);
    modal.find('#modalNISSiswa').text(nis);
    modal.find('#modalNilaiKarater').text(karakter);

    // Kosongkan isi sebelumnya untuk menghindari duplikasi
    modal.find('#tableDetailAktivitasReward tbody').empty();
    modal.find('#modalTotalPoint').remove(); // kalau sebelumnya ada total point

    var datatable_detail_url = base_url + '/' + role_url + '/reward-siswa/rekap-reward-siswa/detail/' + id + '?semester=' + semester + '&karakter=' + karakter;

    $.ajax({
      url: datatable_detail_url,
      method: 'GET',
      success: function (data) {
        if (Array.isArray(data.reward_list)) {
          data.reward_list.forEach(function (item) {

            $('#tableDetailAktivitasReward tbody').append(`
              <tr>
                <td>${item.nm_aktivitas_reward_siswa}</td>
                <td>${item.nm_jenis_aktivitas_reward}</td>
                <td>${item.frekuensi}</td>
              </tr>
            `);
          });
        }
      },
      error: function (xhr) {
        console.error("Gagal mengambil data reward:", xhr);
      }
    });
  });
</script>