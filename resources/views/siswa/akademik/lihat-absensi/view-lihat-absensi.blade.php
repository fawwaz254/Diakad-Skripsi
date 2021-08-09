<link href="{{asset('fullcalendar-5.9.0/lib/main.css')}}" rel='stylesheet' />

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>PRESENSI</h2>
                    </div>
                    <div class="body">
                            
                        <div id='calendar'></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('fullcalendar-5.9.0/lib/main.js')}}"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });
</script>

