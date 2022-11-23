<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $dokumen_tanda_tangan_digital->perihal_dokumen }}</title>
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
</head>

<body>
    @php
        $tanggal_surat = \Carbon\Carbon::create($dokumen_tanda_tangan_digital->created_at)->locale('id_ID');
    @endphp
    <div class="container">
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
                    @if ($dokumen_tanda_tangan_digital->is_approve)
                        {!! QrCode::size(100)->generate(Storage::disk('spaces')->url($dokumen_tanda_tangan_digital->link_dokumen)) !!}
                    @endif
                    <div style="margin-left:15px;">
                        <p>
                            Ditandatangani secara elektronik oleh
                        </p>
                        <p>
                            {{ $kepala_sekolah->guru[0]->pengguna->gelar_depan }}
                            {{ $kepala_sekolah->guru[0]->pengguna->nm_pengguna }}
                            {{ $kepala_sekolah->guru[0]->pengguna->gelar_belakang }}
                        </p>
                        <p>
                            NIP: {{ $kepala_sekolah->guru[0]->pengguna->username }}
                        </p>
                    </div>
                </div>
                <div>
                    <h4>
                        <u>{{ $kepala_sekolah->guru[0]->pengguna->gelar_depan }}
                            {{ $kepala_sekolah->guru[0]->pengguna->nm_pengguna }}
                            {{ $kepala_sekolah->guru[0]->pengguna->gelar_belakang }}</u>
                    </h4>
                    <h4>
                        NIP: {{ $kepala_sekolah->guru[0]->pengguna->username }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
</body>

</html>
