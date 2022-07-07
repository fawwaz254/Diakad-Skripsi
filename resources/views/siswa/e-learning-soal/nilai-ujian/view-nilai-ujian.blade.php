<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        List Nilai Ujian
                    </h2>
                    {{-- @foreach ($question_package as $peng)
                    <p>{{ $peng}}</p>
                    @endforeach --}}
                    {{-- @foreach ($question_package as $pengguna)
                    {{ $pengguna->pengguna->nm_pengguna }}
                    @endforeach --}}

                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Test</th>
                                    <th>Mapel</th>
                                    {{-- <th>Jam Pengerjaan</th> --}}
                                    <th>Nilai Soal Pilihan Ganda</th>
                                    <th>Jumlah Soal</th>
                                    <th>Total Nilai Pilihan Ganda</th>
                                    <th>Total Nilai Essay / Jawaban Submit</th>
                                    <th>Total Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
</div>
@include('scriptjs')
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'nilai-ujian/table';
    var detail_url = role_url + '#' + modul_url + '/' + 'list-ujian/cek/';
    var koreksi_hasil_test_url = role_url + '#' + modul_url + '/' + 'nilai-ujian' + '/' + 'koreksi';
    //  alert(datatable_url);

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'paket_soal.text',
                name: 'paket_soal.text'
            },
            {
                data: 'paket_soal.kategori_soal.nm_kategori_soal',
                name: 'paket_soal.kategori_soal.nm_kategori_soal'
            },
            {
                data: 'paket_soal.nilai',
                name: 'paket_soal.nilai'
            },
            {
                data: 'detail_paket_soal',
                name: 'detail_paket_soal'
            },
            {
                data: 'total_nilai',
                render: function(data) {
                    return data.nilai_pilihan_ganda
                }
            },
            {
                data: 'total_nilai',
                render: function(data) {
                    if(data.validasi_pilihan_essay_submit){
                        return `${data.nilai_pilihan_essay_submit} <a href="${koreksi_hasil_test_url}/${data.id_test}">(Lihat Penilaian)</a>`
                    }else{
                        return '-'
                    }
                }
            },
            {
                data: 'total_nilai',
                render: function(data) {
                    return data.nilai
                }
            }
        ],
        order: [
            [2, 'asc'],
            [1, 'asc']
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        });
    }).draw();
</script>
