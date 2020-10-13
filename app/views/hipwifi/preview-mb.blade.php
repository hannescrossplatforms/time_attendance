<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HipZone Free WiFi</title>
        <meta name = "viewport" content = "width = device-width">
        <meta name="viewport" content="width=320,user-scalable=false" />
        <meta name="author" content="">
        <!--<meta name="format-detection" content="telephone=no">-->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="ico/favicon.png">

        <link href="/web/css/v3m/v3m.css" rel="stylesheet" media="screen" />

        <!-- <script type="text/javascript" src="/web/js/v26/jquery-1.6.js"></script> -->

            <link href="/web/css/v3m/style-blue-login.css" rel="stylesheet" media="screen" />

    </head>

    
<script language="javascript" type="text/javascript">

        document.cookie="testcookie";
        cookieEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
        if (!cookieEnabled) {
            window.location = "/login/cookiesdisabled";
        }

</script>
<noscript> <meta http-equiv="refresh" content="0;url=/login/jsdisabled"> </noscript>

<!-- Begin Mikrotik -->

<!-- End Mikrotik -->

    
        

<body>
    <form action="/login/routerequest" name="login" method="post" >

        <input type="hidden" name="cha" id="cha" value="6da41711b7fc06f36bda7e03fdbfec74" />
        <input type="hidden" name="nas" id="nas" value="kauai_ort" />
        <input type="hidden" name="challenge" id="challenge" value="6da41711b7fc06f36bda7e03fdbfec74" />
        <input type="hidden" name="nasid" id="nasid" value="kauai_ort" />
        <input type="hidden" name="mobile" id="mobile" value="1" />
        <input type="hidden" name="firstvisit" id="firstvisit" value="1" />
        <input type="hidden" name="_csrf_token" value="8e32be24145a2227cd5a1f0b9975d9e5" id="csrf_token" />
        

                
        <fieldset>
            <legend>HipZone WiFi Login </legend>
                        <ul>
                <li>
                    <div class="merrormsg"></div>
                    <input name="login[username]" id="login_username" type="text" placeholder ="Username" />
                    
                </li>
                <BR>
                <li>
                    <div class="merrormsg"></div>
                    
                    <input name="login[password]" id="login_password" type="password" placeholder ="Password"/>
                </li>
            </ul>
            
            <div class="image"><img src="/customer/hipwifi/images/preview-mb.jpg" alt="image"/></div>

            <div class="buttons">
                <button value="zonein" name="route">Zone In</button>
                <button value="lostpassword" name="route">Lost Password</button>
                                    <button>Free Register</button>
                                
                <!-- <button value="purchase" name="route">Buy R50 1Gb DayPass</button> -->
            </div>
        </fieldset>
    </form>
    
    <footer>
        <p>Powered by <a href="#" title="hipzone">hipzone</a></p>
        <ul>
            <li><a href="register/mfaq">FAQ</a></li>
            <li><a href="register/mfaq">Support</a></li>
            <!-- <li><a href="tel:0861438447">0861 438 447</a></li> -->
        </ul>
    </footer>
</body>

<!-- BEGIN FACEBOOK -->
<div id="fb-root"></div>
    <!-- END FACEBOOK -->
    
<script>

    window.fbAsyncInit = function() {
        FB.init({
          appId      : '642086445844937',
          channelUrl : 'http:////www.hip-zone.co.za/channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true  // parse XFBML
        }, { scope: 'publish_actions, publish_stream' });


        FB.Event.subscribe('auth.statusChange', function(response) {
            console.log('The status of the session is: ' + response.status);
        });

        FB.Event.subscribe('auth.authResponseChange', function(response) {
            if (response.status === 'connected') {

                console.log('authResponseChange, in connected');
                // redirectWithCheck();

            } else if (response.status === 'not_authorized') {

                console.log('authResponseChange, in not_authorized');

            } else {

                console.log('authResponseChange, in else');
                FB.login(function(response) {
                    if (response.authResponse) {
                    }
                }, {scope: 'email, user_birthday, user_likes, publish_actions, publish_stream'});

            }
        });
    };

  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));


    function redirectWithCheck() {
        console.log("in hasExtendedPermissions")

        FB.api({ method: 'users.hasAppPermission', ext_perm: 'publish_stream' }, function(resp) {
            if (resp === "1") {
                console.log('Permission granted');
                window.top.location = "/login/facebooklike?res=logoff&uamip=10.1.0.1&uamport=3660&challenge=6da41711b7fc06f36bda7e03fdbfec74&mac=94-39-E5-D6-BC-F7&ip=10.1.0.2&called=68-7F-74-06-1C-F4&nasid=kauai_ort&userurl=http%3A%2F%2Fwww.yahoo.com%2F&mobile=1&testcookie=&symfony=f5930b01f48c42e21602644ca3ffeb67";
            } else {
                console.log("Permission not granted");
            }
        });
    };


  function facebookLogin() {

  FB.login(function(response) {
          if (response.authResponse) {
            redirectWithCheck()
          }
        }, {scope: 'email, user_birthday, user_likes, publish_actions, publish_stream'});
  };

</script><!-- BEGIN FACEBOOK -->
<div id="fb-root"></div>
    <!-- END FACEBOOK -->
    
<script>

    window.fbAsyncInit = function() {
        FB.init({
          appId      : '642086445844937',
          channelUrl : 'http:////www.hip-zone.co.za/channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true  // parse XFBML
        }, { scope: 'publish_actions, publish_stream' });


        FB.Event.subscribe('auth.statusChange', function(response) {
            console.log('The status of the session is: ' + response.status);
        });

        FB.Event.subscribe('auth.authResponseChange', function(response) {
            if (response.status === 'connected') {

                console.log('authResponseChange, in connected');
                // redirectWithCheck();

            } else if (response.status === 'not_authorized') {

                console.log('authResponseChange, in not_authorized');

            } else {

                console.log('authResponseChange, in else');
                FB.login(function(response) {
                    if (response.authResponse) {
                    }
                }, {scope: 'email, user_birthday, user_likes, publish_actions, publish_stream'});

            }
        });
    };

  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));


    function redirectWithCheck() {
        console.log("in hasExtendedPermissions")

        FB.api({ method: 'users.hasAppPermission', ext_perm: 'publish_stream' }, function(resp) {
            if (resp === "1") {
                console.log('Permission granted');
                window.top.location = "/login/facebooklike?res=logoff&uamip=10.1.0.1&uamport=3660&challenge=6da41711b7fc06f36bda7e03fdbfec74&mac=94-39-E5-D6-BC-F7&ip=10.1.0.2&called=68-7F-74-06-1C-F4&nasid=kauai_ort&userurl=http%3A%2F%2Fwww.yahoo.com%2F&mobile=1&testcookie=&symfony=f5930b01f48c42e21602644ca3ffeb67";
            } else {
                console.log("Permission not granted");
            }
        });
    };


  function facebookLogin() {

  FB.login(function(response) {
          if (response.authResponse) {
            redirectWithCheck()
          }
        }, {scope: 'email, user_birthday, user_likes, publish_actions, publish_stream'});
  };

</script>
   
    
              
 

</html>