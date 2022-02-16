<div class="container-fluid">




    

  

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

       
         
         

            <div class="card">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-5">
                            <label>Date</label>
                            <input type="date" class="form-control" value="{{$date}}" name="date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2" style="display: flex;" >
                            <div>
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;"
                                onclick="filterAction()">Change Date</button>
                            </div>
                            
                            </div>

                        
                                                   
                            
                              

                    </div>

                </div>

            </div>
        </div>
    </div>

    <br>
    <div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <button class="btn btn-block bg-red waves-effect" onclick=addAbsensi()><i class="material-icons">add</i><span>Add Shift Pengguna</span></button>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <button class="btn btn-block bg-red waves-effect" onclick=managementShift()><i class="material-icons">settings</i><span>Management Shift</span></button>
        </div>
    </div>
    <br>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

              

                <div class="header" >
                    <h2>Shift Pengguna   </h2>
                    
                    
                   
                </div>

                <div class="body">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background:#9C27B0;color:white">
                            <tr>
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">Nama</th>
                                <th style="text-align: center;">Role</th>
                     
                              
                              
                                <th style="text-align: center;">Shift</th>
                                <th style="text-align: center;">Time</th>
                                <th style="text-align: center;">Action</th>
                               
                          
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil as $key => $r)
                            @if($key%2==1)
                            <tr style="background: #DDA0DD">
                                @else
                            <tr>
                                @endif
                          
                                <td style="text-align: center;">{{ $key+1 }}</td>
                                <td >{{$r['nm_pengguna']}}</td>
                                <td style="text-align: center;">{{$r['status_join_table'] == 1 ? 'Pegawai' : 'Guru' }}</td>
                                <td style="text-align: center;">{{ $r['id_shift_master'] }}</td>
                                <td style="text-align: center;">{{ $r['time'] }}</td>
                                    
                                    <td style="text-align: center;display:flex;justify-content:center">
                                    @if ($r['id_shift_master'] =='-')
                                    -
                                    @else
                                  
                                    <button type="button" class="btn bg-teal  waves-effect" onclick="editAbsensi('{{$r['id_shift_pengguna']}}')">
                                        <i class="material-icons">edit</i>
                                    </button>
                                   
                                   

                                    @endif

                                
                                    
                                    
                                    {{-- {{  $r['id_shift_pengguna']  }}</td> --}}
                             
                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>

            </div>
        </div>
    </div>

</div>
@include('scriptjs')
<script>


function filterAction(){
        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('input[name=date]').val());
    }
    function editAbsensi(currUser){
        window.location='/humas#absensi/shift_pengguna/' + currUser + '/' + $('input[name=date]').val()+'/edit'
    }

    function managementShift(){
        window.location='/humas#absensi/shift_pengguna/managementShift'
    }

    function addAbsensi(){
        window.location='/humas#absensi/shift_pengguna/add'
    }
</script>