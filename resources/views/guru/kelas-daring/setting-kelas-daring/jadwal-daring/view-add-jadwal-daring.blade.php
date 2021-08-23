<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas/data-jadwal/'.$item->id_kelas_mp_grup)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Input Jadwal Kelas Online
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/jadwal-kelas/data-jadwal/action/add-jadwal')}}">
                        {{csrf_field()}}
                        <input type="hidden" name="id_kelas_mp_grup" value="{{$item->id_kelas_mp_grup}}">
                        <h2 class="card-inside-title">
                            Nama Kelas Daring
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" readonly="" aria-required="true"
                                aria-invalid="true" value="{{$item->nm_kelas_mp_grup}}">
                            </div>
                        </div>

                        <div class="row clearfix">

                            <div class="col-md-3">
                                <label>Pertemuan Ke</label>
                                <input type="number" class="form-control" min="1" name="pertemuan_ke" required="" aria-required="true" aria-invalid="true" value="1" >
                            </div>

                            <div class="col-md-3">
                                <label>Jenis Pertemuan</label>
                                <select class="form-control show-tick" name="jenis_materi">
                                    <option value="" disabled selected >-- Pilih jenis pertemuan --</option>
                                    <option value="1">KBM</option>
                                    <option value="2">UH</option>
                                    <option value="3">UTS</option>
                                    <option value="4">UAS</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label> Online/Offline</label>
                                <select class="form-control show-tick" name="is_daring">
                                    <option value="" disabled selected >-- Pilih online/offline --</option>
                                    <option value="1">Online</option>
                                    <option value="0">Offline</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label> Toleransi Keterlambatan (Menit)</label>
                                <input type="number" class="form-control" min="0" name="torelansi_terlambat" required="" aria-required="true" aria-invalid="true" value="0" >
                            </div>

                        </div>

                        <div class="row clearfix">

                            <div class="col-md-4">
                                <label>Tanggal</label>
                                <input type="text" class="datepicker form-control" name="tgl_presensi" required="" aria-required="true" aria-invalid="true" value="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
                            </div>

                            <div class="col-md-4">
                                <label> Waktu Mulai</label>
                                <input type="text" class="datepicker-time form-control" name="waktu_mulai" required="" aria-required="true" aria-invalid="true" value="">
                            </div>

                            <div class="col-md-4">
                                <label>Waktu Selesai</label>
                                <input type="text" class="datepicker-time form-control" name="waktu_selesai" required="" aria-required="true" aria-invalid="true" value="">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')

<script>
    $(function(){  
        $('.datepicker-time').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: false
        });

        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>