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
                            <input type="date" class="form-control" value="" name="date"
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
                                <th>Role</th>
                     
                              
                              
                                <th>Shift</th>
                                <th>Action</th>
                               
                          
                              
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($hasil as $key => $r)
                            @if($key%2==1)
                            <tr style="background: #DDA0DD">
                                @else
                            <tr>
                                @endif --}}
                          
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                             
                               
                            </tr>
                     
                        </tbody>
                    </table>
                </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
    function addAbsensi(){
        window.location='/humas#absensi/shift_pengguna/add'
    }
</script>