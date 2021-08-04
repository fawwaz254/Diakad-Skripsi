<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        EDIT SALDO KAS AWAL TAHUN
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
           
            <div class="card">
                <div class="body">
                   
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/spp/action-setting-saldo-kas-awal-tahun/edit/'.$data->id_tutup_buku_bulanan_kas)}}">
                    {{csrf_field()}}

                    <div class="row clearfix">
                        
                        <div class="col-md-4">
                            <label>Semester Mulai</label>
                            <input type="text" disabled="" class="form-control" value="{{$data->semester_mulai->tahun_ajaran}} {{$data->semester_mulai->nm_semester}}" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-4">
                            <label>Semester Selesai</label>
                            <input type="text" disabled="" class="form-control" value="{{$data->semester_selesai->tahun_ajaran}} {{$data->semester_selesai->nm_semester}}" aria-required="true" aria-invalid="true">
                        </div>

                         <div class="col-md-4">
                            <label>Kas Akhir Bulan</label>
                            <input type="number" name="kas_akhir_bulan" class="form-control" value="{{$data->kas_akhir_bulan}}" required="" aria-required="true" aria-invalid="true">
                        </div>

                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                        </div>
                    </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')