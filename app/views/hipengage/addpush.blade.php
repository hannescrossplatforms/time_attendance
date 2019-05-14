<?php $edit = $data["edit"] ?>
@extends('layout')

@section('content')

<body class="hipENGAGE">
  <form role="form" id="mbimageform" method="post" enctype="multipart/form-data" action="{{ url('lib_savepushmedia'); }}"></form>
  <div id="mb_ext_div" name="mb_ext" style="display:none"></div>

  <div class="container-fluid">
    <div class="row">

      @include('hipengage.sidebar')

      <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

        <h1 class="page-header">Edit Push Notification</h1>

              <div class="pushname">
                <label>Name</label>
                <input id="pushname" class="form-control no-radius" placeholder="Choose a name" type="text">
              </div>

              <div class="pushname">
                <label>Brand Name</label>
                @if ($edit) 
                  <input class="form-control" type="text" value="{{ $data['notification']->brand->name }}" disabled >
                @else
                <select id="brandlist" name="brand_id" class="form-control no-radius">
                    @foreach($data['brands'] as $brand)
                      <option name="brand_id" value="{{ $brand->id }}">
                        {{ $brand->name }}
                      </option>
                    @endforeach 
                </select>
                @endif
              </div>

              <div class="col-md-4">
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
            <div class="col-md-8">
              <div class="panel panel-default">
                <div class="panel-heading">
                  Push Notification Settings
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <label>Notification Type</label>
                    <br>
                    <label class="checkbox-inline">
                      <input id="pushtypesound" type="checkbox">
                      Sound </label>
                      <label class="checkbox-inline">
                        <input id="pushtypevibrate" type="checkbox">
                        Vibrate </label>
                      </div>
                      <div class="form-group">
                        <label>Preload Notification</label>
                        <select id="pushpreload" class="form-control no-radius">
                          <option value="1">true</option>
                          <option value="0">false</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      Message
                    </div>
                    <div class="panel-body">
                      <input id="pushmessage" class="form-control no-radius" placeholder="Message content to be sent to device" type="text">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button id="savepushnotification" type="button" class="btn btn-primary">Save</button>
                  <a href="{{ url('hipengage_shownotifications'); }}" class="btn btn-default">Cancel</a>
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

    <script src="/js/hipengage/event_manager/push.js"></script> 

    <script>
    $(function() {

      edit = {{ $edit }};
      id = <?php if ($edit) { echo $data["id"]; } else { echo 0; };?>;
      previewurl = "{{$data['previewurl']}}";
      shownotificationsurl="{{ url('hipengage_shownotifications'); }}"

      if( edit ) {
        populateEditFields(id);
      }
    });

    </script>

  </body>

  @stop
