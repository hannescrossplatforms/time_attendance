      $(function() {
        $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      });

      $(document).delegate('#buildtable', 'click', function() {
        showVenuesTable(venuesJason);
      });

      $( document ).on( 'click', '.btn-showvenue', function () {
        window.venue_id = this.getAttribute('data-venueid');
        $( "#venuereportperiod" ).change();
      });


      $(document).delegate('#reset', 'click', function() {
        showVenuesTable(venuesJason);
      });

      $(document).delegate('#filter', 'click', function(event) {

        event.preventDefault();

        sitename = $( "#src-sitename" ).val();
        macaddress = $( "#src-macaddress" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
              'sitename': sitename, 
              'macaddress': macaddress 
            },
            url: filterdvenuesurl,
            success: function(filteredVenuesjson) {
              // alert("success");
              showVenuesTable(filteredVenuesjson);
            }
         });
      });

      function showVenuesTable(venuesjson) {
        // alert("showVenuesTable");
        table = '';
        rows = '';
        beginTable = '\
                <table class="table table-striped">\n\
                  <thead>\n\
                    <tr>\n\
                      <th>Venue</th>\n\
                      <th>Macaddress</th>\n\
                      <th>Location</th>\n\
                      <th>Server</th>\n\
                      <th>Contact</th>\n\
                      <th>\n\
                      </th>\n\
                    </tr>\n\
                  </thead>\n\
                  <tbody>  \n';
        $.each(venuesjson, function(index, value) {
            rows = rows + '\
                    <tr>\n\
                      <td> ' + value["sitename"]  + '</td>\n\
                      <td> ' + value["macaddress"]  + '</td>\n\
                      <td> ' + value["location"]  + '</td>\n\
                      <td> ' + value["hostname"]  + '</td>\n\
                      <td> ' + value["contact"]  + '</td>\n\
                      <td> <a id="xxxx" href="#showvenue" role="tab" data-toggle="tab" class="btn btn-default btn-sm btn-showvenue"  data-venueid = ' + value["id"] + ' >Show Venue </a></td>\n\
                    </tr>\n\
                    ';
        });

        endTable = ' \
                  </tbody>\n\
                </table>';

        table = beginTable + rows + endTable;
        $( "#venueTable" ).html( table );
      }