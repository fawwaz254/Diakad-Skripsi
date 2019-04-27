<style>
select[id="penerimaan_awal"]:disabled {
  background: #dddddd;
}
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header bg-lime">
                        <h2>Pindah Penerimaan</h2>
                    </div>

                    <div class="body" style="padding-bottom:50px;">

                        <!-- form cari voucher -->
                        <form id="form-validation" method="POST" enctype="multipart/form-data" action="{{url(Request::segment(1).'/'.Request::segment(2).'/pindah-penerimaan')}}">
                            {{csrf_field()}}
                            <div class="card-inside-title">
                                No. Formulir / Kode Voucher
                                <small class="form-text text-muted">
                                    *masukkan kode voucher yang ingin dipindah
                                </small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                    <input type="text" class="form-control simulateplz" id="kode_voucher" name="kode_voucher" aria-required="true" aria-invalid="true" value="{{($voucher==null?'':$voucher->kode_voucher)}}" autofocus>
                                </div>
                            </div>

                            <h2><button class="btn bg-green waves-effect" type="submit"><i class="material-icons">search</i><span>Cari Voucher</span></button></h2>
                        </form>

                        <!-- detail voucher -->
                        @if($voucher != null)
                        <div class="row clearfix" style="margin-top:40px;">
                            <div class="col-lg-7" style="margin-bottom:0px !important;">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Detail Voucher</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Kode Voucher</td>
                                                <td>{{ $voucher->kode_voucher }}</td>
                                            </tr>
                                            <tr>
                                                <td>Pin Voucher</td>
                                                <td>{{ $voucher->pin_password }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tarif Voucher</td>
                                                <td class="tarif">{{ $voucher->tarif }}</td>
                                            </tr>
                                            <tr>
                                                <td>Gelombang Penerimaan</td>
                                                <td>{{ $voucher->nm_penerimaan }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Ambil</td>
                                                <td>{{ $voucher->tgl_ambil or '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Bayar</td>
                                                <td>{{ $voucher->tgl_bayar or '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Nama Calon Siswa</td>
                                                <td>{{ $voucher->nm_c_siswa or '-' }}</td>
                                            </tr>
                                        <tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- form pindah penerimaan voucher -->                        
                        <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/pindah-penerimaan/'.$voucher->kode_voucher.'/pindah')}}">
                            {{csrf_field()}}
                            <input type="hidden" name="kode_voucher" value="{{ $voucher->kode_voucher }}">

                            <div class="card-inside-title">
                                Penerimaan Sekarang
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                    <select class="form-control show-tick" name="penerimaan_awal" id="penerimaan_awal" disabled>
                                        <option value="">-</option>
                                        @foreach($grup_penerimaan_tahun as $tahun => $grup_penerimaan)
                                            @foreach($grup_penerimaan as $semester => $datapergrup)
                                                    @foreach($datapergrup as $data)
                                                    <option value="{{$data->id_penerimaan}}">{{$tahun}} {{$semester}} - {{"Gelombang " . $data->gelombang_penerimaan . " " . $data->nm_penerimaan}}</option>
                                                    @endforeach
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($voucher->id_penerimaan != null)
                            <script>
                                var $option_id_penerimaan = $('#penerimaan_awal option[value={{ $voucher->id_penerimaan }}]'); 
                                $option_id_penerimaan.attr('selected','selected');
                            </script>
                            @endif

                            <div class="card-inside-title">
                                Pindah ke
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                    <select class="form-control show-tick" name="penerimaan_tujuan" id="penerimaan_tujuan">
                                        <option value="">-</option>
                                        @foreach($penerimaan_tujuan as $data)
                                            <option value="{{$data->id_penerimaan}}">{{ $data->tahun_penerimaan }} {{ $data->nm_semester_penerimaan }} - {{ $data->nm_penerimaan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <button class="btn bg-green waves-effect" type="submit" {{ ($voucher->tgl_ambil == null? 'disabled':'')  }}><i class="material-icons">note_add</i><span>Pindah Voucher</span></button>
                        </form>
                        <!-- form pindah penerimaan voucher -->

                        @endif
                        <!-- end of detail voucher -->
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>    
    var primary_table = null;
    $('#form-validation1').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
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
                    }else if(response.status == 204){
                        loadURI(response.path);
                    }else if(response.status == 300){
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>
<script>
    (function(){
        var tarif = document.querySelectorAll('.tarif');
        Array.prototype.forEach.call(tarif, function(elements, index) {
            // conditional here.. access elements
            elements.innerHTML = formatRupiah(elements.innerHTML, "Rp. ");
        });
    })();

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>