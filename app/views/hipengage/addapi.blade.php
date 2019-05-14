<?php $edit = $data["edit"] ?>
@extends('layout')

@section('content')

<body class="hipENGAGE">

  <div class="container-fluid">

    <div class="row">

      @include('hipengage.sidebar')

      <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

        <h1 class="page-header">Edit Api Notification</h1>

            <div class="apiname">
              <label>Name</label>
              <input id="apiname" class="form-control no-radius" placeholder="Choose a name" type="text">
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
          
            <div class="col-md-12">
              <div class="panel panel-default">

                <div class="panel-heading">
                  Api Notification Settings
                </div>

                <div class="panel-body">

                  <div class="form-group">
                    <label>Auth Token</label><br>
                    <input id="apiauth" class="form-control no-radius" placeholder="" type="text">
                  </div>
                  
                  <div class="form-group">
                    <label>Endpoint</label><br>
                    <input id="apiurl" class="form-control no-radius" placeholder="" type="text">
                  </div>

                </div>
              </div>
            </div>

          <div class="modal-footer">
            <button id="sendtestapinotification" type="button" class="btn btn-primary">Send Test Message</button>
            <button id="saveapinotification" type="button" class="btn btn-primary">Save</button>
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

    <script src="/js/hipengage/event_manager/api.js"></script> 

    <script>
    $(function() {
      edit = {{ $edit }};
      id = <?php if ($edit) { echo $data["id"]; } else { echo 0; };?>;
      shownotificationsurl="{{ url('hipengage_shownotifications'); }}"

      if( edit ) {
        populateEditFields(id);
      }
    });

    $("#sendtestapinotification").click(function(event) {
      $.ajax({

        url: "{{ url('hipengage_sendtestapinotification'); }}",
        type: 'get',
        dataType: 'json',
        data : {"auth": $("#apiauth").val(), "url": $("#apiurl").val() },
        success: function(data) {
        }
      });
    }); 

    </script>

  </body>

  @stop
