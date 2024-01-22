@push('assets')
<style>
    .card-div {
        transition: transform 300 ease-in;
    }

    .opacity-0 {
        opacity: 0;
    }
</style>
@endpush



<div class="container-fluid">
    <div class="block-header">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <div class="d-flex justify-content-between">
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">

                        <a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i class="material-icons">backspace</i><span>Kembali</span></a>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="display: flex; justify-content:end;">

                        <button id="submit" type="submit" form="form-validation" class="btn btn-lg bg-blue waves-effect target-link">SIMPAN</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <form id="form-validation" method="post" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/'. Request::segment(4) ) }}">
                @method('PUT')
                <div class="card">
                    {{ csrf_field() }}

                    <div class="header" style="border-top: 8px solid #555;">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input style="height: 50px; font-size:x-large; outline: none; border: none; width: 100%; " placeholder="Formulir Tanpa Judul" type="text" class="" name="nm_custom_form" required="" aria-required="true" aria-invalid="true" value="{{$form->nm_custom_form}}">
                            </div>
                        </div>
                    </div>
                    <div class="body">

                        <h2 style="font-size:large">
                            Pengaturan
                        </h2>


                        <h2 class="card-inside-title">
                            Ditujukkan Kepada
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role" required="">
                                    <option selected disabled>Pilih Role</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id_role }}" {{$form->id_role == $role->id_role ? 'selected' : '' }} >{{ $role->nm_role }}</option>
                                    @endforeach
                                    <option value="99">Public</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif" required="">
                                    <option value="1" {{ $form->is_aktif == 1? 'selected': ''}}>Aktif</option>
                                    <option value="0" {{ $form->is_aktif == 0? 'selected': ''}}>Non-aktif</option>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <h2 class="card-inside-title">
                                    Jam Mulai Pengisian
                                </h2>
                                <div>
                                    <input type="input" class="timepicker form-control" name="start_time" required="" aria-required="true" aria-invalid="true" value="{{$form->start_time}}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <h2 class="card-inside-title">
                                    Jam Akhir Pengisian
                                </h2>
                                <div>
                                    <input type="input" class="timepicker form-control" name="end_time" required="" aria-required="true" aria-invalid="true" value="{{$form->end_time}}">
                                </div>
                            </div>


                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h2 class="card-inside-title">
                                    Lainnya
                                </h2>
                                <div class="switch" style="margin-bottom: 8px; ">
                                    <label for="multiple">
                                        <input type="checkbox" id="multiple" name="multiple">
                                        <span class="lever"></span>
                                        Hanya dapat mengirimkan 1 (satu) Respon.
                                    </label>
                                </div>
                                <div class="switch">
                                    <label for="random" style="margin-bottom: 8px; ">
                                        <input type="checkbox" id="random" name="random">
                                        <span class="lever"></span>
                                        Urutan acak.
                                    </label>
                                </div>
                                <div class="switch">
                                    <label for="editable" style="margin-bottom: 8px; ">
                                        <input type="checkbox" id="editable" name="editable" checked>
                                        <span class="lever"></span>
                                        Responden dapat mengubah Respon yang sudah diserahkan.
                                    </label>
                                </div>

                            </div>
                        </div>





                    </div>
                </div>
                <div class="card-div" style="position: relative;">
                    @foreach($form->form_komponen as $key => $komponen)
                    <div class="card" id="kartu" style="margin: 15px 0;" draggable="true">
                        <div style="cursor: move; display:flex; justify-content:center; align-items:center;">
                            <i class="material-icons">drag_handle</i>
                        </div>
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <input style="padding: 5px;border-radius: 5px; height: max-content; background-color: rgba(204, 204, 204, 0.2); font-size:larger; outline: none; border: none; width: 100%; border-bottom: 2px solid rgba(204, 204, 204, 0.35);" placeholder="Pertanyaan Tanpa Judul" type="text" class="" name="komponen[{{$key}}][label_custom_form_komponen]" required="" aria-required="true" aria-invalid="true" value="{{$komponen->label_custom_form_komponen}}">
                                    <input name="komponen[{{$key}}][id_custom_form_komponen]" value="{{$komponen->id_custom_form_komponen}}" type="hidden"></input>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <select class="form-control show-tick" name="komponen[{{$key}}][tipe_custom_form_komponen]" id="{{$key}}" required="">
                                        <option value="null" {{ $komponen->tipe_custom_form_komponen == 'null' ? 'selected' : '' }}>PILIH TIPE</option>
                                        <option value="text" {{ $komponen->tipe_custom_form_komponen == 'text' ? 'selected' : '' }}>Text (BASIC)</option>
                                        <option value="number" {{ $komponen->tipe_custom_form_komponen == 'number' ? 'selected' : '' }}>Number (BASIC)</option>
                                        <option value="select" {{ $komponen->tipe_custom_form_komponen == 'select' ? 'selected' : '' }}>Select (BASIC)</option>
                                        <option value="checkbox" {{ $komponen->tipe_custom_form_komponen == 'checkbox' ? 'selected' : '' }}>Checkbox (BASIC)</option>
                                        <option value="custom_kelas" {{ $komponen->tipe_custom_form_komponen == 'custom_kelas' ? 'selected' : '' }}>Kelas (CUSTOM)</option>
                                        <option value="custom_siswa" {{ $komponen->tipe_custom_form_komponen == 'custom_siswa' ? 'selected' : '' }}>Siswa (CUSTOM)</option>
                                        <option value="custom_ttd" {{ $komponen->tipe_custom_form_komponen == 'custom_ttd' ? 'selected' : '' }}>Tanda Tangan (CUSTOM)</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="pertanyaan{{$key}}">
                                    <input type="text" class="form-control" name="komponen[{{$key}}][option_custom_form_komponen][]" required="" aria-required="true" aria-invalid="true" value="Teks Singkat" placeholder="Teks Singkat" disabled>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div style="margin-left: 20px; margin-bottom: 20px;">
                                        <div class="switch">
                                            <button class="btn btn-danger" onclick="deletePertanyaan('{{$key}}')">Hapus Pertanyaan Ini</button>
                                            <label for="mandatory{{$key}}" style="margin-bottom: 8px; ">
                                                <input type="checkbox" id="mandatory{{$key}}" name="komponen[{{$key}}][mandatory]" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') checked @endif>
                                                <span class="lever"></span>
                                                Wajib Diisi.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div>


            </form>
            <div>
                <button id="addPertanyaan" style="margin-bottom: 50px; margin-top: 20px;" class="btn btn-lg text-lg bg-blue waves-effect target-link">Tambah Pertanyaan</button>
            </div>

        </div>
    </div>
</div>
@include('scriptjs')
<script>
    /**@readonly docs code gimang
     * 
     * Hello Kembali lagi dengan saya Reza
     * 
     * Kode dibawah digunakan untuk mengurutkan Pertanyaan
     * 
     * (DELETED) =======
     * Logikanya sederhana (dihapus)
     * 1. Multiple Input
     * 2. Unique
     * Jika terdapat select yang sudah di klik maka akan disabled dan sebaliknya!
     * 
     * sekian ~
     * =================
     * 
     * 17/01/2024
     * 
     * new saya pakai draggable component saja seperti google forms
     * logicnya simple
     * 
     * bermain Linked List dan temp variabel
     *  jika element sekarang di drag keatas maka before akan ditaruh di temp var
     * lalu sekarang masuk ke atas dan kembali menjadi after element sekarang
     * 
     * rosources/app.js
     */
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
                                    <div class="btn btn-danger delete" id="delete${id}" onclick="hapusOpsi(${id})" style="margin: 2px 10px 0px 10px; width: 100px;">
                                         Hapus 
                                    </div>
                                </div>
        `)
    }
</script>
<script>
    let draggedElem;
    const parent = document.querySelector(".card-div");

    parent.addEventListener("drag", (dragEvent) => {
        draggedElem = dragEvent.target.closest(".card-div > [draggable]");

    });

    parent.addEventListener("dragover", (event) => {
        event.preventDefault();
        const target = event.target.closest(".card-div > [draggable]");
        draggedElem.style.opacity = '0'

        if (target) {
            const temp = new Text("");
            target.after(temp);

            draggedElem.replaceWith(target);
            temp.replaceWith(draggedElem);
        }
    });

    parent.addEventListener("drop", (dropEvent) => {
        dropEvent.preventDefault();
        const target = dropEvent.target.closest(".card-div > [draggable]");
        if (target) {
            const temp = new Text("");
            target.before(temp);
            draggedElem.style.opacity = '100'
            draggedElem.replaceWith(target);
            temp.replaceWith(draggedElem);
        }
    });

    parent.addEventListener("dragend", (event) => {
        event.preventDefault();
        draggedElem.style.opacity = '100'

    })
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
        $('select').selectpicker();

    });

    $('#addPertanyaan').on('click', function() {
        var current = $('select[name*="tipe_custom_form_komponen"]').length
        var form = $('.card-div');
        var content = `<div class="card" id="kartu" style="margin: 15px 0;" draggable="true">
        <div style="cursor: move; display:flex; justify-content:center; align-items:center;">
                            <i class="material-icons">drag_handle</i>
                        </div>
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
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="margin-left: 20px; margin-bottom: 20px;">
                            <div class="switch">
                                <button class="btn btn-danger" onclick="deletePertanyaan(${current})">Hapus Pertanyaan Ini</button>
                                        <label for="mandatory${current}" style="margin-bottom: 8px; " >
                                            <input type="checkbox" id="mandatory${current}" name="komponen[${current}][mandatory]" checked>
                                            <span class="lever"></span>
                                            Wajib Diisi.
                                        </label>
                                    </div>   
                            </div>
                            </div>
                        </div>
                    </div>
                </div>`

        form.append(content);

        $('select').selectpicker();

        menuTipe()

    })


    $('.card-div').on('change', 'select[name*=tipe_custom_form_komponen]', function() {

        menuTipe()
    })
</script>