$( "#initiateemaillist" ).on( "click", function() {
	// buildEmailDropDown();
});

$( "#saveemailnotification" ).click(function(event) {

	if($( "#emailname" ).val() == "") {
		alert("Please choose a name");
	} else {

		if(edit) {
		  	url = "/hipengage_editemail";
		} else {
		  	url = "/hipengage_addemail";
		}

	    var name = $( '#emailname' ).val();
	    var subject = $( '#emailsubject' ).val();
	    var brand_id = $( '#brandlist' ).val();
	    var message = $( '#emailmessage' ).val();
	    var data = {'id': id, 'name': name, 'subject': subject, 'message': message, 'brand_id': brand_id};
	    console.log(data);

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  data: data,
		  url: url ,
		  success: function(data) {
		    emailnotificationid = data["id"];
		    console.log("saveemailnotification : " + emailnotificationid);
			buildEmailDropDown();
			$("#addEmailNotificationModal").modal('hide');
			window.location.href = shownotificationsurl;
		  }
		});
	}
});

function displayEmailNotification(id) {

	var notification_html = '\
                  <div class="col-md-8">\
                    <div class="panel panel-default">\
                      <div class="panel-heading">\
                        Email Notification Settings\
                      </div>\
                      <div class="panel-body">\
                        <div class="form-group">\
                          <label>Subject</label>\
                          <div id="emailsubject"> </div>\
                        </div>\
                        <div class="form-group">\
                          <label>Message </label> \
                          <div id="emailmessage"> </div>\
                        </div>\
                      </div>\
                    </div>\
                  </div>';

	$("#notificationdisplay").html(notification_html);

	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: "/lib_getengageemailnotification/" + id  ,
	  success: function(data) {

	  	if(data) {

			var emailnotificationsson = JSON.parse(data);
	    	var subject = emailnotificationsson['subject'];
	    	var message = emailnotificationsson['message'];

		    console.log("displayEmailNotification : " + data);
			$("#emailsubject").html(subject);
			$("#emailmessage").html(message);

	  	}
	  }
	});	
};

function populateEditFields(id) {

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "/lib_getengageemailnotification/" + id  ,
		  success: function(data) {
			var Emailnotificationsson = JSON.parse(data);
		  	$( '#emailname' ).val(Emailnotificationsson['name']);
		  	$( '#emailsubject' ).val(Emailnotificationsson['subject']);
	    	$( '#emailmessage' ).val(Emailnotificationsson['message']);
		    console.log("editemailnotification : " + data);
		  }
		});		
}

function buildEmailDropDown () {

	// console.log("buildEmailDropDown : notificationid = " + notificationid);

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getengageemailnotifications",
	    success: function(emailnotifications) {
	      var emailnotificationsjson = JSON.parse(emailnotifications); 
	      var openSelect = '<select id="emailnotificationlist" name="emailnotification" class="form-control">';
	      var options = '<option selected="selected" value="0">Please select</option>';

	      $.each(emailnotificationsjson, function(index, value) {
	      	  if(emailnotificationid == value["id"]) {
	      	  	selected = "selected";
	      	  } else {
	      	  	selected = "";
	      	  }
	          options = options + '<option value="' + value["id"] + ' "' + selected + '>' + value["name"] + '</option>';
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#emailnotificationlist" ).html( selectHtml );
		  $('#emailnotificationlist').change();
	    }
	});

	$('#emailnotificationlist').click();
}