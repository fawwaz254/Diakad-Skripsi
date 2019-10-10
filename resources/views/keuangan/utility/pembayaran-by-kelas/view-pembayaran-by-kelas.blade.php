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
            <div class="card">
                <div class="header bg-amber">
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

                @if($id_semester != null && $id_kelas != null)
                <div class="body">
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
                                    @foreach($data_tagihan->where('id_siswa', $siswa->id_siswa)->unique('nm_bulan')->sortBy('id_bulan')->values()->all() as $tagihan)
                                    @if($tagihan->is_tagih == 1)
                                    @php
                                        $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                                    @endphp
                                    <td><button class="btn btn-block bg-black waves-effect" onclick="lunasAction(this)" data-id="{{$tagihan->id_tagihan_biaya}}" data-nis="{{$tagihan->nis_siswa}}">Rp{{number_format($tagihan_bulanan)}}</button></td>
                                    @elseif($tagihan->is_tagih == 0)
                                    <td class="tdbg-{{date_format(date_create($tagihan->tgl_pembayaran),'n')}}">{{date_format(date_create($tagihan->tgl_pembayaran),'d/m')}}</td>
                                    @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    var modul_url                   = 'utility';
    var lunas_url                   = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/lunas';
    var detail_tagihan_siswa_url    = base_url + '/' + role_url + '#' + modul_url + '/' + 'pembayaran-siswa/view-detail-tagihan-siswa';

    function lunasAction(element){
        var item = $(element);

        $('button').attr('disabled', 'disabled');

        swal({
            title: "Pembayaran Tagihan Langsung LUNAS?",
            text: 'Untuk cicilan <a class="btn bg-cyan" target="_blank" href="'+ detail_tagihan_siswa_url + '/' + item.attr('data-id') +'/' + item.attr('data-nis') + '">Klik Di sini</a class="btn bg-cyan">',
            html: 'Untuk cicilan <a class="btn bg-cyan" target="_blank" href="'+ detail_tagihan_siswa_url + '/' + item.attr('data-id') +'/' + item.attr('data-nis') + '">Klik Di sini</a class="btn bg-blue">',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, LUNAS!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: lunas_url + '/' + item.attr('data-id'),
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
