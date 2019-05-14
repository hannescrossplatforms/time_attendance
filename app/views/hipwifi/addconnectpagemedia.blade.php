@extends('layout')
<?php $edit = $data["edit"] ?>

@section('content')

<body class="hipWifi">
    <div class="container-fluid">
            <div class="row">
        @include('hipwifi.sidebar')
            <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
                <h3 class="page-header">Connect Page Manager</h3>
        @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
              </div>
        @endif
              {{Form::open(array('url'=>"hipwifi_editcpmediasave", 'files' => true))}}
                  {{Form::hidden('id', $data['cpmediaid'])}}

                  {{Form::label("brand", "Brand")}}
                  {{Form::hidden('brand_id', $data['brandid'])}}
                  {{Form::text('brand', $data['brandname'], ['class' => 'form-control', 'disabled' => 'disabled']); }}<br>

                  <div class="form-group">
                    <label>Connect Page Enabled</label>
                    <input class="input-big" type="checkbox" name="connect_btn_enabled" @if ($edit) {{$data['connect_btn_enabled'];}} @endif> 
                  </div>

                  <div class="form-group">
                    <label>Button Colour </label>
                    <input class="input-big" id="connect_btn_colour" name="connect_btn_colour" type="color" value="{{$data['connect_btn_colour']}}">
                  </div>

                  <div class="form-group">
                    <label>Button Text Colour </label>
                    <input class="input-big" id="connect_text_colour" name="connect_text_colour" type="color" value="{{$data['connect_text_colour']}}">
                  </div>

                  <div class="form-group">
                    <label>Button Offset From Top (%) </label>
                    <input class="form-control" id="connect_btn_offset_from_top" name="connect_btn_offset_from_top" type="text" value="{{$data['connect_btn_offset_from_top']}}">
                  </div>

                  <div id="previewmb">
                    <a id="mbpreview" class="btn btn-default btn-sm  btn-block" target="blank">
                      Preview Background
                    </a>
                  </div> 
                  <div id="mbboxlink"></div>
                  <br>
                  {{Form::submit('Submit', ["class" => 'btn btn-primary'])}}
                 <a class="btn btn-default" href="{{url('hipwifi_showsinglebrandmedia/'.$data['brandid'])}}">Cancel</a>
              {{ Form::close()}}

                     
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
    <script src="/js/colpick.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>


    <script type="text/javascript">
      $(document).ready(function() {

      });
      $(".fancybox").fancybox();
      $('a.fancybox').fancybox();
    </script>

    <script>


$(document).delegate('#previewmb', 'click', function() {
    brand_id = "1";

        // alert(brand_id);

        $(".fancybox").fancybox({
            'width': 400,
            'height': 700,
            'transitionIn': 'elastic', // this option is for v1.3.4
            'transitionOut': 'elastic', // this option is for v1.3.4
            // if using v2.x AND set class fancybox.iframe, you may not need this
            'type': 'iframe',
            // if you want your iframe always will be 600x250 regardless the viewport size
            'fitToView' : false  // use autoScale for v1.3.4
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
            fullurl = baseurl + "login/mconnect?res=logoff&nasid=brand_preview&mobile=1";
            fullurl = fullurl + "&preview=true";;
            fullurl = fullurl + "&location={{ $data['location'] }}";
            fullurl = fullurl + "&dt_ext={{ $data['dt_ext'] }}";
            fullurl = fullurl + "&mb_ext={{ $data['mb_ext'] }}";
            fullurl = fullurl + "&connect_btn_offset_from_top=" + $( "#connect_btn_offset_from_top" ).val();
            fullurl = fullurl + "&connect_btn_colour=" + $( "#connect_btn_colour" ).val();
            fullurl = fullurl + "&connect_text_colour=" + $( "#connect_text_colour" ).val();
            
            // fullurl = encodeURI(fullurl);
            fullurl = encodeURI(fullurl).replace(/[!'()*#]/g, escape);
            console.log("brand code :  " + brand['code']);

            $("#mbboxlink").html("<a id='mb1' class='fancybox fancybox.iframe' href='" + fullurl + "'>Iframe</a>");
            $("#mb1").trigger( "click" );
            $("#mbboxlink").html("");

          }
        });

      });

    </script>


































</body>


@endsection