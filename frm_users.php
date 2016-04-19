<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

	$data="";
  if(empty($_GET['id'])){
    $get_id=$_GET['id'];
  }
  else{
    $get_id="";
  }

	if(!empty($_GET['id'])){
  		$data=$con->myQuery("SELECT id,employee_id,username,password,user_type_id FROM users WHERE is_deleted=0 AND id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  		if(empty($data)){
  			Modal("Invalid Record Selected");
  			redirect("users.php");
  			die;
  		}
	}
  if(empty($_GET['id'])){
    $employee=$con->myQuery("SELECT id,CONCAT(first_name,' ',last_name) as name FROM employees e WHERE is_deleted=0 and is_terminated=0 AND id NOT IN(SELECT employee_id FROM users WHERE is_deleted=0 AND employee_id=e.id)")->fetchAll(PDO::FETCH_ASSOC);    
  }else{
    $employee=$con->myQuery("SELECT id,CONCAT(first_name,' ',last_name) as name FROM employees WHERE is_deleted=0 and is_terminated=0")->fetchAll(PDO::FETCH_ASSOC);    
  }
  $user_type=$con->myQuery("SELECT id,description FROM user_type")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("User Form");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create User Form
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-10 col-md-offset-1'>
				<?php
					Alert();
				?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                	<div class='col-md-12'>
                  
		              	<form class='form-horizontal' action='save_users.php' method="POST">

                      <input type='hidden' name='get_id' value='<?php echo !empty($get_id)?$get_id:''; ?>'>
		              		<input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:''; ?>'>

                       <div class='form-group'>
                        <label for="name" class="col-sm-2 control-label"> Employee *</label>
                          <div class='col-sm-9'>
                            <?php 
                              if ($get_id>0) {
                            ?>
                            <select class='form-control select2' name='emp_id' data-placeholder="Select Employee" <?php echo !(empty($data))?"data-selected='".$data['employee_id']."'":NULL ?> disabled>           
                            <?php echo makeOptions($employee); }else{ ?>
                            <select class='form-control select2' name='emp_id' data-placeholder="Select Employee" <?php echo !(empty($data))?"data-selected='".$data['employee_id']."'":NULL ?> required>           
                            <?php
                                echo makeOptions($employee);
                              }
                            ?>
                            </select>

                          </div>
                        </div> 

  		              		<div class="form-group">
  	                      <label for="name" class="col-sm-2 control-label">Username *</label>
  	                      <div class="col-sm-9">
  	                        <input type="text" class="form-control" id="username" placeholder="Username" name='username' value='<?php echo !empty($data)?htmlspecialchars($data['username']):''; ?>' required>
  	                      </div>
  		                  </div>

                        <div class="form-group">
                          <label for="name" class="col-sm-2 control-label">Password *</label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" placeholder="Password" name='password' value='<?php echo !empty($data)?htmlspecialchars(decryptIt($data['password'])):''; ?>' required>
                          </div>
                        </div>

                        <div class='form-group'>
                          <label for="name" class="col-sm-2 control-label">User Type *</label>  
                            <div class='col-sm-9'>
                              <select class='form-control select2' name='utype_id' data-placeholder="Select User Type" <?php echo!(empty($data))?"data-selected='".$data['user_type_id']."'":NULL ?> required>
                                <?php
                                  echo makeOptions($user_type);
                                ?>  
                              </select>
                            </div>
                        </div>

		                    <div class="form-group">
		                      <div class="col-sm-9 col-md-offset-2 text-center">
		                      	<a href='users.php' class='btn btn-default'>Cancel</a>
		                        <button type='submit' class='btn btn-success'>Save </button>
		                      </div>
		                    </div>
		                </form>	
                	</div>
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
	makeFoot();
?>