<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#magang-siswa/input-nilai-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-nilai-magang/save/'.$id_periode_magang)}}">
    {{csrf_field()}}
    <input type="hidden" name="id_periode_magang" id="id_periode_magang" value="{{$id_periode_magang}}" />
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
                        <h2>INPUT NILAI MAGANG Periode {{$periodeMagang->nm_periode_magang}}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS - Nama Siswa</th>
                                        <th>Semester</th>
                                        <th>Rekanan Magang</th>
                                        @foreach($list_data as $data)
                                            <th style="text-align:  center;">
                                                {{$data->nm_komponen_magang}}
                                                <br>
                                                ({{$data->persentase_komponen_magang}}%)
                                            </th>
                                        @endforeach
                                        <th>Nilai Angka</th>
                                        <th>Nilai Huruf</th>
                                        <th>Action</th>
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
                                        <td>{{$siswa->nm_semester}}/{{$siswa->thn_akademik_semester}}</td>
                                        <td>
                                            {{$siswa->nm_rekanan_magang}}
                                             <input type="hidden" name="id_rekanan_magang_{{$siswa->id_siswa}}" id="id_rekanan_magang_{{$siswa->id_siswa}}" value="{{$siswa->id_rekanan_magang}}" />
                                             <input type="hidden" name="id_pengambilan_magang_{{$siswa->id_siswa}}" id="id_pengambilan_magang_{{$siswa->id_siswa}}" value="{{$siswa->id_pengambilan_magang}}" />
                                        </td>
                                        @foreach($list_data as $nilai)
                                            <td>
                                                <input type="text" name="nilai{{$nilai->id_komponen_magang}}-{{$siswa->id_siswa}}" id="nilai{{$nilai->id_komponen_magang}}-{{$siswa->id_siswa}}" value="{{$nilai_magang_siswa[$siswa->id_pengambilan_magang.$nilai->id_komponen_magang]}}"/ style="width: 50%">
                                            </td>
                                        @endforeach
                                        <td>
                                            {{isset($siswa->nilai_angka) ? $siswa->nilai_angka : 0}}
                                        </td>
                                        <td>{{$siswa->nilai_huruf}}</td>
                                        <td>
                                            <button class="btn btn-info btn-circle waves-effect waves-circle waves-float"><i class="material-icons">visibility</i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#primary_table').DataTable();
    } );
</script>