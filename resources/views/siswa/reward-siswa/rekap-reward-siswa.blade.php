<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Rekap Aktivitas Reward</h2>
            </div>
            <div class="body">
                <h3>Bulan {{ $now->isoFormat('MMMM Y') }}</h3>
                <h4>Input Aktivitas Harian</h4>
                <div class="table-responsive">
                    <table
                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                        <thead>
                            <tr>
                                <th style="text-align: center;" colspan="{{$dates->count()}}">Tanggal</th>
                            </tr>
                            <tr>
                                @foreach($dates as $d)
                                <th>{{$d->format('d')}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($dates as $d)
                                @if($data_pengisian_kegiatan_harian->where('id_kegiatan_harian', 'reward-siswa-harian')->where('tgl_pengisian', $d->format('Y-m-d'))->first())
                                <td style="background-color: #bffa85;"> 
                                    1x
                                </td>
                                @else
                                <td></td>
                                @endif
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4>Input Aktivitas Mingguan</h4>
                <div class="table-responsive">
                    <table
                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                        <thead>
                            <tr>
                                <th style="text-align: center;" colspan="{{ count($week_dates) }}">Minggu </th>
                            </tr>
                            <tr>
                                @foreach($week_dates as $d)
                                <th>{{$d['start']->isoFormat('dddd, DD MMM')}} - {{$d['end']->isoFormat('dddd, DD MMM')}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($week_dates as $d)
                                @if($data_pengisian_kegiatan_harian->where('id_kegiatan_harian', 'reward-siswa-mingguan')->whereBetween('tgl_pengisian', [$d['start']->format('Y-m-d'), $d['end']->format('Y-m-d')])->first())
                                <td style="background-color: #bffa85;"> 
                                    1x
                                </td>
                                @else
                                <td></td>
                                @endif
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4>Input Aktivitas Bulanan</h4>
                <div class="table-responsive">
                    <table
                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                        <thead>
                            <tr>
                                <th>Bulan {{ $now->isoFormat('MMMM Y') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @if($data_pengisian_kegiatan_harian->where('id_kegiatan_harian', 'reward-siswa-bulanan')->first())
                                <td style="background-color: #bffa85;"> 
                                    1x
                                </td>
                                @else
                                <td></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>