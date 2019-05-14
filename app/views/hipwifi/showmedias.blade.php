@extends('layout')

@section('content')

  <body class="hipWifi">
    <a id="buildtable"></a>

    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h2 class="page-header">{{$data['brandname']}} Media Management</h2>
           <div class="row">
              <div class="col-md-8">
            <h3 class="small-page-header"> Authentication Pages Media</h3>
              </div>
              <div class="col-md-4">
             <a href="{{ url('hipwifi_addmedia', ['id' => $data['brandid']]); }}" class="btn btn-primary" style="margin-top:20px; float:right;"><i class="fa fa-plus"></i> Add WIFI Media</a>
             </div>
             </div>
			      <form role="form">
                       </form>
            <div class="table-responsive">
                <table id="mediaTable" class="table table-stripe"> 
                  <thead>
                   <tr>
                    <th>Brand Name</th>
                    <th>Targeting...</th>
                    <th></th>
                    <tr>
                  </thead>
                  <tbody>
                     @for($i = 0; $i<count($data['medianame']); $i++)
                        <tr>
                          <td>{{$data['medianame'][$i]}}</td>
                          <td>{{$data['mediatarget'][$i]}}</td>
                          <td><a href="{{url('hipwifi_editmedia/'.$data['mediaid'][$i])}}" class="btn btn-sm btn-default">Edit Splash Page</a></td>
                          <td><a href="{{url('hipwifi_editcpmedia/'.$data['mediaid'][$i])}}" class="btn btn-sm btn-default">Edit Connect Page</a></<td>
                          <td><a href="{{url('hipwifi_deletemedia/'.$data['mediaid'][$i])}}" class="btn btn-sm btn-default btn-delete">Delete</a></td>
                        </tr>
                      @endfor
                  </tbody>

                </table>
                </div>
                <br>

                <br>
            
            <div class="row">
            <div class="col-md-8">
           <h3 class="small-page-header">Advert Media</h3>
           </div>
           <div class="col-md-4">
           <a href="{{ url('hipwifi_addadvert/'.$data['brandid']); }}" class="btn btn-primary" style="margin-top:20px; float:right;"><i class="fa fa-plus"></i> Add Advert Media</a>
           </div>
           </div>
           <table id="advertTable" class="table table-striped"> 
                  <thead>
                    <th>Campaign</th>
                    <th>Targeting...</th>
                    <th>Type</th>
                    <th></th>
                  </thead>
                  <tbody>
                       @for($i=0; $i < count($data['advertname']); $i++)
                      <tr>
                      <td>{{$data['advertname'][$i]}}</td>
                      <td>{{$data['adverttarget'][$i]}}</td>
                      <td>{{$data['adverttype'][$i]}}</td>
                      <td><a href="{{ route('hipwifi_editadvertmedia',  array($data['advertid'][$i], $data['brandid'])); }}" class="btn btn-sm btn-default">Edit</a></td>
                      <td><a href="{{ route('hipwifi_deleteadvertmedia',  array($data['advertid'][$i], $data['brandid'])); }}" class="btn btn-sm btn-danger deladvert">Delete</a></td>
                      </tr>
                      @endfor
                  </tbody>

                </table>

                <!--connect page media starts-->
            


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
    <script>
    $('.deladvert').on('click', function(){
        return confirm("Do you want to delete this advert?");

    });

      $('.delcp').on('click', function(){
        return confirm("Do you want to delete this connect page media?");

    });
    </script>
   

  </body>

@stop