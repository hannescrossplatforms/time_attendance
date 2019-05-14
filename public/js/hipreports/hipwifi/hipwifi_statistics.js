
      // $('.datepicker').datepicker({
      //   format: 'yyyy-mm-dd',
      //   autoclose: true,
      //   orientation: "bottom"
      // });

      // statsdata = {{ $data['statsdata'] }};

      $(function() {
      	// alert("statsdata : " + statsdata);
      	console.log(statsdata);
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load

      });

      $(document).delegate('#buildtable', 'click', function() {
        showStatsTable(statsdata);
      });

      $(document).delegate('#reset', 'click', function() {
        // alert("booboo");
        showStatsTable(statsdata);
      });

      $(document).delegate('#filter', 'click', function(event) {

        event.preventDefault();
        console.log("In Filter");

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
              'sitename': $('#filtresitename').val(), 
              'from': $('#from').val(), 
              'to': $('#to').val()
            },
              url: statsurl,
              success: function(filteredstatsdata) {
              console.log(filteredstatsdata);
              // var fsd = JSON.parse(filteredstatsdata);
              showStatsTable(filteredstatsdata);
            }
         });
      });

      function showStatsForBrand() {

          $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
              'sitename': $('#sitename').val(), 
              'from': $('#from').val(), 
              'to': $('#to').val(),
              'brand_id': $('#brandlist').val()
            },
              url: statsurl,
              success: function(filteredstatsdata) {
              console.log(filteredstatsdata);
              // var fsd = JSON.parse(filteredstatsdata);
              showStatsTable(filteredstatsdata);
            }
         });
      }

      function showStatsTable(venuestatsdata) {
        console.log("In showStatsTable : " + venuestatsdata);
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
        $( "#statsTable" ).html( table );
      }

  
