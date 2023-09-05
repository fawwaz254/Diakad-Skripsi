<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        ABASENSI KODE QR
                    </h2>
                </div>
                <div class="container">
                    <div class="row">
                        <div id="reader"></div>
                    </div>
                </div>
                <input type="hidden" name="result" id="result">

            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    function onScanSuccess(decodedText, decodedResult) {
        $('#result').val(decodedText);
        let id = decodedText;
        var modul_url = '{{ Request::segment(2) }}';
        var menu_url = '{{ Request::segment(3) }}';
        var result_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/result';
        html5QrcodeScanner.clear().then(_ => {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({

                url: result_url,
                type: 'POST',
                data: {
                    _methode: "POST",
                    _token: CSRF_TOKEN,
                    qr_code: id
                },
                success: function(response) {
                    console.log(response);
                    if (response.status == 200) {
                        alert('berhasil');
                    } else {
                        alert('gagal');
                    }

                }
            });
        }).catch(error => {
            alert('something wrong');
        });
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        // console.warn(`Code scan error = ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        /* verbose= */
        false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
