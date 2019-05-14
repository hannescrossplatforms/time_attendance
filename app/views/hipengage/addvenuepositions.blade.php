@extends('layout')


<?php $edit = $data["edit"] ?>
<?php $myerrors = array("first" => "1", "second" => "2"); ?>
<?php $selected_server_id = $data["venue"]->server_id ?>

@section('content')

  <body class="hipENGAGE">

    <form role="form" id="useradmin-form" method="get" 
        action=" @if ($edit) {{ url('hipengage_showvenues'); }} @else {{ url('hipengage_addvenue'); }} @endif ">
    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 
            <h1 class="page-header">Device Positions</h1>
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
                        <label for="exampleInputEmail1">Sitename </label><br>
                        {{$data['venue']->sitename}}
                      </div>


                      <div  class="form-group">
                        <label for="exampleInputEmail1">Location</label><br>
                        {{ $data['venue']->location }}
                        <input  type="hidden" name="location" value="{{ $data['venue']->location }}" >
                      </div>

                      <div  class="form-group">
                        <label>Positions</label><br>

                        <form class="form-inline">
                          <table class="addpositionstable">

                              <tr class="venuepositiontoptr">
                                <td class="spacersmalltd cr"></td>

                                <td class="vspace venueposition_name cr" >
<!--                                   <select id="positionnamelist " class="form-control no-radius logicelement venueposition_name">
                                    <option selected="selected" value="0">Select venue position name</option>
                                  </select> -->
                                    <select id="positionnamelist" class="form-control no-radius logicelement venueposition_name">
                                      <option selected="selected" value="0">Select Position Name</option>
                                    </select>

                                </td>

                                <td class="spacersmalltd cr"> OR </td>

                                <td class="vspace venueposition_name cr" >
                                  <input id="venueposition_name" class="form-control no-radius" placeholder="Type a new name" 
                                      type="text" required pattern="^\S+$">
                                </td>

                                <td class="spacersmalltd cr"></td>

                                <td class="vspace beacon_name cr">
<!--                              <select id="beacon_id" class="form-control no-radius">
                                    <option selected="selected" value="0">Select beacon</option>
                                    <option selected="selected" value="0">XKAUAI0000000001</option>
                                  </select> -->
                                    <select id="beaconlist" class="form-control no-radius logicelement">
                                      <option selected="selected" value="0">Select beacon</option>
                                    </select>
                                </td>

                                <td class="spacersmalltd cr"></td>

                                <td class="vspace cr">
                                  <button id="addposition" class="btn btn-primary no-radius">Add</button>
                                </td>

                                <td class="spacersmalltd cr"></td>

                              </tr>

                              <tr class="bottomtr "></tr>
                          </table>

                          <table id="positionstable"></table>

<!--                           <table id="positionstable">

                              <tr>

                                <td class="spacersmalltd cr"></td>

                                <td class="vspace venueposition_name cr" > </td>

                                <td class="spacersmalltd cr"></td>

                                <td class="vspace venueposition_name cr" >
                                  <input id="venueposition_name" class="form-control no-radius" placeholder="Edit name" type="text">
                                </td>

                                <td class="spacersmalltd cr"></td>

                                <td class="vspace cr">
                                  XKAUAI0000000001
                                </td>
                                
                                <td class="spacersmalltd cr"></td>

                                <td class="vspace"> 
                                  <btn id="updateposition" class="btn btn-default btn-delete btn-sm">Update </btn>
                                </td>

                                <td class="vspace"> 
                                  <btn id="removeposition" class="btn btn-default btn-delete btn-sm">Remove </btn>
                                </td>

                              </tr>

                          </table> -->

                        </form>
                      </div>

                    </div>

                    <br> 
                    <button id="submitform" class="btn btn-primary">Done</button>
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
    <script src="/js/hipengage/device_positions/devicepositions.js"></script> 

    <script>

      $(function() {
        $('#countrielist').change(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $('#brandlist').change(); 
        brandCode = "{{ $data['brandcode'] }}";
        locationCode = "{{ $data['venue']->location }}";
        buildbeaconlist(brandCode);
        buildpositionnamelist(brandCode);
        getPositions(locationCode)
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
                alert("true "); 
                return 1; 
              } else { 
                alert("false ");
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
