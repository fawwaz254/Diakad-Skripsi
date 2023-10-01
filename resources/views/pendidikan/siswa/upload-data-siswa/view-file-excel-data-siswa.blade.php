<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#siswa/upload-data-siswa') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        CEK UPLOAD DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive ">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    @foreach ($datas as $data1)
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        @foreach ($data1 as $key => $i)
                                            <th>{{ $key }}</th>
                                        @endforeach
                                    @break
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data2)
                                <tr>
                                    <td id="{{ $data2['nis'] }}status"></td>
                                    <td id="{{ $data2['nis'] }}keterangan"></td>
                                    @foreach ($data1 as $key => $i)
                                        <td id="{{ $data2['nis'] . $key }}">{{ $data2[$key] }}</td>
                                    @endforeach
                                    {{-- 


                                    <td id="{{ $data['nis'] }}nis">{{ $data['nis'] }}</td>
                                    <td id="{{ $data['nis'] }}nisn">{{ $data['nisn'] }}</td>
                                    <td id="{{ $data['nis'] }}nama_lengkap">{{ $data['nama_lengkap'] }}</td>
                                    <td id="{{ $data['nis'] }}jenis_kelamin">{{ $data['jenis_kelamin'] }}</td>
                                    <td id="{{ $data['nis'] }}status_siswa">{{ $data['status_siswa'] }}</td>
                                    <td id="{{ $data['nis'] }}kelas">{{ $data['kelas'] }}</td>
                                    <td id="{{ $data['nis'] }}tahun_masuk">{{ $data['tahun_masuk'] }}</td>
                                    <td id="{{ $data['nis'] }}semester_masuk">{{ $data['semester_masuk'] }}
                                    </td>
                                    <td id="{{ $data['nis'] }}jalur">{{ $data['jalur'] }}</td>
                                    <td id="{{ $data['nis'] }}orang_tua_kandung">
                                        {{ $data['orang_tua_kandung'] }}</td>
                                    <td id="{{ $data['nis'] }}kewarganegaraan">{{ $data['kewarganegaraan'] }}
                                    </td>
                                    <td id="{{ $data['nis'] }}merupakan_penerima_kartu_perlindungan_sosial">
                                        {{ $data['merupakan_penerima_kartu_perlindungan_sosial'] }}</td>
                                    <td id="{{ $data['nis'] }}merupakan_penerima_kartu_indonesia_pintar">
                                        {{ $data['merupakan_penerima_kartu_indonesia_pintar'] }}</td>
                                    <td id="{{ $data['nis'] }}layak_pip">{{ $data['layak_pip'] }}</td>
                                    <td id="{{ $data['nis'] }}apakah_berjilbab">
                                        {{ $data['apakah_berjilbab'] }}</td>
                                    <td id="{{ $data['nis'] }}apakah_buta_warna">
                                        {{ $data['apakah_buta_warna'] }}</td>
                                    <td id="{{ $data['nis'] }}golongan_darah">{{ $data['golongan_darah'] }}
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card" style="margin-top: 20px">
            <div class="header">
                <h2>
                    Data Jenis
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive ">
                    <table
                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                        <tbody>
                            <tr>
                                <td><b>Kelas:</b></td>
                                @foreach ($jenis['data_kelas'] as $kelas)
                                    <td>{{ $kelas->nm_kelas }} </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Jalur:</b></td>
                                @foreach ($jenis['data_jalur'] as $jalur)
                                    <td>{{ $jalur->kode_jalur }} </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Status Pengguna:</b></td>
                                @foreach ($jenis['data_status_pengguna'] as $status_pengguna)
                                    <td>{{ $status_pengguna->nm_status_pengguna }} </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Semester Masuk:</b></td>
                                @foreach ($jenis['data_semester_masuk'] as $semester_masuk)
                                    <td>{{ $semester_masuk->kode_semester }} </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Tahun Masuk:</b></td>
                                @foreach ($jenis['data_penerimaan'] as $penerimaan)
                                    <td>{{ $penerimaan->tahun_penerimaan }} </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Agama:</b></td>
                                @foreach ($jenis['data_agama'] as $agama)
                                    <td>{{ $agama->kode_agama }} ({{ $agama->nm_agama }}) </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Kebutuhan Khusus:</b></td>
                                @foreach ($jenis['data_kebutuhan_khusus'] as $kebutuhan_khusus)
                                    <td>{{ $kebutuhan_khusus->kode_kebutuhan_khusus }}
                                        ({{ $kebutuhan_khusus->nm_kebutuhan_khusus }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Jenis Tinggal:</b></td>
                                @foreach ($jenis['data_jenis_tinggal'] as $jenis_tinggal)
                                    <td>{{ $jenis_tinggal->kode_jenis_tinggal }}
                                        ({{ $jenis_tinggal->nm_jenis_tinggal }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Jenis Transportasi:</b></td>
                                @foreach ($jenis['data_jenis_transportasi'] as $jenis_transportasi)
                                    <td>{{ $jenis_transportasi->kode_jenis_transportasi }}
                                        ({{ $jenis_transportasi->nm_jenis_transportasi }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Jenis Layak PIP:</b></td>
                                @foreach ($jenis['data_jenis_layak_pip'] as $jenis_layak_pip)
                                    <td>{{ $jenis_layak_pip->kode_jenis_layak_pip }}
                                        ({{ $jenis_layak_pip->nm_jenis_layak_pip }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Jenis Pendidikan:</b></td>
                                @foreach ($jenis['data_jenis_pendidikan'] as $jenis_pendidikan)
                                    <td>{{ $jenis_pendidikan->kode_jenis_pendidikan }}
                                        ({{ $jenis_pendidikan->nm_jenis_pendidikan }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Jenis Pekerjaan:</b></td>
                                @foreach ($jenis['data_jenis_pekerjaan'] as $jenis_pekerjaan)
                                    <td>{{ $jenis_pekerjaan->kode_jenis_pekerjaan }}
                                        ({{ $jenis_pekerjaan->nm_jenis_pekerjaan }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Jenis Penghasilan:</b></td>
                                @foreach ($jenis['data_jenis_penghasilan'] as $jenis_penghasilan)
                                    <td>{{ $jenis_penghasilan->kode_jenis_penghasilan }}
                                        ({{ $jenis_penghasilan->nm_jenis_penghasilan }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Voucer:</b></td>
                                @foreach ($jenis['data_voucher'] as $voucher)
                                    <td>{{ $voucher->kode_voucher }}
                                        ({{ $voucher->keterangan_voucher }})
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Provinsi:</b></td>
                                @foreach ($jenis['data_provinsi'] as $provinsi)
                                    <td>{{ $provinsi->nm_provinsi }} </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td><b>Kota:</b></td>
                                @foreach ($jenis['data_kota'] as $kota)
                                    <td>{{ $kota->nm_kota }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@include('scriptjs')
<script>
    $(document).ready(function() {
        validasiCekDataSiswa();
    });

    function validasiCekDataSiswa() {
        $.ajax({
            type: "POST",
            url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/cek-data-siswa`,
            success: function(response) {
                const data = JSON.stringify(response['validasi']);
                $.each(response['nis'], function(key, nis) {
                    var cek = false;
                    $.each(response['jenis'], function(key, jenis) {
                        var elements = document.getElementById(nis + jenis);
                        if (elements && response['validasi'][nis][jenis]) {
                            elements.style.backgroundColor = "#fa8d87"
                            cek = true;
                        }
                    })

                    var status = document.getElementById(nis +
                        "status");
                    if (status && response['validasi'][nis]['status']) {
                        status.textContent = response['validasi'][nis]['status'];
                    }

                    var keterangan = document.getElementById(nis +
                        "keterangan");
                    if (keterangan && cek) {
                        keterangan.textContent = 'Belum Benar';
                    } else {
                        keterangan.textContent = 'Benar';
                        keterangan.style.backgroundColor = "#6cf542"
                    }

                })
            }
        });
    }
</script>
