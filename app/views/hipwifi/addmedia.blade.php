@extends('angle_wifi_layout')

<?php $edit = $data["edit"] ?>
<?php $selected_server_id = $data["media"]->server_id ?>
<form role="form" id="dtimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savedtmedia'); }}"></form>
<form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savembmedia'); }}"></form>
@section('content')

<section class="section-container">
  <form role="form" id="mainform" method="post" enctype="multipart/form-data" action=" @if ($edit) {{ url('hipwifi_editmedia'); }} @else {{ url('hipwifi_addmedia'); }} @endif ">
    <div class="content-wrapper">
      <div class="content-heading">
        <div>Login Page Manager<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
      </div><!-- START cards box-->
      <form role="form">
        <div class="row">
          <div class="col-12">
            <div class="card card-default card-demo">
              <div class="card-header">
                <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
                  <em class="fas fa-sync"></em>
                </a>
                <div class="card-title">
                  Details

                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    @if ($errors->has())
                    <div class="alert alert-danger">
                      @foreach ($errors->all() as $error)
                      {{ $error }}<br>
                      @endforeach
                    </div>
                    @endif
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <label>Brand Name</label>
                      @if ($edit)
                      <input class="form-control" type="text" value="{{$data['media']['brand_name']}}" disabled>
                      @else
                      <input id="brandlist" name="brand_id" type="hidden" class="form-control no-radius" value="{{$data['brandid']}}">
                      <input name="brand_name" class="form-control no-radius" value="{{$data['brandname']}}" readonly="readonly">

                      @endif
                    </div>

                    <div id="login_process_name"> <label>Login Process : </label> </div>

                    <br>
                    <div id="dt_ext_div" name="dt_ext" style="display:none"></div>
                    <div id="mb_ext_div" name="mb_ext" style="display:none"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-12">
            <div class="card card-default card-demo">
              <div class="card-header">
                <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
                  <em class="fas fa-sync"></em>
                </a>
                <div class="card-title">
                  Desktop Login Page

                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Desktop Background</label>
                      <div id="imagedisplaydt" style="display:none"></div>

                      <div id="previewdt">
                        <a id="dtpreview" class="btn btn-info btn-sm  btn-block" target="blank" style="color: white">
                          Preview Background
                        </a>
                      </div>
                      <div id="dtboxlink"></div>
                      <input id="dtimage" type="file" name="dtimage" form="dtimageform">
                      <a id="dt-file" href="#" class="btn btn-purple btn-sm  btn-block " data-toggle="modal" data-target="#desktopBgModal">
                        Upload new image
                      </a>
                      </input>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>



        <div class="row">
          <div class="col-12">
            <div class="card card-default card-demo">
              <div class="card-header">
                <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
                  <em class="fas fa-sync"></em>
                </a>
                <div class="card-title">
                  Mobile Login Page

                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Mobile Background</label>
                      <div id="imagedisplaymb" style="display:none"></div>

                      <div id="previewmb">
                        <a id="mbpreview" class="btn btn-info btn-sm  btn-block" target="blank" style="color: white">
                          Preview Background
                        </a>
                      </div>
                      <div id="mbboxlink"></div>
                      <input id="mbimage" type="file" name="mbimage" form="mbimageform">
                      <a id="mb-file" href="#" class="btn btn-purple btn-sm  btn-block " data-toggle="modal" data-target="#mobileBgModal" style="color: white">
                        Upload new image
                      </a>
                      </input>
                    </div>
                  </div>
                  <div class="col-12">
                    <a id="configButton" href="#" class="btn btn-warning" data-toggle="modal" data-target="#configMobileModal"><i class="fa fas-gears"></i> Configuration</a>
                  </div>

                  <div class="clear"></div>

                </div>
              </div>
            </div>
          </div>
        </div>




        <div class="row">
          <div class="col-12">
            <div class="card card-default card-demo">
              <div class="card-header">
                <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
                  <em class="fas fa-sync"></em>
                </a>
                <div class="card-title">
                  Targeting

                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    @if ($edit)
                    {{ Form::hidden('id', $data['media']->id) }}
                    {{ Form::hidden('countrie_id', $data['media']->countrie_id) }}
                    {{ Form::hidden('province_id', $data['media']->province_id) }}
                    {{ Form::hidden('citie_id', $data['media']->citie_id) }}
                    {{ Form::hidden('brand_id', $data['media']->brand_id) }}
                    {{ Form::hidden('location', $data['media']->location) }}
                    <input id="locationcode" name="location" class="form-control" type="text" value="{{$data['media']->location}}" disabled>
                    <input id="hannes_test" name="hannes_test" value="10101" type="text" class="form-control">
                    @else

                    <div class="form-group">
                      <label>Country</label>
                      <select id="countrielist" name="countrie_id" class="form-control">
                        @foreach($data['allcountries'] as $countrie)
                        <option value="{{ $countrie->id }}">
                          {{ $countrie->name }}
                        </option>
                        @endforeach
                      </select>
                      <span id="helpBlock" class="help-block">If you wish to choose all venues in a country leave all other fields below this one blank</span>
                    </div>

                    <div class="form-group">
                      <label>Province/State</label>
                      <select id="provincelist" name="province_id" class="form-control no-radius"></select>
                      <span id="helpBlock" class="help-block">If you wish to choose all venues in a Province leave all other fields below this one blank</span>
                    </div>

                    <div class="form-group">
                      <label>City</label>
                      <select id="citielist" name="citie_id" class="form-control no-radius" placeholder"">
                        <option selected="selected"></option>
                      </select>
                      <span id="helpBlock" class="help-block">If you wish to choose all venues in a City leave the field below this one blank</span>
                    </div>

                    <div class="form-group">
                      <label>Venue</label>
                      <select id="venuelist" name="venue_id" class="form-control no-radius" placeholder"">
                        <option selected="selected"></option>
                      </select>
                      <span id="helpBlock" class="help-block">This will load the media ONLY at this venue</span>
                    </div>

                    @endif

                    <div id="locationCodeHidden"></div> <br />
                    <button class="btn btn-primary">@if ($edit) Update @else Submit @endif</button>
                    <a href="{{ url('hipwifi_showbrandmedia'); }}" class="btn btn-default">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Configure Mobile -->
        <div class="modal fade" id="configMobileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel" style="font-size: 20px;font-weight: 700;">Configuration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-4">
                    <img src="/img/wifi_mobile_skin.png" class="img-responsive" style="width: 100%;" />
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label style="font-weight: 700;">Welcome Message</label>
                      <br>
                      <label class="radio-inline">
                        <input type="radio" name="welcome_flag" value="1" {{ $data['flag_on_checked']}}> On
                      </label>
                      <label class="radio-inline">
                        <input type="radio" style="margin-left: 10px;" name="welcome_flag" value="0" {{ $data['flag_off_checked']}}> Off
                      </label>
                    </div>

                    <div class="form-group">
                      <label style="font-weight: 700;">‘Entry field group’ position (offset from top) </label>
                      <input id="ef_group_pos" name="ef_group_pos" value="{{$data['media']->ef_group_pos}}" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">‘Entry field’ Transparency level (0% to 100%)</label>
                      <input id="ef_transparency" name="ef_transparency" value="{{$data['media']->ef_transparency}}" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">‘Background’ Colour </label>
                      <input class="input-big" style="float: right;" id="bg_colour" name="bg_colour" type="color" value='{{$data['media']->bg_colour}}'>
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">‘Entry field’ Colour </label>
                      <input class="input-big" style="float: right;" id="ef_colour" name="ef_colour" type="color" value='{{$data['media']->ef_colour}}'>
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">‘Entry field’ Outline and Text colour </label>
                      <input class="input-big" style="float: right;" id="ef_outline_text_colour" name="ef_outline_text_colour" type="color" value='{{$data['media']->ef_outline_text_colour}}'>
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">Zone In Button gap to ‘Entry field group’ </label>
                      <input id="zonein_gap" name="zonein_gap" value="{{$data['media']->zonein_gap}}" type="text" class="form-control" placeholder="100px">
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">Zone In Button colour </label>
                      <input class="input-big" style="float: right;" id="zonein_btn_colour" name="zonein_btn_colour" type="color" value='{{$data['media']->zonein_btn_colour}}'>
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">Zone In Button – text colour </label>
                      <input class="input-big" style="float: right;" id="zone_txt_colour" name="zone_txt_colour" type="color" value='{{$data['media']->zone_txt_colour}}'>
                    </div>
                    <div class="form-group">
                      <label style="font-weight: 700;">FAQ |SUPPORT & Powered By - Text Colour</label>
                      <input class="input-big" style="float: right;" id="faq_colour" name="faq_colour" type="color" value='{{$data['media']->faq_colour}}'>
                    </div>


                    <div class="form-group">
                      <label style="font-weight: 700;">Logo Choice</label>
                      <br>
                      <label class="radio-inline">
                        <input type="radio" id="logo_choice" name="logo_choice" value="white" {{ $data['logo_choice_white']}}> White
                      </label>
                      <label class="radio-inline">
                        <input type="radio" style="margin-left: 10px;" id="logo_choice" name="logo_choice" value="black" {{ $data['logo_choice_black']}}> Black
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>





      </form>
    </div>
  </form>
</section>
<style>
  .modal-dialog.full {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
  }

  .modal-content.full {
    height: auto;
    min-height: 100%;
    border-radius: 0;
    width: 100vw;
  }
</style>

<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="/js/colpick.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>
<!-- <script src="locationmanager.blade.php"></script>  -->


<script type="text/javascript">
  $(".fancybox").fancybox();
  $('a.fancybox').fancybox();
</script>

<script>
  dt_ext = "{{$data["
  media "]->dt_ext}}";
  mb_ext = "{{$data["
  media "]->mb_ext}}";
  @if($edit)
  brand_id = "{{$data["
  media "]->brand_id}}";
  @else
  brand_id = 0;
  @endif


  $(function() {
    $('#countrielist').change(); // Need to go indirectly via a simulated click because can't do document delegate on page load
    @if($edit) $('#dtfunction').click();
    @endif
    @if($edit) $('#mbfunction').click();
    @endif
    handleBrandChange(brand_id);

    showDesktopImage(dt_ext)
    showMobileImage(mb_ext)

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
    buildVenueList();
    buildMatchLocationCode();
  });

  $(document).delegate('#venuelist', 'change', function() {
    buildMatchLocationCode();
  });

  $(document).delegate('#brandlist', 'change', function() {
    handleBrandChange($("#brandlist").val());
    buildVenueList();
    buildMatchLocationCode();
  });

  $(document).delegate('#isplist', 'change', function() {
    buildVenueList();
    buildMatchLocationCode();
  });

  $(document).delegate('#sitename', 'focusout', function() {
    buildMatchLocationCode();
  });

  $(document).delegate('#macaddress', 'focusout', function() {
    buildMatchLocationCode();
  });

  $(document).delegate('#previewdt', 'click', function() {

    $.ajax({
      type: "GET",
      dataType: 'json',
      contentType: "application/json",
      data: {
        'brand_id': brand_id,
      },
      url: "{{ url('lib_getserverurl'); }}",
      success: function(data) {

        baseurl = data["hostname"];
        fullurl = baseurl + "login?res=logoff&nasid=kauai_bayside";
        fullurl = fullurl + "&preview=true";
        fullurl = fullurl + "&mb_ext=" + $("#mb_ext").val();
        fullurl = fullurl + "&dt_ext=" + $("#dt_ext").val();
        fullurl = fullurl + "&login_process=" + $("#login_process").val();
        fullurl = fullurl + "&welcome_flag=" + $("#welcome_flag").val();
        fullurl = fullurl + "&welcome_message=" + $("#welcome_message").val();
        fullurl = fullurl + "&faq_colour=" + $("#faq_colour").val().replace("#", "");


        // $("#dtboxlink").html("<a id='dt1' class='fancybox fancybox.iframe' href='" + fullurl + "'>Iframe</a>");
        // $("#dt1").trigger( "click" );
        // $("#dtboxlink").html("");
        debugger;
        $('#preview_desktop_iframe').attr('src', fullurl);
        $('#preview_desktop_splash').modal('show');

        // newWindow = window.open(fullurl,"_blank");
        // 
      }
    });

  });

  $(document).on('click', '#previewmb', function() {

    // alert(brand_id);

    $(".fancybox").fancybox({
      'width': 400,
      'height': 700,
      'transitionIn': 'elastic', // this option is for v1.3.4
      'transitionOut': 'elastic', // this option is for v1.3.4
      // if using v2.x AND set class fancybox.iframe, you may not need this
      'type': 'iframe',
      // if you want your iframe always will be 600x250 regardless the viewport size
      'fitToView': false // use autoScale for v1.3.4
    });

    $.ajax({
      type: "GET",
      dataType: 'json',
      contentType: "application/json",
      data: {
        'brand_id': brand_id,
      },
      url: "{{ url('lib_getserverurl'); }}",
      success: function(data) {

        console.log("logo choice : " + $("input[name='logo_choice']:checked").val());
        brand = data["brand"];


        baseurl = data["hostname"];
        fullurl = baseurl + "login?res=logoff&nasid=brand_preview&mobile=1";
        fullurl = fullurl + "&preview=true";
        fullurl = fullurl + "&nastype=" + data["ispcode"] + brand['code'];
        fullurl = fullurl + "&mb_ext=" + $("#mb_ext").val();
        fullurl = fullurl + "&dt_ext=" + $("#dt_ext").val();
        fullurl = fullurl + "&login_process=" + $("#login_process").val();
        fullurl = fullurl + "&welcome_flag=" + $("input[name=welcome_flag]:checked").val();
        fullurl = fullurl + "&welcome_message=" + $("#welcome_message").val();
        fullurl = fullurl + "&ef_group_pos=" + $("#ef_group_pos").val();
        fullurl = fullurl + "&ef_transparency=" + $("#ef_transparency").val();
        fullurl = fullurl + "&bg_colour=" + $("#bg_colour").val().replace("#", "");
        fullurl = fullurl + "&ef_colour=" + $("#ef_colour").val().replace("#", "");
        fullurl = fullurl + "&ef_outline_text_colour=" + $("#ef_outline_text_colour").val().replace("#", "");
        fullurl = fullurl + "&zonein_gap=" + $("#zonein_gap").val().replace("#", "");
        fullurl = fullurl + "&zonein_btn_colour=" + $("#zonein_btn_colour").val().replace("#", "");
        fullurl = fullurl + "&zone_txt_colour=" + $("#zone_txt_colour").val().replace("#", "");
        fullurl = fullurl + "&faq_colour=" + $("#faq_colour").val().replace("#", "");
        fullurl = fullurl + "&logo_choice=" + $("input[name='logo_choice']:checked").val();
        fullurl = fullurl + "&register_field=" + data["brand"]["register_field"];
        fullurl = fullurl + "&f1_display=" + data["brand"]["f1_display"];
        fullurl = fullurl + "&f1_placeholder=" + data["brand"]["f1_placeholder"];
        fullurl = fullurl + "&f1_type=" + data["brand"]["f1_type"];
        fullurl = fullurl + "&f2_display=" + data["brand"]["f2_display"];
        fullurl = fullurl + "&f2_placeholder=" + data["brand"]["f2_placeholder"];
        fullurl = fullurl + "&f2_type=" + data["brand"]["f2_type"];
        fullurl = fullurl + "&f3_display=" + data["brand"]["f3_display"];
        fullurl = fullurl + "&f3_placeholder=" + data["brand"]["f3_placeholder"];
        fullurl = fullurl + "&f3_type=" + data["brand"]["f3_type"];
        fullurl = fullurl + "&f4_display=" + data["brand"]["f4_display"];
        fullurl = fullurl + "&f4_agegate=" + data["brand"]["f4_agegate"];
        fullurl = fullurl + "&f4_type=" + data["brand"]["f4_type"];
        fullurl = fullurl + "&f5_display=" + data["brand"]["f5_display"];
        fullurl = fullurl + "&f5_placeholder=" + data["brand"]["f5_placeholder"];
        fullurl = fullurl + "&f5_type=" + data["brand"]["f5_type"];

        fullurl = fullurl + "&f6_display=" + data["brand"]["f6_display"];
        fullurl = fullurl + "&f6_placeholder=" + data["brand"]["f6_placeholder"];
        fullurl = fullurl + "&f6_url=" + data["brand"]["f6_url"];
        fullurl = fullurl + "&f6_type=" + data["brand"]["f6_type"];

        fullurl = fullurl + "&f7_display=" + data["brand"]["f7_display"];
        fullurl = fullurl + "&f7_placeholder=" + data["brand"]["f7_placeholder"];
        fullurl = fullurl + "&f7_type=" + data["brand"]["f7_type"];

        fullurl = fullurl + "&f8_display=" + data["brand"]["f8_display"];
        fullurl = fullurl + "&f8_placeholder=" + data["brand"]["f8_placeholder"];
        fullurl = fullurl + "&f8_type=" + data["brand"]["f8_type"];

        fullurl = fullurl + "&sm_color=" + data["brand"]["sm_color"].replace("#", "");
        fullurl = fullurl + "&sm_buttonsize=" + data["brand"]["sm_buttonsize"];
        fullurl = fullurl + "&sm_text=" + data["brand"]["sm_text"];

        fullurl = fullurl + "&zonein_btn_text=" + data["brand"]["zonein_btn_text"];
        // fullurl = encodeURI(fullurl);
        fullurl = encodeURI(fullurl).replace(/[!'()*]/g, escape);

        console.log("brand code :  " + brand['code']);

        $('#mobile_preview_iframe').attr('src', fullurl);
        $('#preview_mobile_splash').modal('show');


        // $("#mbboxlink").html("<a id='mb1' class='fancybox fancybox.iframe' href='" + fullurl + "'>Iframe</a>");
        // $("#mb1").click();
        // $("#mbboxlink").html("");
        // window.open(fullurl,"_blank");

      }
    });

  });


  $('body').delegate('#dtfunction', 'click', function() {
    showDesktopImage(dt_ext);
    // $( "#dt_ext" ).html( "<input type='hidden' name='dt_ext' value='" + dt_ext + "'/>" );
  });

  $('body').delegate('#mbfunction', 'click', function() {
    showMobileImage(mb_ext);
    // $( "#mb_ext" ).html( "<input type='hidden' name='mb_ext' value='" + mb_ext + "'/>" );
  });
  // Hannes image 3
  $('body').delegate('#dtimage', 'change', function() {
    var options = {
      success: showDesktopImage,
      dataType: 'text'
    };
    $('#dtimageform').ajaxForm(options).submit();
  });

  $('body').delegate('#mbimage', 'change', function() {
    var options = {
      success: showMobileImage,
      dataType: 'text'
    };
    $('#mbimageform').ajaxForm(options).submit();
  });

  function showDesktopImage(extension) {
    //Hannes image 1
    // The Math.random() is to ensure that the image gets refreshed by making the url unique
    src = "src='{{$data['previewurl']}}preview-dt." + extension + "?" + Math.random() + "'";
    imgtag = "<img " + src + " style='margin-bottom: 10px; width: 100%;' class='img-responsive'/>"

    $("#imagedisplaydt").html(imgtag);
    $("#imagedisplaydt").css('display', 'block');

    $("#dt_ext_div").html("<input type='hidden' id='dt_ext' name='dt_ext' value='" + extension + "'/>");
  }

  function showMobileImage(extension) {

    // The Math.random() is to ensure that the image gets refreshed by making the url unique
    src = "src='{{$data['previewurl']}}preview-mb." + extension + "?" + Math.random() + "'";
    imgtag = "<img " + src + " style='margin-bottom: 10px; max-height:500px' class='img-responsive'/>";

    $("#imagedisplaymb").html(imgtag);
    $("#imagedisplaymb").css('display', 'block');

    $("#mb_ext_div").html("<input type='hidden' id='mb_ext' name='mb_ext' value='" + extension + "'/>");
  }

  function handleBrandChange(brand_id) {

    $.ajax({
      type: "GET",
      dataType: 'json',
      contentType: "application/json",
      data: {
        'brand_id': brand_id,
      },
      url: "{{ url('lib_getbranddata'); }}",
      success: function(arr) {

        login_process = arr["login_process"];
        if (login_process == "full") {
          $("#configButton").hide();

        } else {
          $("#configButton").show();
        }

        $("#login_process_div").html("<input type='hidden' name='login_process' id='login_process' value='" + login_process + "'/>")
        $("#welcome_message_div").html("<input type='hidden' name='welcome_message' id='welcome_message' value='" + arr['welcome_message'] + "'/>")

        htmlstring = "<label>Login Process : </label> " + arr["login_process_name"];
        $("#login_process_name").html(htmlstring);
      }
    });

  }

  var dtwrapper = $('<div/>').css({
    height: 0,
    width: 0,
    'overflow': 'hidden'
  });
  var dtimage = $('#dtimage').wrap(dtwrapper);
  dtimage.change(function() {
    $this = $(this);
    $('#dt-file').text($this.val());
  })
  $('#dt-file').click(function() {
    dtimage.click();
  }).show();

  var mbwrapper = $('<div/>').css({
    height: 0,
    width: 0,
    'overflow': 'hidden'
  });
  var mbimage = $('#mbimage').wrap(mbwrapper);
  mbimage.change(function() {
    $this = $(this);
    $('#mb-file').text($this.val());
  })
  $('#mb-file').click(function() {
    mbimage.click();
  }).show();
</script>




<script>
  $(function() {
    $('[data-toggle="popover"]').popover()
  })
</script>
<script>
  $('#picker').colpick({
    layout: 'hex',
    submit: 0,
    colorScheme: 'dark',
    onChange: function(hsb, hex, rgb, el, bySetColor) {
      $(el).css('border-color', '#' + hex);
      // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
      if (!bySetColor) $(el).val(hex);
    }
  }).keyup(function() {
    $(this).colpickSetColor(this.value);
  });
</script>



<script>
  function buildMatchLocationCode() {
    console.log("buildLocationCode");

    if ($("#isplist")) {
      isp_id = $("#isplist").val();
    } else {
      isp_id = 1;
    }
    brand_id = $("#brandlist").val();
    countrie_id = $("#countrielist").val();
    province_id = $("#provincelist").val();
    citie_id = $("#citielist").val();
    venue_id = $("#venuelist").val();

    $.ajax({
      type: "GET",
      dataType: 'json',
      contentType: "application/json",
      data: {
        'isp_id': isp_id,
        'brand_id': brand_id,
        'countrie_id': countrie_id,
        'province_id': province_id,
        'citie_id': citie_id,
        'venue_id': venue_id
      },
      url: "/lib_buildMatchLocationCode",
      success: function(locationCode) {
        locationmatchcode = locationCode;
        htmlstring = '<input id="locationcode" type="text" class="form-control"  \
                placeholder="' + locationCode + '" disabled>';
        $("#locationCodeDisplayed").html(htmlstring);

        htmlstring = '<input type="hidden" name="location" value = "' + locationCode + '">';
        $("#locationCodeHidden").html(htmlstring);
        if (venue_id != "" && currentProduct == "hipENGAGE") { // This is for Engage only
          showspecificbeaconpositions(locationCode);
        }

      }
    });
  }

  function buildProvinceList() {
    var countrie_id = $("#countrielist").val();
    console.log("buildProvinceList " + countrie_id);

    // url: "{{ url('/lib_getprovinces/" + countrie_id + "'); }}",
    $.ajax({
      type: "GET",
      dataType: 'json',
      contentType: "application/json",
      url: "/lib_getprovinces/" + countrie_id,
      success: function(provinces) {
        var provincesjson = JSON.parse(provinces);
        console.log("Provinces : " + provinces);

        openSelect = '<select id="provincelist" name="countrie_id" class="form-control">';
        options = '<option selected="selected" value="0">--</option>';
        $.each(provincesjson, function(index, value) {
          options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
        });
        closeSelect = '</select>';

        selectHtml = openSelect + options + closeSelect;

        $("#provincelist").html(selectHtml);

      }
    });
  }

  function buildCityList() {
    var province_id = $("#provincelist").val();
    console.log("buildCityList " + province_id);

    // url: "lib_getcities/" + province_id ,
    baseurl =
      $.ajax({
        type: "GET",
        dataType: 'json',
        contentType: "application/json",
        url: "{{ url('lib_getcities/" + province_id + "'); }}",
        success: function(cities) {
          var citiesjson = JSON.parse(cities);
          console.log("cities : " + cities);

          options = '<option id="citielist" selected="selected" value="0">--</option>';
          $.each(citiesjson, function(index, value) {
            options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
          });
          closeSelect = '</select>';

          selectHtml = openSelect + options + closeSelect;

          $("#citielist").html(selectHtml);

        }
      });
  }

  function buildVenueList() {
    var isp_id = $("#isplist").val();
    var brand_id = $("#brandlist").val();
    var citie_id = $("#citielist").val();

    // $( "#venuelist" ).html( "" );

    if (citie_id) {
      console.log("buildVenueList " + citie_id);

      $.ajax({
        type: "GET",
        dataType: 'json',
        contentType: "application/json",
        data: {
          'isp_id': isp_id,
          'brand_id': brand_id,
          'citie_id': citie_id,
        },
        url: "{{ url('lib_getvenues'); }}",
        success: function(venues) {
          var venuesjson = JSON.parse(venues);
          console.log("venues : " + venues);

          options = '<option id="venuelist" selected="selected" value="0">Please select</option>';
          $.each(venuesjson, function(index, value) {
            options = options + '<option value="' + value["id"] + '">' + value["sitename"] + '</option>';
          });
          closeSelect = '</select>';

          selectHtml = openSelect + options + closeSelect;

          $("#venuelist").html(selectHtml);
        }
      });
    }
  }
</script>





@stop

@section('modals')
<!-- mobile preview -->
<div class="modal fade" id="preview_mobile_splash" tabindex="-1" role="dialog" aria-labelledby="preview_mobile_splash_label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="preview_mobile_splash_label">Preview mobile splash page</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe id="mobile_preview_iframe" style="height: 700px; width: 400px;margin-left: 35px;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- desktop preview -->
<div class="modal fade" id="preview_desktop_splash" tabindex="-1" role="dialog" aria-labelledby="preview_desktop_splash_label" aria-hidden="true">
  <div class="modal-dialog full" role="document">
    <div class="modal-content full">
      <div class="modal-header">
        <h5 class="modal-title" id="preview_desktop_splash_label">Preview desktop splash page</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe id="preview_desktop_iframe" style="height: 100vh; width: 100%;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Login Process -->
<div class="modal fade" id="loginProcessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Login Process</h4>
      </div>
      <div class="modal-body">
        <h6>Full Registration</h6>
        <ol>
          <li>Register with Username, Password, Gender, Email, DOB.</li>
          <li>Login using Username/Password.</li>
        </ol>
        <h6>Zero Registration</h6>
        <ol>
          <li>Mobile number and email on 1st login.</li>
          <li>Mobile number only on subsequent logins.</li>
        </ol>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="dtfunction"></div>
<div id="mbfunction"></div>
<div id="login_process_div"></div>
<div id="welcome_message_div"></div>
@stop