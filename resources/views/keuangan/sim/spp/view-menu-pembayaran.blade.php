<style>
    .tdbg-1{
        background: #efee9d;
    }
    .tdbg-2{
        background: #d1eaa3;
    }
    .tdbg-3{
        background: #dbc6eb;
    }
    .tdbg-4{
        background: #abc2e8;
    }
    .tdbg-5{
        background: #ddf3f5;
    }
    .tdbg-6{
        background: #f2aaaa;
    }
    .tdbg-7{
        background: #f6def6;
    }
    .tdbg-8{
        background: #f4ebc1;
    }
    .tdbg-9{
        background: #a6dcef;
    }
    .tdbg-10{
        background: #f2aaaa;
    }
    .tdbg-11{
        background: #ddf3f5;
    }
    .tdbg-12{
        background: #a0c1b8;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        PEMABAYARAN SISWA
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card is-gap">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->thn_akademik_semester}}" 
                                    @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                        selected
                                    @endif>
                                {{$semester->tahun_ajaran}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Kelas
                            </h2>
                            <select class="form-control show-tick" name="kelas">
                            <option value="">Pilih kelas</option>
                            @foreach($data_kelas as $data)
                            <option value="{{$data->id_kelas}}" @if(!empty($id_kelas) && $id_kelas == $data->id_kelas) selected @endif>
                                {{$data->nm_kelas}}
                            </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran/kelas</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        PEMBAYARAN SPP
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
                                    @if(!empty($bulan->id_bulan))
                                    <th class="tdbg-{{$bulan->id_bulan}}">{{$bulan->nm_bulan}}</th>
                                    @else
                                    <th class="tdbg">{{$bulan->nm_biaya}}</th>
                                    @endif
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
                                            @if($tagihan->is_request == 0)
                                            <button class="btn btn-block bg-black waves-effect" onclick="takeAction(this)" data-id="{{$tagihan->id_tagihan_biaya}}" data-nis="{{$tagihan->nis_siswa}}">Rp{{number_format($tagihan_bulanan)}}</button>
                                            @else
                                            Rp{{number_format($tagihan_bulanan)}}<br><b>Online</b>
                                            @endif
                                        </td>
                                        @elseif($tagihan->is_tagih == 0)
                                        <td class="tdbg-{{date_format(date_create($tagihan->tgl_pembayaran),'n')}}">{{date_format(date_create($tagihan->tgl_pembayaran),'d/m')}}
                                            <br>
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" style="width: 25px; height: 25px;" onclick="deleteActionKhusus(this)" data-id="{{$tagihan->id_pembayaran_biaya}}">
                                                <i class="material-icons" style="left: -7px; top: -7px;">close</i>
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
                        loadContent('sim/spp/pembayaran/{{$tahun_akademik_semester}}/{{$id_kelas}}');
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

    function filterAction(){
        var kelas = $('select[name=kelas]').val();
        var tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
        loadURI('sim/spp/pembayaran/'+tahun_akademik_semester+'/'+kelas);
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