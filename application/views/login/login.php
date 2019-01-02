
<!DOCTYPE html>
<html>
<head>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script type="text/javascript" src="<?=BASE_URL()?>assets/js/bootstrap.min.js"></script>

  <link rel="stylesheet" type="text/css" href="<?=BASE_URL()?>assets/css/bootstrap.min.css">
  <style type="text/css">
    
  body {
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #eee;

  }
  .fullscreen_bg {
    padding-top: 40px;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-size: cover;
    background-position: 50% 50%;
    background-image: url(<?php echo BASE_URL().'assets/img/login_background.jpg'; ?>);
    /*-webkit-filter: blur(4px);filter: blur(4px);*/
  }

  .container {
    max-width: 500px;
    margin: 0 auto;
    font-weight: bold;
  }

  .logo {
    display: block;
    max-width: 300px;
    margin: 0 auto;

    border-radius: 8px;
  }

  .login-or {
    position: relative;
    font-size: 18px;
    color: #aaa;
    margin-top: 10px;
    margin-bottom: 10px;
    padding-top: 10px;
    padding-bottom: 10px;
  }
  .span-or {
    display: block;
    position: absolute;
    left: 50%;
    top: -2px;
    margin-left: -25px;
    background-color: #fff;
    width: 50px;
    text-align: center;
  }
  .hr-or {
    background-color: #cdcdcd;
    height: 1px;
    margin-top: 0px !important;
    margin-bottom: 0px !important;
  }
  h3 {
    text-align: center;
    line-height: 300%;
  }
  </style>
</head>

<body>
        <div id="fullscreen_bg" class="fullscreen_bg"/>

        <div class="container">
          
          <!-- <h3>Welcome to Pannell Industries Inc.</h3> -->

          <!-- <img class="img_backgroud" src="<?php echo BASE_URL().'assets/img/login_background.jpg'; ?>"> -->
          <img class="logo" src="<?php echo BASE_URL().'assets/img/logo_rec_big2.JPG'; ?>">

          <!-- <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <a href="#" class="btn btn-lg btn-primary btn-block">Facebook</a>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
              <a href="#" class="btn btn-lg btn-info btn-block">Google</a>
            </div>
          </div>
          <div class="login-or">
            <hr class="hr-or">
            <span class="span-or">or</span>
          </div> -->

          <form role="form" action="<?php echo BASE_URL();?>login/login_do" method='post' style="padding-top:50px;">
            <input type="hidden" name="fromurl" value="<?php echo isset($fromurl) && !empty($fromurl) ? $fromurl : $_SERVER['HTTP_REFERER']; ?>">
            <div class="form-group">
              <label for="inputUsernameEmail">Username</label>
              <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
              <a class="pull-right" href="#" title="Please ask IT to reset.">Forgot password?</a>
              <label for="inputPassword">Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <!-- <div class="checkbox pull-right">
              <label>
                <input type="checkbox">
                Remember me </label>
            </div> -->
            <button type="submit" class="btn btn btn-primary">
              Log In
            </button>
          </form>
        
        </div>


</body>