<style>
    .card .card-inside-title {
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/pembukaan-voucher/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>PEMBUKAAN NOMOR PENDAFTARAN - TAMBAH TARIF</h2>
                    </div>

                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/pembukaan-voucher/'.$penerimaan->id_penerimaan.'/generate-voucher')}}">
                            {{csrf_field()}}
                            <input name="id_penerimaan" type="hidden" value="{{$penerimaan->id_penerimaan}}">
                            <input name="id_semester" type="hidden" value="{{$penerimaan->id_semester}}">

                            <div class="card-inside-title">
                                Tarif Nomor Pendaftaran
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select id="tarif" class="form-control show-tick" name="id_voucher_tarif">
                                        <option value="">-</option>
                                        @foreach($voucher_tarif as $tarif)
                                            <option class="tarif" value="{{$tarif->id_voucher_tarif}}" data-jurusan="{{$tarif->nm_jurusan}}">{{$tarif->tarif}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="card-inside-title">
                                Kode Prefix Nomor Pendaftaran
                                <small class="form-text text-muted">
                                    *prefix awal nomor pendaftaran, misal: ABC001 maka prefix nomor pendaftaran:ABC
                                </small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control simulateplz" id="code_prefix" name="code_prefix"  aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>
                            
                            <div class="card-inside-title">
                                Nomor Seri Nomor Pendaftaran Awal
                                <small class="form-text text-muted">
                                    *nomor seri awal nomor pendaftaran, misal: ABC001 nomor awal: 1
                                </small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" onkeypress='validate(event)' class="form-control simulateplz" id="seri_awal" name="seri_awal"  aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>

                            <div class="card-inside-title">
                                Banyak Nomor Pendaftaran
                                <small class="form-text text-muted">
                                    *berapa banyak voucher yang ingin di generate (minimal 1, maksimal 999)
                                </small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" onkeypress='validate(event)' class="form-control simulateplz" id="n_voucher" name="n_voucher" aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>

                            <div class="card-inside-title">
                                Jumlah Digit Kode Seri
                                <small class="form-text text-muted">
                                    *berapa banyak digit pada nomor seri kode, misal: ABC001, maka 3 digit nomor seri (001)
                                </small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" onkeypress='validate(event)' class="form-control simulateplz" id="n_digit" name="n_digit"  aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>

                            <div class="card-inside-title">
                                Simulasi Kode Nomor Pendaftaran
                                <small class="form-text text-muted">
                                    *hasil simulasi voucher yang akan di generate sistem
                                </small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea id="simulasi" name="simulasi" cols="30" rows="5" class="form-control" aria-required="true" disabled style="background-color:#EEE;"></textarea>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Generate Nomor Pendaftaran</span></button>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <a class="btn bg-blue btn-block waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/pembukaan-voucher/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">cancel</i><span>Cancel</span></a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    $('.simulateplz').keyup(function(e){
        simulate();
    });

    /** 
     * method untuk memberikan simulasi voucher yang akan di generate
     */
    function simulate(){
        var code_prefix = $('#code_prefix').val(); // prefix awal voucher ABC for ABC001
        var seri_awal = $('#seri_awal').val(); // seri awal voucher 1 for ABC001
        var n_voucher = $('#n_voucher').val(); // jumlah banyak voucher yang ingin di generate
        var n_digit = $('#n_digit').val(); // jumlah n digit untuk nomor seri 001 = 3 digit 

        // checkker if null variabel
        if(seri_awal == "") seri_awal = 1;
        if(n_voucher == "") n_voucher = 1;
        if(n_digit == "") n_digit = 1;

        // for looping n voucher
        var i;
        var text = "";
        var zero = new Padder(parseInt(n_digit));
        for (i = 0; i < n_voucher; i++) { 
            var seri = zero.pad((parseInt(seri_awal)+i));
            text += code_prefix + "" + seri + "\n";
        }
        $('#simulasi').val(text);
    }

    /** 
     * method untuk menjadikan 1 -> "001"
     * @param Padder(3)
     * example : 
     *    var zero4 = new Padder(4);
     *    zero4.pad(12); // result "0012"
     */
    function Padder(len, pad) {
        if (len === undefined) {
            len = 1;
        } else if (pad === undefined) {
            pad = '0';
        }
        var pads = '';
        while (pads.length < len) {
            pads += pad;
        }
        this.pad = function (what) {
            var s = what.toString();
            return pads.substring(0, pads.length - s.length) + s;
        };
    }

    /** 
     * method to avoid input only on number format only
     */
    function validate(evt) {
        var theEvent = evt || window.event;

        if (theEvent.type === 'paste') { // Handle paste
            key = event.clipboardData.getData('text/plain');
        } else { // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }

    /**
     * function to convert nominal to readable rupiah
     */
    (function(){
        var tarif = document.querySelectorAll('.tarif');
        Array.prototype.forEach.call(tarif, function(elements, index) {
            // conditional here.. access elements
            
            // custom code for jurusan
            var ket_jurusan = $(elements).attr('data-jurusan'); // semua jurusan atau tidak
            if (ket_jurusan == "") { ket_jurusan = "Semua Jurusan";} 
            else { ket_jurusan = "Jurusan " + ket_jurusan; } 
            
            elements.innerHTML = formatRupiah(elements.innerHTML, "Rp. ") + ",-  " + ket_jurusan;
        });
    })();

    /* Fungsi convert to formatRupiah */
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