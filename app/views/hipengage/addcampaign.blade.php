@extends('layout')

@section('content')

  <body class="hipENGAGE">

    <div class="container-fluid">
      <div class="row">

        @include('hipengage.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

            <h1 class="page-header">Add Campaign</h1>
            <div class="form-group">
                <label for="exampleInputEmail1">Campaign Name</label>
                <input class="form-control" id="" placeholder="" type="text">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Start Date</label>
                <input class="form-control" id="" placeholder="" type="text">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">End Date</label>
                <input class="form-control" id="" placeholder="" type="text">
              </div>
              <div class="form-group">
                <label class="checkbox-inline">
  <input id="inlineCheckbox1" value="option1" type="checkbox"> External
</label>
<label class="checkbox-inline">
  <input id="inlineCheckbox2" value="option2" type="checkbox"> Internal
</label>
              </div>
              <div class="form-group">
                  <div class="input-group">
                    <label>Campaign Type</label>
                    <select class="form-control no-radius">
                      <option></option>
                      <option>option</option>
                      <option>option</option>
                    </select>
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
                    </span> </div> 
                </div>
                <br>
            <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading"> Targeting </div>
              <div class="panel-body">
                <div class="form-group">
                  <div class="input-group">
                    <label>Country</label>
                    <select class="form-control no-radius">
                      <option></option>
                      <option>South Africa</option>
                    </select>
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
                    </span> </div>
                  <span id="helpBlock" class="help-block">If you wish to choose all venues in a country leave all other fields below this one blank</span> 
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <label>Province/State</label>
                    <select class="form-control no-radius">
                    <option></option>
                      <option>Western Cape</option>
                      <option>2</option>
                    </select>
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
                    </span> 
                  </div>
                  <span id="helpBlock" class="help-block">If you wish to choose all venues in a Province leave all other fields below this one blank</span> 
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <label>City</label>
                    <select class="form-control no-radius">
                    <option></option>
                      <option>Cape Town</option>
                      <option>2</option>
                    </select> 
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
                    </span> 
                  </div>
                  <span id="helpBlock" class="help-block">If you wish to choose all venues in a City leave the field below this one blank</span> 
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <label>Venue</label>
                    <select class="form-control no-radius">
                    <option></option>
                      <option>Kauai Claremont</option>
                      <option>2</option>
                    </select> 
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="button"><i class="fa fa-plus"></i> Add</button>
                    </span> 
                  </div>
                  <span id="helpBlock" class="help-block">This will load the media ONLY at this venue</span> 
                </div>
              </div>
            </div>
          </div>
        </div>
        <a href="{{ url('hipengage_showcampaigns'); }}" class="btn btn-primary">Submit</a>
        <a href="{{ url('hipengage_showcampaigns'); }}" class="btn btn-default">Cancel</a>
        
        </div>

      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/prefixfree.min.js"></script> 

  </body>
@stop
