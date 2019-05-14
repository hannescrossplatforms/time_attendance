@extends('layout')

@section('content')

  <body class="hipJAM">

    <div class="container-fluid">
      <div class="row">

        @include('hipjam.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
 
            <h1 class="page-header">Add Prospect</h1>
          <form>
              <div class="form-group">
                  <label>Reseller Name</label>
                    <input class="form-control" placeholder="" type="text">
                </div>
                <div class="form-group">
                  <label>Prospect Name</label>
                    <input class="form-control" placeholder="" type="text">
                </div>
                <div class="form-group">
                  <label>Country of Origin</label>
                    <select class="form-control">
                      <option>South Africa</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                </div>
                <div class="form-group">
                  <label>No of Venues</label>
                    <input class="form-control" placeholder="" type="text">
                </div>
                <div class="form-group">
                  <label>Initial Contact Made</label>
                    <br>
                    <label class="radio-inline">
  <input name="inlineRadioOptions" id="inlineRadio1" value="option1" type="radio"> Yes
</label>
<label class="radio-inline">
  <input name="inlineRadioOptions" id="inlineRadio2" value="option2" type="radio"> No
</label>
                </div>
                <div class="form-group">
                  <label>Registration Date</label>
                    <input class="form-control" placeholder="21/01/2015" disabled="" type="text">
                </div>
                <a href="{{ url('hipjam_showvenues'); }}" class="btn btn-primary">Submit</a> 
                <a href="{{ url('hipjam_showvenues'); }}" class="btn btn-default">Cancel</a>
            </form>
                 
        </div>

      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/prefixfree.min.js"></script> 
    
    <script>
  
    $('.btn-removeAccount').click(function(){
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to remove this brand?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, remove it!',
        closeOnConfirm: false,
        //closeOnCancel: false
      },
      function(){
        swal("Removed!", "Brand has been removed!", "success");
      });
    });
      


    </script>

  </body>
</html>
