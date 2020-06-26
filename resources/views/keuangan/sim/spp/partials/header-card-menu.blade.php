<div class="body">
    <ol class="breadcrumb breadcrumb-col-teal">
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                PENERIMAAN LAIN-LAIN
            </a>
            <ul class="dropdown-menu pull-left">
                <li><a href="{{Request::segment(1)}}#sim/spp/input" class="target-link waves-effect waves-block">Input Penerimaan</a></li>
                <li><a href="{{Request::segment(1)}}#sim/spp/penerimaan" class="target-link waves-effect waves-block">Tampilkan</a></li>
            </ul>
        </li>
        <li><a href="{{Request::segment(1)}}#sim/spp/pemasukan" class="target-link">PEMASUKAN SPP</a></li>
        <li><a href="{{Request::segment(1)}}#sim/spp/pembayaran" class="target-link">PEMBAYARAN</a></li>
        <li><a href="{{Request::segment(1)}}#sim/spp/cari" class="target-link">CARI</a></li>
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
                TUNGGAKAN
            </a>
            <ul class="dropdown-menu pull-left">
                <li><a href="javascript:void(0);" class=" waves-effect waves-block">Tunggakan Siswa Aktif</a></li>
                <li><a href="javascript:void(0);" class=" waves-effect waves-block">Tunggakan Alumni</a></li>
                <li><a href="{{Request::segment(1)}}#sim/spp/tunggakan" class="target-link waves-effect waves-block">Tunggakan Tahun Lalu</a></li>
                <li><a href="javascript:void(0);" class=" waves-effect waves-block">Input Belum Masuk Keseluruhan</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
            SETTING
            </a>
            <ul class="dropdown-menu pull-left">
                <li><a href="{{Request::segment(1)}}#sim/spp/setting" class="target-link waves-effect waves-block">Setting SPP</a></li>
                <li><a href="{{Request::segment(1)}}#sim/spp/setting-non-spp" class="target-link waves-effect waves-block">Setting NON-SPP</a></li>
            </ul>
        </li>
        <li><a href="{{Request::segment(1)}}#sim/spp/upload-pembayaran" class="target-link">UPLOAD PEMBAYARAN</a></li>
    </ol>
</div>