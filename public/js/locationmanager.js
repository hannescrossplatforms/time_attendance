      function buildMatchLocationCode() {
        console.log("buildLocationCode");

        if($( "#isplist" ) ) {
          isp_id=$( "#isplist" ).val();
        } else {
          isp_id=1;
        }
        brand_id=$( "#brandlist" ).val();
        countrie_id=$( "#countrielist" ).val();
        province_id=$( "#provincelist" ).val();
        citie_id=$( "#citielist" ).val();
        venue_id=$( "#venuelist" ).val();

        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
                'isp_id': isp_id, 
                'brand_id': brand_id, 
                'countrie_id': countrie_id, 
                'province_id': province_id, 
                'citie_id': citie_id,
                'venue_id': venue_id
            },
            url: "/lib_buildMatchLocationCode",
            success: function(locationCode) {
              locationmatchcode = locationCode;
              htmlstring = '<input id="locationcode" type="text" class="form-control"  \
                placeholder="' + locationCode + '" disabled>';
              $( "#locationCodeDisplayed" ).html( htmlstring );

              htmlstring = '<input type="hidden" name="location" value = "' + locationCode + '">';
              $( "#locationCodeHidden" ).html( htmlstring );
              if(venue_id != "" && currentProduct == "hipENGAGE") { // This is for Engage only
                  showspecificbeaconpositions(locationCode);
              }

            }
          });
      }

      function buildProvinceList() {
        var countrie_id = $( "#countrielist" ).val();
        console.log("buildProvinceList " + countrie_id);

            // url: "{{ url('/lib_getprovinces/" + countrie_id + "'); }}",
        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "/lib_getprovinces/" + countrie_id,
            success: function(provinces) {
              var provincesjson = JSON.parse(provinces); 
              console.log("Provinces : " + provinces);

              openSelect = '<select id="provincelist" name="countrie_id" class="form-control">';
              options = '<option selected="selected" value="0">--</option>';
              $.each(provincesjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#provincelist" ).html( selectHtml );

            }
          });
      }

            // url: "{{ url('lib_getcities/" + province_id + "'); }}",
      function buildCityList() {
        var province_id = $( "#provincelist" ).val();
        console.log("buildCityList " + province_id);

            baseurl = 
        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            url: "lib_getcities/" + province_id ,
            success: function(cities) {
              var citiesjson = JSON.parse(cities); 
              console.log("cities : " + cities);

              options = '<option id="citielist" selected="selected" value="0">--</option>';
              $.each(citiesjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#citielist" ).html( selectHtml );

            }
          });
      }

      function buildVenueList() {
        var isp_id = $( "#isplist" ).val();
        var brand_id = $( "#brandlist" ).val();
        var citie_id = $( "#citielist" ).val();

        // $( "#venuelist" ).html( "" );

        if(citie_id) {
          console.log("buildVenueList " + citie_id);

          $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
                'isp_id': isp_id, 
                'brand_id': brand_id, 
                'citie_id': citie_id,
            },
            url: "lib_getvenues",
            success: function(venues) {
              var venuesjson = JSON.parse(venues); 
              console.log("venues : " + venues);

              options = '<option id="venuelist" selected="selected" value="0">Please select</option>';
              $.each(venuesjson, function(index, value) {
                  options = options + '<option value="' + value["id"] + '">' + value["sitename"] + '</option>';
              });
              closeSelect = '</select>';

              selectHtml = openSelect + options + closeSelect;

              $( "#venuelist" ).html( selectHtml );
            }
          });
        }
      }

