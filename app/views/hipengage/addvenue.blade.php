@extends('layout')


<?php $edit = $data["edit"] ?>
<?php error_log("EEEEEEEEEEEEEEEEEEEEEEEDIT : $edit");  ?>
<?php $myerrors = array("first" => "1", "second" => "2"); ?>
<?php $selected_server_id = $data["venue"]->server_id ?>

@section('content')

  <body class="hipENGAGE">

    <form role="form" id="useradmin-form" method="post" 
        action=" @if ($edit) {{ url('hipengage_editvenue'); }} @else {{ url('hipengage_addvenue'); }} @endif ">
    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 
            <h1 class="page-header">@if ($edit) Edit @else Add @endif  Venue</h1>
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

                      <div  class="form-group">
                        <label for="exampleInputEmail1">Location</label>
                        <div id="locationCodeHidden"></div>
                        <div id="locationCodeDisplayed">
                            @if (\User::hasAccess("superadmin")) 
                            <input  id="locationcode" name="location" class="form-control" type="text" 
                                    value="{{$data['venue']->location}}" 
                                    placeholder="This field autocompletes2 - please complete all fields above" required>
                            @else
                            <input  id="" name="" class="form-control" type="text" 
                                    value="{{$data['venue']->location}}" disabled >
                            <input  type="hidden" name="location" value="{{ $data['venue']->location }}" >
                            @endif
                            <input  type="hidden" name="oldlocation" value="{{ $data['venue']->location }}" >
                        </div>
                      </div>


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

                    <br> 
                    <button id="submitform" class="btn btn-primary">Submit</button>
                    <a href="{{ url('hipengage_showvenues'); }}" class="btn btn-default">Cancel</a>
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

    <script>

      $(function() {
        $('#countrielist').change(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $('#brandlist').change(); 
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


    </script>

  </body>
@stop
