<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#reward-siswa/input-reward-siswa/view-kelas/'.$data_siswa->id_kelas)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT REWARD SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-reward-siswa/add/'.$data_siswa->id_siswa)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Data Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="id_siswa" required="" value="{{$data_siswa->id_siswa}}">
                                <input type="hidden" name="id_kelas" required="" value="{{$data_siswa->id_kelas}}">
                                <input type="text" class="form-control" disabled="" value="{{$data_siswa->nm_pengguna}} - {{$data_siswa->nis_siswa}} ({{$data_siswa->nm_kelas}})">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Judul Pemberian Reward
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_reward_siswa" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi Pemberian Reward
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea class="form-control" name="deskripsi_reward_siswa" rows="4" cols="100"></textarea>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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