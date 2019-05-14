@extends('layout')

@section('content')

<body class="hipWifi">
<div class="container-fluid">
  <div class="row">
    @include('hipwifi.sidebar')

<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <a id="buildtable"></a>
            <h1 class="page-header">Statistics</h1>

            <form class="form-inline" role="form" style="margin-bottom: 15px;">
              <div class="form-group">
                <input type="text" class="form-control" name="sitename" id="sitename" placeholder="Venue Name">
              </div>

              <div class="form-group">
                {{ Form::text('date', null, 
                array('name' => 'from', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", 'class' => 'form-control datepicker','placeholder' => 'From Date', 'id' => 'from')) }}
              </div>

              <div class="form-group">
                {{ Form::text('date', null, array('name' => 'to', 'type' => 'text', 'data-date-format' => "dd/mm/yyyy", 'class' => 'form-control datepicker','placeholder' => 'To Date', 'id' => 'to')) }}
              </div>

              <button id="filter" type="submit" class="btn btn-primary">Filter</button>
              <button id="reset" type="submit" class="btn btn-default">Reset</button>

            </form>
            
            <div class="table-responsive">
            <div class="table-responsive">
                <table id="venueTable" class="table table-striped"> </table>
            </div>
          </div>  

        </div>
    
  </div>
</div>

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/bootstrap-datepicker.js"></script> 
<script src="js/prefixfree.min.js"></script>



  <script>

      statsdata = {{ $data['statsdata'] }};

      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });

      $(document).delegate('#buildtable', 'click', function() {
        showStatsTable(statsdata);
      });

      $(document).delegate('#reset', 'click', function() {
        showStatsTable(statsdata);
      });

      $(document).delegate('#filter', 'click', function(event) {

        event.preventDefault();
        console.log("In Filter")

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
              'sitename': $('#sitename').val(), 
              'from': $('#from').val(), 
              'to': $('#to').val()
            },
              url: "{{ url('hipwifi_showstatistics/1'); }}",
              success: function(filteredstatsdata) {
              var fsd = JSON.parse(filteredstatsdata);
              showStatsTable(fsd);
            }
         });
      });

      function showStatsTable(venuestatsdata) {
        console.log("In showStatsTable");
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Venue</th>\n\
                      <th>Total Sessions</th>\n\
                      <th>Unique Users</th>\n\
                      <th>New Users</th>\n\
                      <th>Dwell Time</th>\n\
                      <th>Data Used</th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuestatsdata['venues'], function(index, value) {
            rows = rows + '\
                    <tr>\n\
                      <td> ' + index  + '</td>\n\
                      <td> ' + value["totalsessions"]  + '</td>\n\
                      <td> ' + value["uniqueusers"] + '</td>\n\
                      <td> ' + value["newusers"] + '</td>\n\
                      <td> ' + value["dwelltime"] + '</td>\n\
                      <td> ' + value["dataused"] + '</td>\n\
                    </tr>\n\
                    ';
        });
        totals = '\
                    <tr class="totals">\n\
                      <td>Total</td>\n\
                      <td> ' + venuestatsdata["totalsessionstotal"]  + '</td>\n\
                      <td> ' + venuestatsdata["uniqueuserstotal"] + '</td>\n\
                      <td> ' + venuestatsdata["newuserstotal"] + '</td>\n\
                      <td> ' + venuestatsdata["dwelltimetotalaverage"] + '</td>\n\
                      <td> ' + venuestatsdata["datausedtotal"] + '</td>\n\
                    </tr>\n\
                    ';

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + totals + endTable;
        $( "#venueTable" ).html( table );
      }

  
    </script>


</body>
@stop
