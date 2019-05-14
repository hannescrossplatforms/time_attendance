
$(function() {

   	console.log("Events : trigger.js");
	$('#timefrom').timepicker({ 'timeFormat': 'H:i' });
	$('#timeto').timepicker({ 'timeFormat': 'H:i' });
		  
   	$("#standardcriteriadiv").hide() ;

	measurenames = {};

	$("#triggerevent").hide();
	$("#chooseapps").hide();

});

$(".expandcontract").on('click', function() {
    if ($(this).attr("class") == "expandcontract") {
      this.src = this.src.replace("expand","contract");
    } else {
      this.src = this.src.replace("contract","expand");
    }
    $(this).toggleClass("on");
 });

$('#timefrom').on('changeTime', function() {

	$('#timeto').timepicker('option', 'minTime', $(this).val());
	$('#timeto').timepicker('option', 'maxTime', '12am');
});

$( "#initiatelists" ).on( "click", function() {
	setWeekDays();
	buildApplicationList();

});

$( "#initiatepositions" ).on( "click", function() {
    showspecificbeaconpositions(locationmatchcode);
});

$(document).delegate('#countrielist', 'change', function() {
	buildProvinceList();
	buildMatchLocationCode();
});

$(document).delegate('#provincelist', 'change', function() {
	buildCityList();
	buildMatchLocationCode();
});

$(document).delegate('#citielist', 'change', function() {
	$( "#specificbeaconpositions" ).hide();

	buildVenueList();
	buildMatchLocationCode();
});

$(document).delegate('#venuelist', 'change', function() {
   	if($( '#venuelist').val() == 0 ) { 
   		$( "#specificbeaconpositions" ).hide(); 
   	};
	buildMatchLocationCode();
});

$(document).delegate('#brandlist', 'change', function() {
	if( !$('#brandlist').val() ) {
		alert("First select a brand");
	} else {
   		buildQuickieList();
	}

	buildVenueList();
	buildMatchLocationCode();
});

$( "#applicationlist" ).on( "change", function() {
	application_code = $( '#applicationlist' ).val();

	if(application_code == 999) {
		$("#triggerevent").hide();
		$("#chooseapps").show();

	} else {
		$("#chooseapps").hide();
		$("#triggerevent").show();

		buildTriggerList( application_code );
		$(' #addcriteria' ).attr('disabled','disabled');
		$("#beaconpositionsdiv").hide();

		$("#hiprmcriteriadiv").hide() ;
	   	$("#standardcriteriadiv").hide() 

	}
});

$( "#triggerlist" ).on( "change", function() {
	trigger_code = $( '#triggerlist' ).val();
	buildMeasureList( trigger_code );
    if(trigger_code == "HipJAM0002") { $("#beaconpositionsdiv").show() } else { $("#beaconpositionsdiv").hide() } ;

	if(trigger_code == "HipRM0001") { 
    	$("#hiprmcriteriadiv").show() ;
    	$("#standardcriteriadiv").hide() ;
   		buildQuickieList();

    } else { 
    	$("#hiprmcriteriadiv").hide() ;
    	$("#standardcriteriadiv").show() ;
    } ;

	$(' #addcriteria' ).attr('disabled','disabled');
});

$( "#addtimes" ).on( "click", function(event) {
	event.preventDefault();

	var fromto =  { timefrom: $( '#timefrom' ).val(), timeto: $( '#timeto' ).val() };
	eventtimesArray.push(fromto);

	buildEventTimesList();
});

$( "#quickielist" ).on( "change", function() {
	quickie_id = $('#quickielist').val();
	buildAnswerList(quickie_id);
});


$(document).delegate('#removetimes', 'click', function() {
  eventtimeindex = $(this).data('eventtime-index');
  eventtimesArray[eventtimeindex] = null;
  buildEventTimesList();
});

$( "#notificationlist" ).on( "change", function() {
	var id = $( "#notificationlist" ).val();
	if($( '#notificationtypelist' ).val() == "push") {
		displayPushNotification(id);
	} else if($( '#notificationtypelist' ).val() == "sms") {
		displaySmsNotification(id);
	} else if($( '#notificationtypelist' ).val() == "api") {
		displayApiNotification(id);
	} else if($( '#notificationtypelist' ).val() == "email") {
		displayEmailNotification(id);
	} else if($( '#notificationtypelist' ).val() == "mgr") {
		displayMgrNotification(id);
	}
});

$( "#notificationtypelist" ).on( "change", function() {
	notification_type = $( '#notificationtypelist' ).val();
	buildNotificationDropDown( notification_type );
});

function buildNotificationDropDown ( notification_type ) {

	console.log("buildNotificationDropDown : notification_type = " + notification_type);

	if(notification_type == "sms") {
		notificationid = eventjson["smsnotification_id"];
	} else if(notification_type == "email") {
		notificationid = eventjson["emailnotification_id"];
	} else if(notification_type == "push") {
		notificationid = eventjson["pushnotification_id"];
	} else if(notification_type == "api") {
		notificationid = eventjson["apinotification_id"];
	} else if(notification_type == "mgr") {
		notificationid = eventjson["mgrnotification_id"];
	}


	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getengagenotifications/" + notification_type,
	    success: function(notifications) {
	      var notificationsjson = JSON.parse(notifications); 
	      // console.log("buildPushDropDown : pushnotifications : " + pushnotifications);

	      openSelect = '<select id="notificationlist" name="notification" class="form-control">';
	      options = '<option selected="selected" value="0">Please select</option>';

	      $.each(notificationsjson, function(index, value) {
	      	  if(notificationid == value["id"]) {
	      	  	// console.log("buildPushDropDown : selected id = " + value["id"]);
	      	  	selected = "selected";
	      	  } else {
	      	  	selected = "";
	      	  }
	          options = options + '<option value="' + value["id"] + ' "' + selected + '>' + value["name"] + '</option>';
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#notificationlist" ).html( selectHtml );
		  $('#notificationlist').change();
	    }
	});

	$('#notificationlist').click();
}


function buildEventTimesList () {

	console.log("eventtimesArray : " + eventtimesArray);
	var timesTable = "";
	$.each(eventtimesArray, function(index, value) {
		if(value) {
	      	timesTable = timesTable + '\
		      	<div class="hoursfromto"> ' + value["timefrom"] + '</div>\
		      	<div class="hoursseparator">to</div>\
		      	<div class="hoursfromto"> ' + value["timeto"] + '</div>\
                <div class="hoursfromto"> <btn id="removetimes" data-eventtime-index="' + index + '" class="btn btn-default btn-delete btn-sm"> remove </btn></div>\
		        <div class="clear"></div>';
		};
	});
	$( "#timeslist" ).html(timesTable);
}

function showspecificbeaconpositions (locationCode) {

	$.ajax({
		type: "GET",
		dataType: 'json',
		contentType: "application/json",
		url: "/lib_getvenuepositions/" + locationCode,
		success: function(positions) {
		  var positionsjson = JSON.parse(positions); 
		  positioncheckboxes = "";

		  begin = ' \
				<div class="form-group"> \
						<label>Match Beacon To Specific Positions</label><br>';
		  $.each(positionsjson, function(index, value) {
		      positioncheckboxes = positioncheckboxes + 
		      '<label class="checkbox-inline"> \
		      		<input id="position_' + value["id"] + '" name="positions[]" class="positioncheckbox" value="' + value["id"] + '" type="checkbox">'+ value["name"] +  
		      '</label>';
		  });
		  end = '</div>';

		  checkboxesHtml = begin + positioncheckboxes + end;

	      $( "#specificbeaconpositions" ).html(checkboxesHtml);
		  $( "#specificbeaconpositions" ).show();

		  if(eventedit) { // Set the checkboxes for the venue positions 
			  $.each(venuepositions, function(index, value) {
				$('#position_' + value["id"]).prop('checked', true);
			  });
		  }
		}
	});
}


$( "#measurelist" ).on( "change", function(e) {
	
	measure_code = $( '#measurelist' ).val();

	var selected = $(this).find('option:selected');
	buildPeriodDropDown(selected.data('hasperiod'));

	// Hide the operator list and value fields if its an alwaystrigger
	if(measure_code == "alwaystrigger") {

		$( "#operatorlist" ).hide();
		$( "#value" ).hide();
		$(' #addcriteria ' ).removeAttr('disabled');

	} else {

		buildOperatorList( measure_code );
		$( "#operatorlist" ).show();
		$( "#value" ).show();
		$(' #addcriteria' ).attr('disabled','disabled');

	}

});

$( "#operatorlist" ).on( "change", function() {
	operator_code = $( '#operatorlist' ).val();
	setAddCriteriaButton();
});

// $( "#addcriteria" ).on( "click", function() {
$( "#addcriteria" ).click(function(event) {

	event.preventDefault();

	var selected = $( '#measurelist' ).find('option:selected');
	if(!selected.data('hasperiod')) {
		numperiods = "N/A";
		p = "N/A";
	} else {
		p = $( '#periodlist' ).val();		
		numperiods = $( '#numperiodslist' ).val();
	}

	// Add to the criteria array
	var thisCriteria =  {	
						measure_name: measurenames[$( '#measurelist' ).val()], measure_code: $( '#measurelist' ).val(), operator: $( '#operatorlist' ).val(), 
						value: $( '#value' ).val(), numperiods: numperiods, period: p, 
						required: $( '#requiredlist' ).val()
					 };
	// var thisCriteria =  {	
	// 					measure_name: measurenames[$( '#measurelist' ).val()], measure_code: $( '#measurelist' ).val(), operator: $( '#operatorlist' ).val(), 
	// 					value: $( '#value' ).val(), numperiods: $( '#numperiodslist' ).val(), period: $( '#periodlist' ).val(), 
	// 					required: $( '#requiredlist' ).val()
	// 				 };

	criteriaArray.push(thisCriteria);

	console.log(criteriaArray);
	buildCriteriaList();
});



$(document).delegate('.btn-delete', 'click', function() {
  criteriaindex = $(this).data('criteria-index');
  criteriaArray[criteriaindex] = null;
  buildCriteriaList();
});

function setWeekDays () {

		if (monday == 1) $('#monday').prop('checked', true);
		if (tuesday == 1) $('#tuesday').prop('checked', true);
		if (wednesday == 1) $('#wednesday').prop('checked', true);
		if (thursday == 1) $('#thursday').prop('checked', true);
		if (friday == 1) $('#friday').prop('checked', true);
		if (saturday == 1) $('#saturday').prop('checked', true);
		if (sunday == 1) $('#sunday').prop('checked', true);

}
function buildPeriodDropDown (hasperiod) {

	if(hasperiod) {

	        var numperiodsdropdown = ' \
		        	<select id="numperiodslist" class="form-control no-radius"> \
		              <option value="1">1</option> \
		              <option value="2">2</option> \
		              <option value="3">3</option> \
		              <option value="4">4</option> \
		              <option value="5">5</option> \
		              <option value="6">6</option> \
		              <option value="7">7</option> \
		              <option value="8">8</option> \
		              <option value="9">9</option> \
		              <option value="10">10</option> \
		              <option value="11">11</option> \
		              <option value="12">12</option> \
		            </select> \
	            ';

	        var perioddropdown = ' \
		        	<select id="numperiodsdropdown" class="form-control no-radius"> \
		              <option value="minutes">Minutes</option> \
		              <option value="hours">Hours</option> \
		              <option value="days">Days</option> \
		              <option value="weeks">Weeks</option> \
		              <option value="months">Months</option> \
		              <option value="years">Years</option> \
		            </select> \
	            ';

		    $( "#numperiodslist" ).html( numperiodsdropdown );
		    $( "#periodlist" ).html( perioddropdown );
		    $( "#numperiodslist" ).show();
			$( "#periodlist" ).show();

		} else {

			$( "#numperiodslist" ).hide();
			$( "#periodlist" ).hide();

		}

}

function setAddCriteriaButton () {

	if ( $( '#measurelist' ).val() == 0 || $( '#operatorlist' ).val() == 0 ) {
		$(' #addcriteria' ).attr('disabled','disabled');
	} else {
		$(' #addcriteria ' ).removeAttr('disabled');
	}
}

function buildCriteriaList() {

    var rows = '<table id="criterialisttable" class="criteriatable">';
    var row;
    $.each(criteriaArray, function( index, criteria ) {
    	console.log("buildCriteriaList criteria : " + criteria);

        if(criteria) {
        	row = '\
                  <tr class="cr criterialistelements">\
                      <td class="cr vspace"><label class="logicLbl">IF</label></td>\
                      <td class="cr measuretdyn"> ' +  criteria["measure_name"]  + ' </td>\
                      <td class="cr operatortdyn"> ' +  criteria["operator"]  + ' </td>\
                      <td class="cr valuetdyn"> ' +  criteria["value"]  + ' </td>\
                      <td class="cr spacertdyn"></td>\
                      <td class="cr counttdyn"> ' +  criteria["numperiods"]  + ' </td>\
                      <td class="cr periodtdyn"> ' +  criteria["period"]  + ' </td>\
                      <td class="cr"> <btn data-criteria-index="' + index + '" class="btn btn-default btn-delete btn-sm"> remove </btn></td>\
                  </tr>\
              ';
          	rows = rows + row;
        }
    });
   	rows = rows + '</table>';

    $( "table#criterialisttable" ).replaceWith( rows );
}

function buildApplicationList() {
	console.log("buildApplicationList ");

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getengageapplications",
	    success: function(applications) {
	      console.log("applications : " + applications);
	      var applicationsjson = JSON.parse(applications); 
	      console.log("applicationsjson : " + applicationsjson);

	      openSelect = '<select id="applicationlist" name="application" class="form-control">';
	      options = '<option selected="selected" value="0">Please select</option>';
	      $.each(applicationsjson, function(index, value) {
	          options = options + '<option value="' + value["code"] + '">' + value["name"] + '</option>';
	      });
	      options = options + '<option value="999">Multiple Applications</option>';
	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#applicationlist" ).html( selectHtml );
	    }
	});

	$('#applicationlist').click();
}

function buildTriggerList(application_code) {
	console.log("buildTriggerList");

    url = "/lib_getengagetriggers/" + application_code;
	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    success: function(triggers) {
	      var triggersjson = JSON.parse(triggers); 
	      console.log("triggersjson : " + triggersjson);

	      openSelect = '<select id="triggerlist" name="trigger" class="form-control">';
	      options = '<option selected="selected" value="0">Please select</option>';
	      $.each(triggersjson, function(index, value) {
	          options = options + '<option value="' + value["code"] + '">' + value["name"] + '</option>';
	      });
	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#triggerlist" ).html( selectHtml );

	    }
	});
}
	      
function buildMeasureList(trigger_code) {
	console.log("buildMeasureList");

    url = "/lib_getengagemeasures/" + trigger_code;
	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    success: function(measures) {
	      var measuresjson = JSON.parse(measures); 
	      console.log("measuresjson : " + measuresjson);

	      openSelect = '<select id="measurelist" name="measure_code" class="form-control">';
	      options = '<option selected="selected" value="0" data-measure-name="' + value["name"] + '">Please select</option>';

	      $.each(measuresjson, function(index, value) {
	          options = options + '<option value="' + value["code"] + '" data-hasperiod="' + value["hasperiod"] + '">'
	          				 		+ value["name"] 
	          					+ '</option>';
	          measurenames[ value["code"] ] = value["name"];
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#measurelist" ).html( selectHtml );

	    }
	});
}


function buildOperatorList(measure_code) {
	console.log("buildOperatorList");

    url = "/lib_getengageoperators/" + measure_code;
	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: url,
	    success: function(operators) {
	      var operatorsjson = JSON.parse(operators); 
	      console.log("operatorsjson : " + operatorsjson);

	      openSelect = '<select id="operatorlist" name="operator" class="form-control">';
	      options = '<option selected="selected" value="0">Please select</option>';
	      $.each(operatorsjson, function(index, value) {
	          options = options + '<option value="' + value["operator"] + '">' + value["operator"] + '</option>';
	      });
	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#operatorlist" ).html( selectHtml );

	    }
	});
}

function buildQuickieList() {
	console.log("buildQuickieList ");

	brand_id = $('#brandlist').val();

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getrmquickies/" + brand_id,
	    success: function(quickies) {

	      console.log("quickies : " + quickies);
	      var quickiesjson = JSON.parse(quickies); 
	      console.log("quickiesjson : " + quickiesjson);

	      openSelect = '<select id="quickielist" name="quickie" class="form-control">';
	      options = '<option selected="selected" value="0">Please select</option>';
	      $.each(quickiesjson, function(index, value) {
	          options = options + '<option value="' + value["id"] + '">' + value["question"] + '</option>';
	      });
	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#quickielist" ).html( selectHtml );
	    }
	});

	// $('#applicationlist').click();
}

function buildAnswerList(quickie_id) {
	console.log("buildAnswerList ");

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getrmquickieanswers/" + quickie_id,
	    success: function(answers) {

	      console.log("answers : " + answers);
	      var answersjson = JSON.parse(answers); 
	      console.log("answersjson : " + answersjson);

	      answerhtml = '<div id="answerlist" >Select answer(s)';
	      $.each(answersjson, function(index, value) {
            answerhtml = answerhtml  + '<div class="answerlist">\
	            							<div class="rmfirstcol">\
							                	<input name="response_ids[]" type="checkbox" value="' + value["id"] + '">\
								            </div>\
								            <div class="rmsecondcol">\
								                ' + value["response"] + '\
								            </div>\
							            </div>';
		  });
		  answerhtml = answerhtml + "</div>";

	      $( "#answerlist" ).html( answerhtml );
    	  checkRmAnswerCheckboxes();
	    }
	});

}

function checkRmAnswerCheckboxes() {

	$.each(rmanswers, function(index, value) {
		response_id = value["value"];
		$('input[value="' + response_id + '"]').prop("checked", true);
	});

}
