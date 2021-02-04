<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'. Request::segment(2) .'/rekap-nilai') }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    @if($jumlah_komponen < 100)
        <div class="demo-color-box bg-red" style="height: 350px">
            <h2>Tidak dapat merekap nilai. Presentase Komponen Kurang dari 100%</h2>
        </div>
    @elseif($jumlah_subkomponen < 1)
        <div class="demo-color-box bg-red" style="height: 350px">
            <h2>Tidak dapat merekap nilai.</h2>
            <p>Sub Komponen Tidak Lengkap/Tidak Ditemukan</p>
        </div>
    @else
        <div class="col-xs-12 col-sm-4 col-md-4">
            <div class="block-header">
                <a class="btn btn-block bg-red waves-effect" href="{{url(Request::segment(1).'/'. Request::segment(2) . '/rekap-nilai/print/' . $id_kelas_mp)}}" target="_blank"><i class="material-icons">print</i><span>Cetak</span></a>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Rekap Nilai : {{ $data_kelas->nm_mata_pelajaran .' - '. $data_kelas->nm_kelas .' - '. $data_kelas->tahun_ajaran . ' (' . $data_kelas->nm_semester . ')' }}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive wrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">NIS - Nama Siswa</th>
                                        @foreach($list_data as $komponen => $sub_komponen)
                                            <th colspan="{{count($sub_komponen)}}" style="text-align:  center;">
                                                {{$komponen}}
                                            </th>
                                        @endforeach
                                        <th rowspan="2">Nilai Angka <br>(Rata-rata)</th>
                                        <th rowspan="2">Nilai Huruf</th>
                                    </tr>
                                    <tr>
                                        @foreach($list_data as $komponen => $sub_komponen)
                                            @foreach($sub_komponen as $sub)
                                                <th>
                                                    {{ $sub->kd_subkomponen_mp }} <br> {{ $sub->nm_subkomponen_mp }}
                                                </th>
                                            @endforeach
                                        @endforeach
                                        
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
                                        @foreach($list_data as $komponen)
                                            @foreach($komponen as $nilai)
                                                <td>
                                                    <input type="text" class="form-control" value="{{ collect($siswa->nilai_siswa_komponen)->where('id_subkomponen_mp', $nilai->id_subkomponen_mp)->first()['nilai_subkomponen_mp'] }}" readonly>
                                                </td>
                                            @endforeach
                                        @endforeach
                                        <td>
                                            {{isset($siswa->nilai_angka) ? $siswa->nilai_angka : 0}}
                                        </td>
                                        <td>{{$siswa->nilai_huruf}}</td>
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
        var table = $('#primary_table').DataTable({
            pageLength: 100
        });
    } );
</script>