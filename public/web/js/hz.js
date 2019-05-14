

// $.metadata.setType("attr", "validate");

//$(document).ready(function() {
//    $.metadata.setType("attr", "validate");
//    $("#quickieformx").validate();
//});
var widthslidermoved = "notexists";
var heightslidermoved = "notexists";
$(document).ready(function() {

    // Slider validation
    $('#quickieform').submit(function(e) {

        if(widthslidermoved == "no") {
            $("#widthslidermsg").html("<label>Please indicate your response below.</label>");
            e.preventDefault();
        } else {
            $("#widthslidermsg").html("");
        }
        if(heightslidermoved == "no") {
            $("#heightslidermsg").html("<label>Please indicate your response below.</label>");
            e.preventDefault();
        } else {
            $("#heightslidermsg").html("");
        }
        
    });

    // Radio and checkbox validation
    $.metadata.setType("attr", "validate");
    $("#quickieform").validate({

//        submitHandler: function(form) {
//            form.submit();
//        }

    });


});



//$(document).ready(function() {
//    $.metadata.setType("attr", "validate");
//    $("#quickieformxx").validate({
//        rules: {
//                answer: "required"
//
//            },
//
//        errorPlacement: function(error, element) {
//            if ( element.is(":radio"))
//                // alert("Please make a selection");
//                $("#alertbox").html("Please make aaaa selection");
//
//            else if ( element.is(":checkbox") )
//                // alert("Please make at least one selection");
//                $("#alertbox").html("Please make at least one selection");
//
//            else
//                error.appendTo( ("#alertbox") );
//        },
//        submitHandler: function(form) {
//                form.submit();
//        }
//
//     });
//});

//$(function () {
//
//    $("#quickieform1").validate({
//        rules: {
//                answer: "required"
//            },
//
//        errorPlacement: function(error, element) {
//            if ( element.is(":radio"))
//                // alert("Please make a selection");
//                $("#alertbox1").html("Please make a selection");
//
//            else if ( element.is(":checkbox") )
//                // alert("Please make at least one selection");
//                $("#alertbox1").html("Please make at least one selection");
//
//            else
//                error.appendTo( ("#alertbox1") );
//        },
//        submitHandler: function(form) {
//                form.submit();
//        }
//
//     })
//
//});
//
//$(function () {
//
//    $("#quickieform2").validate({
//        rules: {
//                answer: "required"
//            },
//
//        errorPlacement: function(error, element) {
//            if ( element.is(":radio"))
//                // alert("Please make a selection");
//                $("#alertbox2").html("Please make a selection");
//
//            else if ( element.is(":checkbox") )
//                // alert("Please make at least one selection");
//                $("#alertbox2").html("Please make at least one selection");
//
//            else
//                error.appendTo( ("#alertbox2") );
//        },
//        submitHandler: function(form) {
//                form.submit();
//        }
//
//     })
//
//});


