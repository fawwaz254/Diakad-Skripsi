<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />
<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/rapor-pendukung') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Input Nilai Rapor {{ $rapor->nm_rapor }}</h2>

                </div>
                <div class="body" style="overflow-x:auto;">
                    <div id="spreadsheet"></div>

                    <button class="btn btn-block bg-indigo waves-effect" onclick="actionUpdate()"><i
                            class="material-icons">save</i><span>Save</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var list_data = @json($list_data);
    var data_array = Object.values(list_data);

    var changed = function(instance, cell, x, y, value) {
        var cellName = jspreadsheet.getColumnNameFromId([x, y]);
        table.setStyle(cellName, 'background-color', 'MediumAquaMarine');
    }

    var table = jspreadsheet(document.getElementById('spreadsheet'), {
        data: data_array,
        colHeaders: Object.keys(data_array[0]),
        colWidths: Object.keys(data_array[0]).map(function(column, index) {
            if (index == 0) {
                return 150;
            } else if (index == 1) {
                return 300;
            } else {
                return 200;
            }
        }),
        columns: Object.keys(data_array[0]).map(function(column, index) {
            return {
                readOnly: (index === 0 || index === 1),
            };
        }),
        onchange: changed,
    });

    function actionUpdate() {
        $('button').attr('disabled', 'disabled');

        var data = $('#spreadsheet').jexcel('getData');
        var rapor = @json($rapor);
        var komponen_rapor = @json($komponen_rapor);
        let list_nm_indikator = komponen_rapor.flatMap(komponen =>
            komponen.indikator_rapor_pendukung.map(indikator => indikator.id_indikator_rapor_pendukung)
        );

        let merged_data = [];

        data.forEach((item, index) => {
            let newData = {
                "nis_siswa": item[0],
                "nama_siswa": item[1],
                "data": []
            };

            list_nm_indikator.forEach((id, idx) => {
                newData.data.push({
                    "id_indikator_rapor_pendukung": id,
                    "nilai": item[idx + 2] || ""
                });
            });

            merged_data.push(newData);
        });

        $.ajax({
            url: base_url + '/' + role_url + '/wali-kelas/rapor-pendukung/predikat/' + rapor.id_rapor_pendukung,
            type: 'POST',
            data: {
                data: merged_data,
            },
            success: function(response) {
                vex.dialog.alert(response.message);
                setTimeout(() => {
                    loadURI(response.path);
                }, 2000);
            },
            complete: function() {
                $('button').removeAttr('disabled');
            }
        });
    }
</script>
