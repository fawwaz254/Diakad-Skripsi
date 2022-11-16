<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-default"  onclick="viewShiftPengguna()">
                Data Shift Guru dan Pegawai
            </button>
            <button type="button" class="btn btn-primary"  >
                Data Shift Siswa
            </button>
            <div class="card" style="margin-top: 10px">
                <div class="header" >
                    <h2>Filter Data</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <label>
                                Kelas
                            </label>
                            <select class="form-control show-tick" name="kelas" >
                                <option>Pilih kelas</option>
                                <option value="0" @if($id_kelas == "0") selected @endif>Semua</option>
                                @foreach ($kelas as $lk)
                                    <option value="{{ $lk->id_kelas }}" @if($id_kelas == $lk->id_kelas) selected @endif >{{ $lk->nm_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <label>Date</label>
                            <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                value="{{ $date }}" name="date" aria-required="true" aria-invalid="true">
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;" onclick="filterAction()">Lihat </button>  
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <br>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Shift Siswa</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">Nama</th>
                                    <th style="text-align: center;">Kelas</th>
                                    <th style="text-align: center;">Shift</th>
                                    <th style="text-align: center;">Time</th>
                                    {{-- <th style="text-align: center;">Action</th> --}}
                                </tr>
                            </thead>
                            @php
                            $no = 1;   
                           @endphp
                            @foreach ($hasil as $key => $r)
                                @if ($no % 2 == 1)
                                    <tr style="background: #DDA0DD">
                                    @else
                                    <tr>
                                @endif

                             
                                  
                                    <td style="text-align: center;">{{ $no++ }}</td>
                                    <td>{{ $r['nm_pengguna'] }}</td>
                                    <td style="text-align: center;">{{ $r['kelas'] }}</td>
                                    <td style="text-align: center;">{{ $r['id_shift_master'] }}</td>
                                    <td style="text-align: center;">{{ $r['time'] }}</td>
                                    {{-- <td style="text-align: center;display:flex;justify-content:center">
                                        @if ($r['id_shift_master'] == '-')
                                            -
                                        @else
                                            <button type="button" class="btn bg-teal  waves-effect"
                                                onclick="editAbsensi('{{ $r['id_shift_pengguna'] }}')">
                                                <i class="material-icons">edit</i>
                                            </button>
                                        @endif --}}
                                        </tr>
                            @endforeach
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@include('scriptjs')
<script>
    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/view-kelas-shift-siswa/' + 
            $('select[name=kelas]').val() + '/' +  $('input[name=date]').val());
    }

    function viewShiftPengguna() {
        window.location = '/humas#absensi/shift_pengguna'
    }
    </script>