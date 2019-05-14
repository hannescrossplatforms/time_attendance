$(function() {
   	console.log("Events : devicepositions.js");
});

$(document).on('focusout','.venueposition',function() {
    var full_id = this.id;
	id = full_id.replace(/.*_/,'');
    name = $('#' + full_id).val();
    url = "/lib_updatevenueposition/";

    var data = {
    			'id': id, 
    			'name': name, 
    		   };

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    data: data,
	    success: function(result) {
	    }
	});
});


$(document).on('change','#positionnamelist',function() {
	positionname = $("#positionnamelist").val();

		// $("#venueposition_name").val(positionname);
	if(positionname == 0) {
		$("#venueposition_name").val("Type a new name");
	} else {
		$("#venueposition_name").val(positionname);
	}
});


$(document).on('click','.deleteposition',function() {
    var full_id = this.id;
	id = full_id.replace(/.*_/,'');

    var data = {
			'id': id, 
			'location': locationCode, 
		   };

    url = "/lib_deletevenueposition/";
    // alert(url);

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    data: data,
	    success: function(positions) {
	      	var positionsjson = JSON.parse(positions); 
			buildPositionsList(positionsjson);
        	buildbeaconlist(brandCode);
	    }
	});
});

function buildpositionnamelist(brandCode) {
	console.log("buildbeaconlist");

    url = "/lib_getpositionnames/" + brandCode;
	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    success: function(positions) {
	      var positionsjson = JSON.parse(positions); 
	      // console.log("positionsjson : " + positionsjson);

	      openSelect = '<select id="positionnamelist" name="postionname" class="form-control">';
	      options = '<option selected="selected" value="0" data-postionname-id="0">Select Position Name</option>';

	      $.each(positionsjson, function(index, value) {
	          options = options + '<option value="' + value["name"] + '">'+ value["name"] + '</option>';
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#positionnamelist" ).html( selectHtml );

	    }
	});
}

function buildbeaconlist(brandCode) {
	console.log("buildbeaconlist");

    url = "/lib_getbeacons/" + brandCode;
	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    success: function(beacons) {
	      var beaconsjson = JSON.parse(beacons); 
	      console.log("beaconsjson : " + beaconsjson);

	      openSelect = '<select id="beaconlist" name="beacon_id" class="form-control">';
	      options = '<option selected="selected" value="0" data-beacon-id="0">Select Beacon</option>';

	      $.each(beaconsjson, function(index, value) {
	          options = options + '<option value="' + value["beacon_id"] + '">'+ value["beacon_id"] + '</option>';
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#beaconlist" ).html( selectHtml );

	    }
	});
}

$( "#addposition" ).click(function(event) {

	event.preventDefault();

	if($("#beaconlist").val() == 0) {
		alert("Please select a beacon");
		return;
	}

	var name = $("#venueposition_name").val();
	if(name.match(/\s/g)){
		alert("No spaces allowed in position name");
		return;
	}

    url = "/lib_savevenueposition/";

    var data = {
    			'beacon_id': $("#beaconlist").val(), 
    			'venueposition_name': $("#venueposition_name").val(), 
    			'location': locationCode, 
    		   };

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    data: data,
	    success: function(positions) {
	      	var positionsjson = JSON.parse(positions); 
	      	console.log("positionsjson : " + positionsjson);
			buildPositionsList(positionsjson);
        	buildbeaconlist(brandCode);
	    }
	});
});

function getPositions(locationCode) {
    url = "/lib_getvenuepositions/";
	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url + locationCode,
	    success: function(positions) {
	      	var positionsjson = JSON.parse(positions); 
	      	console.log("positionsjson : " + positionsjson);
			buildPositionsList(positionsjson);
	    }
	});
};

function buildPositionsList (positionsjson) {

    var rows = '<table id="positionstable" class="positionstable">';
    var row;
    $.each(positionsjson, function( index, position ) {

      	row = '\
                 <tr>\
                    <td class="spacersmalltd cr"></td>\
                    <td class="vspace venueposition_name cr" > </td>\
                    <td class="spacersmalltd cr"></td>\
                    <td class="vspace venueposition_name cr" >\
                      <input id="venuepositionname_' + position["id"] + '" class="form-control no-radius venueposition" value="' + position["name"] +'" type="text">\
                    </td>\
                    <td class="spacersmalltd cr"></td>\
                    <td class="vspace cr">\
                      ' + position["beacon_id"] +'\
                    </td>\
                    <td class="spacersmalltd cr"></td>\
                    <td class="vspace"> \
                      <btn id="deleteposition_' + position["id"] +'" class="btn btn-default btn-delete btn-sm deleteposition">Delete</btn>\
                    </td>\
                  </tr>\
               ';

      	rows = rows + row;
    });
   	rows = rows + '</table>';

    $( "table#positionstable" ).replaceWith( rows );
}


// $( "#initiatelists" ).on( "click", function() {
// 	setWeekDays();
// 	buildApplicationList();

// });

// function showspecificbeaconpositions (locationCode) {

// 	$.ajax({
// 		type: "GET",
// 		dataType: 'json',
// 		contentType: "application/json",
// 		url: "/lib_getvenuepositions/" + locationCode,
// 		success: function(positions) {
// 		  var positionsjson = JSON.parse(positions); 
// 		  positioncheckboxes = "";

// 		  begin = ' \
// 				<div class="form-group"> \
// 						<label>Match Beacon To Specific Positions</label><br>';
// 		  $.each(positionsjson, function(index, value) {
// 		      positioncheckboxes = positioncheckboxes + 
// 		      '<label class="checkbox-inline"> \
// 		      		<input id="position_' + value["id"] + '" name="positions[]" class="positioncheckbox" value="' + value["id"] + '" type="checkbox">'+ value["name"] +  
// 		      '</label>';
// 		  });
// 		  end = '</div>';

// 		  checkboxesHtml = begin + positioncheckboxes + end;

// 	      $( "#specificbeaconpositions" ).html(checkboxesHtml);
// 		  $( "#specificbeaconpositions" ).show();

// 		  if(eventedit) { // Set the checkboxes for the venue positions 
// 			  $.each(venuepositions, function(index, value) {
// 				$('#position_' + value["id"]).prop('checked', true);
// 			  });
// 		  }
// 		}
// 	});
// }


// // $( "#addcriteria" ).on( "click", function() {
// $( "#addcriteria" ).click(function(event) {

// 	event.preventDefault();

// 	// alert(measurenames[$( '#measurelist' ).val()]);

// 	if($( '#measurelist' ).val() != "passes") {
// 		numperiods = "N/A";
// 		p = "N/A";
// 	} else {
// 		numperiods = $( '#periodlist' ).val();		
// 		p = $( '#numperiodslist' ).val();
// 	}

// 	// Add to the criteria array
// 	var thisCriteria =  {	
// 						measure_name: measurenames[$( '#measurelist' ).val()], measure_code: $( '#measurelist' ).val(), operator: $( '#operatorlist' ).val(), 
// 						value: $( '#value' ).val(), numperiods: numperiods, period: p, 
// 						required: $( '#requiredlist' ).val()
// 					 };

// 	criteriaArray.push(thisCriteria);

// 	console.log(criteriaArray);
// 	buildCriteriaList();
// });



// $(document).delegate('.btn-delete', 'click', function() {
//   criteriaindex = $(this).data('criteria-index');
//   criteriaArray[criteriaindex] = null;
//   buildCriteriaList();
// });


// function setAddCriteriaButton () {

// 	if ( $( '#measurelist' ).val() == 0 || $( '#operatorlist' ).val() == 0 ) {
// 		$(' #addcriteria' ).attr('disabled','disabled');
// 	} else {
// 		$(' #addcriteria ' ).removeAttr('disabled');
// 	}
// }

// function buildCriteriaList() {

//     var rows = '<table id="criterialisttable" class="criteriatable">';
//     var row;
//     $.each(criteriaArray, function( index, criteria ) {
//     	console.log("buildCriteriaList criteria : " + criteria);

//         if(criteria) {
//         	row = '\
//                   <tr class="cr criterialistelements">\
//                       <td class="cr vspace"><label class="logicLbl">IF</label></td>\
//                       <td class="cr measuretdyn"> ' +  criteria["measure_name"]  + ' </td>\
//                       <td class="cr operatortdyn"> ' +  criteria["operator"]  + ' </td>\
//                       <td class="cr valuetdyn"> ' +  criteria["value"]  + ' </td>\
//                       <td class="cr spacertdyn"></td>\
//                       <td class="cr counttdyn"> ' +  criteria["numperiods"]  + ' </td>\
//                       <td class="cr periodtdyn"> ' +  criteria["period"]  + ' </td>\
//                       <td class="cr"> <btn data-criteria-index="' + index + '" class="btn btn-default btn-delete btn-sm"> remove </btn></td>\
//                   </tr>\
//               ';
//           	rows = rows + row;
//         }
//     });
//    	rows = rows + '</table>';

//     $( "table#criterialisttable" ).replaceWith( rows );
// }


