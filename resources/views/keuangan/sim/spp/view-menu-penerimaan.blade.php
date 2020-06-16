<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        PENERIMAAN
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->thn_akademik_semester}}" 
                                    @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                        selected
                                    @endif>
                                {{$semester->tahun_ajaran}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran</span></button>
                        </div>
                    </div>
                    <style>
                        table tr th{
                            text-align: center;
                        }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>Bulan </th>
                                    <th>Jumlah Pemasukan</th>
                                    @foreach($data_subkategori as $subkategori)
                                    <th>{{$subkategori->nm_subkategori_rapb}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_bulan as $bulan)
                                @php
                                    $all_realisasi_this_month = collect($data_realisasi->where('month', $bulan->id_bulan)->all());
                                @endphp
                                <tr>
                                    <td>Bulan {{$bulan->nm_bulan}}</td>
                                    <td>{{number_format($all_realisasi_this_month->sum('dana_realisasi'))}}</td>
                                    @foreach($data_subkategori as $subkategori)
                                    <td>{{number_format($all_realisasi_this_month->where('rapb.subkategori.id_subkategori_rapb', $subkategori->id_subkategori_rapb)->sum('dana_realisasi'))}}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function filterAction(){
    var tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
    loadURI('sim/spp/penerimaan/'+tahun_akademik_semester);
}
</script>