@push('assets')
<style>
.text-gray-400 {
  color: #718096 !important;
}

.my-5 {
  margin-top: 1.25rem !important;
  margin-bottom: 1.25rem !important;
}

.w-full {
  width: 100% !important;
}

#multi {
  width: 100% !important;
}

.border-dashed {
  border-style: dashed !important;
}

.border {
  border-width: 1px !important;
}

.rounded-lg {
  border-radius: 0.375rem !important;
}

.flex {
  display: flex !important;
}

.justify-center {
  justify-content: center !important;
}

.items-center {
  align-items: center !important;
}

.h-max {
  height: max-content !important;
}

.absolute {
  position: absolute !important;
}

.mx-auto {
  margin-left: auto !important;
  margin-right: auto !important;
}

.flex-col {
  flex-direction: column !important;
}

#belum-ada {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
}

.fill-indigo-400 {
  fill: #667eea !important;
}

.text-xs {
  font-size: 0.75rem !important;
}

.my-2 {
  margin-top: 0.5rem !important;
  margin-bottom: 0.5rem !important;
}

#file-holder {
  display: flex !important;
  flex-direction: column !important;
  gap: 0.5rem !important;
  z-index: 100 !important;
  align-items: center !important;
  justify-content: center !important;
}

.opacity-0 {
  opacity: 0 !important;
}

.w-full {
  width: 100% !important;
}

.min-h-40 {
  min-height: 10rem !important;
}

#file_multi {
  width: 100% !important;
}

.title {
  content: "" !important;
}

.mt-1 {
  margin-top: 0.25rem !important;
}

.text-gray-500 {
  color: #4a5568 !important;
}

.dark .text-gray-300 {
  color: #cbd5e0 !important;
}

#file_input_help {
  margin-top: 0.125rem !important;
  font-size: 0.75rem !important;
  color: #a0aec0 !important;
}


</style>
@endpush



<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/custom-form' ) }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    @if(isset($pesan))
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">
                <div class="body">
                    <h1>{{$pesan}}</h1>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <form id="form-validation" enctype="multipart/form-data" method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' .  Request::segment(4) . '/' .  Request::segment(5) . '/'  ) }}">
                <div class="card">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{$form->id_custom_form}}" name="id_custom_form">
                    <div class="header" style="border-top: 8px solid #555;">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input style="height: 50px; font-size:x-large; outline: none; border: none; width: 100%; " placeholder="Formulir Tanpa Judul" type="text" class="" name="nm_custom_form"  aria-required="true" aria-invalid="true" value="FORM {{$form->nm_custom_form}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="body">

                        <h2 style="font-size:large">
                            Deskripsi
                        </h2>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                            <small class="text-danger">* Menunjukkan Pertanyaan Yang Wajib Diisi</small>
                            </div>
                        </div>




                    </div>
                </div>
                @foreach($form->form_komponen as $index => $komponen)
                <div class="card" style="margin: 15px 0;">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h4>{{$komponen->label_custom_form_komponen}}  @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true')<span class="text-danger"> *</span>@endif</h4>
                                    
                            </div>

                        </div>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="pertanyaan{{$komponen->id_custom_form_komponen}}">
                                @if($komponen->tipe_custom_form_komponen == "text")
                                <input type="text" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]"  aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Jawaban..." @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                @elseif($komponen->tipe_custom_form_komponen == "number")
                                <input type="number" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]"  aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Angka..." @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>

                                @elseif($komponen->tipe_custom_form_komponen == "select")
                                @foreach(json_decode($komponen->option_custom_form_komponen) as $key => $options)
                                <div style="margin-bottom: 20px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="radio" value="{{$options}}" name="respon[{{$komponen->id_custom_form_komponen}}][]" id="rad_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                    <label for="rad_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" style="width: 100%; font-size: 16px;">
                                        <p>{{$options}}</p>
                                    </label>
                                </div>
                                @endforeach

                                @elseif($komponen->tipe_custom_form_komponen == "checkbox")
                                @foreach(json_decode($komponen->option_custom_form_komponen) as $key => $options)
                                <div style="margin-bottom: 20px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="checkbox" value="{{$options}}" name="respon[{{$komponen->id_custom_form_komponen}}][]" id="check_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                    <label for="check_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" style="width: 100%;">
                                        <p>{{$options}}</p>
                                    </label>
                                </div>
                                @endforeach
                                @elseif($komponen->tipe_custom_form_komponen == "custom_kelas" && isset($kelas))
                                <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <select class="form-control show-tick" id="kelas_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}" name="respon[{{$komponen->id_custom_form_komponen}}][]" placeholder="" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                        <option>Pilih Kelas</option>
                                        @foreach($kelas as $k)
                                        <option value="{{$k->nm_kelas}}">{{$k->nm_kelas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @elseif($komponen->tipe_custom_form_komponen == "custom_siswa" && isset($kelas))
                                <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <select class="form-control show-tick" onchange="getSiswa(this)" id="kelas_{{$komponen->id_custom_form_komponen}}_{{$komponen->tipe_custom_form_komponen}}" placeholder="" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                        <option disabled selected>Pilih Kelas</option>
                                        @foreach($kelas as $k)
                                        <option value="{{$k->id_kelas}}" >{{$k->nm_kelas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="margin-bottom: 40px; display:flex; align-items:center; gap: 10px">
                                    <select class="form-control show-tick" id="kelas_{{$komponen->id_custom_form_komponen}}_{{$komponen->tipe_custom_form_komponen}}_siswa" name="respon[{{$komponen->id_custom_form_komponen}}][]" placeholder="" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                        <option disabled selected>Pilih Siswa</option>
                                    </select>
                                </div>
                                @elseif($komponen->tipe_custom_form_komponen == "custom_ttd")

                                <!-- <input type="file" class="hidden form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]" id="ttd{{$komponen->id_custom_form_komponen}}" required aria-required="true" aria-invalid="true" onchange=""> -->
                                <textarea type="text" class="hidden form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]" id="ttd{{$komponen->id_custom_form_komponen}}" required aria-required="true" aria-invalid="true" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif></textarea>
                                <div class="wrapper">
                                    <canvas id="signature-pad{{$komponen->id_custom_form_komponen}}" class="signature-pad" width="400" height="200" style="border:1px solid black;" onmouseleave="getData('{{$komponen->id_custom_form_komponen}}')"></canvas>
                                </div>


                                <button class="btn btn-lg btn-danger" type="button" id="clear" onclick="clearTTD('{{$komponen->id_custom_form_komponen}}')">
                                    <h5>Clear</h5>
                                </button>
                                @elseif($komponen->tipe_custom_form_komponen == "file_single")
                                <small class="text-gray-400">Tarik File Kedalam kotak atau klik kotak</small>
                                <div class="my-5 w-full ">
                                    <div class="border-dashed border rounded-lg flex justify-center items-center">
                                        <div class="absolute mx-auto flex flex-col items-center">
                                            <svg class="fill-indigo-400" xmlns="http://www.w3.org/2000/svg" height="32" width="24" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                                            </svg>
                                            <p class="text-xs my-2 text-gray-400 font-medium">Belum Ada File</p>
                                        </div>
                                        <input class="opacity-0
                                        w-full h-40
                                        " accept="{{implode(',',$komponen->komponen_settings['jenis_file'])}}"  aria-describedby="file_input_help" id="file_{{$komponen->id_custom_form_komponen}}" type="file" name="respon[{{$komponen->id_custom_form_komponen}}][]" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                        <input id="" type="hidden" name="respon[{{$komponen->id_custom_form_komponen}}][]" value="{{$komponen->id_custom_form_komponen}}">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-300" id="file_input_help">{{implode(',',$komponen->komponen_settings['jenis_file'])}}</p>
                                </div>
                                
                                @elseif($komponen->tipe_custom_form_komponen == "file_multiple")
                                <small class="text-gray-400">Tarik File Kedalam kotak atau klik kotak</small>
                                <div class="my-5 w-full " id="multi">
                                    <div class="border-dashed border rounded-lg flex justify-center items-center h-max">
                                        <div class="absolute mx-auto flex flex-col items-center">
                                            <div id="belum-ada" class="flex flex-col items-center justify-center">
                                                <svg class="fill-indigo-400" xmlns="http://www.w3.org/2000/svg" height="32" width="24" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                                                </svg>
                                                <p class="text-xs my-2 text-gray-400 font-medium">Belum Ada File</p>
                                            </div>
                                            <div id="file-holder" class="absolute flex flex-col gap-2 z-[100] items-center justify-center">

                                            </div>
                                        </div>
                                        <input class="opacity-0
                                        w-full min-h-40
                                        " id="file_multi" type="file" title="" accept="{{implode(',',$komponen->komponen_settings['jenis_file'])}}" name="respon[{{$komponen->id_custom_form_komponen}}][]" multiple @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                                        <input id="" type="hidden" name="respon[{{$komponen->id_custom_form_komponen}}][]" value="{{$komponen->id_custom_form_komponen}}">
                                        
                                    </div>

                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-300" id="file_input_help">{{implode(',',$komponen->komponen_settings['jenis_file'])}}</p>
                                </div>
                                @else
                                <input type="text" class="form-control" name="respon[{{$komponen->id_custom_form_komponen}}][]"  aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Jawaban..." @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>

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
                <button id="submit" type="submit" form="form-validation" style="margin-bottom: 50px;" class="btn btn-lg text-lg bg-blue waves-effect target-link">
                    <h5>KIRIM</h5>
                </button>
            </div>


        </div>
    </div>
    @endif
</div>
@include('scriptjs')
@if(!isset($pesan))
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var siswa_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'data/siswa'

    function getSiswa(ths){
        let id_siswa_component = $('#' +$(ths).attr('id')+ '_siswa');
            $.ajax({
                url: siswa_url,
                type: "POST",
                dataType: "json",
                data:{
                    id_kelas: $(ths).val()
                },
                success: function (data) {
                    id_siswa_component.prop('disabled',false)
                    id_siswa_component.children().remove();
                    $.each(data.data,function(index,value){
                        $(id_siswa_component).append(`
                            <option value="${value.nm_pengguna}">${value.nm_pengguna}</option> 
                        `)

                    });
                },
                error: function () {
                    id_siswa_component.prop('disabled',true)
                },
            });
    }

    $(function(){
        $('select').select2()
    })
</script>
<script>
    var signaturePad = [];
    var canvas = [];
    var ttd = {!! json_encode($form->form_komponen->where('tipe_custom_form_komponen', 'custom_ttd')) !!};

    $(document).ready(function(){

        for (const [key, value] of Object.entries(ttd)) {
            canvas.push($('#signature-pad' + value['id_custom_form_komponen'])[0]);
            
            signaturePad[value['id_custom_form_komponen']] = new SignaturePad(canvas[canvas.length - 1], {
                backgroundColor: 'rgb(255, 255, 255)',
            });
            
            
        }
    })

    

    $(window).on('resize', resizeCanvas);
    resizeCanvas(canvas);

    function resizeCanvas(canvas) {
        Array.prototype.forEach.call(canvas, val => {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            val.width = val.offsetWidth * ratio;
            val.height = val.offsetHeight * ratio;
            val.getContext('2d').scale(ratio, ratio);
        });
        
    }

    function clearTTD(id) {
        signaturePad[id].clear();
    }



    function getData(id)
    {   
        console.log(signaturePad[id].toDataURL('image/png'))
        $('#ttd'+id).val(signaturePad[id].toDataURL('image/png'))
    }

</script>
@endif