@php
$today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{$today->format('d M Y')}}</h2>
    </div>
    <div class="row clearfix">
        @if ($role_dashboard)
        @if ($role_dashboard->isi_dashboard!=null)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PENGUMUMAN
                    </h2>
                </div>
                <div class="body">
                    {!! $role_dashboard->isi_dashboard !!}
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
    <div class="row clearfix" style="margin-top:40px;">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        Filter Form
                    </h2>
                </div>

                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Pilih Form
                            </h2>
                            <select class="form-control show-tick" name="id_form">
                                @foreach($forms as $form)
                                <option value="{{$form->id_form}}">Form {{$form->nm_form}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tanggal
                            </h2>
                            <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY" value="{{date('d-m-Y')}}" name="date" aria-required="true" aria-invalid="true" id="tanggal">
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" id="submit"><i class="material-icons">save</i><span>Filter</span></button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div id="kelaz">

    </div>

    <div class="row clearfix" id="rekapz">

    </div>
    <div id="load" class="card">
        <div class="body  d-flex align-items-center">
            <h5><strong>Loading Data...</strong></h5>
            <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
        </div>
    </div>

    <div class="row">
        {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="target-link" href="{{url(Request::segment(1).'#kegiatan-harian/mengisi-form-kesehatan')}}">
        <div class="card">
            <div class="body bg-red" style="text-align: -webkit-center;">
                <img class="media-object" src="{{url('media/flaticon/heartbeat.png')}}" width="64" height="64">
                <h5>
                    Monitoring Kesehatan
                </h5>
                <small>Isi Form monitoring kesehatan Anda setiap hari pukul {{$start_monkes}} - {{$end_monkes}}</small>
            </div>
        </div>
        </a>
    </div> --}}
    {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ url(Request::segment(0) . Request::segment(1) .'#absensi/device') }}">
    <div class="card">
        <div class="body bg-green" style="text-align: -webkit-center;">
            <img class="media-object" src="{{ url('media/flaticon/clipboard.png') }}" width="64" height="64">
            <h5>
                FingerPrint
            </h5>
            <small>Informasi alat Fingerprint</small>
        </div>
    </div>
    </a>
</div> --}}
</div>
<br>
<div class="row clearfix">
</div>
</div>

@include('rilis-note')


@include('scriptjs')
<script>
    $('select[name*="id_form"]').select2()

    $('select[name*="id_form"]').on('change', function() {
        $('#kelaz').children().remove()
        $('#rekapz').children().remove()
    })

    $('#tanggal').on('change', function() {
        var tanggal = $('#tanggal').val() === '' ? new Date().toISOString().substring(0, 10) : $('#tanggal').val();
        $('#kelaz').children().remove()
        $('#rekapz').children().remove()
    })

    document.querySelector("#tanggal").valueAsDate = new Date();

    var $loading = $('#load').hide();
    $(document)
        .ajaxStart(function() {
            $loading.show();
        })
        .ajaxStop(function() {
            $loading.hide();
        });
</script>


<script>
    var modul_url = '{{ Request::segment(2) }}';
    var get_data = base_url + '/' + role_url + '/' + 'rekap-chart/';
    var data_chart = []

    function setPieChart(data) {

        var options = {
            series: Object.values(data.series),
            chart: {
                width: 500,
                type: 'pie',
            },
            labels: data.label,
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector('#' + data.id), options);

        chart.render();
        window[data.id + '_chart'] = chart;
    }

    function setBarChart(data) {


        var options = {
            series: [{
                data: Object.values(data.series)
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.jenis == 4 ? data.label : Object.keys(data.label),
            },
            tooltip: {
                enabled: true,
                custom({
                    series,
                    seriesIndex,
                    dataPointIndex,
                    w
                }) {
                    return (
                        '<div style="width: 100%; height: 50px; border-radius: 10px; display:flex; justify-content:center; align-items:center; padding: 0px 15px 0px 15px;">' +
                        "<span>" +
                        w.globals.labels[dataPointIndex] + 
                        "</span>" +
                        "&nbsp <span><strong> ( " + series[seriesIndex][dataPointIndex]+ " ) </strong></span>" +
                        "</div>"
                    );
                }
            },

        }

        var chart = new ApexCharts(document.querySelector('#' + data.id), options);
        chart.render();
        window[data.id + '_chart'] = chart;
    }

    function removeChart(chartId) {
        var chartInstance = window[chartId + '_chart'];

        if (chartInstance) {
            chartInstance.destroy();
            delete window[chartId + '_chart'];
        }
    }

    function getRekapData(param) {
        $.post(get_data, param,
            function(chart, status) {
                data_chart = []
                if (chart.counter !== null) {

                    $('#rekapz').append(`
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2>Rekap Statistik Data Form</h2>
                                            </div>
                                            <div id="tempat" class="body">
                                            </div>
                                        </div>
                                    </div>
                                    `)



                    let counter = chart.counter


                    $.each(chart.form.pertanyaan_form, function(index, data) {
                        $('#rekapz').find('#tempat').append(`
                                    <div class="row clearfix">
                                        <div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
                                            <h2 class="card-inside-title">
                                                ${data.nm_pertanyaan_form}
                                            </h2>
                                            <div id="chart${index}"></div>
                                        </div>
                                        </div>
                                    `)
                        if (data.jenis_pertanyaan === 3 || data.jenis_pertanyaan == 4) {

                            isian = {
                                id: `chart${index}`,
                                label: JSON.parse(data.options),
                                series: counter[data.id_pertanyaan_form],
                                jenis: data.jenis_pertanyaan
                            }
                        } else {
                            isian = {
                                id: `chart${index}`,
                                label: counter[data.id_pertanyaan_form],
                                series: counter[data.id_pertanyaan_form],
                                jenis: data.jenis_pertanyaan
                            }
                        }

                        data_chart.push(isian)
                    })
                } else {
                    $('#rekapz').append(`
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="header">
                                                <h2>Rekap Statistik Data Form</h2>
                                            </div>
                                            <div id="tempat" class="body">
                                                <h1>Data Belum ada, Responden Belum Mengirim Jawaban</h1>
                                            </div>
                                        </div>
                                    </div>
                                    `)
                }

            }).then(function() {

            $.each(data_chart, function(index, data) {
                removeChart(data.id) // jaga jaga refresh chart biar ga berat -reza
                if (data.jenis === 3) {
                    setPieChart(data)
                } else if (data.jenis === 4 || data.jenis === 1) {
                    setBarChart(data)
                } else {
                    $('#' + data.id).append(`
                        Data Dapat Dilihat Pada Menu Rekap
                    `)
                }
            })

        })
    }



    $('#submit').on('click', function() {
        var id = $('select[name*="id_form"]').val()
        tanggal = $('#tanggal').val() === '' ? new Date().toISOString().substring(0, 10) : $('#tanggal').val();

        $('#kelaz').children().remove()
        $('#rekapz').children().remove()

        $.post(get_data, {
                id_form: id
                // date: tanggal
            },
            function(data, status) {

                if (data.form !== null && data.form.id_role.includes('3')) {

                    $('#kelaz').append(`
                    <div class="row clearfix" style="">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card is-gap">
                                <div class="header">
                                    <h2>
                                        Filter Form Siswa
                                    </h2>
                                </div>

                                <div class="body">
                                    <div class="row clearfix">
                                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                            <h2 class="card-inside-title">
                                                Pilih Kelas
                                            </h2>
                                            <select class="form-control show-tick" name="id_kelas">
                                                
                                            </select>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <button class="btn btn-block btn-danger bg-btn-submit waves-effect" id="submitKelaz"><i class="material-icons">save</i><span>Tampilkan</span></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    `)

                    $(data.kelas).each(function(index, data) {
                        $('select[name*="id_kelas"]').append(`<option value="${data.id_kelas}">${data.nm_kelas}</option>`)
                    })
                    $('#submitKelaz').on('click', function() {
                        var kelaz = $('select[name*="id_kelas"]').val()
                        $('#rekapz').children().remove()

                        getRekapData({
                            id_form: id,
                            id_kelas: kelaz,
                            date: tanggal
                        })

                    });

                } else {
                    getRekapData({
                        id_form: id,
                        date: tanggal
                    })
                }
            });
    })
</script>