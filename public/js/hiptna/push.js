
$(function() {
   console.log("Events : push.js");
   pushedit = false;
   console.log("pushedit : " + pushedit);
   notificationid = 0;
});

$( "#initiatepushlist" ).on( "click", function() {
	buildPushDropDown();
});

$( "#addpushnotification" ).click(function(event) {
	pushedit = false;
});

$( "#pushnotificationlist" ).on( "change", function() {
	displayPushNotification($( "#pushnotificationlist" ).val());
});



function populateEditFields(id) {
	if( $('#pushnotificationlist').val() ==  0) {
		alert("Please select a notification");
	} else {

		pushedit = true;
		$('#addNotificationModal').modal('toggle');
	   	$( '#pushtypesound' ).prop('checked', false);
	   	$( '#pushtypevibrate' ).prop('checked', false);

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "/lib_getengagepushnotification/" + id  ,
		  success: function(data) {
			var pushnotificationsson = JSON.parse(data);
		  	$( '#pushname' ).val(pushnotificationsson['name']);
	    	$( '#pushmessage' ).val(pushnotificationsson['message']);
	    	$( '#pushimageurl' ).val(pushnotificationsson['image_url']);
	    	if( pushnotificationsson['sound'] == 1 ) $( '#pushtypesound' ).prop('checked', true);
	    	if( pushnotificationsson['vibrate'] == 1 ) $( '#pushtypevibrate' ).prop('checked', true);
	    	$( '#pushpreload' ).val(pushnotificationsson['preload']);
		    console.log("editpushnotification : " + data);

		    showPushImageEditFirst(pushnotificationsson['image_url']);
		  }
		});		
	}
}
// });


// BEGIN Upload Image
var mbwrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
var mbimage = $('#mbimage').wrap(mbwrapper);
mbimage.change(function(){
  $this = $(this);
  $('#mb-file').text($this.val());
});

$('#mb-file').click(function(){
  mbimage.click();
}).show();

$('body').delegate('#mbimage','change', function(){
	var options = { 
	      success:       showPushImageEdit,
	      dataType: 'text' 
	      }; 
	$('#mbimageform').ajaxForm(options).submit();    
});

function showPushImageDisplay(image_url){

	// The Math.random() is to ensure that the image gets refreshed by making the url unique
	src = image_url + "?" + Math.random();

	imgtag = "<img src='" + src + "' style='margin-bottom: 10px;' class='img-responsive'/>";
	console.log("showPushImageDisplay : imgtag " + imgtag);

	$("#pushimagedisplay").html(imgtag);
	$("#pushimagedisplay").css('display','block');

}

function showPushImageEditFirst(image_url){
	console.log("In showPushImageEditFirst");

	// The Math.random() is to ensure that the image gets refreshed by making the url unique
	src = image_url + "?" + Math.random();
	
	imgtag = "<img src='" + src + "' style='margin-bottom: 10px;' class='img-responsive'/>";

	$("#pushimageedit").html(imgtag);
	$("#pushimageedit").css('display','block');

}

function showPushImageEdit(extension){
	console.log("In showPushImageEdit");

	// The Math.random() is to ensure that the image gets refreshed by making the url unique
	src = previewurl + "preview." + extension + "?" + Math.random();

	imgtag = "<img src='" + src + "' style='margin-bottom: 10px;' class='img-responsive'/>";

	$("#pushimageedit").html(imgtag);
	$("#pushimageedit").css('display','block');

	$( "#mb_ext_div" ).html( "<input type='hidden' id='mb_ext' name='mb_ext' value='" + extension + "' form='mbimageform' />" );
}

function displayPushNotification() {

	var notification_html = '\
	      <div class="col-md-4">\
	        <div class="panel panel-default">\
	          <div class="panel-heading">\
	            image\
	          </div>\
	          <div id="pushimagedisplay" style="display:none"></div>\
	        </div>\
	      </div>\
	      <div class="col-md-8">\
	        <div class="panel panel-default">\
	          <div class="panel-heading">\
	            Push Notification Settings\
	          </div>\
	          <div class="panel-body">\
	            <div class="form-group">\
	              <label>Notification Type: </label> \
	              <div id="pushdisplaynotificationtype"> </div>\
	            </div>\
	            <div class="form-group">\
	              <label>Preload Notification: </label> \
	              <div id="pushdisplaypreload"> </div>\
	            </div>\
	          </div>\
	        </div>\
	      </div>\
	      <div class="col-md-8">\
	        <div class="panel panel-default">\
	          <div class="panel-heading"> Message </div>\
	            <div class="panel-body">\
	              <div id="pushdisplaymessage"></div>\
	          </div>\
	        </div>\
	      </div>';

	 
	$("#notificationdisplay").html(notification_html);


	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: "/lib_getengagepushnotification/" + $('#notificationlist').val()  ,
	  success: function(data) {

	  	if(data) {

			var pushnotificationsson = JSON.parse(data);
	    	var message = pushnotificationsson['message'];
	    	var image_url = pushnotificationsson['image_url'];
	    	var sound = pushnotificationsson['sound'];
	    	var vibrate = pushnotificationsson['vibrate'];
	    	var preload = pushnotificationsson['preload'];
		    console.log("editpushnotification : " + data);
			$("#pushdisplaymessage").html(message);

			var notificationtypes = "";
			if(sound == 1) {
				notificationtypes = notificationtypes + "Sound ";
			} 
			if(vibrate == 1) {
				notificationtypes = notificationtypes + "Vibrate ";
			}
			$("#pushdisplaynotificationtype").html(notificationtypes);

			if(preload == 1) {
				$("#pushdisplaypreload").html("true");
			} else {
				$("#pushdisplaypreload").html("false");
			}
		    showPushImageDisplay(image_url);
	  	}
	  }
	});	
};

// function showMobileImage(image_url){
// 	alert(" extension : " + extension);

// 	// The Math.random() is to ensure that the image gets refreshed by making the url unique
// 	src = "src='" + previewurl + "preview-mb." + extension + "?" + Math.random() + "'";
// 	alert(" src : " + src);

// 	imgtag = "<img " + src + " style='margin-bottom: 10px;' class='img-responsive'/>";

// 	$("#imagedisplaymb").html(imgtag);
// 	$("#imagedisplaymb").css('display','block');

// 	$( "#mb_ext_div" ).html( "<input type='hidden' id='mb_ext' name='mb_ext' value='" + extension + "'/>" );
// }

// END Upload Image

$( "#savepushnotification" ).click(function(event) {

	var url = ""; 
	if($( "#pushname" ).val() == "") {
		alert("Please choose a name");
	} else {

		if(edit) {
		  	url = "/hipengage_editpush";
		} else {
			id = 0;
		  	url = "/hipengage_addpush";
		}

	    var sound = vibrate = 0;
	    var name = $( '#pushname' ).val();
	    var message = $( '#pushmessage' ).val();
	    var image_url = $( '#pushimageurl' ).val();
	    var mb_ext = $( '#mb_ext' ).val();
	    var brand_id = $( '#brandlist' ).val();
	    if( $( '#pushtypesound' ).is(':checked') ) sound = 1;
	    if( $( '#pushtypevibrate' ).is(':checked') ) vibrate = 1;
	    var preload = $( '#pushpreload' ).val();
	    var data = {'id': id, 'name': name, 'message': message, 'image_url': image_url, 'sound': sound, 
	    			'vibrate': vibrate , 'preload': preload, 'mb_ext': mb_ext, 'brand_id': brand_id};
	    console.log(data);

		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  data: data,
		  url: url ,
		  success: function(data) {
		    notificationid = data["id"];
		    console.log("savepushnotification : " + notificationid);
		    showPushImageDisplay(data["image_url"]);
			buildPushDropDown();
			$("#addNotificationModal").modal('hide');
			window.location.href = shownotificationsurl;
		  }
		});
	}
});


function buildPushDropDown () {

	// console.log("buildPushDropDown : notificationid = " + notificationid);

	$.ajax({
	    type: "GET",
	    dataType: 'json',
	    contentType: "application/json",
	    url: "/lib_getengagepushnotifications",
	    success: function(pushnotifications) {
	      var pushnotificationsjson = JSON.parse(pushnotifications); 
	      // console.log("buildPushDropDown : pushnotifications : " + pushnotifications);

	      openSelect = '<select id="pushnotificationlist" name="pushnotification" class="form-control">';
	      options = '<option selected="selected" value="0">Please select</option>';

	      $.each(pushnotificationsjson, function(index, value) {
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

	      $( "#pushnotificationlist" ).html( selectHtml );
		  $('#pushnotificationlist').change();
	    }
	});

	$('#pushnotificationlist').click();
}
