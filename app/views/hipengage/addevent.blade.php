<?php $edit = $data["edit"] ?>
@extends('layout')

@section('content')

  <body class="hipENGAGE">
    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
    <a id="initiatelists"></a>
    <a id="initiatepositions"></a>
    <a id="initiatesmslist"></a>
    <a id="initiateemaillist"></a>
    <a id="initiatepushlist"></a>
    <a id="initiateapilist"></a>

          <h1 class="page-header">Edit Event</h1>
          <div role="tabpanel"> 
            
            <!-- Nav tabs -->
         <!--    <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#eventTrigger" aria-controls="eventTrigger" role="tab" data-toggle="tab">Event Trigger</a></li> -->

            </ul>
           
            <!-- Tab panes -->
            <div class="tab-content">

              <div role="tabpanel" class="tab-pane active" id="eventTrigger"> <br>
                    @include('hipengage.event_triggers')
              </div>

              <div class="input-group submitpush">

                <a href="" id="saveevent" class="btn btn-primary">Submit</a>
                <a href="{{ url('hipengage_showevents'); }}" class="btn btn-default">Cancel</a>

              </div>

            </div>

          </div>

        </div>

      </div>
      
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <script src="/js/jquery.form.js"></script>
    <script src="/js/prefixfree.min.js"></script> 
    <script src="/js/jquery.timepicker.min.js"></script> ft

    <script src="/js/hipengage/event_manager/trigger.js"></script> 
    <script src="/js/hipengage/event_manager/sms.js"></script> 
    <script src="/js/hipengage/event_manager/email.js"></script> 
    <script src="/js/hipengage/event_manager/push.js"></script> 
    <script src="/js/hipengage/event_manager/mgr.js"></script> 
    <script src="/js/hipengage/event_manager/api.js"></script> 
    <script src="/js/locationmanager.js"></script> 
    <script src="/js/bootstrap-datepicker.js"></script> 

    <script>

$("#schedulebegin, #scheduleend").datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true,
  orientation: "right bottom"
});
$("#schedulebegin, #scheduleend").datepicker("setDate", new Date());

      $(function() {

        eventedit = <?php echo $data["edit"] ; ?>;
        trigger_code = "";

        if(!eventedit) $("#hiprmcriteriadiv").hide() ;

        console.log("eventedit : " + eventedit);

        $('#position_wildcards_validationerror').hide();
        $('#eventnamevalidationerror').hide();
        $('#timesvalidationerror').hide();
        $('#criteriavalidationerror').hide();
        $("#beaconpositionsdiv").hide();

        pushnotification_id = "";
        apinotification_id = "";
        smsnotification_id = "";
        emailnotification_id = "";
        mgrnotification_id = "";
        quickie_id = "";

        $('[data-toggle="tooltip"]').tooltip();

        eventtimesArray = [];
        $(' #addhours ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load

        previewurl = "{{$data['previewurl']}}";

        $(' #initiatesmslist ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $(' #initiateemaillist ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $(' #initiatepushlist ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $(' #initiateapilist ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $(' #initiatemgrlist ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        $(' #countrielist ').change(); // Need to go indirectly via a simulated click because can't do document delegate on page load
        criteriaArray =  [];
        $(' #addcriteria' ).attr('disabled','disabled');

        eventjson = <?php echo $data["eventjson"] ; ?>;

        if(eventedit) {

          console.log("eventjson : " + eventjson);

          trigger_code = "{{$data['event']['trigger_code']}}";
          if(trigger_code == "HipJAM0002") { $("#beaconpositionsdiv").show() } else { $("#beaconpositionsdiv").hide() } ;
          if(trigger_code == "HipRM0001") { 
            $("#hiprmcriteriadiv").show() 
            $("#standardcriteriadiv").hide();
          } else { 
            $("#hiprmcriteriadiv").hide() 
            $("#standardcriteriadiv").show();
          } ;

          logicaloperator = "{{$data['event']['logicaloperator']}}";
          $('input[value="' + logicaloperator + '"]').prop("checked", true);

          event_id = (eventjson["id"]);
          isvenuelevel = <?php echo $data["isvenuelevel"] ; ?>;
          locationmatchcode = eventjson["locationmatchcode"];
          venuepositions = <?php echo $data["venuepositions"] ; ?>;
          quickie_id = <?php echo $data["quickie_id"] ; ?>;
          rmanswers = <?php echo $data["rmanswers"] ; ?>;

          if(isvenuelevel) {
            $(' #initiatepositions ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load
          }

          $( '#eventname' ).val(eventjson["name"]);
          $( '#engagebrand_code' ).val(eventjson["engagebrand_code"]);
          $( '#position_wildcards' ).val(eventjson["position_wildcards"]);

          pushnotification_id = eventjson["pushnotification_id"];
          apinotification_id = eventjson["apinotification_id"];
          smsnotification_id = eventjson["smsnotification_id"];
          emailnotification_id = eventjson["emailnotification_id"];
          mgrnotification_id = eventjson["mgrnotification_id"];
          notification_type_primary = eventjson["notification_type_primary"];
          $( '#notificationtypelist' ).val(notification_type_primary);
          buildNotificationDropDown( notification_type_primary );

          frequency_count = eventjson["frequency_count"];
          $( '#frequency_count' ).val(frequency_count);
          frequency_interval = eventjson["frequency_interval"];
          $( '#frequency_intervallist' ).val(frequency_interval);

          frequency_count1 = eventjson["frequency_count1"];
          $( '#frequency_count1' ).val(frequency_count1);
          frequency_interval1 = eventjson["frequency_interval1"];
          $( '#frequency_intervallist1' ).val(frequency_interval1);

          frequency_count2 = eventjson["frequency_count2"];
          $( '#frequency_count2' ).val(frequency_count2);
          frequency_interval2 = eventjson["frequency_interval2"];
          $( '#frequency_intervallist2' ).val(frequency_interval2);          

          delay = eventjson["delay"];
          $( '#delay' ).val(delay);

          delay_interval = eventjson["delay_interval"];
          $( '#delay_interval' ).val(delay_interval);

          signalstrength = eventjson["signalstrength"];
          $( '#signalstrength' ).val(signalstrength);

          buildMeasureList(eventjson["trigger_code"]);

          if(trigger_code == "HipRM0001") {
            buildAnswerList(quickie_id);
          } else {
            criteriaArray = <?php echo $data["criteriajson"] ; ?>;
            buildCriteriaList();
          }

          eventschedulesArray = <?php echo $data["eventschedulesjson"] ; ?>;
          $( '#schedulebegin' ).val(eventschedulesArray['begin']);
          $( '#scheduleend' ).val(eventschedulesArray['end']);

          eventtimesArray = <?php echo $data["eventtimesjson"] ; ?>;
          buildEventTimesList();

          $.each(criteriaArray, function(index, criteria) {
          });

          monday = eventjson["monday"];
          tuesday = eventjson["tuesday"];
          wednesday = eventjson["wednesday"];
          thursday = eventjson["thursday"];
          friday = eventjson["friday"];
          saturday = eventjson["saturday"];
          sunday = eventjson["sunday"];
          setWeekDays();

        } else {
            monday = tuesday = wednesday = thursday = friday = saturday = sunday = "1";
            $(' #initiatelists ').click(); // Need to go indirectly via a simulated click because can't do document delegate on page load

        }

      });


      function buildRMCriteriaArray() {

        var response_ids = [];
        criteriaArray = [];

        $("input[name='response_ids[]']:checked").each(function ()
        {
            response_ids.push(parseInt($(this).val()));
            var thisCriteria =  { 
                        measure_name: "HipRM Question", measure_code: "hiprmquestion", operator: "N/A", 
                        value: $(this).val(), numperiods: 0, period: "N/A", required: 0
                       };

              criteriaArray.push(thisCriteria)
        });

        console.log(criteriaArray);

      }

      ////////////////////////////////////////////////////////////////////////////////////////////////
      // SAVE EVENT
      ////////////////////////////////////////////////////////////////////////////////////////////////
      $( "#saveevent" ).click(function(event) {


        event.preventDefault();

        if(!eventedit) trigger_code = $( "#triggerlist" ).val();

        errors = 0;

        if(trigger_code == "HipRM0001") {
          logicaloperator = $("input[name=logicaloperator]:checked").val();
          buildRMCriteriaArray();
        } else {
          logicaloperator = "N/A";
        }

        $('#position_wildcards_validationerror').hide();
        $('#eventnamevalidationerror').hide();
        $('#timesvalidationerror').hide();
        $('#criteriavalidationerror').hide();

        // if($('#position_wildcards').val() == "") {
        //   $('#position_wildcards').val("*");
        // }

        if($('#eventname').val() == "") {
          $('#eventnamevalidationerror').show();
          errors = 1;
        }

        if(eventtimesArray.length == 0) {
          $('#timesvalidationerror').show();
          errors = 1;
        }

        if(criteriaArray.length == 0 && trigger_code != "HipRM0001") {
          $('#criteriavalidationerror').show();
          errors = 1;
        }

        if(errors) return 0;

        var selected = $(" #brandlist ").find('option:selected');

        name = $( '#eventname' ).val();
        application_code = $( '#applicationlist' ).val();
        trigger_code = $( '#triggerlist' ).val();
        position_wildcards = $( '#position_wildcards' ).val();
        brand_id = $( '#brandlist' ).val();

        if($( '#notificationtypelist' ).val() == "sms") {
          smsnotification_id = $('#notificationlist').val();
        } else if($( '#notificationtypelist' ).val() == "email") {
          emailnotification_id = $('#notificationlist').val();
        }  else if($( '#notificationtypelist' ).val() == "push") {
          pushnotification_id = $('#notificationlist').val();
        } else if($( '#notificationtypelist' ).val() == "api") {
          apinotification_id = $('#notificationlist').val();
        } else if($( '#notificationtypelist' ).val() == "mgr") {
          mgrnotification_id = $('#notificationlist').val();
        } 

        notification_type_primary = $('#notificationtypelist').val();


        frequency_count = $('#frequency_count').val();
        frequency_interval = $('#frequency_intervallist').val();
        frequency_count1 = $('#frequency_count1').val();
        frequency_interval1 = $('#frequency_intervallist1').val();
        frequency_count2 = $('#frequency_count2').val();
        frequency_interval2 = $('#frequency_intervallist2').val();

        if(frequency_count2 < 1) frequency_count2 = 1; // Enusre that the min event interval is at least 1

        delay = $('#delay').val();
        delay_interval = $('#delay_interval').val();

        signalstrength = $('#signalstrength').val();

        var venueposition_ids =[];
        $('.positioncheckbox:checked').each(function() {
            venueposition_ids.push($(this).val());
        });

        schedulebegin = $('#schedulebegin').val();
        scheduleend = $('#scheduleend').val();

        monday = ($("#monday").is(':checked') ? 1 : 0);
        tuesday = ($("#tuesday").is(':checked') ? 1 : 0);
        wednesday = ($("#wednesday").is(':checked') ? 1 : 0);
        thursday = ($("#thursday").is(':checked') ? 1 : 0);
        friday = ($("#friday").is(':checked') ? 1 : 0);
        saturday = ($("#saturday").is(':checked') ? 1 : 0);
        sunday = ($("#sunday").is(':checked') ? 1 : 0);

        if(eventedit) {

          data = { 'event_id': event_id, 'criterias': criteriaArray, 
                   'notification_type_primary': notification_type_primary,
                   'pushnotification_id': pushnotification_id,  "apinotification_id": apinotification_id, 
                   'smsnotification_id': smsnotification_id,  "emailnotification_id": emailnotification_id,
                   'mgrnotification_id': mgrnotification_id, 
                   'logicaloperator': logicaloperator,
                   'monday': monday, 'tuesday': tuesday, 'wednesday': wednesday, 'thursday': thursday, 
                   'friday': friday, 'saturday': saturday, 'sunday': sunday,
                   'frequency_count': frequency_count, 'frequency_interval': frequency_interval,  
                   'frequency_count1': frequency_count1, 'frequency_interval1': frequency_interval1,  
                   'frequency_count2': frequency_count2, 'frequency_interval2': frequency_interval2,  
                   'delay': delay, 'delay_interval': delay_interval, 
                   'venueposition_ids': venueposition_ids, "position_wildcards": position_wildcards, 
                   'eventtimes': eventtimesArray, 'schedulebegin': schedulebegin, 'scheduleend': scheduleend, "signalstrength": signalstrength};
          url = "/hipengage_editevent";

        } else {

          data = { 'name': name, 'application_code': application_code, 'trigger_code': trigger_code, 'brand_id': brand_id,  
                   'notification_type_primary': notification_type_primary, 'criterias': criteriaArray, 
                   "pushnotification_id": pushnotification_id, "apinotification_id": apinotification_id,
                   'smsnotification_id': smsnotification_id,  "emailnotification_id": emailnotification_id, 
                   'mgrnotification_id': mgrnotification_id, 'quickie_id': quickie_id,
                   'locationmatchcode': locationmatchcode,
                   'logicaloperator': logicaloperator,
                   'monday': monday, 'tuesday': tuesday, 'wednesday': wednesday, 'thursday': thursday, 
                   'friday': friday, 'saturday': saturday, 'sunday': sunday,
                   'frequency_count': frequency_count, 'frequency_interval': frequency_interval,  
                   'frequency_count1': frequency_count1, 'frequency_interval1': frequency_interval1,  
                   'frequency_count2': frequency_count2, 'frequency_interval2': frequency_interval2,   
                   'delay': delay, 'delay_interval': delay_interval, 
                   'venueposition_ids': venueposition_ids, "position_wildcards": position_wildcards, 
                   'eventtimes': eventtimesArray, 'schedulebegin': schedulebegin, 'scheduleend': scheduleend, "signalstrength": signalstrength};
          url = "/hipengage_addevent";

        }

        console.log(data);

        $.ajax({
            type: "POST",
            dataType: 'json',
            data: data,
            url: url,
            success: function(status) {
              console.log("Save : " + status);
              window.location.href = "{{ url('hipengage_showevents'); }}";
            }

          });
      });

      function setEventValues(eventjson) {


      }

    </script> 

  </body>

@stop
