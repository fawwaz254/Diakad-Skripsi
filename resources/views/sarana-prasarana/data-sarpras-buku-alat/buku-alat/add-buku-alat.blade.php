<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-sarpras-buku-alat/buku-alat')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-purple">
                    <h2>
                        TAMBAH BUKU/ALAT
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-buku-alat/add/'.$id_buku_alat)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Jenis Buku/Alat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_buku_alat">
                                    @foreach($data_jenis_buku_alat as $data)
                                        <option value="{{$data->id_jenis_buku_alat}}">{{$data->nm_jenis_buku_alat}} - {{$data->kode_jenis_buku_alat}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Buku/Alat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_buku_alat" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <div class="card-inside-title">
                            Jenis
                            <small class="form-text text-muted">
                                *Buku atau Alat
                            </small>
                        </div>
                        <div class="row clearfix" style="margin-bottom:28px">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                <select class="form-control show-tick" name="jenis" id="jenis" >
                                    <option value="0">Alat</option>
                                    <option value="1">Buku</option>
                                </select>
                            </div>
                        </div>

                        <div class="hidden" id="jenis-section">
                            <div class="card-inside-title">
                                Tingkat Pendidikan <small>* Opsional</small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 via" style="margin-bottom:0px !important;">
                                    <select class="form-control show-tick" name="tingkat_pendidikan_buku_alat">
                                        <option value="" >-- Pilih Tingkat Pendidikan --</option>
                                        @foreach($data_tingkat_pendidikan as $data)
                                            <option value="{{ $data->tingkat }}">{{ $data->tingkat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Mata Pelajaran <small>* Opsional</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_mata_pelajaran">
                                    <option value="" >-- Pilih Mata Pelajaran --</option>
                                    @foreach($data_mata_pelajaran as $data)
                                        <option value="{{$data->id_mata_pelajaran}}">{{$data->nm_mata_pelajaran}} - {{$data->nm_jurusan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Buku/Alat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_buku_alat" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pembelian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_pembelian" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jumlah Buku/Alat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_buku_alat" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kondisi Baik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_kondisi_baik" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kondisi Rusak
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_kondisi_rusak" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_buku_alat" required="" aria-required="true" aria-invalid="true">
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
<script>

$('#jenis').on('change', function (e) {
        var optionSelected = $(this).find("option:selected");
        if($(this).val() == "1"){ 
            $('#jenis-section').removeClass('hidden');
        } else {
            $('#jenis-section').addClass('hidden');
        }
    });

$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: false
    });

});
</script>