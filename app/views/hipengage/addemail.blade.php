<?php $edit = $data["edit"] ?>
@extends('layout')

@section('content')

<body class="hipENGAGE">

  <div class="container-fluid">

    <div class="row">

      @include('hipengage.sidebar')

      <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

        <h1 class="page-header">Edit Email Notification</h1>
          <div class="pushname">
            <label>Name</label>
            <input id="emailname" class="form-control no-radius" placeholder="Choose a name" type="text">
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

          <div class="pushname">
            <label>Subject</label>
            <input id="emailsubject" class="form-control no-radius" placeholder="Type subject here" type="text">
          </div>

          <div class="pushname">
            <label>Message</label>
            <textarea id="emailmessage" rows="10" class="form-control no-radius" placeholder="Email text" type="text"></textarea>
          </div>
<!--           
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                Message
              </div>
              <div class="panel-body">
                <textarea id="emailmessage" rows="10" class="form-control no-radius" placeholder="Email text" type="text"></textarea>
              </div>
            </div>
          </div> -->
<!--           <div class="panel-body">
              <label>Attachment</label>
                <br>
                <button class="btn btn-default">Choose file</button>
          </div> -->
          <div class="modal-footer">
            <button id="saveemailnotification" type="button" class="btn btn-primary">Save</button>
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

    <script src="/js/hipengage/event_manager/email.js"></script> 

    <script>
    $(function() {
      edit = {{ $edit }};
      id = <?php if ($edit) { echo $data["id"]; } else { echo 0; };?>;
      shownotificationsurl="{{ url('hipengage_shownotifications'); }}"

      if( edit ) {
        populateEditFields(id);
      }
    });

    </script>

  </body>

  @stop

            
