<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

	$data=""; 

	if(!empty($_GET['id'])){
  		$data=$con->myQuery("SELECT id,employee_id,leave_id,date_start,date_end,supervisor_id,final_approver_id,date_filed,supervisor_date_action,approver_date_action,reason,status FROM employees_leaves WHERE id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  		if(empty($data)){
  			Modal("Invalid Record Selected");
  			redirect("employee_leave_request.php");
  			die;
  		}
	}

  $leave_type=$con->myQuery("SELECT eal.leave_id,
                                  CONCAT((SELECT NAME FROM LEAVES WHERE id=eal.leave_id),' (',eal.balance_per_year,' day/s left)') AS leave_balance
                            FROM employees_available_leaves eal
                            WHERE is_cancelled=0 AND is_deleted=0 AND employee_id=?",array($_SESSION[WEBAPP]['user']['employee_id']))->fetchAll(PDO::FETCH_ASSOC);
  $leave_bal=$con->myQuery("SELECT SUM(eal.balance_per_year) AS leave_balance FROM employees_available_leaves eal WHERE employee_id=? AND is_deleted=0",array($_SESSION[WEBAPP]['user']['employee_id']))->fetch(PDO::FETCH_ASSOC);
//echo intval($leave_bal['leave_balance']);
//die();

	makeHead("Application for Leave Form");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
             Application for Leave Form
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
		              	<form class='form-horizontal' action='save_leave_request.php' method="POST">

                      <input type='hidden' name='get_id' value='<?php echo !empty($get_id)?$get_id:''; ?>'> 
<!--                      <input type='hidden' name='get_employee_id' value='<?php //echo !empty($get_employee_id)?$get_id:''; ?>'>   -->
		              		<input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:''; ?>'>

                       <div class='form-group'>
                        <label for="name" class="col-sm-2 control-label">Type of Leave *</label>
                          <div class='col-sm-9'>
                            <select class='form-control select2' name='leave_id' data-placeholder="Select Type of Leave" <?php echo !(empty($data))?"data-selected='".$data['leave_id']."'":NULL ?> required >           
                            <?php
                              if(intval($leave_bal['leave_balance']) == 0 || intval($leave_bal['leave_balance']) == NULL){
                                echo makeOptions($leave_type,"Without Pay Leave",0,"disabled");
                              }
                              else
                              {
                                echo makeOptions($leave_type);
                              }
                            ?>
                            </select>
                          </div>
                        </div> 

                        <div class="form-group">
                          <label for="date_start" class="col-md-2 control-label">Leave Start Date *</label>
                          <div class="col-md-9">
                            <input type="date" class="form-control" id="date_start" name='date_start' required>
                          </div>
                        </div> 

                        <div class="form-group">
                          <label for="date_end" class="col-md-2 control-label">Leave End Date *</label>
                          <div class="col-md-9">
                            <input type="date" class="form-control" id="date_end" name='date_end'>
                          </div>
                        </div> 

                        <div class="form-group">
                          <label for="reason" class="col-md-2 control-label">Reason *</label>
                          <div class="col-md-9">
                            <textarea class='form-control' name='reason' id='reason'  required><?php echo !empty($data)?htmlspecialchars($data['reason']):''; ?></textarea>
                          </div>
                        </div>

		                    <div class="form-group">
		                      <div class="col-sm-9 col-md-offset-2 text-center">
		                      	<a href='employee_leave_request.php' class='btn btn-default'>Cancel</a>
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