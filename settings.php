<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
     toLogin();
     die();
    }

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

  $data=$con->myQuery("SELECT id,email_username,email_password,email_host,email_port,time_in_module FROM settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    makeHead("Email Settings");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Email Settings
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-8 col-md-offset-2'>
              <?php 
                Alert();
              ?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                        <form class='form-horizontal' action='save_settings.php' method='POST' >
                          <div class='form-group'>
                            <label class='col-md-3'>Email</label>
                            <div class='col-md-9'>
                              <input type="email" name='email_username' required="" class='form-control' value='<?php echo !empty($data)?$data['email_username']:'' ?>'/>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label class='col-md-3'>Password</label>
                            <div class='col-md-9'>
                              <input type="password" name='email_password' required="" class='form-control' value='<?php echo !empty($data)?decryptIt($data['email_password']):'' ?>'/>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label class='col-md-3'>Email Host</label>
                            <div class='col-md-9'>
                              <input type="text" name='email_host' required="" class='form-control' value='<?php echo !empty($data)?$data['email_host']:'' ?>'/>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label class='col-md-3'>Email Port</label>
                            <div class='col-md-9'>
                              <input type="text" name='email_port' required="" class='form-control' value='<?php echo !empty($data)?$data['email_port']:'' ?>'/>
                            </div>
                          </div>
                          <div class='form-group'>
                            <label class='col-md-3'>Time-in/Time-out Module:</label>
                            <div class='col-md-9'>
                              <div class="checkbox">
                              <label>
                                <input type="checkbox" name='time_in_module' value='1' <?php echo !empty($data['time_in_module'])?'checked="true"':'' ?>>
                                Enabled
                              </label>
                            </div>
                            </div>
                          </div>
                          <div class='form-group'>
                            <div class='col-md-9 col-md-offset-3 text-center'>
                              <button type='submit' class='btn btn-success'>Save</button>
                            </div>
                          </div>
                        </form>
                        
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
</script>

<?php
  Modal();
    makeFoot();
?>