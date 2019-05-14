
$(function() {
   smsedit = false;
   smsnotificationid = 0;
   console.log("smsedit : " + smsedit);
});

$( "#initiatesmslist" ).on( "click", function() {
	buildSmsDropDown();
});

$( "#addsmsnotification" ).click(function(event) {
	smsedit = false;
});

$( "#smsnotificationlist" ).on( "change", function() {
	
	displaySmsNotification($( "#smsnotificationlist" ).val());
});


function populateEditFields(id) {

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "/lib_getengagesmsnotification/" + id  ,
		  success: function(data) {
			var Smsnotificationsson = JSON.parse(data);
		  	$( '#smsname' ).val(Smsnotificationsson['name']);
		  	$( '#survey_nastype' ).val(Smsnotificationsson['survey_nastype']);
		  	$( '#survey_q1' ).val(Smsnotificationsson['survey_q1']);
		  	$( '#survey_q2' ).val(Smsnotificationsson['survey_q2']);
	    	$( '#smsmessage' ).val(Smsnotificationsson['message']);
		    console.log("editsmsnotification : " + data);
		  }
		});		
}

function displaySmsNotification(id) {

	var notification_html = '\
            <div class="col-md-10">\
              <div class="panel panel-default">\
                <div class="panel-heading"> Message </div>\
                  <div class="panel-body">\
                    <div id="smsdisplaymessage"></div>\
                </div>\
              </div>\
            </div>';

	$("#notificationdisplay").html(notification_html);

	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: "/lib_getengagesmsnotification/" + id  ,
	  success: function(data) {

	  	if(data) {

			var smsnotificationsson = JSON.parse(data);
	    	var message = smsnotificationsson['message'];
		    console.log("editsmsnotification : " + data);
			$("#smsdisplaymessage").html(message);

	  	}
	  }
	});	
};

$( "#editsmsnotification" ).click(function(event) {
	if( $('#smsnotificationlist').val() ==  0) {
		alert("Please select a notification");
	} else {

		smsedit = true;
		$('#addSmsNotificationModal').modal('toggle');

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "/lib_getengagesmsnotification/" + $('#smsnotificationlist').val()  ,
		  success: function(data) {
			var smsnotificationsson = JSON.parse(data);
		  	$( '#smsname' ).val(smsnotificationsson['name']);
	    	$( '#smsmessage' ).val(smsnotificationsson['message']);
		    console.log("editsmsnotification : " + data);

		  }
		});		
	}
});


$( "#savesmsnotification" ).click(function(event) {

	if($( "#smsname" ).val() == "") {
		alert("Please choose a name");
	} else {

		if(edit) {
		  	url = "/hipengage_editsms";
		} else {
		  	url = "/hipengage_addsms";
		}

	    var name = $( '#smsname' ).val();
	    var brand_id = $( '#brandlist' ).val();
	    var survey_nastype = $( '#survey_nastype' ).val();
	    var survey_q1 = $( '#survey_q1' ).val();
	    var survey_q2 = $( '#survey_q2' ).val();
	    var message = $( '#smsmessage' ).val();
	    var data = {'id': id, 'name': name, 'survey_nastype': survey_nastype, 'survey_q1': survey_q1, 'survey_q2': survey_q2, 'message': message, 'brand_id': brand_id};
	    console.log(data);

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  data: data,
		  url: url ,
		  success: function(data) {
		    smsnotificationid = data["id"];
		    console.log("savesmsnotification : " + smsnotificationid);
			buildSmsDropDown();
			$("#addSmsNotificationModal").modal('hide');
			window.location.href = shownotificationsurl;
		  }
		});
	}
});


function buildSmsDropDown () {

	// console.log("buildSmsDropDown : notificationid = " + notificationid);

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getengagesmsnotifications",
	    success: function(smsnotifications) {
	      var smsnotificationsjson = JSON.parse(smsnotifications); 
	      var openSelect = '<select id="smsnotificationlist" name="smsnotification" class="form-control">';
	      var options = '<option selected="selected" value="0">Please select</option>';

	      $.each(smsnotificationsjson, function(index, value) {
	      	  if(smsnotificationid == value["id"]) {
	      	  	selected = "selected";
	      	  } else {
	      	  	selected = "";
	      	  }
	          options = options + '<option value="' + value["id"] + ' "' + selected + '>' + value["name"] + '</option>';
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#smsnotificationlist" ).html( selectHtml );
		  $('#smsnotificationlist').change();
	    }
	});

	$('#smsnotificationlist').click();
}
