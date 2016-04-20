<header class="main-header" style="">

        <!-- Logo -->
        <a href="index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img src='dist/img/sgtsi favico.png' /></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SGTSI</b> CRM</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <!-- <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a> -->
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              
             
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="" class="dropdown-toggle" data-toggle="dropdown">
                  <?php
                  $php_timestamp = time();
                  $php_timestamp_date = date("F d, Y l h:i A", $php_timestamp);
                  echo $php_timestamp_date;
                   ?>

                </a>
              </li>


              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php
                    // if(empty($_SESSION[WEBAPP]['user']['image'])){
                    //     // if($_SESSION[WEBAPP]['user']['gender']=='Male'){
                    //     //   $image="dist/img/user_male.png";
                    //     // }
                    //     // else{
                          $image="dist/img/user_female.png";
                       // }
                    // }
                    // else{
                    //   $image="employee_images/".$_SESSION[WEBAPP]['user']['image'];
                    // }
                  ?>
                  
                  <span class="hidden-xs">
                    <?php
                        echo "Welcome ";
                        echo  htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
                      ?>
                       <img src="<?php echo $image;?>" class="user-image" alt="User Image">
                  </span>
                 
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">                    
                    <p>
                      <?php
                        echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
                      ?>
                    </p>
                    <img src="<?php echo $image;?>" class="img-circle" alt="User Image">
                  </li>
                  <!-- Menu Footer-->

                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="user_profile.php" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

        </nav>
      </header>
