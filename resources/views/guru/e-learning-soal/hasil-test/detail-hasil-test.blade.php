<div class="container-fluid">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/hasil-test') }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a></h2>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        List Hasil Test
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
                                    <th>Nama Siswa</th>
                                    {{-- <th>Jam Pengerjaan</th> --}}
                                    <th>Nilai Soal Pilihan Ganda</th>
                                    <th>Jumlah Soal</th>
                                    <th>Total Nilai Pilihan Ganda</th>
                                    <th>Total Nilai Jawaban Essay / Jawaban File</th>
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
<script>
    var paket_soal = '{{ $question_package->id_paket_soal }}';
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'hasil-test/detail/table/' + paket_soal;
    var detail_url = role_url + '#' + modul_url + '/' + 'paket-soal';
    var koreksi_hasil_test_url = role_url + '#' + modul_url + '/' + 'hasil-test' + '/' + 'koreksi';

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
                data: 'pengguna.nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'paket_soal.nilai',
            },
            {
                data: 'detail_paket_soal',
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
                    if (!data.status_koreksi) {
                        return `<a href="${koreksi_hasil_test_url}/${data.id_paket_soal}/${data.id_test}/${data.id_pengguna}">Koreksi Jawaban</a>`
                    } else {
                        return data.nilai_pilihan_essay_submit
                    }
                }
            },
            {
                data: 'total_nilai',
                render: function(data) {
                    return data.nilai
                }
            }
            // { data: 'waktu_mulai_pengerjaan' },
            // { data: 'jawaban_test' },
            // { data: 'total_question', name: 'total_question', searchable: false, orderable: false },
            // { data: 'total_answer', name: 'total_answer', searchable: false, orderable: false },
            // { data: 'nilai'},                { data: 'nilai'},                { data: 'nilai'},
            // { data: 'action', name: 'action', searchable: false, orderable: false,
            //     render: function(data) {
            //         return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' + detail_url +'/detail/' + data.id +'">' +
            //             '    <i class="material-icons">remove_red_eye</i>'+
            //             '</a>'
            //     }
            // }
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
