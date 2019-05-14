@extends('layout')

@section('content')

  <body class="hipTnA">
    <form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savelookupmedia'); }}"></form>
    <div id="mb_ext_div" name="mb_ext" style="display:none"></div>
  
    <div class="container-fluid">
      <div class="row">

        @include('hiptna.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          <h1 class="page-header">Instant Messaging</h1>
          <div class="row">

            <div class="form-group">
              <!-- <label>Select Group </label>
              <select id="xxx" name="xxx" class="form-control no-radius" ></select>
              <br>
              <button id="savebrands" type="button" class="btn btn-primary">Add</button> -->
              <br><br>
              <div class="col-md-12">
                <div class="col-md-6 field_wrapper1">
                    <div>
                        <!-- <input type="hidden" name="hub_names[]" value=""/> -->
                        <select id="hub_name" name="hub_name" style="width:54%"; >
                          <option value="">Select Hub</option>
                          <?php 
                            foreach ($data['hubs'] as $key => $value) { ?>
                                <option value="<?php echo $value['hubname']; ?>" ><?php echo $value['hubname']; ?></option>
                          <?php }
                          ?>
                          <!-- <option value="abc">abc</option>
                          <option value="aaa">aaa</option>
                          <option value="bbb">bbb</option> -->
                        </select>
                        <a href="javascript:void(0);" class="add_button1" title="Add field"><img src="add-icon.png"/></a>
                    </div>
                </div>

                <div class="col-md-6 field_wrapper2">
                    <div>
                        <!-- <input type="hidden" name="channel_names[]" value=""/> -->
                        <select id="channel_name" name="channel_name" style="width:54%"; >
                          <option value="">Select Channel</option>
                          <?php 
                            foreach ($data['channels'] as $key => $value) { ?>
                                <option value="<?php echo $value['channel']; ?>" ><?php echo $value['channel']; ?></option>
                          <?php }
                          ?>
                          <!-- <option value="abc">abc</option>
                          <option value="aaa">aaa</option>
                          <option value="bbb">bbb</option> -->
                        </select>
                        <a href="javascript:void(0);" class="add_button2" title="Add field"><img src="add-icon.png"/></a>
                    </div>
                </div>
              </div>
            </div>
            <br><br>
            <div class="form-group">
              <textarea id="pushmessage" class="form-control no-radius" placeholder="Message content " rows="4" cols="100""></textarea>
              <br>
              <div class="">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Image
                  </div>
                  <div class="panel-body">

                    <div class="col-md-4 pushpic">
                      <div class="form-group">
                        <div id="pushimageedit" style="display:none"></div>
                        <input id="mbimage" type="file" name="mbimage" form="mbimageform">
                          <a  id="mb-file" href="#" class="btn btn-default btn-sm " data-toggle="modal" data-target="#mobileBgModal"  >
                            Upload new image
                          </a>
                        </input>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <div class="form-group">
                Send On: <input id="send_at_date" class="form-control datepicker" type="text" placeholder="Send Date" data-date-format="yyyy-mm-dd" name="send_at_date">

              </div>
              <div class="form-group">
                Send At:
                <input id="send_at_time" name="send_at_time" class="form-control time ui-timepicker-input" placeholder="Send Time" type="text" autocomplete="off">

              </div>
              <br><br>
              <div id='sentmessage'></div>
              <button id="sendMessage" type="button" class="btn btn-primary">Send</button>
              <a href="" class="btn btn-default" data-dismiss="modal">Cancel</a>
            </div>

          </div>
        </div>
      </div>

    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
     
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script src="{{ asset('js/prefixfree.min.js') }}"></script> 
    <script src="{{ asset('js/hiptna/push.js') }}"></script>
    <script src="{{ asset('/js/bootstrap-datepicker.js') }}"></script> 


    <script type="text/javascript">
      $(function() {
        availableInstances = "{{ Session::get('availableInstances') }}";
        currentInstance = "{{ Session::get('currentInstance') }}";
      });
    </script>

    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>
    
    <script src="{{ asset('/js/jquery.timepicker.min.js') }}"></script> 

    <script type="text/javascript">

    $(function() {
      $('#filter').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
      pathname = $('#url').val();
      previewurl = "{{$data['previewurl']}}";

    });

    $(document).ready(function(){

      $("#send_at_date").datepicker("setDate", new Date());

        var maxField = 10; //Input fields increment limitation
        var addButton1 = $('.add_button1'); //Add button selector
        var wrapper1 = $('.field_wrapper1'); //Input field wrapper
        var addButton2 = $('.add_button2'); //Add button selector
        var wrapper2 = $('.field_wrapper2'); //Input field wrapper
         
        var x = 1; //Initial field counter is 1
        $(addButton1).click(function(){ //Once add button is clicked
            //if(x < maxField){//Check maximum number of input fields              
                if($('#hub_name').val() != ''){
                      x++; //Increment field counter
                      $(wrapper1).append('<div><input type="text" name="hub_names[]" value="'+$("#hub_name").val()+'"/><a href="javascript:void(0);" class="remove_button" title="Remove field"><img style="padding:0px 0px 0px 8px;" src="remove-icon.png"/></a></div>'); // Add field html
                }
            //}
        });
        $(wrapper1).on('click', '.remove_button', function(e){ //Once remove button is clicked
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });

        var y = 1; //Initial field counter is 1
        $(addButton2).click(function(){ //Once add button is clicked
            //if(y < maxField){ //Check maximum number of input fields
                if($('#channel_name').val() != ''){
                    y++; //Increment field counter
                    $(wrapper2).append('<div><input type="text" name="channel_names[]" value="'+$("#channel_name").val()+'"/><a href="javascript:void(0);" class="remove_button" title="Remove field"><img style="padding:0px 0px 0px 8px;" src="remove-icon.png"/></a></div>'); // Add field html
                }
            //}
        });
        $(wrapper2).on('click', '.remove_button', function(e){ //Once remove button is clicked
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            y--; //Decrement field counter
        });
    });


    $( "#sendMessage" ).on( "click", function(event) {
        event.preventDefault();
        $("#sentmessage").html("Sending message ...");

        
        content           = $("#pushmessage").val();
        var send_at;
        var hub_values    = $("input[name='hub_names\\[\\]']")
              .map(function(){return $(this).val();}).get();
        var channel_values = $("input[name='channel_names\\[\\]']")
              .map(function(){return $(this).val();}).get();
              //alert(hub_values);
        hub_names         = JSON.stringify(hub_values),
        channels          = JSON.stringify(channel_values);

        send_at       = $("#send_at_date").val()+" "+$("#send_at_time").val();
       
        $.ajax({
            type: "GET",
            dataType: 'json',
            contentType: "application/json",
            data: { 
              'content': content,
              'hub_names': hub_values,
              'channels':channel_values,
              'mb_ext' : $("#mb_ext").val(),
              'send_at'   : send_at
            },
            url: "{{ url('hiptna_sendGroupNotifications'); }}",
            success: function(filteredStaffjson) {
              // Put some code here to handle a failure
              $("#sentmessage").html("Message Sent");
            }
         });

        event.preventDefault();
      });
    
    </script>

  </body>
@stop
