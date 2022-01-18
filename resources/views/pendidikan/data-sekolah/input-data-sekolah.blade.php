<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Input Data Sekolah 
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-data-sekolah/edit/'.$sekolah->id_sekolah)}}">
                        {{csrf_field()}}
                        <div class="demo-color-box bg-success">
                                DATA SEKOLAH
                        </div>
                        <h2 class="card-inside-title">
                            Nama Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_sekolah" required="" aria-required="true" aria-invalid="true" value="{{$sekolah->nm_sekolah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Pokok Sekolah Nasional
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="npsn_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->npsn_sekolah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Bentuk Pendidikan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_bentuk_pendidikan" id="id_bentuk_pendidikan">
                                    @if($sekolah->id_bentuk_pendidikan == NULL)
                                        <option value="">-- Pilih Bentuk Pendidikan --</option>
                                        @foreach($bentuk_pendidikan as $bentuk_pendidikan)
                                            <option value="{{$bentuk_pendidikan->id_bentuk_pendidikan}}">{{$bentuk_pendidikan->nm_bentuk_pendidikan}}</option>
                                        @endforeach
                                    @else
                                        <option value="">-- Pilih Bentuk Pendidikan --</option>
                                        @foreach($bentuk_pendidikan as $bentuk_pendidikan)
                                            @if($bentuk_pendidikan->id_bentuk_pendidikan == $sekolah->id_bentuk_pendidikan)
                                                <option value="{{$bentuk_pendidikan->id_bentuk_pendidikan}}" selected >{{$bentuk_pendidikan->nm_bentuk_pendidikan}}</option>
                                            @else
                                                <option value="{{$bentuk_pendidikan->id_bentuk_pendidikan}}">{{$bentuk_pendidikan->nm_bentuk_pendidikan}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                                KEPEMILIKAN SEKOLAH
                        </div>
                        <h2 class="card-inside-title">
                            No. SK Pendirian Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_pendirian_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->nomor_sk_pendirian_sekolah}}">
                            </div>
                        </div>


                        <h2 class="card-inside-title">
                            Tanggal SK Pendirian Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_sk_pendirian_sekolah" aria-required="true"aria-invalid="true" value="{{strftime('%d %B %Y', strtotime($sekolah->tgl_sk_pendirian_sekolah))}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Kepemilikan Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="status_kepemilikan" id="status_kepemilikan" onchange="kepemilikan(this);">
                                    <option value="1" @if($sekolah->status_kepemilikan == 1) selected @endif>Pemerintah Pusat</option>
                                    <option value="2" @if($sekolah->status_kepemilikan == 2) selected @endif>Pemerintah Daerah</option>
                                    <option value="3" @if($sekolah->status_kepemilikan == 3) selected @endif>Yayasan</option>
                                    <option value="4" @if($sekolah->status_kepemilikan == 4) selected @endif>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        @if($sekolah->status_kepemilikan == 3)
                            <h2 class="card-inside-title" id="title_nm_yayasan_sekolah">
                                Nama Yayasan Sekolah
                            </h2>
                            <div class="row clearfix" id="nm_yayasan_sekolah">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_yayasan_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->nm_yayasan_sekolah}}">
                                </div>
                            </div>
                        @else
                            <h2 class="card-inside-title" id="title_nm_yayasan_sekolah" style="display: none;">
                                Nama Yayasan Sekolah
                            </h2>
                            <div class="row clearfix" id="nm_yayasan_sekolah" style="display: none;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_yayasan_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->nm_yayasan_sekolah}}">
                                </div>
                            </div>
                        @endif
                        <h2 class="card-inside-title">
                            No. SK Izin Operasional
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_izin_operasional" aria-required="true" aria-invalid="true" value="{{$sekolah->nomor_sk_izin_operasional}}">
                            </div>
                        </div>


                        <h2 class="card-inside-title">
                            Tanggal SK Izin Operasional
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_sk_izin_operasional" aria-required="true"aria-invalid="true" value="{{strftime('%d %B %Y', strtotime($sekolah->tgl_sk_izin_operasional))}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Manajemen Berbasis Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_mbs" id="is_mbs">
                                    <option value="1" @if($sekolah->is_mbs == 1) selected @endif>Ya</option>
                                    <option value="0" @if($sekolah->is_mbs == 0) selected @endif>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Luas Tanah Milik Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="luas_tanah_milik_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->luas_tanah_milik_sekolah}}"> 
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                m<sup>2</sup>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Luas Tanah Bukan Milik Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="luas_tanah_non_milik_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->luas_tanah_non_milik_sekolah}}"> 
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                m<sup>2</sup>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Wajib Pajak Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_wajib_pajak_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->nm_wajib_pajak_sekolah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NPWP Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="npwp_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->npwp_sekolah}}">
                            </div>
                        </div>



                        
                        <div class="demo-color-box bg-success">
                                ALAMAT SEKOLAH
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Jalan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_jalan" aria-required="true" aria-invalid="true" value="{{$sekolah->alamat_jalan}}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                Kelurahan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="alamat_kelurahan" aria-required="true" aria-invalid="true" value="{{$sekolah->alamat_kelurahan}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                Kecamatan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="alamat_kecamatan" aria-required="true" aria-invalid="true" value="{{$sekolah->alamat_kecamatan}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                Provinsi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="alamat_provinsi" id="alamat_provinsi">
                                        @if($sekolah->alamat_provinsi == NULL)
                                            <option value="0">-- Pilih Provinsi --</option>
                                            @foreach($provinsi as $provinsi)
                                                <option value="{{$provinsi->id_provinsi}}">{{$provinsi->nm_provinsi}}</option>
                                            @endforeach
                                        @else
                                            <option value="0">-- Pilih Provinsi --</option>
                                            @foreach($provinsi as $provinsi)
                                                @if($provinsi->id_provinsi == $sekolah->alamat_provinsi)
                                                    <option value="{{$provinsi->id_provinsi}}" selected >{{$provinsi->nm_provinsi}}</option>
                                                @else
                                                    <option value="{{$provinsi->id_provinsi}}">{{$provinsi->nm_provinsi}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                Kota
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="alamat_kota" id="alamat_kota">
                                        @if($sekolah->alamat_kota == NULL)
                                            <option value="0">-- Pilih Kota --</option>
                                            @foreach($kota as $kota)
                                                <option value="{{$kota->id_kota}}">{{$kota->nm_kota}}</option>
                                            @endforeach
                                        @else
                                            <option value="0">-- Pilih Kota --</option>
                                            @foreach($kota as $kota)
                                                @if($kota->id_kota == $sekolah->alamat_kota)
                                                    <option value="{{$kota->id_kota}}" selected >{{$kota->nm_kota}}</option>
                                                @else
                                                    <option value="{{$kota->id_kota}}">{{$kota->nm_kota}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                Dusun
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="alamat_dusun" aria-required="true" aria-invalid="true" value="{{$sekolah->alamat_dusun}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                RT
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="alamat_rt" aria-required="true" aria-invalid="true" value="{{$sekolah->alamat_rt}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                RW
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="alamat_rw" aria-required="true" aria-invalid="true" value="{{$sekolah->alamat_rw}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <h2 class="card-inside-title">
                                Kodepos
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="alamat_kodepos" aria-required="true" aria-invalid="true" value="{{$sekolah->alamat_kodepos}}">
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                                KONTAK SEKOLAH
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Telepon Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_telp_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->nomor_telp_sekolah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Fax Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_fax_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->nomor_fax_sekolah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Email Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="email_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->email_sekolah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Website Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="website_sekolah" aria-required="true" aria-invalid="true" value="{{$sekolah->website_sekolah}}">
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                               FILE SEKOLAH
                        </div>
                        
                        <table class="table table-bordered" id="dynamicAddRemove">  
                            <tr>
                                <th>Nama File</th>
                                <th>Link Gdrive</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($file_sekolah as $file)
                                <tr>  
                                    <td>{{$file->nama_file}}</td>
                                    <td><a href="{{$file->link_gdrive}}">Link Gdrive</a></td>
                                    <input type="hidden" class="delete-id" value="{{$file->id_file_sekolah}}" >
                                    <td><button type="button" class="btn btn-danger delete-file" onclick="destroyFileSekolah('{{$file->id_file_sekolah}}')">Remove</button></td>
                                </tr>  
                            @endforeach
                    <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add More</button></td>  
                        </table> 
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<script>
let i = 0;
 
$("#add-btn").click(function () {
    ++i;

    $("#dynamicAddRemove").append(
        '<tr><td><input type="text" name="fileSekolah[' +
            i +
            '][nama_file]" placeholder="Nama File" class="form-control" /></td><td><input type="text" name="fileSekolah[' +
            i +
            '][link_gdrive]" placeholder="Link File" class="form-control" /> </td>  <input type="hidden" name="fileSekolah[' +
            i +
            '][id_sekolah]" value="{{ $sekolah->id_sekolah }}" /><td> <button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
    );
});
$(document).on("click", ".remove-tr", function () {
    $(this).parents("tr").remove();
});

    $(function(){
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
    function kepemilikan(that) {
        if (that.value == "3") {
            document.getElementById("title_nm_yayasan_sekolah").style.display = "block";
            document.getElementById("nm_yayasan_sekolah").style.display = "block";
        } else {
            document.getElementById("title_nm_yayasan_sekolah").style.display = "none";
            document.getElementById("nm_yayasan_sekolah").style.display = "none";
        }
    }
    function destroyFileSekolah(id) {
        swal(
            { title: "Are you sure?", showCancelButton: true },
            function (isConfirm) {
                if (isConfirm) {
                    const token = $("meta[name='csrf-token']").attr("content");
                    //swall
                    $.ajax({
                        url: `pendidikan/data-sekolah/action-input-file-sekolah/delete/${id}`,
                        type: "post",

                        data: {
                            _token: token,
                        },

                        success: function () {
                            location.reload();
                            swal({
                                title: "Delete Succes",
                                text: "data berhasil dihapus",
                                icon: "success",
                            });
                        },
                    });
                }
                return;
            }
        );
    }
$(document).ready(function () {
    $("select").select();
});

    var modul_url       = 'data-sekolah';
    $('#alamat_provinsi').on('change', function(e){
    console.log(e);
    var alamat_provinsi = e.target.value;
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'data-sekolah/get-kota/' + alamat_provinsi,function(data) {
            console.log(data);
            $('#alamat_kota').empty();


            $('#alamat_kota').append($("<option>")
                .attr("value", 0)
                .text("-- Pilih Kota --")
            );
            $.each(data, function(index, kotaObj){
                $('#alamat_kota').append($("<option>")
                    .attr("value", kotaObj.id_kota)
                    .text(kotaObj.nm_kota)
                );
            })

            $('select').select();
        });
    });

</script>