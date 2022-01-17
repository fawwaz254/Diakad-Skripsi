

<meta name="csrf-token" content="{{ csrf_token() }}">
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

                                    @if($r['id_presensi_pengguna']  == '-')
                                    <a href="/humas#absensi/histori-absensi/izin/{{ $id_pengguna }}/{{$r['date']}}/{{ $start_date }}/{{ $end_date }}" class="btn btn-warning">
                                        <i class="material-icons ">edit</i>
                                    </a>
                                    @else

                                    <a href="/humas#absensi/histori-absensi/edit/{{ $r['id_presensi_pengguna'] }}/{{ $start_date }}/{{ $end_date }}" class="btn btn-info">
                                        <i class="material-icons ">edit</i>
                                    </a>
                                  @endif


                                    @if($r['id_presensi_pengguna']  == '-')
                                    @else
                                    {{-- <button onClick="Delete(this.id)" class="btn btn-sm btn-danger" id="{{ $r['id_presensi_pengguna'] }}">
                                        <i class="material-icons">delete</i>
                                    </button> --}}

                                    <button class="deleteRecord" data-id="{{  $r['id_presensi_pengguna'] }}" >Delete Record</button>

                                    {{-- <button onclick="deleteAction(/humas/absensi/histori-absensi/delete/{{ $r['id_presensi_pengguna'] }})">
                                        {{ csrf_field() }}
                                        <i class="material-icons">delete</i>
                                    </button>


                                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                                        '    <i class="material-icons">delete_forever</i>'+
                                        '</button>'; --}}


                                    {{-- <form action="/humas/absensi/histori-absensi/delete/{{ $r['id_presensi_pengguna'] }}/{{ $start_date }}/{{ $end_date }}" method="POST" class="d-inline">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('are you yakin mau menghapus data')"><i class="material-icons">delete</i></button>
                                    </form> --}}
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







    $(".deleteRecord").click(function(){

var id = $(this).data("id");

var token = $("meta[name='csrf-token']").attr("content");


//swall








$.ajax(

{
    
   
    url: " /humas/absensi/histori-absensi/delete/"+id,

    type: 'DELETE',

    data: {

        "id": id,

        "_token": token,

    },

    success: function (){




 swal({
  title: "Delete Succest",
  text: "data berhasil dihapus",
  icon: "warning",});

        location.reload(); 
        // window.location.assign("/humas#absensi/histori-absensi/D4Ka215877868755ea3b47b31d90/2022-01-01/2022-01-31")



    }

});



});



//     function Delete(id)
//         {
//             var id = id;
//             var token = $("meta[name='csrf-token']").attr("content");


//             swal({
//   title: "Are you sure?",
//   text: "Once deleted, you will not be able to recover this imaginary file!",
//   icon: "warning",
//   buttons: true,
// })
// .then((willDelete) => {
//   if (willDelete) {
//     swal("Poof! Your imaginary file has been deleted!", {
//       icon: "success",
//     });
//   } else {
//     swal("Your imaginary file is safe!");
//   }
// });

// //             swal({
// //                 title: "APAKAH KAMU YAKIN ?",
// //                 text: "INGIN MENGHAPUS DATA INI!",
// //                 icon: "warning",
// // //                 buttons: [
// // //                     'TIDAK',
// // //                     'YA'
// // //                 ],
// // //                 dangerMode: true,
// // //             }).then(function(isConfirm) {
// // //                 if (isConfirm) {
// // //                     alert('You clicked the button!')

// // // //                     // //ajax delete
// // // //                     // jQuery.ajax({
                       
// // // //                     //     url: "/humas/absensi/histori-absensi/delete/"+id,
                     
// // // //                     //     data:     {
// // // //                     //         "id": id,
// // // //                     //         "_token": token
// // // //                     //     },
// // // //                     //     type: 'DELETE',
// // // //                     //     success: function (response) {
// // // //                     //         if (response.status == "success") {
// // // //                     //             swal({
// // // //                     //                 title: 'BERHASIL!',
// // // //                     //                 text: 'DATA BERHASIL DIHAPUS!',
// // // //                     //                 icon: 'success',
// // // //                     //                 timer: 1000,
// // // //                     //                 showConfirmButton: false,
// // // //                     //                 showCancelButton: false,
// // // //                     //                 buttons: false,
// // // //                     //             }).then(function() {
// // // //                     //                 location.reload();
// // // //                     //             });
// // // //                     //         }else{
// // // //                     //             swal({
// // // //                     //                 title: 'GAGAL!',
// // // //                     //                 text: 'DATA GAGAL DIHAPUS!',
// // // //                     //                 icon: 'error',
// // // //                     //                 timer: 1000,
// // // //                     //                 showConfirmButton: false,
// // // //                     //                 showCancelButton: false,
// // // //                     //                 buttons: false,
// // // //                     //             }).then(function() {
// // // //                     //                 location.reload();
// // // //                     //             });
// // // //                     //         }
// // // //                     //     }
// // // //                     // });

// // //                 } else {
// // //                     return true;
// // //                 }
// // //             })
// // //         }



</script>