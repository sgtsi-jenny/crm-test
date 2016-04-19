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
	if(!empty($_GET['id'])){
  		$data=$con->myQuery("SELECT id,name,description,parent_id,approver_id FROM departments dpt WHERE is_deleted=0 AND id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  		if(empty($data)){
  			Modal("Invalid Record Selected");
  			redirect("departments.php");
  			die;
  		}
	}

  $parent_dept=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
  $approver=$con->myQuery("SELECT id,CONCAT(first_name,' ',last_name) as name FROM employees WHERE is_deleted=0 and is_terminated=0")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("Departments Form");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Departments Form
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
		              	<form class='form-horizontal' action='save_departments.php' method="POST">
		              		<input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:''; ?>'>

		              		<div class="form-group">
		                      <label for="name" class="col-sm-2 control-label">Department Name *</label>
		                      <div class="col-sm-9">
		                        <input type="text" class="form-control" id="name" placeholder="Department Code Name" name='name' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
		                      </div>
		                  </div>

                      <div class="form-group">
                          <label for="name" class="col-sm-2 control-label">Description *</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" id="description" placeholder="Description" name='description' value='<?php echo !empty($data)?htmlspecialchars($data['description']):''; ?>' required>
                          </div>
                      </div>

                      <div class='form-group'>
                          <label for="parent_dept" class="col-sm-2 control-label"> Parent Department</label>
                          <div class='col-sm-9 '>
                                    <select class='form-control' name='parent_id' data-placeholder="Select Parent Department" <?php echo!(empty($data))?"data-selected='".$data['parent_id']."'":NULL ?> >
                                        <?php
                                            echo makeOptions($parent_dept);
                                        ?>
                                    </select>
                          </div>
                      </div>

                      <div class='form-group'>
                          <label for="approver" class="col-sm-2 control-label"> Department Approver *</label>
                          <div class='col-sm-9 '>
                                    <select class='form-control select2' name='approver_id' data-placeholder="Select Department Approver" <?php echo!(empty($data))?"data-selected='".$data['approver_id']."'":NULL ?> required>
                                        <?php
                                            echo makeOptions($approver);
                                        ?>
                                    </select>
                          </div>
                      </div>

		                    <div class="form-group">
		                      <div class="col-sm-10 col-md-offset-2 text-center">
		                      	<a href='departments.php' class='btn btn-default'>Cancel</a>
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