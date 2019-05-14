


//$("#indexPage").live('pageinit', function() {
//    alert("Index page");
//});

$(document).ready(function() {
    $.metadata.setType("attr", "validate");
    $("#quickieform").validate({

        submitHandler: function(form) {
                form.submit();
        }

     });
});

//  The following commented code is for client side slider validation (If we can ever get jQuery to work)

//var isSlider = "false";
//var slidermoved = "no";
//$(document).ready(function() {
//$("#indexPage").live('pageinit', function() {
    //alert('document ready');


    // Radio and checkbox validation
//    if(isSlider == "true") {
//        alert('isSlider is true');
//        // Slider validation
//        $( "#slider-0" ).bind( "change", function(event, ui) {
//            slidermoved = "yes";
//        });


//        $('#quickieform').submit(function(e) {
//            //alert('quickieform submitted');
//
//            if(slidermoved == "no") {
//                alert("slidermoved : no")
//                //$("#widthslidermsg").html("<label>Please indicate your response below.</label>");
//                $("#slidermsg").html("<label>Please indicate your response below.</label>");
//                e.preventDefault();
//                return false;
//            } else {
//                alert("slidermoved : yes")
//            }
//
//        });

//    } else {
//        alert('isSlider is NO');
//        $.metadata.setType("attr", "validate");
//        $("#quickieform").validate({
//
//            submitHandler: function(form) {
//                form.submit();
//            }
//
//        });
//    }
//});
