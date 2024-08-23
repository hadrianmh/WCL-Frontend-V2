<?php
// LOAD CONFIG
$configfile = 'config.json';
if (file_exists($configfile)) {
  $getconfig = file_get_contents($configfile);
  $ENV = json_decode($getconfig, TRUE);
  if($ENV !== null && $ENV['base_url_api'] !== null && $ENV['base_path_api'] !== null && $ENV["base_port_api"] !== null && $ENV["base_url"] !== null && $ENV["base_port"] !== null && $ENV["base_path"] !== null){
    // Define env to set cookie
		setcookie('base_url', $ENV["base_url"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_path', $ENV["base_path"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_port', $ENV["base_port"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_url_api', $ENV["base_url_api"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_path_api', $ENV["base_path_api"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_port_api', $ENV["base_port_api"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_dashboard_api', $ENV["base_dashboard_api"], time() + (10 * 365 * 24 * 60 * 60), "/");

  } else {
    echo "Config is not configured well.";
    exit();
  }
} else {
  echo "Config file not found.";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="files/img/favicon.ico" type="image/x-icon">
    <title>Sign up App</title>
    <script src="lib/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="lib/css/bootstrap/bootstrap.min.css">
    <link href="lib/css/theme/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="lib/css/theme/style.css" rel="stylesheet">
    <script>
      $(document).ready(function(){
        $('.form-daftar').validate();

        $('#signup').click(function(){
          if($('.form-daftar').valid() == true){
            $('#loader').show();
            var name = $('#nama').val();
            var email = $('#email').val();
            var pass = $('#password').val();
            var role = $('#role').val();
            var code = $('#captcha_code').val();
            $.ajax({
              type : 'POST',
              url : 'auth/registration.php',
              data : {
                name: name,
                email: email,
                password: pass,
                role: role,
                code: code
              },
              cache: false,
              success : function(output){
                $('#loader').hide();
                $('.responeMsg').show();
                var obj = JSON.parse(output);
                if(obj.code == 200) {
                  $('#nama').val('');
                  $('#email').val('');
                  $('#password').val('');
                  $('#role').val('');
                  $('#captcha_code').val('');
                  $('.responeMsg').html("<div class='alert alert-success'>"+obj.status+"</div>");
                } else {
                  $('.responeMsg').html("<div class='alert alert-danger'>"+obj.response.message+"</div>");
                }
              }
            });
          }
        });
      });
    </script>
  </head>
  <body>
    <div class="container">
      <form class="form-signin form-daftar">
        <h2 class="form-signin-heading">Sign up App</h2>
        <hr/>
        
        <div class='responeMsg' style="display: none"></div>

        <div class="form-group">
          <label>Full Name</label>
          <input type="text" id="nama" class="form-control" value="" required autofocus>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" id="email" class="form-control" value="" placeholder="example@gmail.com" required autofocus>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" id="password" class="form-control" value="" required autofocus>
        </div>

        <div class="form-group">
          <label>Role as</label>
          <select class="form-control" id="role" required>
            <option value="" selected>Select role</option>
            <?php
            if($sql->num_rows > 0){ ?>
              <option value="2">Administrator</option>
              <option value="3">Sales Order</option>
              <option value="4">Finance</option>
              <option value="5">Guest</option>
              <option value="6">Production</option>
            <?php } else { ?>
              <option value="1">Root</option>
              <option value="2">Administrator</option>
              <option value="3">Sales Order</option>
              <option value="4">Finance</option>
              <option value="5">Guest</option>
              <option value="6">Production</option>
            <?php } ?>
          </select>
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
          <label><a href="forgot.php">Forgot password</a></label>
        </div>

        <input class="btn btn-lg btn-primary btn-block" type="button" id="signup" value="Sign up">
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
