<style>
    input {
        position: relative;
        width: 150px;
        height: 20px;
        color: white;
    }

    input:before {
        position: absolute;
        top: 3px;
        left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    input::-webkit-datetime-edit,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button {
        display: none;
    }

    input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-input-nilai-kpi') }}">
                        {{ csrf_field() }}
                    @foreach ($kpi as $item)
                    <input type="hidden" id="id_siswa" name="id_siswa" value="{{$siswa->id_siswa}}">
                    <input type="hidden" id="id_semester" name="id_semester" value="{{$semester->id_semester}}">
                    <h4>{{$item->nm_kategori}}</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kecakapan</th>
                                <th>Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->subkategorikpi as $key1=>$subkategorikpis)
                            @php
                                if($subkategorikpis->nm_subkategori == null){
                                    $rowspan = $subkategorikpis->komponenkpi->count();
                                }else {
                                    $rowspan = $subkategorikpis->komponenkpi->count() + 1;    
                                }
                                // dd($rowspan);
                            @endphp
                                @if ($subkategorikpis->nm_subkategori)
                                    <tr>
                                        <td rowspan="{{$rowspan}}">{{$key1+1}}</td>
                                        <td>{{$subkategorikpis->nm_subkategori}}</td>
                                        <td></td>                                                              
                                    </tr>
                                    @foreach ($subkategorikpis->komponenkpi as $key=>$komponenkpis)
                                        <tr>
                                            <td>{{$komponenkpis->nm_komponen}}</td>
                                            <td>
                                                <input type="hidden" id="id_komponen" name="id_komponen[]" value="{{$komponenkpis->id_komponen_kpi}}">
                                                <select name="nilai_komponen[]" id="nilai_komponen">
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                </select>
                                            </td>                                                               
                                        </tr>
                                    @endforeach    
                                @else
                                    @foreach ($subkategorikpis->komponenkpi as $key=>$komponenkpis)
                                        @if ($key == 0)
                                        <tr>
                                            <td rowspan="{{$rowspan}}">{{$key1+1}}</td>
                                            <td>{{$komponenkpis->nm_komponen}}</td>
                                            <td>
                                                <input type="hidden" id="id_komponen" name="id_komponen[]" value="{{$komponenkpis->id_komponen_kpi}}">
                                                <select name="nilai_komponen[]" id="nilai_komponen">
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                </select>
                                            </td>                                                           
                                        </tr>
                                        @else
                                        <tr>
                                            <td>{{$komponenkpis->nm_komponen}}</td>
                                            <td>
                                                <input type="hidden" id="id_komponen" name="id_komponen[]" value="{{$komponenkpis->id_komponen_kpi}}">
                                                <select name="nilai_komponen[]" id="nilai_komponen">
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                </select>
                                            </td>                                                               
                                        </tr>
                                        @endif
                                    @endforeach 
                                @endif                                
                            @endforeach
                        </tbody>
                    </table>
                    @endforeach
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                    class="material-icons">save</i><span>Save</span></button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
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
        highlight: function(input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status == 200) {
                        vex.dialog.alert(response.message);
                    } else if (response.status == 201) {
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    } else if (response.status == 202) {
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
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
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });

    function changeKelas(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/pertemuan-byjadwalkelasmp') }}',
            type: 'POST',
            data: {
                id_jadwal_kelas_mp: $('select[name=id_jadwal_kelas_mp]').val()
            },
            success: function(result) {
                $('select[name=pertemuan_ke]').html('');
                $('select[name=pertemuan_ke]').append(
                    '<option value="" disabled selected >-- Pilih Pertemuan pekan ke --</option>');
                $.each(result, function(key, item) {
                    $('select[name=pertemuan_ke]').append('<option value="' + item.value + '">' +
                        item.text + '</option>');
                });
            }
        });
    }
</script>
