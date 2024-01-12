@push('assets')
<style>

</style>
@endpush



<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <form id="form-validation" method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/'. Request::segment(4) . '/action/add') }}">
                <div class="card">
                    {{ csrf_field() }}

                    <div class="header" style="border-top: 8px solid #555;">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input style="height: 50px; font-size:x-large; outline: none; border: none; width: 100%; " placeholder="Formulir Tanpa Judul" type="text" class="" name="nm_custom_form" required="" aria-required="true" aria-invalid="true" value="FORM {{$form->nm_custom_form}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="body">

                        <h2 style="font-size:large">
                            Deskripsi
                        </h2>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <h2 class="card-inside-title">
                                    Jam Mulai Pengisian
                                </h2>
                                <div>
                                    <input type="text" class=" form-control" name="start_time" required="" aria-required="true" aria-invalid="true" value="{{$form->start_time}}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <h2 class="card-inside-title">
                                    Jam Akhir Pengisian
                                </h2>
                                <div>
                                    <input type="text" class=" form-control" name="end_time" required="" aria-required="true" aria-invalid="true" value="{{$form->end_time}}" disabled>
                                </div>
                            </div>

                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <button id="submit" type="submit" class="btn btn-lg bg-blue waves-effect target-link">SIMPAN</button>
                            </div>
                        </div> -->




                    </div>
                </div>
                @foreach($form->form_komponen as $index => $komponen)
                <div class="card" style="margin: 15px 0;">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="">
                                    <h4>{{$komponen->label_custom_form_komponen}}</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="pertanyaan{{$komponen->id_custom_form_komponen}}">
                                @if($komponen->tipe_custom_form_komponen == "text")
                                <input type="text" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Jawaban...">
                                @elseif($komponen->tipe_custom_form_komponen == "number")
                                <input type="number" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Angka...">

                                @elseif($komponen->tipe_custom_form_komponen == "select")
                                <!-- <input type="text" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Jawaban..."> -->
                                @foreach(json_decode($komponen->option_custom_form_komponen) as $key => $options)
                                <div style="margin-bottom: 20px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="radio" name="respon[{{$komponen->id_custom_form_komponen}}]" id="rad_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}">
                                    <label for="rad_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" style="width: 100%; font-size: 16px;">
                                        <p>{{$options}}</p>
                                    </label>
                                </div>
                                @endforeach

                                @elseif($komponen->tipe_custom_form_komponen == "checkbox")
                                @foreach(json_decode($komponen->option_custom_form_komponen) as $key => $options)
                                <div style="margin-bottom: 20px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="checkbox" name="respon[{{$komponen->id_custom_form_komponen}}]" id="check_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}">
                                    <label for="check_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" style="width: 100%;">
                                        <p>{{$options}}</p>
                                    </label>
                                </div>
                                @endforeach
                                @elseif($komponen->tipe_custom_form_komponen == "custom_ttd")

                                <input type="hidden" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="">
                                <div class="wrapper">
                                    <canvas id="signature-pad" class="signature-pad" width="400" height="200" style="border:1px solid black;"></canvas>
                                </div>

                                <!-- <button id="save-png">Save as PNG</button>
                                <button id="save-jpeg">Save as JPEG</button>
                                <button id="save-svg">Save as SVG</button>
                                <button id="draw">Draw</button>
                                <button id="erase">Erase</button>
                                <button id="undo">Undo</button> -->
                                <button class="btn btn-lg btn-danger" type="button" id="clear">
                                    <h5>Clear</h5>
                                </button>
                                @else
                                <input type="text" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Jawaban...">

                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="footer">
                        <div class="row clearfix">
                            <div class="p-5">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </form>
            <div>
                <button id="submit" type="submit" style="margin-bottom: 50px;" class="btn btn-lg text-lg bg-blue waves-effect target-link">
                    <h5>KIRIM</h5>
                </button>
            </div>


        </div>
    </div>
</div>
@include('scriptjs')
<script>
    function menuTipe() {
        $('select[name*=tipe_custom_form_komponen]').on('change', function() {
            var id = $(this).attr('id')

            console.log($(this).val())
            $('#pertanyaan' + id).children().remove()
            var pilihan = $(this).val()

            switch (pilihan) {
                case 'text':
                    $('#pertanyaan' + id).append(`
            <input type="text" class="form-control" style="" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="Teks Singkat" placeholder="Teks Singkat" disabled>`)
                    break
                case 'number':
                    $('#pertanyaan' + id).append(`
            <input type="text" class="form-control" style="" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="Jawaban Angka" placeholder="Jawaban Angka" disabled>`)
                    break
                case 'custom_ttd':
                    $('#pertanyaan' + id).append(`
                    <div>Responden Dapat Mengirim TandaTangan</div>
            <input type="text" class="form-control" style="" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="Jawaban TTD" placeholder="Jawaban TTD" disabled>`)

                    break
                case 'select':
                    $('#pertanyaan' + id).append(`
                    <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="radio" id="rad" disabled>
                                    <label for="rad" style="width: 100%;">
                                        <input type="text" class="form-control form-check-label" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Jawaban 1">
                                    </label>
                                    <div class="btn btn-info" id="tambah${id}" onclick="tambahOpsi(${id})" style="margin: 2px 10px 0px 10px; width: 100px;">
                                         Tambah
                                    </div>
                                </div>`)
                    break
                case 'custom_kelas':
                    $('#pertanyaan' + id).append(`
                                <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                <select id="multiKelas" class="form-control show-tick" id="select${id}" name="komponen[${id}][option_custom_form_komponen][]" multiple="multiple">
                                        <option value="null" disabled>Pilih Kelas (Klik Untuk Menambahkan Silang Untuk Menghapus )</option>
                                    </select>
                                </div>`)

                    $('#multiKelas').select2()
                    break
                case 'custom_siswa':
                    $('#pertanyaan' + id).append(`
                        <div class="d-flex">
                                <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <select id="multiKelas" class="form-control show-tick" id="select${id}" name="komponen[${id}][option_custom_form_komponen][]" multiple="multiple">
                                        <option value="null" disabled>Pilih Kelas (Klik Untuk Menambahkan Silang Untuk Menghapus )</option>
                                    </select>

                                    <select id="multiSiswa" class="form-control show-tick" id="select${id}" name="siswa[]" disabled>
                                        <option value="null" disabled>Pilih Kelas (Klik Untuk Menambahkan Silang Untuk Menghapus )</option>
                                    </select>
                                </div>
                        </div>`)

                    $('#multiKelas').select2()
                    $('#multiSiswa').select2()
                    break
                case 'checkbox':
                    $('#pertanyaan' + id).append(`
                    <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="checkbox" id="rad" disabled>
                                    <label for="rad" style="width: 100%;">
                                        <input type="text" class="form-control form-check-label" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Jawaban 1">
                                    </label>
                                    <div class="btn btn-info" id="tambah${id}" onclick="tambahMultipleChoice(${id})" style="margin: 2px 10px 0px 10px; width: 100px;">
                                         Tambah
                                    </div>
                                </div>`)

                    break
                default:
                    // $(this).val('text')
                    $('#pertanyaan' + id).append(`
            <input type="text" class="form-control" style="" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="Teks Singkat" placeholder="Jawaban" disabled>
            `)
            }



        })
    }

    menuTipe()

    function deletePertanyaan(id) {
        $('#pertanyaan' + id).parent().parent().parent().remove()
    }

    function tambahOpsi(id) {
        // $('#tambah'+id).on('click', function() {
        // $('#tambah')
        $('#pertanyaan' + id).append(`
                            <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="radio" id="rad" disabled>
                                    <label for="rad" style="width: 100%;">
                                        <input type="text" class="form-control form-check-label" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Jawaban 1">
                                    </label>
                                    <div class="btn btn-danger delete" id="delete${id}" onclick="hapusOpsi(${id})" style="margin: 2px 10px 0px 10px; width: 100px;">
                                         Hapus 
                                    </div>
                                </div>
        `)
        // })
    }

    function hapusOpsi(id) {
        $("#pertanyaan" + id).on("click", ".delete", function() {
            $(this).parent().remove();
        })
    }

    function tambahMultipleChoice(id) {
        $('#pertanyaan' + id).append(`
                            <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="checkbox" id="rad" disabled>
                                    <label for="rad" style="width: 100%;">
                                        <input type="text" class="form-control form-check-label" name="komponen[${id}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Jawaban 1">
                                    </label>
                                    <div class="btn btn-danger delete" id="delete${id}" onclick="hapusMultipleChoice(${id})" style="margin: 2px 10px 0px 10px; width: 100px;">
                                         Hapus 
                                    </div>
                                </div>
        `)
    }

    function hapusMultipleChoice(id) {
        $("pertanyaan" + id).on("click", ".delete", function() {
            $(this).parent().remove();
        })
    }

    // $('#tambah').on('click', function() {
    //     $('#tambah')
    //     $('#pertanyaan1').append(`
    //                         <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
    //                                 <input class="" type="radio" id="rad" disabled>
    //                                 <label for="rad" style="width: 100%;">
    //                                     <input type="text" class="form-control form-check-label" name="pertanyaan" required="" aria-required="true" aria-invalid="true" value="" placeholder="Jawaban 1">
    //                                 </label>
    //                                 <div class="btn btn-danger delete" id="delete" style="margin: 2px 10px 0px 10px; width: 100px;">
    //                                      Hapus 
    //                                 </div>
    //                             </div>
    //     `)
    // })
</script>

<script>
    $(document).ready(function() {
        var canvas = $('#signature-pad')[0];

        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
        }

        $(window).on('resize', resizeCanvas);
        resizeCanvas();

        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
        });

        $('#save-png').on('click', function() {
            if (signaturePad.isEmpty()) {
                return alert('Please provide a signature first.');
            }

            var data = signaturePad.toDataURL('image/png');
            console.log(data);
            window.open(data);
        });

        $('#save-jpeg').on('click', function() {
            if (signaturePad.isEmpty()) {
                return alert('Please provide a signature first.');
            }

            var data = signaturePad.toDataURL('image/jpeg');
            console.log(data);
            window.open(data);
        });

        $('#save-svg').on('click', function() {
            if (signaturePad.isEmpty()) {
                return alert('Please provide a signature first.');
            }

            var data = signaturePad.toDataURL('image/svg+xml');
            console.log(data);
            console.log(atob(data.split(',')[1]));
            window.open(data);
        });

        $('#clear').on('click', function() {
            signaturePad.clear();
        });

        $('#draw').on('click', function() {
            signaturePad.compositeOperation = 'source-over';
        });

        $('#erase').on('click', function() {
            signaturePad.compositeOperation = 'destination-out';
        });

        $('#undo').on('click', function() {
            var data = signaturePad.toData();
            if (data) {
                data.pop();
                signaturePad.fromData(data);
            }
        });
    });
</script>
<script>
    $(function() {
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            lang: 'id',
            time: true,
            date: false,
            shortTime: false
        });
    });

    // var values = [];

    // // $(document).ready(function() {
    // var form = $('#form-validation');
    // var selects = $('select[name*="order"]');
    // // var notify = $('#notification');

    // function disableOther(currentIndex) {
    //     selects.each(function(i) {
    //         console.log('iterasi' + i + '? ' + `${currentIndex}`)
    //         if (i !== currentIndex) {
    //             $(this)
    //                 .find('option')
    //                 .each(function(j) {
    //                     if (values.includes($(this).val())) {
    //                         $('this').css('background-color', '#0000');
    //                         console.log($(this).val())
    //                         $(this).attr('disabled', 'disabled');
    //                     } else {
    //                         $(this).attr('disabled', false);
    //                     }
    //                 });
    //         } else {
    //             $(this)
    //                 .find('option')
    //                 .each(function(j) {
    //                     if ($(this).val() === selects.eq(currentIndex).val()) {
    //                         $('this').css('background-color', '#0000');

    //                         $(this).attr('disabled', 'disabled');
    //                     }
    //                     if (!values.includes($(this).val())) {
    //                         $(this).attr('disabled', false);
    //                     }
    //                 });
    //         }
    //     });
    // }

    // function getOthers(current) {
    //     values = [];
    //     for (var i = 0; i < selects.length; i++) {
    //         if (selects[i].value !== 'null' && selects[i] !== current) {
    //             values.push(selects[i].value);
    //         }
    //     }
    //     return values;
    // }

    // function checkUnique() {
    //     if (this.value && getOthers(this).indexOf(this.value) > -1) {
    //         vex.dialog.alert('You already selected that');
    //         this.value = null;
    //     } else {
    //         if (this.value !== 'null') values.push(this.value);
    //     }
    //     console.log(selects.toArray().indexOf(this))
    //     disableOther(selects.toArray().indexOf(this));
    // }

    // $('#submit').onclick = function() {
    //     var selectedValues = getOthers();
    //     console.log(selectedValues);
    //     if (selectedValues.length < 6) {
    //         vex.dialog.alert('Select all six');
    //         return false;
    //     }
    //     return true;
    // };

    // for (var i = 0; i < selects.length; i++) {
    //     selects[i].onchange = checkUnique;
    // }


    // $('#submit').on('click', function() {
    //     var selectedValues = getOthers();
    //     console.log(selectedValues);
    //     if (selectedValues.length < selects.length) {
    //         vex.dialog.alert('Wajib Mengisi Urutan');
    //         return false;
    //     }
    //     return true;
    // });
    // var i = 1

    // function addUrutan() {
    //     i++;
    //     $('select[name*="order"]').children().remove().end()
    //     for (var x = 0; x <= i; x++) {
    //         if (x == 0) {
    //             $('select[name*="order"]').append($('<option>', {
    //                 value: 'null',
    //                 text: `Pilih Urutan`
    //             }));
    //         } else {
    //             $('select[name*="order"]').append($('<option>', {
    //                 value: x,
    //                 text: `${x}`
    //             }));
    //         }
    //     }
    // }


    $('#addPertanyaan').on('click', function() {
        var current = $('select[name*="tipe_custom_form_komponen"]').length
        var form = $('#form-validation');
        var content = `<div class="card" style="margin: 15px 0;">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <input style="padding: 5px;border-radius: 5px; height: max-content; background-color: rgba(204, 204, 204, 0.2); font-size:larger; outline: none; border: none; width: 100%; border-bottom: 2px solid rgba(204, 204, 204, 0.35);" placeholder="Pertanyaan Tanpa Judul" type="text" class="" name="komponen[${current}][label_custom_form_komponen]" required="" aria-required="true" aria-invalid="true" value="">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <select class="form-control show-tick" name="komponen[${current}][tipe_custom_form_komponen]" id="${current}" required="">
                                    <option value="null">PILIH TIPE</option>
                                    <option value="text">Text (BASIC)</option>
                                    <option value="number">Number (BASIC)</option>
                                    <option value="select">Select (BASIC)</option>
                                    <option value="checkbox">Checkbox (BASIC)</option>
                                    <option value="custom_kelas">Kelas (CUSTOM)</option>
                                    <option value="custom_siswa">Siswa (CUSTOM)</option>
                                    <option value="custom_ttd">Tanda Tangan (CUSTOM)</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="pertanyaan${current}">
                                <input type="text" class="form-control" name="komponen[${current}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="Teks Singkat" placeholder="Teks Singkat" disabled>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <select class="form-control show-tick" name="komponen[${current}][order]"  required="">
                                    <option value="null">Pilih Urutan</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="margin-left: 20px; margin-bottom: 20px;">
                            <button class="btn btn-danger" onclick="deletePertanyaan(${current})">Hapus Pertanyaan Ini</button>
                            </div>

                            </div>
                        </div>
                    </div>
                </div>`

        form.append(content);
        // addUrutan()
        // addUrutan2(form.find('select#urutan'))
        // $('#'+id).change()
        menuTipe()

    })
    // });
    // var form = $('#form-validation');
    // var selects =$('select[name*="order"]');


    // form.on('change', 'select[name*="order"]', function() {
    //     var currentIndex = form.find('select[name*="order"]').index(this);
    //     selects = $('select[name*="order"]');
    //     console.log("form")
    //     console.log(selects.length)
    //     console.log(values)
    //     // checkUnique();
    //     for (var i = 0; i < selects.length; i++) {
    //         selects[i].onchange = checkUnique;
    //     }
    // });

    $('#form-validation').on('change', 'select[name*=tipe_custom_form_komponen]', function() {
        // console.log('called')

        menuTipe()
    })



    /**@readonly
     * 
     * Hello Kembali lagi dengan saya Reza
     * 
     * Kode dibawah digunakan untuk mengurutkan Pertanyaan
     * 
     * Logikanya sederhana
     * 1. Multiple Input
     * 2. Unique
     * Jika terdapat select yang sudah di klik maka akan disabled dan sebaliknya!
     * 
     * sekian ~
     * 
     * rosources/app.js
     */
</script>