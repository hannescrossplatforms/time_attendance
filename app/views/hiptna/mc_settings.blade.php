@extends('layout')

  @section('content')

  <body class="hipTnA">

    <div class="container-fluid">
      <div class="row">

        @include('hiptna.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          <h1 class="page-header">Settings (IM)</h1>

        @if( $data['current_instance'] === "NR01" || $data['current_instance'] === "NR02" )
            @include('hiptna.nonroster_instancesettings')
        @else 
            @include('hiptna.roster_instancesettings')
        @endif
          <div class="panel panel-default">
            <div class="panel-heading">Reporting</div>
            <div class="panel-body">

              <div class="form-group">
                <div class="table-responsive">

                      <table class="tnastafftable">
                        <thead>
                          <tr>
                            <th class="tnastafftd_name">  </th>
                            <th class="tnastafftd_email">  </th>
                            <th class="tnastafftableheader" colspan="3"> Report Type</th>
                            <th class="tnastafftableheader" colspan="3"> Report Frequency</th>
                          </tr>
                        </thead>

                        <tbody>
                          <tr>
                            <td class="tnastafftd_name"> <input id="add_name" class="form-control no-radius" placeholder="Name"  type="text"> </td>
                            <td class="tnastafftd_email"> <input id="add_email" class="form-control no-radius" placeholder="Email" type="text"> </td>
                            <td class="tnastafftd_absence"> <input id="add_absence" type="checkbox"> <br>Absence  </td>
                            <td class="tnastafftd_time_management"> <input id="add_time_management" value="" type="checkbox"> <br>Lateness </td>
                            <td style="width:15%;" class="tnastafftd_ws_proximity"> <input id="add_ws_proximity" value="" type="checkbox"><br> WS Proximity </td>
                            <td class="tnastafftd_daily"> <input id="add_daily" value="" type="checkbox"> <br>Daily  </td>
                            <td class="tnastafftd_weekly"> <input id="add_weekly" value="" type="checkbox"> <br>Weekly </td>
                            <td class="tnastafftd_monthy"> <input id="add_monthly" value="" type="checkbox"> <br>Monthly </td>
                            <td class="tnastafftd_add_update" style="width:16%;"> <a id="addreportuser"  class="btn btn-default btn-delete btn-sm">Add</a> </td>
                            <td class="tnastafftd5">  </td>
                          </tr>
                        </tbody>
                      </table>

                      <div id="tableview"></div>

                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <a href="" id="saveevent" class="btn btn-primary">Save Event</a>
    <a href="{{ url('hipengage_showevents'); }}" class="btn btn-default">Cancel</a>


      <!-- Bootstrap core JavaScript
      ================================================== 
      <!-- Placed at the end of the document so the pages load faster -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>

      <script type="text/javascript">
        $(function() {                    

            availableInstances = "{{ Session::get('availableInstances') }}";
            currentInstance = "{{ Session::get('currentInstance') }}";
            $.ajax({
                 type: "POST",
                 dataType: 'json',
                 //contentType: "application/json",
                 url: "{{ url('hiptna_showReportRecipients')}}",
                 // data: "newrecord="+dataJson,
                 //async: false,
                 success:  function(objResult){

                   reportRecipients = objResult
                   renderReportRecipientsRows()

                 }
             });
        });
      </script>
      <script type="text/javascript">
            function submitForm(formId) {
                $('#submit_msg').html('Upload submitted. You will be notified via email when it is complete');
                var NewWindow = window.open('about:blank', "{{ url('hiptna/fileupload'); }}",'left=20,top=20,width=500,height=250,toolbar=1,resizable=0');
                NewWindow.document.write('<span id="infomessgae" style="color:green;text-align:center;font-size:36px;">Do not close this window. <br>Your upload is in progress.<br>You will receive and email once the upload has completed</span>');
                document.rosterupload.target="{{ url('hiptna/fileupload'); }}";
                document.rosterupload.method = "post";
                document.rosterupload.action="{{ url('hiptna/fileupload'); }}";
                document.rosterupload.submit();

              
            }
          
      </script>

      <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>
      <script src="js/prefixfree.min.js"></script> 

      <script>

        reportRecipients = new Array();

        $('[data-toggle="tooltip"]').tooltip();

        $( "#updatethresholds" ).click(function(event) {
            var data = {
              'lateness_threshold': $( "#lateness_threshold" ).val(), 
              'proximity_target': $( "#proximity_target" ).val(), 
              'tnaproximitydistance_a': $( "#tnaproximitydistance_a" ).val(), 
              'tnaproximitytime_a': $( "#tnaproximitytime_a" ).val(),
              'tnaproximitydistance_b': $( "#tnaproximitydistance_b" ).val(), 
              'tnaproximitytime_b': $( "#tnaproximitytime_b" ).val(), 
              'tnaproximitydistance_c': $( "#tnaproximitydistance_c" ).val(), 
              'tnaproximitytime_c': $( "#tnaproximitytime_c" ).val(), 
              'late_sms_trigger': $( "#late_sms_trigger" ).val(),
              'absence_sms_trigger': $( "#absence_sms_trigger" ).val(),
             };

            url = "{{ url('hiptna_updateThresholds/'); }}";

            $.ajax({
                type: "GET",
                dataType: 'json',
                contentType: "application/json",
                url: url,
                data: data,
                success: function(positions) {
                }
            }); 
        });

        $( "#addreportuser" ).click(function(event) {
          //$(document).delegate('#addreportuser','click', function(event){


          event.preventDefault();
          var newrecord = {};
          newrecord['add_name'] = $('#add_name').val(); 
          newrecord['add_email'] = $('#add_email').val(); 
          if ($('#add_absence').is(':checked')) newrecord['add_absence'] = 1; else newrecord['add_absence'] = 0;

          if ($('#add_time_management').is(':checked')) newrecord['add_time_management'] = 1; else newrecord['add_time_management'] = 0;

          if ($('#add_ws_proximity').is(':checked')) newrecord['add_ws_proximity'] = 1; else newrecord['add_ws_proximity'] = 0;

          if ($('#add_daily').is(':checked')) newrecord['add_daily'] = 1; else newrecord['add_daily'] = 0;

          if ($('#add_weekly').is(':checked')) newrecord['add_weekly'] = 1; else newrecord['add_weekly'] = 0;

          if ($('#add_monthly').is(':checked')) newrecord['add_monthly'] = 1; else newrecord['add_monthly'] = 0;

          var url = '{{ URL::route('hiptna_addReportuser')}}';
          //console.log(newrecord);
          var dataJson = JSON.stringify(newrecord);

          if(!newrecord['add_name']) {
            alert("Enter Name");
            return false; 

          } else if(!newrecord['add_email']) {
            alert("Enter Email");
            return false; 

          } else if(!validateEmail(newrecord['add_email'])){

              alert("Enter Valid Email");
              return false;

          } else if(!(newrecord['add_absence'] || newrecord['add_time_management'] || newrecord['add_ws_proximity'])){
            alert("Select atleast one Report Type ");
            return false; 

          } else if(!(newrecord['add_daily'] || newrecord['add_weekly'] || newrecord['add_monthly'])){
            alert("Select atleast one Report Frequency ");
            return false; 

          } else {

            $.ajax({
                type: "POST",
                dataType: 'json',
                //contentType: "application/json",
                url: url,
                data: "newrecord="+dataJson,
                //async: false,
                success:  function(objResult){
                  console.log(objResult.row);                
                  $(objResult.row).prependTo("table#reportRecipientsTable > tbody");
                  $('#add_name').val('');
                  $('#add_email').val('');
                  $('#add_absence').attr('checked', false);
                  $('#add_time_management').attr('checked', false);
                  $('#add_ws_proximity').attr('checked', false);
                  $('#add_daily').attr('checked', false);
                  $('#add_weekly').attr('checked', false);
                  $('#add_monthly').attr('checked', false);
                }
            }); 


          }

        });

        function validateEmail(sEmail) {
          var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
          if (filter.test(sEmail)) {
             return true;
          } else {
             return false;
          }
        }



        function updateReportUser(update_id){

          var newrecord = {};
          newrecord['update_id'] = update_id ; 
          newrecord['update_name'] = $('#add_name_'+ update_id).val(); 
          newrecord['update_email'] = $('#add_email_'+ update_id ).val(); 
          
          if ($('#add_absence_' + update_id).is(':checked')) newrecord['update_absence'] = 1; else newrecord['update_absence'] = 0;

          if ($('#add_time_management_' + update_id).is(':checked')) newrecord['update_time_management'] = 1; else newrecord['update_time_management'] = 0;

          if ($('#add_ws_proximity_' + update_id).is(':checked')) newrecord['update_ws_proximity'] = 1; else newrecord['update_ws_proximity'] = 0;

          if ($('#add_daily_' + update_id).is(':checked')) newrecord['update_daily'] = 1; else newrecord['update_daily'] = 0;

          if ($('#add_weekly_' + update_id).is(':checked')) newrecord['update_weekly'] = 1; else newrecord['update_weekly'] = 0;

          if ($('#add_monthly_' + update_id).is(':checked')) newrecord['update_monthly'] = 1; else newrecord['update_monthly'] = 0;

          //alert(newrecord['update_monthly']);

          

          if(!newrecord['update_name']) {
              alert("Enter Name");
              return false; 

          } else if(!newrecord['update_email']) {
              alert("Enter Email");
              return false; 

          } else if(!validateEmail(newrecord['update_email'])){

              alert("Enter Valid Email");
              return false;

          } else if(!(newrecord['update_absence'] || newrecord['update_time_management'] || newrecord['update_ws_proximity'])){
              alert("Select atleast one Report Type ");
              return false; 

          } else if(!(newrecord['update_daily'] || newrecord['update_weekly'] || newrecord['update_monthly'])){
              alert("Select atleast one Report Frequency ");
              return false; 

          } else {
            var url = '{{ URL::route('hiptna_updateReportUser')}}';

            //console.log(newrecord);
            var dataJson = JSON.stringify(newrecord);
            $.ajax({
                type: "POST",
                dataType: 'json',               
                url: url,
                data: "newrecord="+dataJson,                
                success:  function(objResult){                
                }
            }); 


          }

        }

        function deleteReportUser(id){

          var newrecord = {};
          newrecord['id'] = id ;
          var url = '{{ URL::route('hiptna_deleteReportUser')}}';
          
          var dataJson = JSON.stringify(newrecord);
          $.ajax({
              type: "POST",
              dataType: 'json',                
              url: url,
              data: "newrecord="+dataJson,                
              success:  function(objResult){
                $('#add_name_'+objResult.id).closest('tr').remove();
              }
          }); 



        }

        function renderReportRecipientsRows() {

          rows = '<table  id="reportRecipientsTable"  class="tnastafftable"><tbody>';

          
          $.each( reportRecipients, function( index, value ) {
           
             if(value["absence"] == 1){
                var absence = 'checked="checked"';
            } else {
                var absence = '';
            }

            if(value["lateness"] == 1){
                var lateness = 'checked="checked"';
            } else {
                var lateness = '';
            }

            if(value["ws_proximity"] == 1){
                var ws_proximity = 'checked="checked"';
            } else {
                var ws_proximity = '';
            }

            if(value["daily"] == 1){
                var daily = 'checked="checked"';
            } else {
                var daily = '';
            }

            if(value["weekly"] == 1){
                var weekly = 'checked="checked"';
            } else {
                var weekly = '';
            }

            if(value["monthly"] == 1){
                var monthly = 'checked="checked"';
            } else {
                var monthly = '';
            }
            

            rows = rows + '<tr>\
                        <td class="tnastafftd_name"> <input id="add_name_'+ value["id"] +'" class="form-control no-radius" placeholder="Name"  type="text" value="' + value["name"] + '"> </td>\
                        <td class="tnastafftd_email"> <input id="add_email_'+ value["id"] +'" class="form-control no-radius" placeholder="Email" type="text" value="' + value["email"] + '"> </td>\
                        <td class="tnastafftd_absence"> <input id="add_absence_'+ value["id"] +'" type="checkbox"  '+ absence +'><br> Absence  </td>\
                        <td class="tnastafftd_time_management"> <input id="add_time_management_'+ value["id"] +'" value="" type="checkbox" '+ lateness + '> <br>Lateness </td>\
                        <td style="width:15%;" class="tnastafftd_ws_proximity"> <input id="add_ws_proximity_'+ value["id"] +'" value="" type="checkbox" '+ ws_proximity + ' ><br> WS Proximity </td>\
                        <td class="tnastafftd_daily"> <input id="add_daily_'+ value["id"] +'" value="" type="checkbox" '+ daily + ' > <br>Daily  </td>\
                        <td class="tnastafftd_weekly"> <input id="add_weekly_'+ value["id"] +'" value="" type="checkbox" '+ weekly + ' ><br> Weekly </td>\
                        <td class="tnastafftd_monthy" > <input id="add_monthly_'+ value["id"] +'" value="" type="checkbox" '+ monthly + ' ><br> Monthly </td>\
                        <td class="tnastafftd_add_update" style="width:17%;"> <a id="updateReportuser_'+ value["id"] +'" class="btn btn-default btn-delete btn-sm" onclick="updateReportUser('+ value["id"] +');">Update</a> <a id="deleteReportuser_'+ value["id"] +'" class="btn btn-default btn-delete btn-sm" onclick="deleteReportUser('+ value["id"] +');">Delete</a></td>\
                        <td></td>\
                      </tr>\
                  ';
                  

          });

          rows += '</tbody>\
                </table>';


          $("#tableview").html(rows);
        }
      </script>

    </body>
    @stop
