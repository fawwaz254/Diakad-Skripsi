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
                                    <input type="text" class="form-control" required="" value="2020-03-04">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                    <label>No Nota</label>
                                    <input type="text" class="form-control" required="">
                                </div>
                            </div>
                            <label>Kategori</label>
                            <select class="form-control show-tick" name="role">
                                <option value="">Guru Bidang Studi</option>
                            </select>
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Uraian</label>
                                    <textarea class="form-control" name="materi_ekskul" rows="4" cols="100"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Nilai</label>
                                    <input type="text" class="form-control" required="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>