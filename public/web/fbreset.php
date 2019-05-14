This is the facebook reset page

<button onclick="removeAuth()">Remove authorization</button>
<button onclick="logout()">Logout</button>

<script type="text/javascript" src="/js/v26/jquery.min.js"></script>

<script>

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '570690422943048',
      channelUrl : 'http:////www.hip-zone.co.za/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    console.log("fbAsyncInit");

  };

  function removeAuth() {
    FB.api(
        "/me/permissions",
        "DELETE",
        function (response) {
          console.log("removeAuth 10");
          if (response && !response.error) {
            console.log("removeAuth 20");
          }
        }
    );
  }

  function logout() {
    console.log("logout");
    FB.logout(function(response) {  });
  }
      
  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));
  
</script>