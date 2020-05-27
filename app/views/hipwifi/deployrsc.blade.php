@extends('angle_wifi_layout')
<?php $myerrors = array("first" => "1", "second" => "2"); ?>
<?php $selected_server_id = $data["venue"]->server_id ?>
@section('content')

<section class="section-container">
  <!-- Page content-->
  <div class="content-wrapper">
    <div class="content-heading">
      <div>Update RSC Script<small data-localize="dashboard.WELCOME"></small></div><!-- START Language list-->
    </div><!-- START cards box-->
    <div class="row">
      <div class="col-12">
        <div class="card card-default card-demo">
          <div class="card-header">
            <a class="float-right" href="#" data-tool="card-refresh" data-toggle="tooltip" title="Refresh card">
              <em class="fas fa-sync"></em>
            </a>
            <div class="card-title">
              RSC Script Settings

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
            </div>
            <div class="row">
              <div class="col-12">
                <form role="form" id="useradmin-form" method="post" action="{{ url('hipwifi_deployrsc'); }}">

                  {{ Form::hidden('id', $data['venue']->id) }}

                  {{ $data['message'] }}

                  <label for="scripttext" style="font-weight: 700">Enter the RSC script for venue {{$data['venue']->sitename}} - {{$data['venue']->macaddress}}</label><br>
                  <!-- Modal Button Starts -->
                  <button type="button" class="btn btn-default btn-info pull-right" data-toggle="modal" data-target="#myModal" style="float: right; margin-bottom: 25px;">Show Running Configuration</button>
                  <!-- Modal Button Ends -->

                  <div class="form-group">
                    <input type="checkbox" name="overridersc"> Override Existing Configuration
                  </div>
                  <div class="form-group">
                    <textarea rows="20" cols="30" class="form-control" name="scripttext" id="scripttext" placeholder="Enter script text here"></textarea>
                  </div>

                  <br>
                  <button id="submitform" class="btn btn-primary">Submit</button>

                  <a href="{{ url('hipwifi_showvenues'); }}" class="btn btn-default">Cancel</a>


                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@stop


@section('modals')
  <!-- Modal that displays running config starts-->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">{{$data['venue']["sitename"]}}'s Running Configuration</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @foreach($data['rscconfig'] as $line)
          {{$line . '<br>'}}
          @endforeach
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@stop