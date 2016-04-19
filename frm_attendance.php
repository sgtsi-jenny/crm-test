<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

  $employees=$con->myQuery("SELECT id,CONCAT(first_name,' ',last_name) as name FROM employees WHERE is_deleted=0 and is_terminated=0")->fetchAll(PDO::FETCH_ASSOC);    
	$data="";
	if(!empty($_GET['id'])){
  		$data=$con->myQuery("SELECT a.id,employees_id,in_time,out_time
             FROM attendance a WHERE id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  		if(empty($data)){
  			Modal("Invalid Record Selected");
  			redirect("certifications.php");
  			die;
  		}
	}
  
	makeHead("Attendance Form");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Attendance Form
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
		              	<form class='form-horizontal' action='save_attendance.php' method="POST" onsubmit='return validate(this)'>
		              		<input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:''; ?>'>
		              		<div class="form-group">
		                      <label for="employees_id" class="col-sm-2 control-label">Employee *</label>
		                      <div class="col-sm-10">
		                        <?php 
                              if (!empty($data)) {
                            ?>
                            <select class='form-control select2' name='employees_id' data-placeholder="Select Employee" <?php echo !(empty($data))?"data-selected='".$data['employees_id']."'":NULL ?> disabled>           
                            <?php echo makeOptions($employees); }else{ ?>
                            <select class='form-control select2' name='employees_id' data-placeholder="Select Employee" <?php echo !(empty($data))?"data-selected='".$data['employees_id']."'":NULL ?> required>           
                            <?php
                                echo makeOptions($employees);
                              }
                            ?>
                            </select>
		                      </div>
		                  </div>
                      <div class="form-group">
                          <label for="in_time" class="col-sm-2 control-label">Time in * </label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control date_time_picker" id="in_time"  name='in_time' data-default='<?php echo !empty($data)?htmlspecialchars($data['in_time']):date("Y-m-d"); ?>' value='' required>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="name" class="col-sm-2 control-label">Time out * </label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control date_time_picker" id="out_time"  name='out_time' data-default='<?php echo !empty($data)?htmlspecialchars($data['out_time']):date("Y-m-d"); ?>' required>
                          </div>
                      </div>
		                    <div class="form-group">
		                      <div class="col-sm-10 col-md-offset-2 text-center">
		                      	<a href='monitor_attendance.php' class='btn btn-default'>Cancel</a>
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
  function validate(frm) {

    if(Date.parse($("#in_time").val()) > Date.parse($("#out_time").val())){
      alert("Time in cannot be greater than time out.");
      return false;
    }
    else if(Date.parse($("#in_time").val()) == Date.parse($("#out_time").val())){
      alert("Time out should be greater than time in.")
      return false;
    }

    return true;
  }
</script>

<?php
	makeFoot();
?>