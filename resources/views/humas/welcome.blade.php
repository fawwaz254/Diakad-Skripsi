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
                            <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY" value="" name="date" aria-required="true" aria-invalid="true">
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
</script>
<script>
    var options = {
        series: [44, 55, 13, 43, 22],
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
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

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

<script>
    var modul_url = '{{ Request::segment(2) }}';
    var get_data = base_url + '/' + role_url + '/' + 'rekap-chart/';
    var data_chart = []

    function setChart(data) {
        var options = {
            series: Object.values(data.series),
            chart: {
                width: 380,
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
    }

    $('#submit').on('click', function() {
        var id = $('select[name*="id_form"]').val()
        $('#kelaz').children().remove()
        $('#rekapz').children().remove()


        console.log(id)
        console.log('clicked')
        $.post(get_data, {
                id_form: id
            },
            function(data, status) {
                // alert("Data: " + data + "\nStatus: " + status);
                console.log(data);
                if (data.form.id_role.includes('3')) {
                    console.log('3')
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

                        $.post(get_data, {
                                id_form: id,
                                id_kelas: kelaz
                            },
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

                                    console.log(chart)

                                    // var decoded_series = Object.keys(chart.counter).map(function(_) {
                                    //     return decoded_series[_];
                                    // })
                                    let counter = chart.counter
                                    // const outputArray = Object.entries(counter).map(([id, activities]) => ({ id, activities }));


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
                                        isian = {
                                            id: `chart${index}`,
                                            label: JSON.parse(data.options),
                                            series: counter[data.id_pertanyaan_form]
                                        }

                                        data_chart.push(isian)
                                    })
                                }

                            }).then(function() {

                            $.each(data_chart, function(index, data) {
                                // console.log(data)

                                setChart(data)
                            })

                        })

                    });

                }
            });
    })
</script>