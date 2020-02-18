@extends('layout')

@section('content')

<body class="hipWifi">
  <div id="buildtable"></div>
  <div class="container-fluid">
    <div class="row">
      @include('hipwifi.sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">Venue Monitoring</h1>
            <div id="page-replace-div"></div>
        </div>
    </div>
  </div>

  <div id="viewVenueModals"><span id="closeViewModal">x</span></div>

  <!-- Bootstrap core JavaScript
      ================================================== --> 
  <!-- Placed at the end of the document so the pages load faster --> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
  <script src="js/bootstrap.min.js"></script> 
  <script src="js/prefixfree.min.js"></script>

  <script>

    $('document').ready(function(){
      $.ajax({
        url: 'http://hiphub.hipzone.co.za/hipwifi_populatemonitoring',
            type: 'get',
            dataType: 'html',
            success: function(result) {
              debugger;
                $("#page-replace-div").html(result);
                reloadJquery();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              debugger;
            }
        });
    });

      function reloadJquery(){
        venuesJson = {{ $data['venuesJsonReloaded'] }};
        console.log(venuesJson);

        $(function() {
          $('#buildtable').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load

          $('#gridviewicon').click();
        });

        $(document).delegate('#buildtable', 'click', function(event) {
          event.preventDefault();
          showVenuesTable(venuesJson);
          showVenuesGrid(venuesJson);
        });

        $(document).delegate('#reset', 'click', function() {}); // For some reason refreshes the page - but it works

        $(document).delegate('#filter', 'click', function(event) {
          event.preventDefault();
          showVenuesTable(venuesJson);
          showVenuesGrid(venuesJson);
        });
        
        $( document ).on( 'click', '#listviewicon', function (event) {
          event.preventDefault();

          $('#listview').show();
          $('#gridview').hide();

        });

        $( document ).on( 'click', '#gridviewicon', function (event) {
          event.preventDefault();

          $('#listview').hide();
          $('#gridview').show();

        });

        function createVenueModal(id, sitename, venuedata) {

          modalhtml = 
            '<div class="modal fade" id="modal_' + id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\n\
              <div class="modal-dialog">\n\
                <div class="modal-content">\n\
                  <div class="modal-header">\n\
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\n\
                    <h6 class="modal-title" id="myModalLabel">' + sitename + '</h6>\n\
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
            debugger;
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
          debugger;
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

      }

  </script>

</body>
@stop
