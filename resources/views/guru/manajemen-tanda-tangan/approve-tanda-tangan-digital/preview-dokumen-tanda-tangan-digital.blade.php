<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $dokumen_tanda_tangan_digital->perihal_dokumen }}</title>
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
</head>
{{-- @dd($auth_data->sekolah_data->kota->nm_kota) --}}

<body>
    @php
        $tanggal_surat = \Carbon\Carbon::create($dokumen_tanda_tangan_digital->created_at)->locale('id_ID');
    @endphp
    <div class="container">
        <div style="display: flex;justify-content: space-between;align-items:center">
            <img style="width: 150px;height:150px;object-fit:cover"
                src="{{ asset('media/logo-sidoarjo-hitam-putih.jpg') }}" alt="">
            <div style="text-align: center">
                <h3>PEMERINTAHAN KABUPATEN SIDOARJO</h3>
                <h3>DINAS PENDIDIKAN DAN KEBUDAYAAN</h3>
                <div>
                    <p>{{ $auth_data->sekolah_data->alamat_jalan }} {{ $auth_data->sekolah_data->kota->nm_kota }}
                        Telepon {{ $auth_data->sekolah_data->nomor_telp_sekolah }}</p>
                    <span> <i>Email :</i> <span
                            style="color: rgb(41, 41, 242)"><u>{{ $auth_data->sekolah_data->email_sekolah }}</u></span>
                        <i>Website:</i> <span
                            style="color: rgb(41, 41, 242)"><u>{{ $auth_data->sekolah_data->website_sekolah }}</u></span>
                    </span>
                </div>
            </div>
        </div>
        <hr>
        <table style="width: 100%;">
            <tr>
            <tr>
                <td style="width: 5%">Nomor:</td>
                <td>
                    {{ $dokumen_tanda_tangan_digital->no_dokumen }}/{{ numberToRomanRepresentation($tanggal_surat->month) }}/F/{{ strtoupper($auth_data->sekolah_data->nm_singkat_sekolah) }}/{{ $tanggal_surat->year }}
                </td>
                <td style="text-align: right;">Sidoarjo, {{ $tanggal_surat->format('d F Y') }}</td>
            </tr>
            <tr>
                <td style="width: 5%">Hal:</td>
                <td>
                    {{ $dokumen_tanda_tangan_digital->perihal_dokumen }}
                </td>
            </tr>
        </table>
        <div class="container" style="margin-top:40px;margin-bottom:80px">
            {!! $dokumen_tanda_tangan_digital->isi_dokumen !!}
        </div>
        <div style="display: flex; justify-content: flex-end">
            <div>
                <h4>KEPALA DINAS PENDIDIKAN DAN KEBUDAYAAN</h4>
                <div style="display: flex;">
                    {!! QrCode::size(100)->generate(Storage::disk('spaces')->url($dokumen_tanda_tangan_digital->link_dokumen)) !!}
                    <div style="margin-left:15px;">
                        <p>
                            Ditandatangani secara elektronik oleh
                        </p>
                        <p>
                            {{ $auth_data->pengguna->gelar_depan }} {{ $auth_data->pengguna->nm_pengguna }}
                            {{ $auth_data->pengguna->gelar_belakang }}
                        </p>
                        <p>
                            NIP: {{ $auth_data->pengguna->username }}
                        </p>
                    </div>
                </div>
                <div>
                    <h4>
                        <u>{{ $auth_data->pengguna->gelar_depan }}
                            {{ $auth_data->pengguna->nm_pengguna }} {{ $auth_data->pengguna->gelar_belakang }}</u>
                    </h4>
                    <h4>
                        NIP: {{ $auth_data->pengguna->username }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
    <script>
        window.print();
    </script>
</body>

</html>
