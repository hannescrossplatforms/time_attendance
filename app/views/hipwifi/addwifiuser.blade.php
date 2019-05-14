@extends('layout')

<?php $edit = $data["edit"] ?>

@section('content')

  <body class="hipWifi">

    <div class="container-fluid">
      <div class="row">

        @include('hipwifi.sidebar')

        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main"> 

            <h1 class="page-header">Edit User</h1>
  
      <form role="form">
              <div class="form-group">
                <label for="exampleInputEmail1">Username</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="hekke" type="text">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Mobile Number</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="27 82 827 9423" type="tel">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email Address</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="hekke@hotmail.com" type="email">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Password</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="hekke1" type="text">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Server</label>
                <input class="form-control" id="exampleInputEmail1" placeholder="server" disabled="" type="text">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Usergroup</label>
                <select class="form-control">
                  <option>hipzone</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>
                 
              
            </form>
            
            <a href="{{ url('hipwifi_showwifiusers'); }}" class="btn btn-primary">Update</a>
              <a href="{{ url('hipwifi_showwifiusers'); }}" class="btn btn-default">Cancel</a>
          
                   
        </div>

      </div>
    </div>
  
  </body>
@stop
