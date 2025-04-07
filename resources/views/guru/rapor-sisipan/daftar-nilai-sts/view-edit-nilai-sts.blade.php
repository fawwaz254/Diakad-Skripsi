<style>
    #spreadsheet tbody td:nth-child(2),
    #spreadsheet tbody td:nth-child(3) {
        background-color: black;
        color: white;
    }
</style>

<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/daftar-nilai-sts') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Edit Nilai Rapor Sisipan</h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/daftar-nilai-sts/action-daftar-nilai-sts/editNilai/' . $id_rapor) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="data" id="data">
                        <div id="spreadsheet"></div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div id="loading">
                                    <img src="{{ asset('js/loading_new.gif') }}" />
                                </div>
                                <button class="btn btn-block bg-indigo waves-effect" id="submit" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var modul_url = 'rapor-sisipan';
    var id_rapor = '{{ $id_rapor }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/getNilai/' +
        id_rapor;
    var dynamicColumnsJson = '{!! addslashes(json_encode($dynamicColumns)) !!}';
    var dynamicColumns = JSON.parse(dynamicColumnsJson);
    $(document).ready(function() {
        $.ajax({
            url: datatable_url,
            type: 'GET',
            success: function(data) {
                $('#loading').html('');
                jspreadsheet(document.getElementById('spreadsheet'), {
                    data: data,
                    colHeaders: dynamicColumns.map(function(column) {
                        return column.title
                    }),
                    colWidths: dynamicColumns.map(function(column) {
                        return column.width ||
                            150;
                    }),
                    allowInsertColumn: false,
                    allowDeleteColumn: false,
                    columns: dynamicColumns.map(function(column, index) {
                        return {
                            readOnly: (index === 0 || index ===
                                1), // Menonaktifkan kolom nomor 0 dan 1
                        };
                    }),

                    // tableOverflow: true,
                    // columns: [{
                    //         type: 'text',
                    //         title: 'NIS',
                    //         width: 90,
                    //     },
                    //     {
                    //         type: 'text',
                    //         title: 'Nama',
                    //         width: 300,
                    //     },
                    //     // Tambahkan kolom lain sesuai kebutuhan
                    // ]
                });

            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $(function() {
        var primary_table = null;
        $('#form-validation').validate({
            rules: {
                'checkbox': {
                    required: true
                },
                'gender': {
                    required: true
                }
            },
            highlight: function(input) {
                $(input).parents('.form-group').addClass('error');
            },
            unhighlight: function(input) {
                $(input).parents('.form-group').removeClass('error');
            },
            errorPlacement: function(error, element) {
                $(element).parents('.form-group').append(error);
            },
            submitHandler: function(form) {
                var data = $('#spreadsheet').jexcel('getData');
                var error = false;

                // get panjang kolom dan dikurang 2 karena kolom 1 & 2 hanya data siswa bukan nilai
                var maxColIndex = data[0].length - 2;
                data.forEach(function(row, rowIndex) {
                    row.forEach(function(value, colIndex) {
                        // validasi dari index 2 hingga max kolom
                        if (colIndex >= 2 && colIndex <= maxColIndex) {
                            var cleanValue = value.trim();

                            if (!cleanValue || /[a-zA-Z\-=!@#$%^&*()]/.test(
                                    cleanValue)) {
                                error = true;
                            }
                        }
                    });
                });

                if (error) {
                    swal({
                        title: "Input harus diisi berupa angka",
                    });
                    return false;
                }

                $('#data').val(JSON.stringify(data));
                $('button').attr('disabled', 'disabled');
                $.ajax({
                    processData: false, // Important!
                    contentType: false,
                    cache: false,
                    url: form.action,
                    type: form.method,
                    data: new FormData($(form)[0]),
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
                        } else if (response.status == 205) {
                            $('#modalMaster').modal('hide');
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });
            }
        });
    });
</script>
