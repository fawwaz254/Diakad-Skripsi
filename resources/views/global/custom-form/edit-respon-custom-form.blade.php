@push('assets')
<style>

</style>
@endpush



<div class="container-fluid">
    <div class="block-header">
        @if(isset($form))
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/custom-form/submitted/' . $form->id_custom_form ) }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        @endif
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

            <form id="form-validation" method="post" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4)) }}">
                @method('PUT')
                <div class="card">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{$form->id_custom_form_sheet}}" name="id_custom_form_sheet">
                    <div class="header" style="border-top: 8px solid #555;">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input style="height: 50px; font-size:x-large; outline: none; border: none; width: 100%; " placeholder="Formulir Tanpa Judul" type="text" class="" name="nm_custom_form" required="" aria-required="true" aria-invalid="true" value="FORM {{$form->form->nm_custom_form}}" disabled>
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
                                    <input type="text" class=" form-control" name="start_time" required="" aria-required="true" aria-invalid="true" value="{{$form->form->start_time}}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <h2 class="card-inside-title">
                                    Jam Akhir Pengisian
                                </h2>
                                <div>
                                    <input type="text" class=" form-control" name="end_time" required="" aria-required="true" aria-invalid="true" value="{{$form->form->end_time}}" disabled>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <small class="text-danger">* Menunjukkan Pertanyaan Yang Wajib Diisi</small>
                            </div>
                        </div>




                    </div>
                </div>
                @foreach($form->form_respon as $index => $value)
                <div class="card" style="margin: 15px 0;">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="">
                                    <h4>{{$value->form_komponen->label_custom_form_komponen}}</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="pertanyaan{{$value->form_komponen->id_custom_form_komponen}}">
                                @if($value->form_komponen->tipe_custom_form_komponen == "text")
                                <input type="text" class="form-control" name="respon[{{$value->form_komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="{{head(json_decode($value->respon, true))}}" placeholder="Masukkan Jawaban...">
                                @elseif($value->form_komponen->tipe_custom_form_komponen == "number")
                                <input type="number" class="form-control" name="respon[{{$value->form_komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="{{head(json_decode($value->respon, true))}}" placeholder="Masukkan Angka...">

                                @elseif($value->form_komponen->tipe_custom_form_komponen == "select")
                                <!-- <input type="text" class="form-control" name="respon[{{$value->form_komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Jawaban..."> -->
                                @foreach(json_decode($value->form_komponen->option_custom_form_komponen) as $key => $options)

                                <div style="margin-bottom: 20px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="radio" value="{{$options}}" name="respon[{{$value->form_komponen->id_custom_form_komponen}}][]" id="rad_{{$value->form_komponen->id_custom_form_komponen}}_{{$value->form_komponen->label_custom_form_komponen}}_{{$key}}" {{ (head(json_decode($value->respon,true)) == $options) ? ' checked' : '' }}>
                                    <label for="rad_{{$value->form_komponen->id_custom_form_komponen}}_{{$value->form_komponen->label_custom_form_komponen}}_{{$key}}" style="width: 100%; font-size: 16px;">
                                        <p>{{$options}}</p>
                                    </label>
                                </div>
                                @endforeach

                                @elseif($value->form_komponen->tipe_custom_form_komponen == "checkbox")

                                @foreach(json_decode($value->form_komponen->option_custom_form_komponen) as $key => $options)
                                <div style="margin-bottom: 20px; display:flex; align-items:center; gap: 10px">
                                    <input class="" type="checkbox" value="{{$options}}" name="respon[{{$value->form_komponen->id_custom_form_komponen}}][]" id="check_{{$value->form_komponen->id_custom_form_komponen}}_{{$value->form_komponen->label_custom_form_komponen}}_{{$key}}" {{ in_array($options,json_decode($value->respon,true)) ? ' checked' : ''}}>
                                    <label for="check_{{$value->form_komponen->id_custom_form_komponen}}_{{$value->form_komponen->label_custom_form_komponen}}_{{$key}}" style="width: 100%;">
                                        <p>{{$options}}</p>
                                    </label>
                                </div>
                                @endforeach
                                @elseif($value->form_komponen->tipe_custom_form_komponen == "custom_ttd")

                               
                                <textarea type="text" class="hidden form-control" name="respon[{{$value->form_komponen->id_custom_form_komponen}}][]" id="ttd{{$value->form_komponen->id_custom_form_komponen}}" required aria-required="true" aria-invalid="true"></textarea>
                                <div class="wrapper">
                                    <canvas id="signature-pad{{$value->form_komponen->id_custom_form_komponen}}" class="signature-pad" width="400" height="200" style="border:1px solid black;" onmouseleave="getData('{{$value->form_komponen->id_custom_form_komponen}}')"></canvas>
                                </div>


                                <button class="btn btn-lg btn-danger" type="button" id="clear" onclick="clearTTD('{{$value->form_komponen->id_custom_form_komponen}}')">
                                    <h5>Clear</h5>
                                </button>
                                @else
                                <input type="text" class="form-control" name="respon[{{$value->form_komponen->id_custom_form_komponen}}][]" required="" aria-required="true" aria-invalid="true" value="" placeholder="Masukkan Jawaban...">

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
    @php


    @endphp
    @endif
</div>
</div>
@include('scriptjs')


<script>
    var signaturePad = [];
    var canvas = [];
    var ttd = {!! json_encode($form->form->form_komponen->where('tipe_custom_form_komponen', 'custom_ttd')) !!};

    console.log(ttd);
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
