@extends('layout')

@section('content')

 <body id="login">
    
  	<div class="login-container">
    	<div class="login-inner">
        	<div class="logo-login"><img src="img/logo_hiphub_small.svg" /></div>
        	<form role="form">
            	<div class="form-group">
                    <label for="exampleInputEmail1">Password Reset</label>
                    <span class="help-block">Enter your username below and we'll email you instructions on how to reset your password.</span>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Username">
                </div>
                <a class="btn btn-primary btn-submit" href="#">Submit</a>
                <a href="{{ url('login'); }}" class="pull-right">Back to login</a>
            </form>
        </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <script src="/js/prefixfree.min.js"></script> 
    
    <script>
	
		$('.btn-submit').click(function(){
				
			swal({   
				title: "Success",  
				type: "success", 
				text: "We have sent {email address} an email with reset instructions.",   
				confirmButtonText: "Return to login"
				}, 
				
				function(isConfirm){   if (isConfirm) {  
					window.location.href = '{{ url('login'); }}';   
				} 
				});
			
		});

    </script>

  </body>

  @stop