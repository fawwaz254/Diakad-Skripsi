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
                            <label>Start Date</label>
                            <input type="date" class="form-control" value="{{$start_date}}" name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-5">
                            <label>End Date</label>
                            <input type="date" class="form-control" value="{{$end_date}}" name="end_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;" onclick="filterAction()">Change Date</button>  
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
                    <h2>Histori Absensi</h2>
                </div>

                <div class="body">
                    
                     <table class="table table-bordered">
                        <thead style="background:#9C27B0;color:white">
                            <tr>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil as $key => $r)
                            @if($key%2==1)
                            <tr style="background: #DDA0DD">
                            @else
                            <tr>
                            @endif
                                <td>{{$r['tanggal']}}</td>
                                <td>{{$r['hari']}}</td>
                                <td>{{$r['check_in']}}</td>
                                <td>{{$r['check_out']}}</td>
                                <td>{{$r['status']}}</td>
                                <td>{{$r['notes']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    
    function filterAction(){
        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('input[name=start_date]').val() + '/' + $('input[name=end_date]').val());
    }

</script>