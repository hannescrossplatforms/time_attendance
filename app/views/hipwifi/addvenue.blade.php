@extends('layout')


<?php $edit = $data["edit"] ; 
    if (!$edit) {$editval = 0; } else{$editval = 1; }
    if($data["is_activation"]) { $is_activation = 1; } else { $is_activation = 0; };
?>
<?php error_log("EEEEEEEEEEEEEEEEEEEEEEEDIT : $edit");  ?>
<?php $myerrors = array("first" => "1", "second" => "2"); ?>
<?php $selected_server_id = $data["venue"]->server_id; ?>

<?php $adminssid1 = $data["adminssid1"]; ?>
<?php $adminssid2 = $data["adminssid2"]; ?>
<?php $adminssid3 = $data["adminssid3"]; ?>

<?php if ($edit==true){
  $showadminwificonfig = 1;

  }
  else if ($edit==false){
      $showadminwificonfig = 0;

       //$numadminwifi = 0;
    }?>



@section('content')

  <body class="hipWifi">

    <form role="form" id="useradmin-form" method="post" 
        action=" @if ($is_activation) {{ url('hipwifi_activatevenue'); }} @else {{ url('hipwifi_editvenue'); }} @endif ">
    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 
            <h1 class="page-header">@if($is_activation) Activate @else Edit @endif  Venue</h1>
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
              </div>
            @endif

            <div  id="printererror"> </div>
                  
            

          <div class="row">
              <div class="col-md-12">

                      {{ Form::hidden('id', $data['venue']->id) }}
                      {{ Form::hidden('countrie_id', $data['venue']->countrie_id) }}
                      {{ Form::hidden('province_id', $data['venue']->province_id) }}
                      {{ Form::hidden('citie_id', $data['venue']->citie_id) }}
                      {{ Form::hidden('brand_id', $data['venue']->brand_id, array('id' => 'brand_id')) }}
                      {{ Form::hidden('server_id', $data['venue']->server_id, array('id' => 'server_id')) }}
                      {{ Form::hidden('isp_id', $data['venue']->isp_id) }}
                      {{ Form::hidden('delete_macs', "", array('id' => 'delete_macs')) }}


                      @if ($edit)
                        {{ Form::hidden('old_sitename', $data['old_sitename']) }}
                      @endif 
                      <div class="form-group">
                        <label for="exampleInputEmail1">Sitename* </label>
                        <input  id="sitename" type="text" class="form-control" id="exampleInputEmail1" 
                                name="sitename" placeholder="" 
                                value="@if(Input::old('sitename')){{Input::old('sitename')}}@else{{$data['venue']->sitename}}@endif" 
                                required disabled>
                      </div>


                      <div  class="form-group">
                        <label for="exampleInputEmail1">Location</label>
                        <div id="locationCodeHidden"></div>
                        <div id="locationCodeDisplayed">
                            @if (\User::hasAccess("superadmin")) 
                            <input  id="locationcode" name="location" class="form-control" type="text" 
                                    value="{{$data['venue']->location}}" 
                                    placeholder="This field autocompletes - please complete all fields above" required>
                            @else
                            <input  id="" name="" class="form-control" type="text" 
                                    value="{{$data['venue']->location}}" disabled >
                            <input  type="hidden" name="location" value="{{ $data['venue']->location }}" >
                            @endif
                            <input  type="hidden" name="oldlocation" value="{{ $data['venue']->location }}" >
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="exampleInputEmail1">MAC Address*</label>
                        @if ($is_activation)
                          <input type="text" class="form-control" id="macaddress" 
                              name="macaddress" placeholder="" 
                              value="@if(Input::old('macaddress')){{Input::old('macaddress')}}@else{{$data['venue']->macaddress}}@endif" 
                              required>
                        @else
                          <input type="text" class="form-control" id="macaddress" 
                              name="xxx" placeholder="" 
                              value="{{$data['venue']->macaddress}}" 
                              disabled>
                          {{ Form::hidden('macaddress', $data['venue']->macaddress) }}
                        @endif
                      </div>    

                      <div class="form-group">
                        <label for="exampleInputEmail1">SSID</label>
                          <input type="text" class="form-control" id="ssid" 
                              name="ssid" placeholder="" 
                              value="@if(Input::old('ssid')){{Input::old('ssid')}}@else{{$data['venue']->ssid}}@endif" 
                          >
                      </div>  
                      
                      <div class="form-group">
                       <label>Device Type</label>
                        <select name="device_type" id="device_type_select" class="form-control">
                          @foreach($data['device_types'] as $device_type)
                            <option value="{{ $device_type["name"] }}"  {{ $device_type["selected"] }} >
                              {{ $device_type["name"] }}
                            </option>
                          @endforeach 
                        </select>
                      </div>

                      <div class="form-group">
                        <label>Access Point Status : </label>
                        <label class="radio-inline">
                          <input type="radio" name="ap_active" value="1" {{ $data['ap_active_checked']}}> Active
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="ap_active" value="0" {{ $data['ap_inactive_checked']}}> Inactive
                        </label>
                      </div>

                      <!--Displaying the admin ssid details if any has been created in the database using the "Add Admin SSID" menu below-->
                     

                      @if($edit)
                      <div class="table-responsive" id="config_admin_wifi_table">
                        <table class="table">
                          <tr>
                            <th>Network S/N</th>
                            <th>Admin Network Name</th>
                            <th>Password</th>
                            <th>Type(Open or Hidden)</th>
                            <th> </th>
                          </tr>
                          <tr id="configuredadminwifi1">
                            <td>1</td>
                            <td>{{$data['venue']->adminssid1}}</td>
                            <td>{{$data['venue']->password1}}</td>
                            <td>{{$data['venue']->type1}}</td>
                            <td><a class="btn btn-sm btn-danger" id="deletessid1" venueid="{{$data['venue']->id;}}" adminid="{{$data['venue']->adminssid1;}}" href="{{ url('deladminssid'); }}/{{$data['venue']->id;}}/{{$data['venue']->adminssid1;}}">Delete</a></td>
                          </tr>
                          <tr id="configuredadminwifi2">
                            <td>2</td>
                            <td>{{$data['venue']->adminssid2}}</td>
                            <td>{{$data['venue']->password2}}</td>
                            <td>{{$data['venue']->type2}}</td>
                            <td><a class="btn btn-sm btn-danger" id="deletessid2" venueid="{{$data['venue']->id;}}" adminid="{{$data['venue']->adminssid1;}}" href="{{ url('deladminssid'); }}/{{$data['venue']->id;}}/{{$data['venue']->adminssid2;}}">Delete</a></td>
                          </tr>
                          <tr id="configuredadminwifi3">
                            <td>3</td>
                            <td>{{$data['venue']->adminssid3}}</td>
                            <td>{{$data['venue']->password3}}</td>
                            <td>{{$data['venue']->type3}}</td>
                            <td><a class="btn btn-sm btn-danger"  id="deletessid3" venueid="{{$data['venue']->id;}}" adminid="{{$data['venue']->adminssid1;}}" href="{{ url('deladminssid'); }}/{{$data['venue']->id;}}/{{$data['venue']->adminssid3;}}">Delete</a></td>
                          </tr>

                          

                        </table>
                        




                      </div>

                      
                      

                      <!--adding functionality for admin wifi ssid configuration-->
                      <div class="form-group" id="addadminwifi">
                        

                          <label id="selectadminwifi1"><input type="checkbox" name="admin_wifi" id="selectadminwifi"> Add Admin Network</label><br/>
                          
                          <div id="admin_wifi_details">
                            <div id="adminwifiinput1">
                              <label><input type="text" name="adminssid1" placeholder="Enter First Network Name"></label>
                              <label><input type="text" name="firstnetworkpassword" placeholder="First Network Password"></label>
                              <label>Network Type</label>
                              <label>
                                <select name="type1">
                                  <option value="open">Open</option>
                                  <option value="hidden">Hidden</option>
                                </select>
                              </label>
                             
                          </div>
                          <br/>
                          <div id="adminwifiinput2"> 
                            <label><input type="text" name="adminssid2" placeholder="Enter Second Network Name"></label>
                            <label><input type="text" name="secondnetworkpassword" placeholder="Second Network Password"></label>
                            <label>Network Type</label>
                            <label>
                              <select name="type2">
                                <option value="open">Open</option>
                                <option value="hidden">Hidden</option>
                              </select>
                            </label>
                          </div>
                          <br/> 

                          <div id="adminwifiinput3">
                          <label><input type="text" name="adminssid3" placeholder="Enter Third Network Name"></label>
                           <label><input type="text" name="thirdnetworkpassword" placeholder="Third Network Password"></label>
                           <label>Network Type</label>
                          <label>
                            <select name="type3">
                              <option value="open">Open</option>
                              <option value="hidden">Hidden</option>
                            </select>
                          </label>
                           
                          </div><br/> 
                           
        
                          </div>


                        
                       
                      </div>
                      <!-- Mac-address bypass begins here-->
                      <div id="display_bypass_mac_addresses">
                      
                      <table class="table">
                        <tr>
                            <th>S/N</th>
                            <th>Mac-address</th>
                            <th>Comment</th>
                            <th></th>
                        </tr>
                   @for($i = 0; $i <=9; $i++)
                                 <tr id="mac{{$i}}">
                                    <td>{{$i+1}}</td>
                                    <td>{{$data['bypass'][$i][0]}}</td>
                                    <td>{{$data['bypass'][$i][1]}}</td>
                                    <td><a class="delete-btn btn btn-sm btn-danger" venueid="{{$data['venue']->id;}}" bypassid="{{$data['bypass'][$i][0];}}"  id="deletebypassmac1" href="{{ url('delmacbypass'); }}/{{$data['venue']->id;}}/{{$data['bypass'][$i][0];}}">Delete</a></td>
                                </tr>
                  @endfor
                      </table>
                        

                      </div>

                      <div class="form-group" id="addbypassmacaddress">
                        
                        <label id="selectbypassmac"><input type=checkbox name="bypassmac" id="bypassmac"> Add bypassed MAC-Address</label>
                        <br>
                        <div id="bypassmacaddressgroup">
                        @for($i = 0; $i <=9; $i++)
                        <div id="bypassmacentry{{$i}}">
                        <label><input type="text" name="bypassmac{{$i}}" placeholder="Enter bypass macaddress {{$i + 1}}"></label>
                        <label><input style="margin-left:20px" type="text" name="bypasscomment{{$i}}" placeholder="comment"></label>
                        <br>
                        </div>
                        @endfor

                        </div>
                        </form>
    
                      </div>
                       @endif
                      <div class="form-group">

                      @if($edit)

                           @if (\User::hasAccess("superadmin") || \User::hasAccess("admin")) 
                          <div> 
                                    {{Form::checkbox('checkbox', 'tp_env', null, ["id" => "tpenv"])}}
                                    {{Form::label('tp_env', "Tabletpos Environment")}}
                                    <div class="form-inline">
                                        <table id=tabletpos_printers>
                                            <tr>
                                                <td><input type=text name="name" id="printername" placeholder="printer's location"></td>
                                                <td><input type=text name="ipaddr" id="printerip" placeholder="printer's ipaddress"></td>
                                                <td><a href="javascript:void(0);"  id="submitprinter" class="btn btn-small btn-default btn-primary"><i class="fa fa-plus"></i> Add</a></td>
                                            </tr>
                                            @foreach($data['tabprinters'] as $printer)
                                            <tr id="printerrow{{$printer->id}}">
                                                <td><input type=text name="name" id="oldname{{$printer->id}}" value="{{$printer->name}}"></td>
                                                <td><input type=text name="ipaddr" id="oldip{{$printer->id}}" value="{{$printer->ipaddress}}"></td>
                                                <td><a href="javascript:void(0);"  dbid="{{$printer->id}}" id="updateprinter{{$printer->id}}" class="btn btn-small btn-default">Update</a></td>
                                                <td><a href="javascript:void(0);"  dbid="{{$printer->id}}" id="deleteprinter{{$printer->id}}" class="btn btn-small btn-default btn-danger">Delete</a></td>
                                             </tr> 
                                            
                                            @endforeach
                                        </table>
                                    </div>

                            
                          </div><br/>
                          @endif

                      @endif

                      <div class="form-group" id="storeopeninghours">
                        <label>Store Opening Hours</label>
                        <br>
<!--                         <div class="form-inline">
                          <div class="form-group">
                            <input id="timefrom" name="timefrom"  value="{{$data['venue']->timefrom}}" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <span> to </span>
                          <div class="form-group">
                            <input id="timeto" name="timeto" value="{{$data['venue']->timeto}}" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div> -->

                        <div class="form-inline">
                          <div class="form-group dayofweek">  </div>
                          <div class="form-group datefromtoheader"> From </div>
                          <div class="form-group datefromtoheader"> To </div>
                        </div>

                        <div class="form-inline">
                          <div class="form-group dayofweek"> Monday </div>
                          <div class="form-group datefromto">
                            <input id="mon_from" name="mon_from"  class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <div class="form-group datefromto">
                            <input id="mon_to" name="mon_to"  class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div>

                        <div class="form-inline">
                          <div class="form-group dayofweek"> Tuesday </div>
                          <div class="form-group datefromto">
                            <input id="tue_from" name="tue_from" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <div class="form-group datefromto">
                            <input id="tue_to" name="tue_to" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div>

                        <div class="form-inline">
                          <div class="form-group dayofweek"> Wednesday </div>
                          <div class="form-group datefromto">
                            <input id="wed_from" name="wed_from" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <div class="form-group datefromto">
                            <input id="wed_to" name="wed_to" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div>

                        <div class="form-inline">
                          <div class="form-group dayofweek"> Thursday </div>
                          <div class="form-group datefromto">
                            <input id="thu_from" name="thu_from" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <div class="form-group datefromto">
                            <input id="thu_to" name="thu_to" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div>

                        <div class="form-inline">
                          <div class="form-group dayofweek"> Friday </div>
                          <div class="form-group datefromto">
                            <input id="fri_from" name="fri_from" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <div class="form-group datefromto">
                            <input id="fri_to" name="fri_to" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div>

                        <div class="form-inline">
                          <div class="form-group dayofweek"> Saturday </div>
                          <div class="form-group datefromto">
                            <input id="sat_from" name="sat_from" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <div class="form-group datefromto">
                            <input id="sat_to" name="sat_to" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div>

                        <div class="form-inline">
                          <div class="form-group dayofweek"> Sunday </div>
                          <div class="form-group datefromto">
                            <input id="sun_from" name="sun_from" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                          <div class="form-group datefromto">
                            <input id="sun_to" name="sun_to" class="time ui-timepicker-input" type="text" autocomplete="off">
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Server Hostname*</label>
                        <select id="serverlist" name="server_id" class="form-control no-radius" required></select>
                      </div>  

                      <div class="form-group">
                        <label for="exampleInputEmail1">Venue Status Comment</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" 
                            name="statuscomment" placeholder="" value="{{$data['venue']->statuscomment}}">
                      </div> 

                    </div>

                    <br> 
                    <button id="" class="btn btn-primary">Submit</button> 
                    <button id="submitform" class="btn btn-primary">Submit</button> 
                    <a href="{{ url('hipwifi_showvenues'); }}" class="btn btn-default">Cancel</a>
                    <br>
                    <p id="submitallowed" class="rscnotdeployed"> *** Previously submitted configuration yet to be applied, please try again in 5 minutes ***</p>
                </div>
            </div>
        </div>
      </div>
    </div>
  </form>
     

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/prefixfree.min.js"></script>
    <script src="/js/jquery.timepicker.min.js"></script> 

    <script>

        var actionsQueue = [];


        $('.delete-btn').click(function(e) {
          e.preventDefault();
          debugger;

          let bypassId = $(this).attr("bypassid");
          let venueId = $(this).attr("venueid");

          actionsQueue.push(bypassId);
          // actionsQueue.push({'bypassid': bypassId, 'venueid': venueId, 'action': 'delete'});
          $(this).parent().parent().hide();
          $('#delete_macs').val(actionsQueue);

          //On submit tapped, add new networks and also add the exisiting ones, then add the removed ones



        });



        $(document).ready( function() {

         $('#tabletpos_printers').hide();

        $('#storeopeninghours').hide();

        var edit = <?php echo $editval;?>;
        var device_type = document.getElementById('device_type_select');
        var device_type_sel = device_type.options[device_type.selectedIndex].text;
        
        if (edit == 1 && device_type_sel == 'Mikrotik'){
          $('#storeopeninghours').show();
        }
        if(is_activation) { // if its an activation show the store type regardless
          $('#storeopeninghours').show();
        }

      });

       $("#tpenv").click(function(){
        
          $('#tabletpos_printers').toggle();
        
       }); 

      is_activation = {{ $is_activation }}

      $('#device_type_select').click( function(){
      var device_type = document.getElementById('device_type_select');
      var device_type_sel = device_type.options[device_type.selectedIndex].text;
      if (device_type_sel == 'Mikrotik'){
        $('#storeopeninghours').show();
      }
      else if(device_type_sel == 'Other'){
        $('#storeopeninghours').hide();
      }



    });


    $(document).ready(function(){
      
      $(':checkbox:checked').prop('checked',false);
      $('#admin_wifi_details').hide();
      $("#bypassmacaddressgroup").hide();
      $('#hiddenssid').removeAttr("checked");
      var edit = <?php echo $editval; ?>;
      if (edit == 0) {
        document.getElementById('submitform').disabled = false;
        $('#submitallowed').hide();
      }

      var submitbutton = '<?php echo $data['submitbutton'];?>';
      if(submitbutton == 'off'){
        document.getElementById('submitform').disabled = true;
      }
      else if (submitbutton == 'on'){
        document.getElementById('submitform').disabled = false;
        $('#submitallowed').hide();
      }

      if (edit ==1){
      var checkmac = <?php echo json_encode($data['bypass'])?>;
       for (var i = 0; i <= 9; i++) {
        $('#bypassmacentry' + i).hide();
        
      //the if statement below fetches the content of checkmac, if the mac and comment are both null, only ',' is present in an index, then it hides the entry for that in // //the table.
          debugger;
          let hasAValue = false;
              if (checkmac[i] == ',') {
                  $('#mac' + i).hide();
                  $('#bypassmacentry' + i).show();
              }
              else {
                hasAValue = true;
              }
                // else{
                //   $('#bypassmacentry' + i).show();
                // }
              // if (checkmac[0] == ','){
              //   $('#display_bypass_mac_addresses').hide();
              // }
            }

            if (hasAValue) {
              $('#display_bypass_mac_addresses').show();
            }
            else {
              $('#display_bypass_mac_addresses').hide();
            }
       }   
                
      
      
      //var numadminwifi = '?>';
      var adminssid1 = '<?php echo $adminssid1?>';
      var adminssid2 = '<?php echo $adminssid2?>';
      var adminssid3 = '<?php echo $adminssid3?>';
      //alert(numadminwifi);

      /*var numadminwifi = <?php?>;
      alert(wifi[0]);*/

      
       
      

      
 
    
    
      var showadminwificonfig = <?php echo $showadminwificonfig?>;
      if (showadminwificonfig == 0) {
      $('#config_admin_wifi_table').hide();
      $('#selectadminwifi1').hide();
      }

      if (showadminwificonfig == 1) {
      $('#config_admin_wifi_table').hide();
      $('#selectadminwifi1').show();
      }

      if (showadminwificonfig == 1 && adminssid1 == ''){
        $('#config_admin_wifi_table').show();
        $('#configuredadminwifi1').hide();
        }
          else if (showadminwificonfig == 1 && adminssid1 !== ''){
            $('#config_admin_wifi_table').show();
            $('#adminwifiinput1').hide();
          }
      

       if (showadminwificonfig == 1 && adminssid2 == ''){
        $('#config_admin_wifi_table').show();
        $('#configuredadminwifi2').hide();
        }
          else if (showadminwificonfig == 1 && adminssid2 !== ''){
            $('#config_admin_wifi_table').show();
            $('#adminwifiinput2').hide();
          }

       if (showadminwificonfig == 1 && adminssid3 == ''){
        $('#config_admin_wifi_table').show();
        $('#configuredadminwifi3').hide();
        }
          else if (showadminwificonfig == 1 && adminssid3 !== ''){
            $('#config_admin_wifi_table').show();
            $('#adminwifiinput3').hide();
          }

      if (showadminwificonfig == 1 && adminssid1 == '' && adminssid2 =='' && adminssid3 == ''){
      $('#config_admin_wifi_table').hide();
      $('#selectadminwifi1').show();
      
       }
       else if (showadminwificonfig == 1 && adminssid1 !== '' && adminssid2 !=='' && adminssid3 !== ''){
      $('#selectadminwifi1').hide();
      
       }
      


      });

    $('#selectadminwifi').click(function() {
    $("#admin_wifi_details").toggle();
    });


    $('#bypassmac').click(function() {
    $("#bypassmacaddressgroup").toggle();
    });
      

   


      $('#submitprinter').click(function() {
        submitPrinter();
      });

      function submitPrinter(){
        var printername = $("#printername").val();
        var printerip = $("#printerip").val();
        input = {};
        input["printername"] = printername;
        input["printerip"] = printerip;
        input["venueid"] =  {{$data['venue']->id}};
        $.ajax(
        {
          type: "POST",
          url: "{{url('hipwifi_addtabletposprinter')}}",
          data: input,
          success: function(data){
            if (data['status'] == 422 || data['status'] == 423){
              var errors = data.msg;
              errorsHtml = "";
              $.each(errors, function(key, value){
                  errorsHtml+= "<li>" + value + "</li>";
              });
              $('#printererror').addClass('alert alert-danger');
              $('#printererror').html(errorsHtml);
              
              }
            
            else{
              $("#tabletpos_printers tr:first").after(data); 
              $("#printername").val("");
              $("#printerip").val("");
            }

            }

          });
       

      }

        $("[id^='updateprinter']").click(function() {
          id = $(this).attr('dbid');
          updatePrinter(id);
            });

        function updatePrinter(id){
          newname= $("#oldname" + id).val();
          newip = $("#oldip" + id).val();
          input = {};
          input["printername"] = newname;
          input["printerip"] = newip;
          input["id"] =  id;
          input["venueid"] = {{$data['venue']->id}};
        $.ajax(
        {
          type: "POST",
          url: "{{url('hipwifi_edittabletposprinter')}}",
          data: input,
          success: function(data){
            if (data['status'] == 422 || data['status'] == 423){
              var errors = data.msg;
              errorsHtml = "";
              $.each(errors, function(key, value){
                  errorsHtml+= "<li>" + value + "</li>";
              });
              $('#printererror').addClass('alert alert-danger');
              $('#printererror').html(errorsHtml);
              
              }
            
            else{
             alert('Updated, press Submit button to save to AP.'); 
            }

            }

          });
       
         

        }

        $("[id^='deleteprinter']").click(function() {
          id = $(this).attr('dbid');
          deletePrinter(id);
            });
           
        function deletePrinter(id){
          input = {};
          input["id"] =  id;
          $.ajax(
              {
                  type: "POST",
                  url: "{{url('hipwifi_deletetabletposprinter')}}",
                  data: input,
                  success: function(data){
                    $('#printerrow' + input["id"]).remove();
                    alert("Deleted, press Submit button to save to AP.");
               }
            }); 
        }
          

        
       
         

       

    

    

    
    

      $(function() {
        $('#countrielist').change(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $('#brandlist').change(); 
        buildServerList();
      });
      
      $(document).delegate('#countrielist', 'change', function() {
        buildProvinceList();
      });

      $(document).delegate('#provincelist', 'change', function() {
        buildCityList();
      });

      $(document).delegate('#citielist', 'change', function() {
        buildLocationCode();
      });

      // $(document).delegate('#isplist', 'change', function() {
      //   buildLocationCode();
      // });

      $(document).delegate('#brandlist', 'change', function() {
        buildLocationCode();
        buildServerList();
      });

      $(document).delegate('#sitename', 'focusout', function() {
        buildLocationCode();
      });

      $(document).delegate('#macaddress', 'focusout', function() {
        buildLocationCode();
      });
      

      $('#submitform').click(function() {

        returnval = true;
        message = "";


        // isDuplicate("#macaddress", "macaddress", "venues", "Mac Address").success(function (exists) {
        //     if(exists) {console.log("EXISTS")} else {console.log("NOT EXISTS")}
    // do something with data
        // });

        // if (isDuplicate("#macaddress", "macaddress", "venues", "Mac Address")) {
        //   alert("macaddress is duplicate : ");
          
        //   message = "Mac Address " + $( "#macaddress" ).val() + " already exists";
        //   returnval = false;
        // } else {alert("macaddress is NOT duplicate : ");}



        // if (isSitenameDuplicate($( '#sitename' ).val(), $( "#brandlist" ).val())) {
        //   alert("JJJJJJJJJJ");
        //   message = message + "Sitename " + $( "#sitename" ).val() + " already exists";
        //   returnval = false;
        // }


        // if (!returnval) sweetAlert("Error", message);

        // return false; 
      });

      function isSitenameDuplicate(sitename, brand_id) {

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
                'sitename': sitename, 
                'brand_id': brand_id 
            },
            url: "{{ url('lib_issitenameduplicate'); }}",
            success: function(message) {
                // alert("aaaaaaaaaaaaaaaaaaa "); 
              if(message == "exists") { 
                // alert("true "); 
                return true; 
              } else { 
                // alert("false ");
                return false; 
              }
            }
          });

      }
      function isDuplicate(id, column, table, label) {

        value=$( id ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
                'table': table, 
                'column': column, 
                'value': value 
            },
            url: "{{ url('lib_isduplicate'); }}",
            success: function(message) {
              if(message == "exists") { 
                // alert("true "); 
                return 1; 
              } else { 
                // alert("false ");
                return 0; 
              }
            }
          });

      }

      function buildLocationCode() {
        console.log("buildLocationCode");

        // isp_id=$( "#isplist" ).val();
        brand_id=$( "#brandlist" ).val();
        sitename=$( "#sitename" ).val();
        countrie_id=$( "#countrielist" ).val();
        province_id=$( "#provincelist" ).val();
        citie_id=$( "#citielist" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
                'brand_id': brand_id, 
                'sitename': sitename, 
                'countrie_id': countrie_id, 
                'province_id': province_id, 
                'citie_id': citie_id
            },
            url: "{{ url('lib_buildlocationcode'); }}",
            success: function(locationCode) {
              htmlstring = '<input id="locationcode" type="text" class="form-control"  \
                placeholder="' + locationCode + '" disabled>';
              $( "#locationCodeDisplayed" ).html( htmlstring );

              htmlstring = '<input type="hidden" name="location" value = "' + locationCode + '">';
              $( "#locationCodeHidden" ).html( htmlstring );
            }
          });
      }


      function buildProvinceList() {
        var countrie_id = $( "#countrielist" ).val();
        console.log("buildProvinceList " + countrie_id);

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_getprovinces/" + countrie_id + "'); }}",
            success: function(provinces) {
              var provincesjson = JSON.parse(provinces); 
              console.log("Provinces : " + provinces);

              openSelect = '<select id="provincelist" name="countrie_id" class="form-control">';
              options = '<option selected="selected">Please select</option>';
              $.each(provincesjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#provincelist" ).html( selectHtml );

            }
          });
      }

      function buildCityList() {
        var province_id = $( "#provincelist" ).val();
        console.log("buildCityList " + province_id);

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_getcities/" + province_id + "'); }}",
            success: function(cities) {
              var citiesjson = JSON.parse(cities); 
              console.log("cities : " + cities);

              options = '<option id="citielist" selected="selected">Please select</option>';
              $.each(citiesjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#citielist" ).html( selectHtml );

            }
          });
      }

      function buildServerList() {
        brand_id = $('#brand_id').val() || $( "#brandlist" ).val();
        console.log("brand_id " + brand_id);

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "{{ url('lib_getservers/" + brand_id + "'); }}",
            success: function(servers) {
              var serversjson = JSON.parse(servers); 
              
              openSelect = '<select id="serverlist" name="server_id" class="form-control">';
              options = '';
              selected = '';
              $.each(serversjson, function(index, value) {
                  sid = $('#server_id').val();
                  if( +sid == +value["id"] ) {
                    selected="selected";
                  }
                  options = options + '<option value="' + value["id"] + '" ' + selected + ' >' + value["hostname"] + '</option>';
                  selected = '';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;
              console.log("selectHtml : " + selectHtml);

              $( "#serverlist" ).html( selectHtml );

            }
          });
      }

      $(function() {
        measurenames = {};
        $('#mon_from, #tue_from, #wed_from, #thu_from, #fri_from, #sat_from, #sun_from').timepicker(
          { 'timeFormat': 'H:i' ,
            'noneOption': [
                {
                    'label': 'Closed',
                    'value': 'Closed'
                }
            ]
          }
        );

        $('#mon_from').val('{{$data['venue']->mon_from}}');
        $('#mon_to').val('{{$data['venue']->mon_to}}');

        $('#tue_from').val('{{$data['venue']->tue_from}}');
        $('#tue_to').val('{{$data['venue']->tue_to}}');

        $('#wed_from').val('{{$data['venue']->wed_from}}');
        $('#wed_to').val('{{$data['venue']->wed_to}}');

        $('#thu_from').val('{{$data['venue']->thu_from}}');
        $('#thu_to').val('{{$data['venue']->thu_to}}');

        $('#fri_from').val('{{$data['venue']->fri_from}}');
        $('#fri_to').val('{{$data['venue']->fri_to}}');

        $('#sat_from').val('{{$data['venue']->sat_from}}');
        $('#sat_to').val('{{$data['venue']->sat_to}}');

        $('#sun_from').val('{{$data['venue']->sun_from}}');
        $('#sun_to').val('{{$data['venue']->sun_to}}');

        if($('#mon_from').val() == "Closed") { $('#mon_to').hide(); };
        if($('#tue_from').val() == "Closed") { $('#tue_to').hide(); };
        if($('#wed_from').val() == "Closed") { $('#wed_to').hide(); };
        if($('#thu_from').val() == "Closed") { $('#thu_to').hide(); };
        if($('#fri_from').val() == "Closed") { $('#fri_to').hide(); };
        if($('#sat_from').val() == "Closed") { $('#sat_to').hide(); };
        if($('#sun_from').val() == "Closed") { $('#sun_to').hide(); };


        $('#mon_to, #tue_to, #wed_to, #thu_to, #fri_to, #sat_to, #sun_to').timepicker({ 'timeFormat': 'H:i' });

      });

      $('#mon_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#mon_to').hide();
        } else {
          $('#mon_to').timepicker('option', 'minTime', $(this).val());
          $('#mon_to').timepicker('option', 'maxTime', '12am');
          $('#mon_to').show();
        }
      });

      $('#tue_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#tue_to').hide();
        } else {
          $('#tue_to').timepicker('option', 'minTime', $(this).val());
          $('#tue_to').timepicker('option', 'maxTime', '12am');
          $('#tue_to').show();
        }
      });


      $('#wed_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#wed_to').hide();
        } else {
          $('#wed_to').timepicker('option', 'minTime', $(this).val());
          $('#wed_to').timepicker('option', 'maxTime', '12am');
          $('#wed_to').show();
        }
      });


      $('#thu_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#thu_to').hide();
        } else {
          $('#thu_to').timepicker('option', 'minTime', $(this).val());
          $('#thu_to').timepicker('option', 'maxTime', '12am');
          $('#thu_to').show();
        }
      });


      $('#fri_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#fri_to').hide();
        } else {
          $('#fri_to').timepicker('option', 'minTime', $(this).val());
          $('#fri_to').timepicker('option', 'maxTime', '12am');
          $('#fri_to').show();
        }
      });


      $('#sat_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#sat_to').hide();
        } else {
          $('#sat_to').timepicker('option', 'minTime', $(this).val());
          $('#sat_to').timepicker('option', 'maxTime', '12am');
          $('#sat_to').show();
        }
      });      


      $('#sun_from').on('changeTime', function() {
        if($(this).val() == "Closed") {
          $('#sun_to').hide();
        } else {
          $('#sun_to').timepicker('option', 'minTime', $(this).val());
          $('#sun_to').timepicker('option', 'maxTime', '12am');
          $('#sun_to').show();
        }
      });


    </script>

  </body>
@stop
