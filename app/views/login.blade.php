@extends('layout')

@section('content')


<?php 
// error_log("systemstate is " . $data["systemstate"]);

$systemstate = $data["systemstate"] ;
?>


  <body id="login">
    <div class="login-container">
        <div class="login-inner">
            <div class="logo-login"><img src="img/logo_hiphub_small.svg" /></div>
           <!-- check for login error flash var --> 
            @if (Session::has('flash_error'))
                <div id="flash_error">{{ Session::get('flash_error') }}</div>
            @endif
            @if ($data["systemstate"] == "maintenance")
                <h1>
                    We are currently offline for maintenance. <br><br>
                    Please try again later.
                </h1>
            @else
                <form role="form" method="post" action="{{url('login')}}"> <!-- url(for login) -->
                    <div class="form-group">
                        <a href="login_forgotUsername.html" class="pull-right">Forgot Username?</a>
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <a href="{{ url('login_forgotpassword'); }}" class="pull-right">Forgot Password?</a>
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    </div>
                    <div class="checkbox pull-right">
                        <label>
                          <input type="checkbox"> Keep me logged in
                        </label>
                      </div>
                    <button class="btn btn-primary" >Submit</button>
                </form>
            @endif
        </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <script src="/js/prefixfree.min.js"></script> 

  </body>

@stop