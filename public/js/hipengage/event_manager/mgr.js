$( "#initiatemgrlist" ).on( "click", function() {
	buildMgrDropDown();
});

$( "#savemgrnotification" ).click(function(event) {

	if($( "#mgrname" ).val() == "") {
		alert("Please choose a name");
	} else {

		if(edit) {
		  	url = "/hipengage_editmgr";
		} else {
		  	url = "/hipengage_addmgr";
		}

	    var name = $( '#mgrname' ).val();
	    var cellphone = $( '#mgrcellphone' ).val();
	    var brand_id = $( '#brandlist' ).val();
	    var message = $( '#mgrmessage' ).val();
	    var data = {'id': id, 'name': name, 'cellphone': cellphone, 'message': message, 'brand_id': brand_id};
	    console.log(data);

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  data: data,
		  url: url ,
		  success: function(data) {
		    mgrnotificationid = data["id"];
		    console.log("savemgrnotification : " + mgrnotificationid);
			buildMgrDropDown();
			$("#addMgrNotificationModal").modal('hide');
			window.location.href = shownotificationsurl;
		  }
		});
	}
});

function displayMgrNotification(id) {

	var notification_html = '\
                  <div class="col-md-8">\
                    <div class="panel panel-default">\
                      <div class="panel-heading">\
                        Mgr Notification Settings\
                      </div>\
                      <div class="panel-body">\
                        <div class="form-group">\
                          <label>Cellphone</label>\
                          <div id="mgrcellphone"> </div>\
                        </div>\
                        <div class="form-group">\
                          <label>Message </label> \
                          <div id="mgrmessage"> </div>\
                        </div>\
                      </div>\
                    </div>\
                  </div>';

	$("#notificationdisplay").html(notification_html);

	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: "/lib_getengagemgrnotification/" + id  ,
	  success: function(data) {

	  	if(data) {

			var mgrnotificationsson = JSON.parse(data);
	    	var cellphone = mgrnotificationsson['cellphone'];
	    	var message = mgrnotificationsson['message'];

		    console.log("displayMgrNotification : " + data);
			$("#mgrcellphone").html(cellphone);
			$("#mgrmessage").html(message);

	  	}
	  }
	});	
};

function populateEditFields(id) {

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "/lib_getengagemgrnotification/" + id  ,
		  success: function(data) {
			var Mgrnotificationsson = JSON.parse(data);
		  	$( '#mgrname' ).val(Mgrnotificationsson['name']);
		  	$( '#mgrcellphone' ).val(Mgrnotificationsson['cellphone']);
	    	$( '#mgrmessage' ).val(Mgrnotificationsson['message']);
		    console.log("editmgrnotification : " + data);
		  }
		});		
}

function buildMgrDropDown () {


	// console.log("buildMgrDropDown : notificationid = " + notificationid);

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getengagemgrnotifications",
	    success: function(mgrnotifications) {
	      var mgrnotificationsjson = JSON.parse(mgrnotifications); 
	      var openSelect = '<select id="mgrnotificationlist" name="mgrnotification" class="form-control">';
	      var options = '<option selected="selected" value="0">Please select</option>';

	      $.each(mgrnotificationsjson, function(index, value) {
	      	  if(mgrnotificationid == value["id"]) {
	      	  	selected = "selected";
	      	  } else {
	      	  	selected = "";
	      	  }
	          options = options + '<option value="' + value["id"] + ' "' + selected + '>' + value["name"] + '</option>';
	      });

	      closeSelect = '</select>';

	      selectHtml = openSelect + options + closeSelect;

	      $( "#mgrnotificationlist" ).html( selectHtml );
		  $('#mgrnotificationlist').change();
	    }
	});

	$('#mgrnotificationlist').click();
}