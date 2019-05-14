@extends('layout')

@section('content')

  <body class="hipTnA">

    <div class="container-fluid">
      <div class="row">

        @include('hiptna.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
            <h1 class="page-header">Management Console</h1>
          <div class="row">
            <div id="errormsg"><?php $message = Session::get('msg'); if(isset($message)) { print_r($message); Session::set('msg',''); } ?> {{ $errors->first('cfile') }}</div>
            <form enctype='multipart/form-data' action="{{ url('hiptna/fileupload'); }}" method='post'>
              <input size='50' type='file' id="cfile" name='cfile'><br />
              <input type="submit" name="submit" value="Upload Roster" onclick="if ( $('#cfile').val() == '' ){ alert( 'select file' ); return false; }">
            </form> 

          </div>
        </div>
      </div>
    </div>

    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
      $(function() {
        availableInstances = "{{ Session::get('availableInstances') }}";
        currentInstance = "{{ Session::get('currentInstance') }}";
      });
    </script>
    
    <script src="{{ asset('js/hiptna/hiptna.js') }}"></script>
    <script src="js/prefixfree.min.js"></script> 

  </body>
@stop
