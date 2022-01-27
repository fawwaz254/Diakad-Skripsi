<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header">
                    <h2>Manajemen Hari Libur</h2>
                </div>

                <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <button class="btn btn-block bg-red waves-effect" onclick=addAbsensi()><i class="material-icons">add</i><span>Add Holiday</span></button>
                            </div>
                        </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" id="primary-table">
                        <thead style="background:#9C27B0;color:white">
                            <tr>
                                <th>No</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Date</th>
                                <th>Explanation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidays as $key => $holiday)
                            @if($key%2==1)
                            <tr style="background: #DDA0DD">
                                @else
                            <tr>
                                @endif
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$holiday['year']}}</td>
                                <td>{{$holiday['month']}}</td>
                                <td>{{$holiday['date']}}</td>
                                <td>{{$holiday['explanation']}}</td>
                                <td style="text-align: center;display:flex;justify-content:center">
                                    <button type="button" class="btn bg-teal waves-effect" onclick="editAbsensi('{{$holiday['id']}}')">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button data-id={{$holiday['id']}} style="margin-left:3px;" class="btn bg-red waves-effect delete-record">
                                        <i class="material-icons">delete</i>
                                    </button>
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

</div>
<script type="text/javascript">
$(document).ready(function () {
    $('#primary-table').DataTable();
})

    function addAbsensi(){
        window.location='/humas#absensi/manajemen-hari-libur/add'
    }

    function editAbsensi(id){
        window.location='/humas#absensi/manajemen-hari-libur/' + id + '/edit'
    }

  
    $(".delete-record").click(function () {
        const token = $("meta[name='csrf-token']").attr("content");
        const id= $(this).data("id");
        swal(
        { title: "Are you sure?", showCancelButton: true},
        function (isConfirm) {
            if (isConfirm) {
                $('.delete-record').attr("disabled", true);
                //swall
                $.ajax({
                    url: ` /humas/absensi/manajemen-hari-libur/${id}/delete`,
                    type: "post",

                    data: {
                        _token: token,
                    },

                    success: function () {
                        swal({
                            title: "Delete Success",
                            text: "data berhasil dihapus",
                            icon: "success",
                        });
                        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}');
                    },
                });
            }
            return;
        }
    );
});

</script>