<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pembina-ekskul/input-nilai-ekskul')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    @if($jumlah_persentase_komponen < 100)
        <div class="demo-color-box bg-red" style="height: 350px">
            <h2>Tidak dapat meng-input nilai. Presentase Komponen Ekskul Kurang dari 100%</h2>
        </div>
    @else
        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/input-nilai-ekskul/save')}}">
            {{csrf_field()}}
            <input type="hidden" name="id_ekskul" id="id_ekskul" value="{{$data_ekskul->id_ekskul}}" />
            <input type="hidden" name="id_semester" id="id_semester" value="{{$semester->id_semester}}" />
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="block-header">
                    <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                </div>
            </div>
                <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                            {{csrf_field()}}
                            <div class="header">
                                <h2>Input Nilai Ekskul : {{ $data_ekskul->nm_ekskul }}</h2>
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
                                                        <input type="text" class="form-control" name="nilai{{ $komponen->id_komponen_ekskul }}-{{ $siswa->id_siswa }}" id="nilai{{ $komponen->id_komponen_ekskul }}-{{ $siswa->id_siswa }}" value="{{ collect($siswa->nilai_ekskul)->where('id_komponen_ekskul', $komponen->id_komponen_ekskul)->first()['nilai_komponen_ekskul'] }}">
                                                    </td>
                                                @endforeach
                                                <td>
                                                    {{ isset($siswa->nilai_angka) ? $siswa->nilai_angka : 0 }}
                                                </td>
                                                <td>{{ $siswa->nilai_huruf }}</td>
                                        </form>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    @endif
</div>
@include('scriptjs')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#primary_table').DataTable();
    } );
</script>