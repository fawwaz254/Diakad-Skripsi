<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>

    <form id="form-upload" method="POST"
        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/action-list-form/edit/0') }}"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        
        <input type="hidden" name="id_form" value="{{ $form->id_form }}">
        <input type="hidden" name="id_jawaban_form" value="{{ $jawaban->id_jawaban_form }}">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-black">
                        <h2>
                            {{ $form->nm_form }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($form->pertanyaan_form as $key => $pertanyaan_form)
        
            <input type="hidden" name="id_pertanyaan_form[{{ $key }}][0]"
                value="{{ $pertanyaan_form->id_pertanyaan_form }}">
            <input type="hidden" name="jenis_pertanyaan[{{ $key }}]"
                value="{{ $pertanyaan_form->jenis_pertanyaan }}">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <pre
                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px; white-space: pre-wrap;
				word-wrap: break-word;">{{ $pertanyaan_form->nm_pertanyaan_form }}</pre>
                            @php
                                $ans = $jawaban->detail_jawaban_form->where('id_pertanyaan_form',$pertanyaan_form->id_pertanyaan_form)->first();
                            @endphp
                            <input type="hidden" name="id_pertanyaan_form[{{ $key }}][1]"
                            value="{{ $ans->id_detail_jawaban_form }}">
                            @if ($pertanyaan_form->jenis_pertanyaan == '1')
                                <textarea class="form-control" name="jawaban_pertanyaan[{{ $key }}]" data-sample-short required>{{ $ans->jawaban ?? '' }}</textarea>
                            @elseif($pertanyaan_form->jenis_pertanyaan == '2')
                                <input type="file" class="form-control"
                                    name="jawaban_pertanyaan[{{ $key }}]" aria-required="true"
                                    aria-invalid="true">
                            @elseif($pertanyaan_form->jenis_pertanyaan == '3')
                                <div class="demo-radio-button">
                                    @foreach (json_decode($pertanyaan_form->options, true) as $options)
                                        <input name="jawaban_pertanyaan[{{ $key }}]" type="radio"
                                            id="radio_{{$key}}_{{ $options }}" value="{{ $options }}" @if(isset($ans->jawaban) && $ans->jawaban == $options) checked  @endif required>
                                        <label for="radio_{{$key}}_{{ $options }}">
                                            <pre class="is-answer">{{ $options }}</pre>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($pertanyaan_form->jenis_pertanyaan == '4')
                                <div class="demo-radio-button">
                                    @foreach (json_decode($pertanyaan_form->options, true) as $key1 => $options)
                                        <input name="jawaban_pertanyaan[{{ $key }}][{{ $key1 }}]"
                                            type="checkbox" id="checkbox_{{ $key1 }}"
                                            value="{{  $options }}" @if(isset($ans->jawaban) && in_array($options,json_decode($ans->jawaban))) checked  @endif>
                                        <label for="checkbox_{{ $key1 }}">
                                            <pre class="is-answer">{{ $options }}</pre>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @endforeach
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                class="material-icons">save</i><span>Save</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');

            var formData = new FormData(form);

            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 200) {
                            vex.dialog.alert(response.message);
                        } else if (response.status == 201) {
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        } else if (response.status == 202) {
                            vex.dialog.alert(response.message);
                            setTimeout(() => {
                                loadURI(response.path);
                            }, 2000);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });

            }, 1000);
        }
    });
</script>
