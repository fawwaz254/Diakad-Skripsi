<div class="container-fluid">




    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <button class="btn btn-block bg-red waves-effect" onclick=back()><i class="material-icons">arrow_back</i><span>Kembali</span></button>
        </div>
    </div>

    
    <div class="card">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

       
         
         

         
                <div class="header">
                    <h2>Tambah Shift Pengguna</h2>
                </div>

                <div class="body">

                    <div class="col-sm-6">
                      
                            <table class="table table-bordered" >
                                <tr>
                                    <h4>Pilih Pengguna :</h4>
                                </tr>
                                <tr>
                                <td style="text-align: center;">No</td>
                                <td>Role</td>
                                <td>name</td>

                                </tr>

                                @foreach($penggunas as $key => $pengguna)
                                <tr>
                                
                                <td style="text-align: center;">{{ $key+1 }}</td>
                                <td>{{$pengguna['status_join_table'] == 1 ? 'Pegawai' : 'Guru' }}</td>
                                <td><input type="checkbox" name="{{$pengguna['id_pengguna']}}" id="{{$pengguna['id_pengguna']}}"> <label for="{{$pengguna['id_pengguna']}}">{{$pengguna['nm_pengguna']}} </label></td>


                                </tr>
                                @endforeach


            
                           </table>       
              

                                        </div>
                                        <form method="POST" id="add-form" action="/humas/absensi/shift_pengguna/add">
                                            {{csrf_field()}}
                                        <div class="col-sm-4">

                                    <h4>Pilih Bulan :</h4>
                                    <table class="table" >
                                    <tr>
                                    <td><label for="firstMount" > Bulan Awal :</label></td>
                                        <td>
                                    <select name="firstMount" class="form-control form-control-lg">
                                        <option value="1" selected>Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                    <td> 
                                
                                    </tr>
                                  <tr>
                                    <td> <label for="endMount"> Bulan Akhir :</label></td>
                                    <td><select name="endMount" class="form-control form-control-lg">
                                        <option value="1" selected>Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                    </td>
                                    </tr>

                                </table>
                                    <br>

                                    <h4>Pilih Shift :</h4>
                                    <table class="table">
                                        <tr>
                                        <td>
                                    <label for="senin" > Senin</label></td><td>
                                <select name="senin"  class="form-control form-control-lg">
                                    <option value="0" selected>Libur</option>
                                    @foreach($shifts as $shift)
                                    <option value="{{ $shift['code'] }}" >({{ minimalisTime($shift['start_time']) }} - {{ minimalisTime($shift['end_time']) }}) - {{ $shift['code'] }}</option>
                                
                                        @endforeach
                                    </select>
                                    <td>
                                        </tr>
                                        <tr>
                                            <td>
                                    <label for="selasa">Selasa</label></td><td>
                                        <select name="selasa"  class="form-control form-control-lg">
                                            <option value="0" selected>Libur</option>
                                            @foreach($shifts as $shift)
                                            <option value="{{ $shift['code'] }}" >({{ minimalisTime($shift['start_time']) }} - {{ minimalisTime($shift['end_time']) }}) - {{ $shift['code'] }}</option>
                                        
                                                @endforeach
                                    </select>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td>
                                    <label for="rabu">Rebu</label></td><td>
                                        <select name="rabu"  class="form-control form-control-lg">
                                            <option value="0" selected>Libur</option>
                                            @foreach($shifts as $shift)
                                            <option value="{{ $shift['code'] }}" >( {{ minimalisTime($shift['start_time']) }} - {{ minimalisTime($shift['end_time']) }}) - {{ $shift['code'] }}</option>
                                        
                                                @endforeach
                                    </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                    <label for="kamis">Kamis</label></td><td>
                                        <select name="kamis"  class="form-control form-control-lg">
                                            <option value="0" selected>Libur</option>
                                            @foreach($shifts as $shift)
                                            <option value="{{ $shift['code'] }}" >({{ minimalisTime($shift['start_time']) }} - {{ minimalisTime($shift['end_time']) }}) - {{ $shift['code'] }}</option>
                                        
                                                @endforeach
                                    </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                    <label for="jumat">Jum'at</label></td><td>
                                        <select name="jumat"  class="form-control form-control-lg">
                                            <option value="0" selected>Libur</option>
                                            @foreach($shifts as $shift)
                                            <option value="{{ $shift['code'] }}">({{ minimalisTime($shift['start_time']) }} - {{ minimalisTime($shift['end_time']) }}) - {{ $shift['code'] }}</option>
                                        
                                                @endforeach
                                    </select>
                                        </td>
                                    </tr>
                                <tr>
                                    <td>
                                    <label for="sabtu">Sabtu</label></td><td>
                                        <select name="sabtu"  class="form-control form-control-lg">
                                            <option value="0" selected>Libur</option>
                                            @foreach($shifts as $shift)
                                            <option value="{{ $shift['code'] }}" >({{ minimalisTime($shift['start_time']) }} - {{ minimalisTime($shift['end_time']) }}) - {{ $shift['code'] }}</option>
                                        
                                                @endforeach
                                    </select>
                                    </td>
                                    </tr>
                                    </table>

                                    <button class="btn btn-block bg-green waves-effect" id="btn-submit"><i class="material-icons">save</i><span>Save</span></button>
                      
                                </form>
                                    


                                    {{-- <button class="btn btn-block bg-green waves-effect" id="btn-submit"><i class="material-icons">save</i><span>Save</span></button>
                                        </div>
                         </form> --}}
                </div>

                </div>



            </div>
        </div>
    </div>

</div>

@include('scriptjs')
<script>
$( "#add-form" ).submit(function() {
    $('#btn-submit').attr("disabled", true);
    $('#btn-submit i').text('autorenew')
    $('#btn-submit span').text('Loading')
});

function back(){
        window.location='/humas#absensi/shift_pengguna'
    }
</script>