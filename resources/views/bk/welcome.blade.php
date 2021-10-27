@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{$today->format('d M Y')}}</h2>
    </div>
    <div class="row clearfix">

        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>Semester 2021/2022 Ganjil</h2>
                </div>
                <div class="body">

                    <h5 style="text-align: center;">Pelanggaran Dibawah Tanggung Jawab Saya</h5>
                     <table class="table table-bordered">
                        <thead>
                            <tr class="bg-pink">
                                <th style="text-align: center;">Yang Menginputkan</th>
                                <th style="text-align: center;">Jumlah</th>
                                <th style="text-align: center;">Belum Ditindak</th>
                                <th style="text-align: center;">Sudah Ditindak</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Saya</th>
                                <th style="text-align: center;">100</th>
                                <th style="text-align: center;">100</th>
                                <th style="text-align: center;">0</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">BK</th>
                                <th style="text-align: center;">150</th>
                                <th style="text-align: center;">100</th>
                                <th style="text-align: center;">50</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;" class="bg-pink">Total</th>
                                <th style="text-align: center;">250</th>
                                <th style="text-align: center;">200</th>
                                <th style="text-align: center;">50</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>

@include('rilis-note')