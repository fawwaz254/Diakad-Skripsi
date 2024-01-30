<!DOCTYPE html>
<html lang="en">

<head>
    <base href="{{ url('/') }}">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ request()->segment(1) }}" name="role">
    <meta name="token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @yield('meta')
    @php
        $theme_name = Request::segment(1);
    @endphp

    @if ($theme_name != '')
        <title><?= str_replace('-', ' ', strtoupper($theme_name)) ?> - Sekolah Berbasis Teknologi by EDUMATE</title>
    @else
        <title>{{ strtoupper(env('APP_NAME', 'diakad')) }} - Sekolah Berbasis Teknologi by EDUMATE</title>
    @endif
    <link rel="icon" href="{{ asset('favicon_io/favicon-circle.png') }}" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
    
</head>

<body class="bg-[#f0ebf8] px-4">
    
    <form class="flex flex-col justify-center items-center" id="form" method="post" action="" enctype="multipart/form-data">
        @method('POST')
        @CSRF
        @if(!isset($pesan))<input type="hidden" value="{{$form->id_custom_form}}" name="id_custom_form">@endif

        <div class="bg-white rounded-lg max-w-[640px] w-full h-92 mt-6 relative">
            <div class="bg-indigo-500 w-full h-3 rounded-t-lg">
            </div>
            <div class="m-6">
                <h1 class="font-extrabold text-4xl">@if(isset($form)){{ $form->nm_custom_form }} @else TIDAK DITEMUKAN @endif</h1>
                @if(isset($pesan))
                <div class="my-2">

                    <div class="">
                        <p class="text-sm font-medium">{{$pesan}}</p>
                    </div>
                </div>
                @else
                
                <div class="my-2">
                    <h5 class="text-medium font-semibold">Deskripsi</h5>

                    <div class="bg-gray-200">
                        <!-- <p>Jam Mulai</p> -->
                    </div>
                </div>


                @endif
            </div>
            @if(!isset($pesan))
            <div class="border-t px-6 py-4">
                <small class="text-red-600">* Menunjukkan pertanyaan yang wajib diisi</small>
            </div>
            @endif
        </div>
        @if(!isset($pesan))
        
        @foreach($form->form_komponen as $index => $komponen)


        <div class="bg-white rounded-lg max-w-[640px] w-full h-max mt-4 relative ">
            <div class="m-6" id="pertanyaan{{$komponen->id_custom_form_komponen}}">
                <h1 class="font-semibold text-md">{{$komponen->label_custom_form_komponen}} @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true')<span class="text-red-600"> *</span>@endif</h1>
                @if($komponen->tipe_custom_form_komponen == "text")

                <div class="my-5 w-full">
                    <input type="text" name="respon[{{$komponen->id_custom_form_komponen}}][]" placeholder="Ketuk untuk mengisi Jawaban Teks Singkat" class="focus:bg-gray-100/50 focus:outline-none focus:ring-[0.125px] focus:ring-gray-800 focus:p-2 w-full py-2 rounded-md" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                </div>
                @elseif($komponen->tipe_custom_form_komponen == "number")
                <div class="my-5 w-full">
                    <input type="number" name="respon[{{$komponen->id_custom_form_komponen}}][]" placeholder="Ketuk untuk mengisi Jawaban Angka" class="focus:bg-gray-100/50 focus:outline-none focus:ring-[0.125px] focus:ring-gray-800 focus:p-2 w-full py-2 rounded-md" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                </div>
                @elseif($komponen->tipe_custom_form_komponen == "select")
                <small class="text-gray-400">Pilih Satu Jawaban.</small>

                <div class="my-5 w-full">
                    @foreach(json_decode($komponen->option_custom_form_komponen) as $key => $options)
                    <div class="flex items-center mb-4">
                        <input type="radio" value="{{$options}}" name="respon[{{$komponen->id_custom_form_komponen}}][]" id="rad_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800  dark:bg-gray-700 dark:border-gray-600 focus:rounded-full" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                        <label for="rad_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 w-full">{{$options}}</label>
                    </div>
                    @endforeach
                </div>
                @elseif($komponen->tipe_custom_form_komponen == "checkbox")
                <small class="text-gray-400">Pilih Lebih Dari Satu Jawaban.</small>
                <div class="my-5 w-full">

                    @foreach(json_decode($komponen->option_custom_form_komponen) as $key => $options)
                    <div class="flex items-center mb-4">
                        <input type="checkbox" value="{{$options}}" name="respon[{{$komponen->id_custom_form_komponen}}][]" id="check_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800  dark:bg-gray-700 dark:border-gray-600" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                        <label for="check_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}_{{$key}}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 w-full">{{$options}}</label>
                    </div>
                    @endforeach
                </div>
                @elseif($komponen->tipe_custom_form_komponen == "custom_kelas" && isset($kelas))
                <div class="flex m-4" >
                    <select class="w-full form-control show-tick" id="kelas_{{$komponen->id_custom_form_komponen}}_{{$komponen->label_custom_form_komponen}}" name="respon[{{$komponen->id_custom_form_komponen}}][]" placeholder="" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                        <option>Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{$k->nm_kelas}}">{{$k->nm_kelas}}</option>
                            @endforeach
                    </select>
                </div>
                @elseif($komponen->tipe_custom_form_komponen == "custom_siswa" && isset($kelas))
                <div class="flex m-4" >
                    <select class="w-full form-control show-tick" onchange="getSiswa(this)" id="kelas_{{$komponen->id_custom_form_komponen}}_{{$komponen->tipe_custom_form_komponen}}" placeholder="" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                        <option disabled selected>Pilih Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{$k->id_kelas}}" >{{$k->nm_kelas}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex m-4" >
                    <select class="w-full form-control show-tick" id="kelas_{{$komponen->id_custom_form_komponen}}_{{$komponen->tipe_custom_form_komponen}}_siswa" name="respon[{{$komponen->id_custom_form_komponen}}][]" placeholder="" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif>
                        <option disabled selected>Pilih Siswa</option>
                    </select>
                </div>                
                @elseif($komponen->tipe_custom_form_komponen == "custom_ttd")

                <small class="text-gray-400">Tanda Tangan Pada Kotak Di Bawah, Opsi Hapus untuk menghapus TTD</small>
                <div class="my-5 w-full flex flex-col items-center gap-2 justify-center">
                    <textarea type="text" class="hidden" name="respon[{{$komponen->id_custom_form_komponen}}][]" id="ttd{{$komponen->id_custom_form_komponen}}" aria-required="true" aria-invalid="true" @if(isset($komponen->komponen_settings['mandatory']) && $komponen->komponen_settings['mandatory'] == 'true') required @endif></textarea>
                    <div class="flex justify-center">
                        <canvas id="signature-pad{{$komponen->id_custom_form_komponen}}" class="signature-pad rounded-lg border" width="400" height="200" onmouseleave="getData('{{$komponen->id_custom_form_komponen}}')"></canvas>
                    </div>


                    <button class="bg-red-400 rounded-md py-2 px-4 text-white font-medium" type="button" id="clear" onclick="clearTTD('{{$komponen->id_custom_form_komponen}}')">
                        <h5>Hapus</h5>
                    </button>

                </div>

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
                @endif

            </div>
        </div>

        @endforeach

        <div class="mt-4 max-w-[640px] w-full h-max flex justify-between">
            <button class="bg-blue-400 rounded-md px-4 py-2 font-medium text-white" form="form" type="submit">
                KIRIM
            </button>
            <div id="btn-res" class="cursor-pointer bg-red-400 rounded-md px-4 py-2 font-medium text-white" id="gg">
                KOSONGKAN
            </div>
        </div>
        <div class="bg-indigo-500 rounded-lg max-w-[640px] w-full h-92 mt-6 relative h-16 mb-10 flex justify-center items-center">
            <div class="text-white font-medium">
                Powered By @edumate_id
            </div>
        </div>
        @endif

    </form>
    @if(!isset($pesan))
    <script>
    var siswa_url = '/forms/data/siswa'

    function getSiswa(ths){
        let id_siswa_component = $('#' +$(ths).attr('id') +'_siswa');
        console.log(id_siswa_component.attr('id'))
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: siswa_url,
                type: "POST",
                dataType: "json",
                data:{
                    id_kelas: $(ths).val(),
                },
                success: function (data) {
                    console.log(data);
                    id_siswa_component.prop('disabled',false)
                    id_siswa_component.empty();
                    $.each(data.data,function(index,value){
                        $(id_siswa_component).append(`
                            <option value="${value.nm_pengguna}">${value.nm_pengguna}</option> 
                        `)
                    });
                },
                error: function (data) {
                    id_siswa_component.prop('disabled',true)
                },
                
            });
    }
    
    $(function(){
        $('select').select2()
    })

</script>
    <script>
        $('#btn-res').on('click', function() {
            $('#form').get(0).reset();
        })
    </script>
    <!-- SCRIPT INPUT TYPE FILE -->
    <script>
        var filesToUpload = []

        // $('').on('change', function(e) {
        //     console.log('gg')
        //     for (let i = 0; i < e.target.files.length; i++) {
        //         let myFile = e.target.files[i];
        //         let myFileID = "FID" + (1000 + Math.random() * 9000).toFixed(0);
        //         filesToUpload.push({
        //             file: myFile,
        //             size: myFile.size,
        //             FID: myFileID,
        //             name: myFile.name
        //         });
        //     }
        //     display();
        //     e.target.value = null;
        // })

        // const display = () => {
        //     $('#file-holder').empty();
        //     if (filesToUpload.length !== 0) {
        //         $('#belum-ada').addClass('hidden')
        //     } else {
        //         $('#belum-ada').removeClass('hidden')
        //     }
        //     for (let i = 0; i < filesToUpload.length; i++) {
        //         $("#file-holder").append(`
        //                     <div class="bg-indigo-200 rounded-lg w-full h-12 px-4 flex justify-center items-center text-white font-medium relative">
        //                         <div data-fid="${filesToUpload[i].FID}" class="remove-button absolute -top-2 -right-2 bg-red-400 rounded-full w-5 h-5 flex justify-center items-center ring-2 ring-white">
        //                             <svg xmlns="http://www.w3.org/2000/svg" class="fill-white" height="16" width="12" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
        //                                 <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
        //                             </svg>
        //                         </div>
        //                         <p>${filesToUpload[i].name}</p>
        //                     </div>
        //                     `);
        //     }

        // };

        // $(document).on('click', '.remove-button', function() {
        //     var fidToRemove = $(this).data('fid');
        //     for (let i = 0; i < filesToUpload.length; i++) {
        //         if (filesToUpload[i].FID === fidToRemove) {
        //             filesToUpload.splice(i, 1);
        //             break;
        //         }
        //     }
        //     display();
        // });

        // function bytesToMB(bytes) {
        //     return (bytes / (1024 * 1024)).toFixed(2);
        // }
    </script>

    <!-- SCRIPT INPUT CUSTOM TTD
        @readonly
        Jangan Di Prettier bagian json_encode :) -reza
    -->
    <script>
        var signaturePad = [];
        var canvas = [];
        var ttd = {!! json_encode($form->form_komponen->where('tipe_custom_form_komponen', 'custom_ttd')) !!}; //ini

        $(document).ready(function() {

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



        function getData(id) {
            console.log(signaturePad[id].toDataURL('image/png'))
            $('#ttd' + id).val(signaturePad[id].toDataURL('image/png'))
        }
    </script>
    @endif
</body>

</html>