@extends('layout')

@section('content')

  <body class="hipENGAGE">

    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">Event Manager</h1>
          <form role="form">
              <div class="form-group">
                <input class="form-control" id="exampleInputEmail1" placeholder="start typing event name to filter" type="text">
              </div>
            </form>
            <br>
            <div class="row">
              <div class="col-md-12">
              <a href="{{ url('hipengage_addevent'); }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Event</a>
                  <!-- <h3 class="mod-title">Events</h3> -->
                    <!-- <div class="table-responsive table-campaignManagement"> -->

                <a id="buildtable"></a>
                <div class="table-responsive">
                  <table id="eventTable" class="table table-striped"> </table>
                </div>
            <!-- </div> -->
                </div>
            </div>        
        
        </div>

      </div>
    </div>


    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/prefixfree.min.js"></script> 

    <script>

    eventsJason = {{ $data['eventsJason'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        console.log(eventsJason);
      });

      $(document).delegate('#buildtable', 'click', function() {
        showEventsTable(eventsJason);
      });

      function showEventsTable(eventsJason) {
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Event Name</th>\n\
                      <th>Brand</th>\n\
                      <th>Schedule</th>\n\
                      <th>Status</th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(eventsJason, function(index, value) {
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["name"]  + ' </td>\n\
                      <td> ' + value["brandname"]  + '</td>\n\
                      <td>' + value["schedulebegin"] + value["scheduleend"]  + '</td>\n\
                      <td> ' + value["status"]  + '</td>\n\
                      <td><a href="{{ url('hipengage_editevent'); }}/' + value["id"] + '" class="btn btn-default btn-sm">edit</a>\n\
                          <a class="btn btn-default btn-delete btn-sm" data-eventid = ' + value["id"] + ' href="#">delete</a>\n\
                      </td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#eventTable" ).html( table );
      }

        $(document).delegate('.btn-delete', 'click', function() {
        var eventid = this.getAttribute('data-eventid');
        swal({
          title: "Are you sure?",
          text: "Are you sure you want to delete this event?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: '#DD6B55',
          confirmButtonText: 'Yes, delete it!',
          closeOnConfirm: false,
          //closeOnCancel: false
        },
          function(){
            swal("Deleted!", "Event has been deleted!", "success");
            console.log('Deleting eventid = ' + eventid)
            $.ajax({
              type: "GET",
              dataType: 'json',
              contentType: "application/json",
              url: "{{ url('hipengage_deleteevent/" + eventid + "'); }}",
              success: function(events) {
                var eventsjson = JSON.parse(events); 
                showEventsTable(eventsjson);
              }
            });
          });
      });
  
    </script>

  </body>
@stop
