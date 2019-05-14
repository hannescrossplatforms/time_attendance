@extends('layout')

<?php $myerrors = array("first" => "1", "second" => "2"); ?>
<?php $selected_server_id = $data["venue"]->server_id ?>

@section('content')

  <body class="hipWifi">

    <form role="form" id="useradmin-form" method="post" 
        action="{{ url('hipwifi_deployrsc'); }}">
    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 
            <h1 class="page-header">Update RSC Script</h1>
            @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
              </div>
            @endif

          <div class="row">
            <div class="col-md-12">

                {{ Form::hidden('id', $data['venue']->id) }}

                {{ $data['message'] }}
                  
                <label for="scripttext">Enter the RSC script for venue {{$data['venue']->sitename}} - {{$data['venue']->macaddress}}</label><br>
                <!-- Modal Button Starts -->
                <button type="button" class="btn btn-default btn-info pull-right" data-toggle="modal" data-target="#myModal">Show Running Configuration</button>
                <!-- Modal Button Ends -->

                <div class="form-group">
                    <input type="checkbox" name="overridersc"> Override Existing Configuration
                </div>
                <div class="form-group">
                  <textarea rows="20" cols="30" class="form-control" name="scripttext" id="scripttext" placeholder="Enter script text here" ></textarea>
                </div>     

                <br> 
                <button id="submitform" class="btn btn-primary">Submit</button>
                
                <a href="{{ url('hipwifi_showvenues'); }}" class="btn btn-default">Cancel</a>
                

              </div>              
              
              
            <!-- Modal that displays running config starts-->
              <!-- Modal -->
              <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h6 class="modal-title">{{$data['venue']["sitename"]}}'s Running Configuration</h6>
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
            <!-- Modal ends-->

                </div>
              </div>
          </div>

          


                
                    

                
              
              

          </div>
        </div>
      </div>
    </div>
  </form>
     

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/prefixfree.min.js"></script>
    


  </body>
@stop
