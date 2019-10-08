<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/usulan-mata-ajar/view-semester-usulan-mata-ajar/'.$kelas_mp->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-light-green">
                    <h2>
                        PILIH KELAS UNTUK USULAN MATA AJAR Mata Pelajaran : {{$mapel->nm_mata_pelajaran}}
                    </h2>
                    <br>
                    <h2 style="font-size: 18px">Copy Dari Kelas : {{$kelas_mp->kelas->nm_kelas}} <br> Semester : {{$semester->tahun_ajaran}} ({{$semester->nm_semester}})</h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-usulan-mata-ajar/copy/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Mata Pelajaran
                        </h2>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>{{$mapel->kd_mata_pelajaran}} - {{$mapel->nm_mata_pelajaran}}</label>
                        </div>
                        <h2 class="card-inside-title">
                            Jurusan
                        </h2>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>{{$mapel->nm_jurusan}}</label>
                        </div>
                        <h2 class="card-inside-title">
                            Tingkat Semester
                        </h2>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>{{$mapel->tingkat_semester}}</label>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        @php
                            $i = 0;
                        @endphp
                        @foreach($kelas->groupBy('nm_jurusan')->toArray() as $label_jurusan => $chunk_data)
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                            <input id="checkbox_select_all_{{$i}}" type="checkbox" name="select_all" data-jurusan="{{$i}}" class="filled-in">
                            <label for="checkbox_select_all_{{$i}}" style="margin-bottom: -10px;"></label>
                            <label><b>{{$label_jurusan}}</b></label>
                            @foreach($chunk_data as $data)
                            @php
                                $data = (object) $data;
                            @endphp
                                <div class="row clearfix">
                                        <input type="checkbox" id="checkbox-{{$data->id_kelas}}" name="id_kelas[]" data-jurusan="{{$i}}" class="filled-in" value="{{$data->id_kelas}}">
                                        <label for="checkbox-{{$data->id_kelas}}">{{$data->nm_kelas}}</label>
                                </div>
                            @endforeach
                            </div>
                        @php
                            $i++;
                        @endphp
                        @endforeach
                        <input type="hidden"  value="{{$kelas_mp->id_mata_pelajaran}}" class="form-control" name="id_mata_pelajaran" aria-required="true">
                        <input type="hidden"  value="{{$kelas_mp->id_semester}}" class="form-control" name="id_semester" aria-required="true">
                        <input type="hidden"  value="{{$kelas_mp->jml_pertemuan_kelas_mp}}" class="form-control" name="jml_pertemuan_kelas_mp" aria-required="true">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script type="text/javascript">
    $(document).ready(function() {        
        /* Select All Checkbox */
        $('input[name="select_all"]').change(function() {
            var select_all_checked = this.checked;
            var data_jurusan = $(this).attr('data-jurusan');

            console.log(data_jurusan);
            $('input[data-jurusan="'+ data_jurusan +'"]').prop('checked', this.checked);
        });
    });
</script>