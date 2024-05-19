<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign in App</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/signin.css'); ?>">
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" type="image/x-icon">
    <script src="<?php echo base_url('assets/js/jquery-3.3.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.min.js'); ?>"></script>
  </head>
  <body>
    <div class="container">
      <form class="form-signin" method="POST" action="<?php echo base_url('index.php/action/auth/signin'); ?>">
        <h2 class="form-signin-heading">Sign in App</h2>
        <hr/>
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="email" class="form-control" placeholder="example@gmail.com" name="email">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" id="password" class="form-control" name="password">
        </div>
        <div class="form-group">
          <label>Please prove you're not a robot</label>
          <p>
            <img id="captcha" src="<?php echo base_url('index.php/action/auth/captcha'); ?>" width="160" height="45" border="1" alt="CAPTCHA">
            <small>
              <a href="#" onclick="document.getElementById('captcha').src = '<?php echo base_url('index.php/action/auth/captcha'); ?>?' + Math.random(); document.getElementById('captcha_code').value = ''; return false;">refresh</a>
            </small>
          </p>
            <input id="captcha_code" type="text" class="form-control" name="captcha">
        </div>
        <div class="col-xs-6 form-group">
          <label><a href="#">Sign up</a></label>
        </div>
        <div class="col-xs-6 form-group">
          <label><a href="#">Forgot password</a></label>
        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" id="signin" value="Sign in">
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <input type="hidden" id="" value="">
        <div class='error text-danger text-center h4' style="margin-top:10px;"><?php echo $this->session->flashdata('error'); ?></div>
      </form>
    </div>
  </body>
</html>