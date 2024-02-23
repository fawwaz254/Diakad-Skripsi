<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH HASIL PLACEMENT
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload"
                        method="POST"action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        {{ csrf_field() }}

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" name="id_penerimaan" id="id_penerimaan">
                                    <option value="">- Pilih Penerimaan -</option>
                                    @foreach ($grup_penerimaan_tahun as $tahun => $grup_penerimaan)
                                        @foreach ($grup_penerimaan as $semester => $datapergrup)
                                            <optgroup label="{{ $tahun }} {{ $semester }}">
                                                @foreach ($datapergrup as $data)
                                                    @if ($mode == 'show')
                                                        @if ($data->id_penerimaan == $penerimaan->id_penerimaan)
                                                            <option value="{{ $data->id_penerimaan }}" selected>
                                                                {{ $data->nm_penerimaan . ' ' . ($data->gelombang_penerimaan == '0' ? 'Inden' : $data->gelombang_penerimaan) }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $data->id_penerimaan }}">
                                                                {{ $data->nm_penerimaan . ' ' . ($data->gelombang_penerimaan == '0' ? 'Inden' : $data->gelombang_penerimaan) }}
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $data->id_penerimaan }}">
                                                            {{ $data->nm_penerimaan . ' ' . ($data->gelombang_penerimaan == '0' ? 'Inden' : $data->gelombang_penerimaan) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="ccol-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Data Siswa
                                </h2>
                                <select class="form-control show-tick" name="id_c_siswa" id="siswa">
                                    <option value="0">-- Semua --</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            File Hasil Placement ( pdf , docx , doc , png , jpg , jpeg | max 5 mb )
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="file" class="form-control" name="file_hasil_placement"
                                        id="file_hasil_placement" />
                                </label>
                            </div>
                        </div>

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
@include('scriptjs')

<script>
    var modul_url = 'report';

    $('#id_penerimaan').on('change', function(e) {
        console.log(e);
        var id_penerimaan = e.target.value;
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'input-hasil-placement/upload/get-siswa/' +
            id_penerimaan,
            function(data) {
                console.log(data);
                $('#siswa').empty();


                $('#siswa').append($("<option>")
                    .attr("value", 0)
                    .text("-- Semua --")
                );
                $.each(data, function(index, siswaObj) {
                    $('#siswa').append($("<option>")
                        .attr("value", siswaObj.id_c_siswa)
                        .text(siswaObj.nm_c_siswa)
                    );
                })

                $('select').select();
            });
    });
</script>

<script>
    $('#form-upload').validate({
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                enctype: 'multipart/form-data',
                data: new FormData($('#form-upload')[0]),
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
                    $('input').removeAttr('readonly', 'readonly');
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>
