<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{Carbon\Carbon::now('Asia/Jakarta')->format('d M Y')}}</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        List Paket Soal
                    </h2>
                    {{-- @foreach($question_package as $peng)
                    <p>{{ $peng}}</p>
                    @endforeach --}}
                    {{-- @foreach($question_package as $pengguna)
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
                                    {{-- <th>Kelas</th>
                                    <th>Betul</th>
                                    <th>Salah</th>
                                    <th>Tidak Menjawab</th>--}}
                                    <th>Jam Pengerjaan</th> 
                                    <th>Status</th>
                                    {{-- <th>Nilai</th> --}}
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
     var modul_url       = '{{Request::segment(2)}}';
     var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'hasil-test/detail/table/'+paket_soal;
     var detail_url     =  role_url + '#' + modul_url + '/' + 'paket-soal';


  
        var primary_table = $('#primary_table').DataTable({
            processing: true,
            // serverSide: true,
            ajax: {
                url: datatable_url,
                type: 'POST'
            },
            columns: [
                { data: null, searchable: false, orderable: false },
                { data: 'pengguna.nm_pengguna', name: 'pengguna.nm_pengguna'},
                { data: 'status' },
                // { data: 'waktu_mulai_pengerjaan' },
                // { data: 'jawaban_test' },
                // { data: 'total_question', name: 'total_question', searchable: false, orderable: false },
                // { data: 'total_answer', name: 'total_answer', searchable: false, orderable: false },
                // { data: 'nilai'},                { data: 'nilai'},                { data: 'nilai'},
                { data: 'action', name: 'action', searchable: false, orderable: false,
                    render: function(data) {
                        return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' + detail_url +'/detail/' + data.id +'">' +
                            '    <i class="material-icons">remove_red_eye</i>'+
                            '</a>'
                    }
                }
            ],
            order: [[2, 'asc'], [1, 'asc']]
        });

        primary_table.on( 'draw', function () {
            primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                var start = this.page.info().page * this.page.info().length;
                cell.innerHTML = i + 1;
            } );
        } ).draw();
 

  
</script>