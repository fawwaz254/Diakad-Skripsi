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

                        <div class="col-md-5">
                            <label>Date</label>
                            <input type="date" class="form-control" name="date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;"
                                onclick="filterAction()">Change Date</button>
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
                    <h2>Manajemen Hari Libur</h2>
                </div>

                <div class="body">
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead style="background:#9C27B0;color:white">
                            <tr>
                                <th>No</th>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Date</th>
                                <th>Explanation</th>
                                <th>Extra Money</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($date as $key => $d)
                            @if($key%2==1)
                            <tr style="background: #DDA0DD">
                                @else
                            <tr>
                                @endif
                                <td>{{ $loop->iteration }}</td>
                                <td>{{$d['year']}}</td>
                                <td>{{$d['month']}}</td>
                                <td>{{$d['date']}}</td>
                                <td>{{$d['explanation']}}</td>
                                <td>{{$d['extra_money']}}</td>
                                <td style="text-align: center;display:flex;justify-content:center">
                                    @if (!$d['manajemen_hari_libur_id'])
                                    <button type="button" class="btn bg-teal waves-effect" onclick="addAbsensi('{{$d['date_value']}}')">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    @else
                                    <button type="button" class="btn bg-teal waves-effect" onclick="editAbsensi('{{$d['date_value']}}')">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button data-date={{$d['date_value']}} style="margin-left:3px;" class="btn bg-red waves-effect delete-record">
                                        <i class="material-icons">delete</i>
                                    </button>
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

</div>
<script type="text/javascript">

    function filterAction(){
        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('input[name=date]').val());
    }

    function addAbsensi(date){
        window.location='/humas#absensi/manajemen-hari-libur/' + date + '/add'
    }

    function editAbsensi(date){
        window.location='/humas#absensi/manajemen-hari-libur/' + date + '/edit'
    }

  
    $(".delete-record").click(function () {
        const token = $("meta[name='csrf-token']").attr("content");
        const date= $(this).data("date");
        swal(
        { title: "Are you sure?", showCancelButton: true},
        function (isConfirm) {
            if (isConfirm) {
                $('.delete-record').attr("disabled", true);
                //swall
                $.ajax({
                    url: ` /humas/absensi/manajemen-hari-libur/${date}/delete`,
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