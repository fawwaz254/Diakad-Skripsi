<style>
    #disable,
    textarea {
        background-color: #d1d1d1;
    }
</style>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#rapor-sisipan/daftar-nilai-sas') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <form id="form-validation" method="POST"
        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/daftar-nilai-sas/action-input-nilai-rapor-sisipan/save/' . $id_rapor_sisipan) }}">
        {{ csrf_field() }}
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>INPUT NILAI RAPOR SISIPAN {{ $rapor_sisipan->kelas->nm_kelas }}
                            ({{ $rapor_sisipan->mata_pelajaran->nm_mata_pelajaran }})</h2>

                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th
                                            style="text-align: center;
                                            vertical-align: middle;">
                                            No</th>
                                        <th
                                            style="text-align: center;
                                            vertical-align: middle;">
                                            NIS - Nama Siswa</th>
                                        @foreach ($list_data as $data)
                                            {{-- @if ($data->nm_nilai == 'SAS')
                                                <th style="text-align: center; vertical-align: middle; width:60px">
                                                @else --}}
                                            <th style="text-align: center; vertical-align: middle;">
                                                {{-- @endif --}}
                                                {{ $data->nm_nilai }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 0;
                                    @endphp
                                    @foreach ($list_siswa as $siswa)
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $siswa->nis_siswa }} - {{ $siswa->pengguna->nm_pengguna }}</td>
                                            @foreach ($list_data as $nilai)
                                                <td style="text-align: center;">
                                                    {{-- @if (isset($nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan]) && $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] != '0')
                                                        <input type="number" id="disable"
                                                            name="nilai[{{ $nilai->id_komponen_nilai }}-{{ $siswa->id_siswa }}-{{ $id_rapor_sisipan }}]"
                                                            value="{{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}"
                                                            style="width: 80%; text-align:  center;" disabled>
                                                    @else --}}
                                                    <input type="number"
                                                        name="nilai[{{ $nilai->id_komponen_nilai }}-{{ $siswa->id_siswa }}-{{ $id_rapor_sisipan }}]"
                                                        value="{{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}"
                                                        style="width: 80%; text-align:  center;">
                                                    {{-- @endif --}}
                                                </td>
                                            @endforeach
                                            {{-- <td style="text-align: center;">
                                                <input type="number" id="sas"
                                                    value="{{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2']) / 2) }}"
                                                    style="width: 80%; text-align:  center;" disabled>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" id="rt2smt"
                                                    value="{{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . 'sas']) / 3) }}"
                                                    style="width: 80%; text-align:  center;" disabled>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="block-header">
                            <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                    class="material-icons">save</i><span>Save</span></button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
</div>
</form>
</div>
@include('scriptjs')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#primary_table').DataTable({
            paging: false
        });
    });
</script>
