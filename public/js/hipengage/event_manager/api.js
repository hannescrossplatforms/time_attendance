
$(function() {
   console.log("Events : api.js");
   apiedit = false;
   console.log("apiedit : " + apiedit);
   apinotificationid = 0;
});

$( "#initiateapilist" ).on( "click", function() {
	buildApiDropDown();
});

$( "#addapinotification" ).click(function(event) {
	apiedit = false;
});

$( "#apinotificationlist" ).on( "change", function() {
	displayApiNotification($( "#notificationlist" ).val());
});

function populateEditFields(id) {

	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: "/lib_getengageapinotification/" + id  ,
	  success: function(data) {
		var Smsnotificationsson = JSON.parse(data);
	  	$( '#apiname' ).val(Smsnotificationsson['name']);
    	$( '#apiauth' ).val(Smsnotificationsson['auth']);
    	$( '#apiurl' ).val(Smsnotificationsson['url']);
	    console.log("editapinotification : " + data);
	  }
	});		
}

function displayApiNotification(id) {

	var notification_html = '\
                  <div class="col-md-8">\
                    <div class="panel panel-default">\
                      <div class="panel-heading">\
                        Api Notification Settings\
                      </div>\
                      <div class="panel-body">\
                        <div class="form-group">\
                          <label>Auth Token </label>\
                          <div id="apidisplayauth"> </div>\
                        </div>\
                        <div class="form-group">\
                          <label>Endpoint </label> \
                          <div id="apidisplayurl"> </div>\
                        </div>\
                      </div>\
                    </div>\
                  </div>';

	$("#notificationdisplay").html(notification_html);

	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: "/lib_getengageapinotification/" + id  ,
	  success: function(data) {

	  	if(data) {

			var apinotificationsson = JSON.parse(data);
	    	var auth = apinotificationsson['auth'];
	    	var url = apinotificationsson['url'];

		    console.log("displayApiNotification : " + data);
			$("#apidisplayauth").html(auth);
			$("#apidisplayurl").html(url);

	  	}
	  }
	});	
};

$( "#editapinotification" ).click(function(event) {
	if( $('#apinotificationlist').val() ==  0) {
		alert("Please select a notification");
	} else {

		apiedit = true;
		$('#addApiNotificationModal').modal('toggle');

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "/lib_getengageapinotification/" + $('#apinotificationlist').val()  ,
		  success: function(data) {
			var apinotificationsson = JSON.parse(data);
		  	$( '#apiname' ).val(apinotificationsson['name']);
	    	$( '#apiauth' ).val(apinotificationsson['auth']);
	    	$( '#apiurl' ).val(apinotificationsson['url']);
		    console.log("editapinotification : " + data);

		    showApiImageEditFirst(apinotificationsson['image_url']);
		  }
		});		
	}
});



$( "#saveapinotification" ).click(function(event) {

	if(edit) {
	  	ajaxurl = "/hipengage_editapi";
	} else {
	  	ajaxurl = "/hipengage_addapi";
	}

    var name = $( '#apiname' ).val();
	var brand_id = $( '#brandlist' ).val();
    var auth = $( '#apiauth' ).val();
    var url = $( '#apiurl' ).val();
    var data = { 'id': id, 'name': name, 'auth': auth, 'url': url, 'brand_id': brand_id };
    console.log(data);

	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  data: data,
	  url: ajaxurl ,
	  success: function(data) {
	    apinotificationid = data["id"];
	    console.log("saveapinotification : " + apinotificationid);
		buildApiDropDown();
		$("#addApiNotificationModal").modal('hide');
		window.location.href = shownotificationsurl;
	  }
	});
});


function buildApiDropDown () {

	console.log("buildApiDropDown : apinotificationid = " + apinotificationid);

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getengageapinotifications",
	    success: function(apinotifications) {
	      var apinotificationsjson = JSON.parse(apinotifications); 
	      console.log("buildApiDropDown : apinotifications : " + apinotifications);

	      var openSelect = '<select id="apinotificationlist" name="apinotification" class="form-control">';
	      var options = '<option selected="selected" value="0">Please select</option>';

	      $.each(apinotificationsjson, function(index, value) {
	      	  if(apinotificationid == value["id"]) {
	      	  	console.log("buildApiDropDown : selected id = " + value["id"]);
	      	  	selected = "selected";
	      	  } else {
	      	  	selected = "";
	      	  }
	          options = options + '<option value="' + value["id"] + ' "' + selected + '>' + value["name"] + '</option>';
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#apinotificationlist" ).html( selectHtml );
		  $('#apinotificationlist').change();
	    }
	});

	$('#apinotificationlist').click();
}
