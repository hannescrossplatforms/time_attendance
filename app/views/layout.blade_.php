<?php error_log("herererere"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>Laravel Authentication Demo</title>
    {{ HTML::style('/css/style.css') }}
</head>
<body>
    <div id="container">
        <div id="nav">
            <ul>
                @if(Auth::check())
	                <li><a href="{{ url('profile'); }}">Profile</a></li>
	                <li><a href="{{ url('useradmin'); }}">User Admin</a></li>
	                <li>{{ HTML::linkRoute('logout', 'Logout')  }}</li>
                @else
	                <li>{{ HTML::linkRoute('login', 'Login')  }}</li>
                @endif
            </ul>
        </div><!-- end nav -->

        <!-- check for flash notification message -->
        @if(Session::has('flash_notice'))
            <div id="flash_notice">{{ Session::get('flash_notice') }}</div>
        @endif

        @yield('content')
    </div><!-- end container -->
</body>
</html>