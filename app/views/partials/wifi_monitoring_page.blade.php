<div class="form-group monitoring-form">
    <form class="form-inline" role="form" style="margin-bottom: 15px;">
        <label  class="sr-only" for="exampleInputEmail2">Site Name</label>
        <input type="text" class="form-control" id="src-sitename" placeholder="Site Name">
        <!-- <button id="filter" type="submit" class="btn btn-primary">Filter</button>
        <button id="reset" type="submit" class="btn btn-default">Reset</button> -->
    </form>
</div>

<div class="monitoring-icons">
<a href="" id="listviewicon" title="List view"><i class="fa fa-align-justify fa-3x"></i></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="" id="gridviewicon" title="Grid view"><i class="fa fa-th fa-3x"></i></a>
</div>

<div class="table-responsive dataTable clear" id="listview">
    <table id="venueTable" class="table table-striped"> </table>
</div>
<!-- Button trigger modal -->
<button style="display:none;" type="button" id="launch" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong"> Launch demo modal </button>


<!-- Modal -->
<div class="modal" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="needed-mheader" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="needed-mheader">Tabletpos Printers</h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>

<div class="table-responsive clear" id="gridview">
    <table id="venueGrid" class="table table-venuegrid"> </table>

</div>
<div class="modal" id="tabletposprinter"></div>

<script>
        venuesJson = <?php echo $data['venuesJsonReloaded']; ?>;

        $('#src-sitename').val(searchText);
        if (searchText != null) {
            $('#src-sitename').focus();
        }


        if(selectedToShow == 0) {
            $('#listview').show();
            $('#gridview').hide();
        }
        else {
            $('#listview').hide();
            $('#gridview').show();
        }

        $('#src-sitename').on("input", function() {
            searchText = $(this).val();
            showVenuesTable(venuesJson);
            showVenuesGrid(venuesJson);
        });

        // $(function() {
        //   $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load

        //   $('#gridviewicon').click();
        // });

        // $(document).delegate('#buildtable', 'click', function(event) {
        //   event.preventDefault();
          showVenuesTable(venuesJson);
          showVenuesGrid(venuesJson);

        
        // });

        $(document).delegate('#reset', 'click', function() {}); // For some reason refreshes the page - but it works

        $(document).delegate('#filter', 'click', function(event) {
          event.preventDefault();
          showVenuesTable(venuesJson);
          showVenuesGrid(venuesJson);
        });
        
        $( document ).on( 'click', '#listviewicon', function (event) {
          event.preventDefault();
            
            selectedToShow = 0;
          $('#listview').show();
          $('#gridview').hide();

        });

        $( document ).on( 'click', '#gridviewicon', function (event) {
          event.preventDefault();
          selectedToShow = 1;
          $('#listview').hide();
          $('#gridview').show();

        });

        function createVenueModal(id, sitename, venuedata) {

          modalhtml = 
            '<div class="modal" id="modal_' + id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\n\
              <div class="modal-dialog">\n\
                <div class="modal-content">\n\
                  <div class="modal-header">\n\
                  <h6 class="modal-title" id="myModalLabel">' + sitename + '</h6>\n\
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\n\
                  </div>\n\
                  <div class="modal-body">\n\
                    Status Comment : <b>' + venuedata["statuscomment"]  + '</b> <br> <br> \n\
                    Today MB (Up/Down) : ' + venuedata["bytes"]  + ' <br> \n\
                    Gateway IP : ' + venuedata["gateway"]  + ' <br> \n\
                    Last Check in : ' + venuedata["lastcheckin"]  + ' <br> \n\
                </div>\n\
              </div>\n\
            </div>\n\
          </div>';

          return modalhtml;

        }
        
        function showVenuesGrid(venuesjson) {
            
          table = ''; rows = '';
          beginTable = '\
                  <table class="table-venuegrid">\n\
                    <tbody>  \n';

          numitems = -1;
          rows = rows + '<tr class="table-venuegrid"tr>\n';
          modalshtml = "";

          $.each(venuesjson, function(index, value) {

              // var res = index.split(" ");
              // var venue_name = res["1"];
              venue_name = index.substr(-14);

              modalshtml = modalshtml + createVenueModal(numitems, index, value);
              numitems++;
              modal_id = numitems-1;

              searchstring = $( "#src-sitename" ).val();
              regexp = new RegExp(searchstring,"gi");
              currentcolumn = numitems % 10;

              if(index.match(regexp)) {

                if(currentcolumn == 0) rows = rows + '</tr>\n';
                if(currentcolumn == 0) rows = rows + '<tr class="table-venuegrid">\n';
                rows = rows + '<td class="table-venuegrid ' + value["statuscolor"] + '">' 
                                + '<a href="#" class="gridlinks" data-toggle="modal" data-target="#modal_' + modal_id + '" title="' + index + '">'
                                  + venue_name + 
                                '</a>\n\
                              </td>\n';

             };
          });
          rows = rows + '</tr>\n';

          endTable = ' \
                    </tbody>\n\
                  </table>';

          table = beginTable + rows + endTable;
          $( "#venueGrid" ).html( table );
          $( '#viewVenueModals' ).html(modalshtml);
          }
      

        function showVenuesTable(venuesjson) {
          // alert("In showVenuesTable");
          table = '';
          rows = '';
          beginTable = '\
                  <table class="table table-striped allahu">\n\
                    <thead>\n\
                      <tr>\n\
                        <th>Sitename</th>\n\
                        <th>Today MB (Up/Down)</th>\n\
                        <th>Last Check in</th>\n\
                        <th>Gateway IP</th>\n\
                        <th>Status</th>\n\
                        <th>\n\
                        </th>\n\
                      </tr>\n\
                    </thead>\n\
                    <tbody>  \n';

          $.each(venuesjson, function(index, value) {

              searchstring = $( "#src-sitename" ).val();
              regexp = new RegExp(searchstring,"gi");
              // some_string.match(variable_regex);
              

              if(index.match(regexp)) {
                obj = {};
                obj["id"] = value['id'];
                obj["venuename"] = index.replace(" ", "_");
               
                
     
                  rows = rows + '\
                          <tr >\n\
                            <td onclick="listTabletposPrintersForVenue(' + obj["id"]+')" ><a href="javascript:void(0)"> ' + index  + '</a></td>\n\
                            <td> ' + value["bytes"]  + '</td>\n\
                            <td> ' + value["lastcheckin"]  + '</td>\n\
                            <td> ' + value["gateway"]  + '/' + value["externalIP"] + '</td>\n\
                            <td class="' + value["statuscolor"] + '">' + value["status"] + '</td>\n\
                          </tr><div id="printer' + value["venueid"]+'"></div>\n\
                          ';
             };
             

          });

          endTable = ' \
                    </tbody>\n\
                  </table>';

          table = beginTable + rows + endTable;
          $( "#venueTable" ).html( table );

        }



      /*  $(".hi").click(function(){
            //venueid = $(this).attr('id');
            alert('venueid');
          });

        function testalert(){
          alert('hello');
        }*/
    

      function listTabletposPrintersForVenue(id){
         //id = venueid;
         //data = venuesJson;
         input = {};
         input["venueid"] = id;
        /* alert(venueid);
         alert(venuename);*/
         //neededdata = data[index];
         $.ajax({
          url: "{{url('hipwifi_showtabletposprinters')}}",
          data: input,
          type: "POST",
          success: function(data){
            data = $.parseJSON(data);
            if(data.length > 0){

              beginTable = '<table class="table table-striped"><thead><tr>\
                        <th>Printer Name</th>\n\
                        <th>Printer IP</th>\n\
                        <th>Last seen</th>\
                        <th>Status</th>\n\
                        </tr></thead>\
                        <tbody>';
                rows = '';
              $.each(data, function(index, value){
                  

                      rows += '<tr><td>' + value["name"] + '</td>\
                                <td>'+ value["ipaddress"] + '</td>\
                                <td>' + value["lastseen"] + '</td>\
                                <td class="'+ value["status"] + "Bg" +'">' + value["status"] + '</td></tr>';
                        
                      
                      
                });
              endTable = '</tbody></table>';
              printerTable = beginTable + rows + endTable;
              $(".modal-body").html(printerTable);
              $("#launch").trigger("click");
              //$(".modal-body").show();
            // });

           
         }

       }
     });


      }

      $('.gridlinks').click(function(){        
        selectedModalId = $(this).attr('data-target');
      });

    //   $('#venueTable').DataTable({
    //         "oLanguage": {
    //             "sSearch": ""
    //         },
    //         "pageLength": 100
    //   });

    

  </script>
<style>
  .modal-backdrop.show {
    display: none !important;
  }
  .greenBg, .redBg, .greenBg a, .redBg a {
    color: white !important;
  }
  .greenBg {
    background-color: #5cb85c;
  }
  .redBg {
    background-color: #d9534f;
  }
  .table-venuegrid {
    border: 1px solid white;
  }
  #venueGrid {
    margin-top: 25px;
  }
</style>