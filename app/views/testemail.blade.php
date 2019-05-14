<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html>

    <head>
        <title>Test engage/message</title>
    </head>

    </body>

        <form method="POST" id="mainform">

            <div>
                To
                <input type="text" id="to" name="to" >
            </div>

            <div>
                Subject
                <input type="text" id="subject" name="subject" >
            </div>

            <div>
                Message
                <input type="text" id="message" name="message" >
            </div>

            <div>
                Headers
                <input type="text" id="headers" name="headers" >
            </div>

            <div>
                <button type="submit">Send Email</button>
            </div>

        </form>
        <br>
        <div id="response_message"></div>

        <script src="/js/jquery.min.js"></script>

        <script type="text/javascript">

            $('#mainform').submit(function(event) {

                data = { 
                    'to': $( "#to" ).val(), 
                    'subject': $( "#subject" ).val(),
                    'message': $( "#message" ).val(),
                    'headers': $( "#headers" ).val(),
                  };

                  console.log(data);

                $.ajax({
                  type: "POST",
                  dataType: 'json',
                  data: data,
                  url: '/lib_sendtestemail',
                  success: function(returnjson) {
                    console.log("POST complete : " + returnjson);
                    $( '#response_message' ).html(returnjson);
                  }
                });
                event.preventDefault();
              });

        </script>

    <body>

</html>