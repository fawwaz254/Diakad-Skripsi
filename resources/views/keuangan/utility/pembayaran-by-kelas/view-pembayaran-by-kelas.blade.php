<style>
    .tdbg-1{
        background:aquamarine;
    }
    .tdbg-2{
        background:yellowgreen;
    }
    .tdbg-3{
        background:yellow;
    }
    .tdbg-4{
        background:chartreuse;
    }
    .tdbg-5{
        background:cadetblue;
    }
    .tdbg-6{
        background:chocolate;
    }
    .tdbg-7{
        background:darkgray;
    }
    .tdbg-8{
        background:red;
    }
    .tdbg-9{
        background:plum;
    }
    .tdbg-10{
        background:olivedrab;
    }
    .tdbg-11{
        background:blue;
    }
    .tdbg-12{
        background:hotpink;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER SEMESTER DAN KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-pembayaran-by-kelas')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach($data_semester as $data)
                                    <option value="{{$data->id_semester}}" @if((empty($id_semester) && $data->is_aktif_semester == 1) or (!empty($id_semester) && $id_semester == $data->id_semester)) selected @endif>
                                        {{$data->tahun_ajaran}}
                                        {{$data->nm_semester}} 
                                        @if($data->is_aktif_semester == 1)
                                            (Aktif)
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach($data_kelas as $data)
                                    <option value="{{$data->id_kelas}}" @if(!empty($id_kelas) && $id_kelas == $data->id_kelas) selected @endif>
                                        {{$data->nm_kelas}}
                                    </option>
                                    @endforeach
                                </select>
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
            @if($id_semester != null && $id_kelas != null)
            <div class="card">
                <div class="header">
                    <h2>
                        PEMBAYARAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">
                        Tanggal Pembayaran
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="datepicker form-control" name="tgl_pembayaran" value="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
                        </div>
                    </div>
                    <h2 class="card-inside-title">
                        Aksi yang dilakukan
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="radio" name="action" id="lunas" class="filled-in with-gap" checked="" value="1">
                                <label for="lunas">Langsung Lunas</label>

                                <input type="radio" name="action" id="cicilan" class="filled-in with-gap" value="2">
                                <label for="cicilan" class="m-l-20">Cicilan</label>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    @foreach($data_bulan_tagihan as $bulan)
                                    <th class="tdbg-{{$bulan->id_bulan}}">{{$bulan->nm_bulan}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach($data_siswa as $siswa)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$siswa->nis_siswa}}</td>
                                    <td>{{$siswa->nisn_siswa}}</td>
                                    <td>{{$siswa->pengguna->nm_pengguna}}</td>
                                    @foreach($data_bulan_tagihan as $bulan)
                                    @php
                                        $tagihan = $data_tagihan->where('id_siswa', $siswa->id_siswa)->where('id_bulan', $bulan->id_bulan)->first();
                                    @endphp
                                    @if(!empty($tagihan) > 0)
                                        @if($tagihan->is_tagih == 1)
                                        @php
                                            $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                                        @endphp
                                        <td>
                                            <button class="btn btn-block bg-black waves-effect" onclick="takeAction(this)" data-id="{{$tagihan->id_tagihan_biaya}}" data-nis="{{$tagihan->nis_siswa}}">Rp{{number_format($tagihan_bulanan)}}</button>
                                        </td>
                                        @elseif($tagihan->is_tagih == 0)
                                        <td class="tdbg-{{date_format(date_create($tagihan->tgl_pembayaran),'n')}}">{{date_format(date_create($tagihan->tgl_pembayaran),'d/m')}}
                                            <br>
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionKhusus(this)" data-id="{{$tagihan->id_pembayaran_biaya}}">
                                                <i class="material-icons">close</i>
                                            </button>
                                        </td>
                                        @endif
                                    @else
                                    <td></td>
                                    @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url                   = 'utility';
    var lunas_url                   = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/lunas';
    var detail_tagihan_siswa_url    = base_url + '/' + role_url + '#' + modul_url + '/' + 'pembayaran-siswa/view-detail-tagihan-siswa';
    var delete_pembayaran_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/delete';

    function takeAction(element){
        var item = $(element);
        if($('input[name=action]:checked',).val() == 2){
            window.open(detail_tagihan_siswa_url + '/' + item.attr('data-id') +'/' + item.attr('data-nis'));
        }else if($('input[name=action]:checked',).val() == 1){
            $('button').attr('disabled', 'disabled');
            $.ajax({
                type: "POST",
                url: lunas_url + '/' + item.attr('data-id'),
                data: {
                    tgl_pembayaran: $('input[name=tgl_pembayaran]').val()
                },
                success: function (response) {
                    vex.dialog.alert(response.message);
                    item.parent('td').replaceWith(
                        '<td class="tdbg-' + response.data.month + '">' + response.data.date + 
                        '    <br>'+
                        '    <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionKhusus(this)" data-id="' + response.data.id + '">'+
                        '        <i class="material-icons">close</i>'+
                        '    </button>'+
                        '</td>'
                    );
                    // loadContent('utility/pembayaran-by-kelas/view-detail/{{$id_semester}}/{{$id_kelas}}');
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            }); 
        }
    }

    function deleteActionKhusus(element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_pembayaran_url + '/' + item.attr('data-id'),
                    success: function (response) {
                        vex.dialog.alert(response.message);
                        loadContent('utility/pembayaran-by-kelas/view-detail/{{$id_semester}}/{{$id_kelas}}');
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>
<script>
$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: false
    });
});
</script>