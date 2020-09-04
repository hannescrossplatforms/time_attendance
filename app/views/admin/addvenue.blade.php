@extends('angle_admin_layout')
<?php $edit = $data["edit"] ; 
    if (!$edit) {
      $editval = 0;
    }
    else{
      $editval = 1;
    }

  
?>
<?php error_log("EEEEEEEEEEEEEEEEEEEEEEEDIT : $edit");  ?>
<?php $myerrors = array("first" => "1", "second" => "2"); ?>


<?php if ($edit==true){
  $showadminwificonfig = 1;

  }
  else if ($edit==false){
      $showadminwificonfig = 0;

       //$numadminwifi = 0;
    }?>

@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>@if ($edit) Edit @else Add @endif  Venue</div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              Venue Information
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
              <form role="form" id="useradmin-form" method="post" 
        action=" @if ($edit) {{ url('admin_editvenue'); }} @else {{ url('admin_addvenue'); }} @endif ">
    <div class="container-fluid">
      <div class="row">

        

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
              </div>
            @endif

          <div class="row">
              <div class="col-md-12">

                      {{ Form::hidden('id', $data['venue']->id) }}
                      {{ Form::hidden('countrie_id', $data['venue']->countrie_id) }}
                      {{ Form::hidden('province_id', $data['venue']->province_id) }}
                      {{ Form::hidden('citie_id', $data['venue']->citie_id) }}
                      {{ Form::hidden('brand_id', $data['venue']->brand_id, array('id' => 'brand_id')) }}
                      {{ Form::hidden('server_id', $data['venue']->server_id, array('id' => 'server_id')) }}
                      {{ Form::hidden('isp_id', $data['venue']->isp_id) }}


                      @if ($edit)
                        {{ Form::hidden('old_sitename', $data['old_sitename']) }}
                      @endif 
                      <div class="form-group">
                        <label for="exampleInputEmail1">Sitename* </label>
                        <input  id="sitename" type="text" class="form-control" id="exampleInputEmail1" 
                                name="sitename" placeholder="" 
                                value="@if(Input::old('sitename')){{Input::old('sitename')}}@else{{$data['venue']->sitename}}@endif" 
                                maxlength="18"
                                required>
                      </div>

                      @if (!$edit) 
                      <div class="form-group">
                        <label>Country*</label>
                        <select id="countrielist" name="countrie_id" class="form-control">
                          @foreach($data['allcountries'] as $countrie)
                            <option value="{{ $countrie->id }}">
                              {{ $countrie->name }}
                            </option>
                          @endforeach 
                        </select>
                      </div>

                      <div class="form-group">
                        <label>Province*</label>
                        <select id="provincelist" name="province_id" class="form-control no-radius" required></select>
                      </div>

                      <div class="form-group">
                        <label>City*</label>
                        <select id="citielist" name="citie_id" class="form-control no-radius" placeholder"First select province" required>
                          <option selected="selected">First select province</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label>Brand*</label>
                        <select id="brandlist" name="brand_id" class="form-control no-radius">
                            @foreach($data['brands'] as $brand)
                              <option name="brand_id" value="{{ $brand->id }}">
                                {{ $brand->name }}
                              </option>
                            @endforeach 
                        </select>
                      </div>
                      @endif

                      <div class="form-group">
                        <label>Server*</label>
                        <select name="server_option" value="2" class="form-control no-radius">
                          <option name="server_option" value="1">
                                Hipspot
                          </option>
                          <option name="server_option" value="2">
                                Newspot
                          </option>
                        </select>
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
                                                                                                              
                      <div class="form-group">
                        <label for="exampleInputEmail1">Latitude</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" 
                            name="latitude" placeholder="" value="{{$data['venue']->latitude}}">
                      </div> 

                      <div class="form-group">
                        <label for="exampleInputEmail1">Longitude</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" 
                            name="longitude" placeholder="" value="{{$data['venue']->longitude}}">
                      </div> 

                      <div class="form-group">
                        <label for="exampleInputEmail1">Venue Address</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" 
                            name="address" placeholder="" value="{{$data['venue']->address}}">
                      </div> 

                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" 
                            name="contact" placeholder="" value="{{$data['venue']->contact}}">
                      </div> 

                      <div class="form-group">
                        <label for="exampleInputEmail1">Notes</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" 
                            name="notes" placeholder="" value="{{$data['venue']->notes}}">
                      </div>     

                      
                      </div>  

                    </div>

                    <!-- <br>  -->
                    <button id="submitform" class="btn btn-primary" style="margin-left: 25px">Submit</button>
                    <button onclick="history.go(-1)" href="{{ url('hipwifi_showvenues'); }}" class="btn btn-warning">Cancel</button>
                    <br>
                </div>
            </div>
        </div>
      </div>
  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>













    <script>



    $(document).ready(function(){
      
      $(':checkbox:checked').prop('checked',false);
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

    });

 

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



    </script>


@stop
