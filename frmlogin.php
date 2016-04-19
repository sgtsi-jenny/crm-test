<?php
	require_once("support/config.php");

  if(isLoggedIn()){
    redirect("index.php");
    die();
  }

	makeHead("Login");
?>
    <div class="login-box">
      <div class="login-logo">
        <img src="" class='img-responsive'>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <?php
          Alert();
        ?>
        <p class="login-box-msg">Login to your Account</p>
        <form action="logingin.php" method="post">
          <div class="form-group has-feedback">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            <input type="text" class="form-control" placeholder="Username" name='username'>
          </div>
          <div class="form-group has-feedback">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <input type="password" class="form-control" placeholder="Password" name='password'>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
            </div><!-- /.col -->
            <div class="col-xs-12" align="center">
              <a align="center">Forgot Password?</a>
            </div>
          </div>
        </form>


      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

<?php
  Modal();
	makeFoot();
?>