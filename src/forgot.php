<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="files/img/favicon.ico" type="image/x-icon">
    <title>Forgot Password</title>
    <script src="lib/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="lib/css/bootstrap/bootstrap.min.css">
    <link href="lib/css/theme/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="lib/css/theme/style.css" rel="stylesheet">
    <script>
      $(document).ready(function(){
        $('.form-forgot').validate();

        $('#forgot').click(function(){
          if($('.form-forgot').valid() == true){
            $('#loader').show();
            var email = $('#email').val();
            var code = $('#captcha_code').val();
            $.ajax({
              type : 'POST',
              url : 'auth/forgot.php',
              data : 'email='+email+'&code='+code,
              cache: false,
              success : function(respone){
                $('#loader').hide();
                $('.responeMsg').show();
                $('.responeMsg').html(respone);
              }
            });
          }
        });
      });
    </script>
  </head>
  <body>
    <div class="container">

      <form class="form-signin form-forgot">
        <h2 class="form-signin-heading">Forgot Password</h2>
        <hr/>
        
        <div class='responeMsg' style="display: none"></div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" id="email" class="form-control" value="" placeholder="example@gmail.com" required autofocus>
        </div>

        <div class="form-group">
          <label>Please prove you're not a robot</label>

          <p>
            <img id="captcha" src="plugins/captcha/core.php" width="160" height="45" border="1" alt="CAPTCHA">
            <small>
              <a href="#" onclick="document.getElementById('captcha').src = 'plugins/captcha/core.php?' + Math.random(); document.getElementById('captcha_code').value = ''; return false;">refresh</a>
            </small>
          </p>
            <input id="captcha_code" type="text" class="form-control" name="captcha" required>
        </div>

        <div class="col-xs-6 form-group">
          <label><a href="signin.php">Sign in</a></label>
        </div>

        <div class="col-xs-6 form-group">
          <label><a href="forgot.php">Forgot Password</a></label>
        </div>

        <input class="btn btn-lg btn-primary btn-block" type="button" id="forgot" value="Forgot Password">
        <div id="loader" class="img-responsive" style="display: none">
          <center>
            <img src="files/img/ajax-loader.gif">
          </center>
        </div>
      </form>
    </div>
    <script src="lib/js/theme/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>