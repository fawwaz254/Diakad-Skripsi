<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#penilaian/input-nilai')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    @if($jumlah_komponen < 100)
    <div class="card">
        <div class="header bg-red">
            <h2>Tidak dapat meng-input nilai. Presentase Komponen Kurang dari 100%</h2>
        </div>
    </div>
    @else
    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-nilai/kbm/0')}}">
        {{csrf_field()}}
        <input type="hidden" name="id_kelas_mp" id="id_kelas_mp" value="{{$id_kelas_mp}}" />
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Input Nilai </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS - Nama Siswa</th>
                                        @foreach($list_data as $data)
                                            <th style="text-align:  center;">
                                                {{$data->nm_komponen_mp}}
                                                <br>
                                                ({{$data->persentase_komponen_mp}}%)
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
                                        @foreach($list_data as $nilai)
                                            <td>
                                                <input type="text" name="nilai{{$nilai->id_komponen_mp}}-{{$siswa->id_siswa}}" id="nilai{{$nilai->id_komponen_mp}}-{{$siswa->id_siswa}}" style="width: 50%;  margin:0px auto;">
                                            </td>
                                        @endforeach
                                        <td>
                                            {{isset($siswa->nilai_angka) ? $siswa->nilai_angka : 0}}
                                        </td>
                                        <td>{{$siswa->nilai_huruf}}</td>
                                        <td>
                                            <button class=" btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="showAction(this)" data-id="{{$siswa->id_pengambilan_mp}}">
                                                <i class="material-icons">visibility</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif
</div>
@include('scriptjs')
<script type="text/javascript">
    let show_url = "{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-nilai/tampil')}}";
    $(document).ready(function() {
        var table = $('#primary_table').DataTable({
            pageLength: 100
        });
    } );

    function showAction(element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: show_url + '/' + item.attr('data-id'),
            success: function (response) {
                if(response.status == 200){
                    vex.dialog.alert(response.message);
                }else if(response.status == 201){
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                }else if(response.status == 202){
                    vex.dialog.alert(response.message);
                    loadURI(response.path);
                }else if(response.status == 203){
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                }else if(response.status == 300){
                    vex.dialog.alert(response.message);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>