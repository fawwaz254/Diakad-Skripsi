<style>
    input {
        position: relative;
        width: 150px;
        height: 20px;
        color: white;
    }

    input:before {
        position: absolute;
        top: 3px;
        left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    input::-webkit-datetime-edit,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button {
        display: none;
    }

    input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        Frequently Asked Questions
                    </h2>
                </div>
                <form id="form-validation1" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/lihat-faq/search') }}" method="GET">
                  <div class="col-12 col-md-2 m-3">
                    <input type="text" class="form-control border-0 rounded-pill shadow-sm" id="search" name="search"
                        placeholder="Cari Pertanyaan">
                  </div>
                  <div class="col-md-2 m-3">
                    <button type="submit"
                        class="btn bg-primary border-0 rounded-pill shadow-sm w-100 text-white">Cari</button>
                  </div>
                </form>
                <div class="body">
                    <div class="accordion" id="accordionExample">

                        @foreach ($faq as $key=>$item)    
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                              <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                                {{$item->question}}
                              </button>
                            </h2>
                          </div>
                      
                          <div id="collapse{{$key}}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body" style="padding: 3rem">
                                {!! $item->answer !!}
                            </div>
                          </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<script>
  var primary_table = null;
  $('#form-validation1').validate({
      rules: {
          'checkbox': {
              required: true
          },
          'gender': {
              required: true
          }
      },
      highlight: function (input) {
          $(input).parents('.form-line').addClass('error');
      },
      unhighlight: function (input) {
          $(input).parents('.form-line').removeClass('error');
      },
      errorPlacement: function (error, element) {
          $(element).parents('.form-group').append(error);
      },
      submitHandler: function(form) {
          $('button').attr('disabled', 'disabled');
          $.ajax({
              url: form.action,
              type: form.method,
              data: $(form).serialize(),
              success: function(response) {
                  if(response.status == 200){
                      vex.dialog.alert(response.message);
                  }else if(response.status == 201){
                      vex.dialog.alert(response.message);
                      window.location.href = response.link;
                  }else if(response.status == 202){
                      // vex.dialog.alert(response.message);
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
                  $('button').removeAttr('disabled', 'disabled');
              }
          });
      }
  });
</script>
