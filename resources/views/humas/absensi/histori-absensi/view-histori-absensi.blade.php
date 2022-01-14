<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
                        <h2 class="card-inside-title">
                            Pilih Guru
                        </h2>
                        <select class="form-control show-tick" id="id_pengguna" name="id_pengguna" required="">
                            <option value="0">Pilih Guru</option>
                            @foreach($guru as $r)
                                <option value="{{$r->id_pengguna}}" {{$r->id_pengguna == $id_pengguna ? 'selected' : ''}}>{{$r->nm_pengguna}}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>

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
                                <th style="text-align: center;">Tanggal</th>
                                <th>Hari</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Notes</th>
                        
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
                                <td style="text-align: center;">{{$r['tanggal']}}</td>
                                <td>{{$r['hari']}}</td>
                                <td>{{$r['check_in']}}</td>
                                <td>{{$r['check_out']}}</td>
                                <td>{{$r['status']}}</td>
                                <td>{{$r['notes']}}</td>
                              
                                
                             
                                <td style="text-align: center ; display: flex; justify-content: space-around;">
                                     
                                    <a href="/humas#absensi/histori-absensi/edit/{{ $r['id_presensi_pengguna'] }}/{{ $start_date }}/{{ $end_date }}" class="btn btn-info">
                                        <i class="material-icons ">edit</i>
                                    </a>
                                  
                                    @if($r['id_presensi_pengguna']  == '-')
                                    @else
                                    <form action="/humas/absensi/histori-absensi/delete/{{ $r['id_presensi_pengguna'] }}/{{ $start_date }}/{{ $end_date }}" method="POST" class="d-inline">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('are you yakin mau menghapus data')"><i class="material-icons">delete</i></button>
                                    </form>
                                    @endif
                                </td>
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
        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('#id_pengguna').val()  + '/' + $('input[name=start_date]').val() + '/' + $('input[name=end_date]').val());
    };



//     // var modul_url = location.hash.replace('#','').split('/')[0];
//     var modul_url       = 'data-sarpras-gedung';
//     var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'gedung/datatables';
//     var edit_url        = role_url + '#' + modul_url + '/' + 'gedung/edit';
//     var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-gedung/delete';

//     var primary_table = $('#primary_table').DataTable({
//         processing: true,
//         serverSide: true,
// responsive: true,
//         ajax: {
//             url: datatable_url,
//             type: 'GET'
//         },
//         columns: [
//             { data: null, searchable: false, orderable: false },
//             { data: 'nm_jenis_gedung', name: 'nm_jenis_gedung' },
//             { data: 'kode_gedung', name: 'kode_gedung' },
//             { data: 'nm_gedung', name: 'nm_gedung' },
//             { data: 'lokasi_gedung', name: 'lokasi_gedung' },
//             { data: 'deskripsi_gedung', name: 'deskripsi_gedung' },
//             { data: 'action', name: 'action', searchable: false, orderable: false,
//                 render: function(data){
//                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
//                     '    <i class="material-icons">edit</i>'+
//                     '</a> '+
//                     '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
//                     '    <i class="material-icons">delete_forever</i>'+
//                     '</button>';
//                 }
//             }
//         ]
//     });
























// function deleteAction(){

// }



//  $(".deleteRecord").click(function(){

// var id = $(this).data("id_presensi_pengguna");

// var token = $("meta[name='csrf-token']").attr("content");



// $.ajax(

// {

//     url: "delete/"+id,

//     type: 'DELETE',

//     data: {

//         "id": id,

//         // "_token": token,

//     },

//     success: function (){

//         console.log("it Works");

//     }

// });



// });


</script>