<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                    INPUT
                    </h2>
                </div>
                @include('keuangan/sim/pengeluaran/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Tanggal</label>
                                    <input type="text" class="datepicker form-control" name="tgl_realisasi" required="" value="2020-03-04">
                                </div>
                            </div>
                            <label>Kategori</label>
                            <select class="form-control show-tick" name="id_subkategori_rapb">
                                @foreach($data_subkategori as $subkategori)
                                <option value="{{$subkategori->id_subkategori_rapb}}">{{$subkategori->kode_subkategori_rapb}} {{$subkategori->nm_subkategori_rapb}}</option>
                                @endforeach
                            </select>
                            <br>
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Uraian</label>
                                    <textarea class="form-control" name="nm_realisasi" rows="4" cols="100"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Nilai</label>
                                    <input type="number" class="form-control" name="dana_realisasi" required="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect"><i class="material-icons">save</i><span>Ubah Tahun Ajaran</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
    </script>