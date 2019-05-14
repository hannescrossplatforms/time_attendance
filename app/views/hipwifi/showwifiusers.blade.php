@extends('layout')

@section('content')

<body class="hipWifi">
<div class="container-fluid">
  <div class="row">
    @include('hipwifi.sidebar')
<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
          	<h1 class="page-header">User Management</h1>
			<form style="margin-bottom: 15px;" role="form" class="form-inline">
              <div class="form-group">
                <label for="exampleInputEmail2" class="sr-only">Mobile Phone</label>
                <input type="email" placeholder="Mobile Phone" id="exampleInputEmail2" class="form-control">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail2" class="sr-only">Email</label>
                <input type="email" placeholder="Email Address" id="exampleInputEmail2" class="form-control">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail2" class="sr-only">Username</label>
                <input type="email" placeholder="Username" id="exampleInputEmail2" class="form-control">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail2" class="sr-only">Server</label>
                <input type="email" placeholder="Server" id="exampleInputEmail2" class="form-control">
              </div>
              <button class="btn btn-primary" type="submit">Filter</button>
              <button class="btn btn-default" type="submit">Reset</button>
            </form>
			<div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Mobile Phone</th>
                  <th>Email Address</th>
                  <th>Date of Birth</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Wellborn</td>
                  <td>27 82 827 9423</td>
                  <td>kauai.greenstone@hipzone.net</td>
                  <td>20/10/1986</td>
                  <td><a class="btn btn-default btn-sm" href="{{ url('hipwifi_editwifiuser/1'); }}">edit</a> <a href="#" class="btn btn-default btn-delete btn-sm">delete</a> <a class="btn btn-default btn-sms btn-sm" href="#">sms</a></td>
                </tr>
                
              </tbody>
            </table>
          </div>
          
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
