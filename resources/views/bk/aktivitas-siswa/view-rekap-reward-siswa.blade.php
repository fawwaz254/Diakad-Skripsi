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
        <h2>
          Rekap Reward Siswa
          <button id="btnExportPDF" class="btn btn-success waves-effect">
            <i class="material-icons">file_download</i>
            <span>Export PDF</span>
          </button>
        </h2>
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
            id="table_rekap_presensi">
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

<!-- HTML2PDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
  let header_pdf = '{{ $auth_data->sekolah_data->nm_sekolah }}';
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

  // PDF Export function
  document.getElementById('btnExportPDF').addEventListener('click', function() {
    const kelasName = $('select[name=kelas] option:selected').text().trim();
    const semesterName = $('select[name=semester] option:selected').text().trim();

    const rewardColumnCount = document.querySelectorAll('#table_rekap_reward thead th').length;
    const presensiColumnCount = document.querySelectorAll('#table_rekap_presensi thead th').length;

    const pdfContent = document.createElement('div');
    pdfContent.className = 'pdf-content';

    const styleElement = document.createElement('style');
    styleElement.textContent = `
      .pdf-content {
        font-family: Arial;
        padding: 10px;
      }
      .pdf-header {
        text-align: left;
        margin-bottom: 16px;
      }
      .pdf-namasekolah {
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 4px;
      }
      .pdf-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 5px;
      }
      .pdf-subtitle {
        font-size: 16px;
        margin-bottom: 10px;
      }
      table {
        width: 100%;
        table-layout: auto;
        border-collapse: collapse;
        margin-bottom: 30px;
      }
      table {
        border: 1px solid #000;
        width: 100%;
        table-layout: auto;
        border-collapse: collapse;
        margin-bottom: 30px;
      }
      th, td {
        padding: 3px;
        font-size: 12px;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: break-word;
      }
      th {
        background-color: #f2f2f2;
        font-weight: bold;
        font-size: 14px;
      }
      td:nth-child(2) { 
        text-align: center;
      }
      td:nth-child(3) { 
        text-align: left;
      } 
      .section-title {
        font-size: 16px;
        font-weight: bold;
        margin: 32px 0 13px 0;
      }
    `;
    pdfContent.appendChild(styleElement);

    const header = document.createElement('div');
    header.className = 'pdf-header';
    header.innerHTML = `
      <div class="pdf-namasekolah">${header_pdf}</div>
      <div class="pdf-title">REKAP REWARD SISWA</div>
      <div class="pdf-subtitle">Kelas: ${kelasName} | Semester: ${semesterName}</div>
    `;
    pdfContent.appendChild(header);

    function optimizeTableForPdf(tableId) {
      const originalTable = document.getElementById(tableId);
      const tableClone = originalTable.cloneNode(true);

      const links = tableClone.querySelectorAll('a');
      links.forEach(link => {
        const textContent = link.textContent.trim();
        const parentCell = link.parentNode;
        parentCell.textContent = textContent;
      });

      // Set column 
      const headerCells = tableClone.querySelectorAll('thead th');
      headerCells.forEach((cell, index) => {
        if (index === 0) { // Kolom No
          cell.style.width = '2%';
        } else if (index === 1) { // Kolom NIS
          cell.style.width = '6%';
        } else if (index === 2) { // Kolom Nama
          cell.style.width = '25%';
        } else { // kolom selanjutnya
          cell.style.width = (100 / (headerCells.length - 3)) + '%';
        }
      });
      return tableClone;
    }

    // Rekap Reward Siswa
    const rewardTitle = document.createElement('div');
    rewardTitle.className = 'section-title';
    rewardTitle.textContent = 'Rekap Reward Siswa';
    pdfContent.appendChild(rewardTitle);

    const rewardTable = optimizeTableForPdf('table_rekap_reward');
    pdfContent.appendChild(rewardTable);

    // Page Break
    const pageBreak = document.createElement('div');
    pageBreak.style.pageBreakAfter = 'always';
    pdfContent.appendChild(pageBreak);
    
    // Rekap Presensi Siswa
    const presensiTitle = document.createElement('div');
    presensiTitle.className = 'section-title';
    presensiTitle.textContent = 'Rekap Presensi Siswa';
    pdfContent.appendChild(presensiTitle);

    const presensiTable = optimizeTableForPdf('table_rekap_presensi');
    pdfContent.appendChild(presensiTable);

    let pageFormat, pageOrientation;
    const maxColumns = Math.max(rewardColumnCount, presensiColumnCount);
    if (maxColumns > 10) {
      pageFormat = [297, 420]; // A3
      pageOrientation = 'landscape';
    } else if (maxColumns > 7) {
      pageFormat = 'a4';
      pageOrientation = 'landscape';
    } else {
      pageFormat = 'a4';
      pageOrientation = 'portrait';
    }
    swal({
      title: 'Memproses PDF',
      text: 'Mohon tunggu sebentar...',
      allowOutsideClick: false,
      allowEscapeKey: false,
      onOpen: () => {
        swal.showLoading();
      }
    });
    const pdfOptions = {
      margin: [10, 15, 10, 15],
      filename: `Rekap_Reward_Siswa_${kelasName}_${semesterName.replace(/\s/g, '_')}.pdf`,
      image: {
        type: 'jpeg',
        quality: 9.95
      },
      html2canvas: {
        scale: 2,
        logging: false,
        letterRendering: true
      },
      jsPDF: {
        unit: 'mm',
        format: pageFormat,
        orientation: pageOrientation,
        compress: true
      }
    };

    // Men-Generate PDF
    html2pdf().from(pdfContent).set(pdfOptions).save()
      .then(() => {
        swal.close();
        swal({
          title: 'PDF Berhasil Dibuat',
          text: 'File PDF telah berhasil diunduh',
          icon: 'success',
          timer: 2000
        });
      })
      .catch(err => {
        swal.close();
        swal({
          title: 'Error',
          text: 'Terjadi kesalahan saat membuat PDF',
          icon: 'error'
        });
        console.error('PDF generation error:', err);
      });
  });
</script>

<script>
  $('#modalDetailReward').on('show.bs.modal', function(event) {
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
      success: function(data) {
        if (Array.isArray(data.reward_list)) {
          data.reward_list.forEach(function(item) {

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
      error: function(xhr) {
        console.error("Gagal mengambil data reward:", xhr);
      }
    });
  });
</script>