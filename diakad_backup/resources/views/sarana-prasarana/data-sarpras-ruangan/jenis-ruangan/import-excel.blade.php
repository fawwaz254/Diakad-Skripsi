<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3))}}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        IMPORT EXCEL JENIS RUANGAN
                    </h2>
                </div>
                <div class="body">

                    <form id="form-upload" action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3). '/import-excel')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                   
                    <div class="row clearfix">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <a class="btn btn-success" href="{{asset('template-jenis-ruangan.xlsx')}}">Download Template Excel</a>
                        </div>
                    </div>

                    <div class="row clearfix">
                        
                        <div class="col-md-12">
                            <label>File Excel</label>
                            <input type="file" required="" name="file-excel" id="file-excel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xls" class="form-control" aria-describedby="emailHelp">
                        </div>

                    </div>

                    <div class="row clearfix">

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <button class="btn btn-block bg-red waves-effect" type="submit">
                                <span>Submit</span>
                            </button>
                        </div>

                    </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function (input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function (input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            
            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                        }else if(response.status == 201){
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        }else if(response.status == 202){
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        }else if(response.status == 203){
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        }else if(response.status == 204){
                            loadURI(response.path);
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });
                
            }, 1000);
        }
    });
</script>
