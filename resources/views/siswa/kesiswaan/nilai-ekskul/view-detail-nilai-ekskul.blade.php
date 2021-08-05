<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#kesiswaan/nilai-ekskul')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Rekap Nilai Ekskul : {{ $data_ekskul->nm_ekskul }}</h2>
                    <p>Tahun Ajaran {{ $semester->tahun_ajaran . ' - ' . $semester->nm_semester }}</p>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive wrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS - Nama Siswa</th>
                                    <th>Kelas</th>
                                    @foreach($list_komponen as $komponen)
                                        <th style="text-align:  center;">
                                            {{ $komponen->nm_komponen_ekskul }}
                                        </th>
                                    @endforeach
                                    <th>Nilai Angka <br>(Rata-rata)</th>
                                    <th>Nilai Huruf</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $no = 0;
                            @endphp
                            @foreach($list_siswa as $siswa)
                                <tr>
                                    <td>{{++$no}}</td>
                                    <td>{{$siswa->nis_siswa}} - {{$siswa->nm_pengguna}}</td>
                                    <td>{{ $siswa->nm_kelas }}</td>
                                    @foreach($list_komponen as $komponen)
                                        <td>
                                            <input type="text" class="form-control" value="{{ collect($siswa->nilai_ekskul)->where('id_komponen_ekskul', $komponen->id_komponen_ekskul)->first()['nilai_komponen_ekskul'] }}" readonly>
                                        </td>
                                    @endforeach
                                    <td>
                                        {{ isset($siswa->nilai_angka) ? $siswa->nilai_angka : 0 }}
                                    </td>
                                    <td>{{ $siswa->nilai_huruf }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
@include('scriptjs')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#primary_table').DataTable();
    } );
</script>