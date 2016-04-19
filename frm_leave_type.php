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
  		$data=$con->myQuery("SELECT id,name FROM leaves WHERE is_deleted=0 AND id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  		if(empty($data)){
  			Modal("Invalid Record Selected");
  			redirect("leave_type.php");
  			die;
  		}
	}

	makeHead("Leave Type Form");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
              Leave Type Form
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
		              	             <form class='form-horizontal' action='save_leave_type.php' method="POST">
		              		              <input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:''; ?>'>
		              		              
                                    <div class="form-group">
		                                    <label for="name" class="col-sm-2 control-label">Leave Type *</label>
		                                    <div class="col-sm-9">
		                                        <input type="text" class="form-control" id="name" placeholder="Leave Type" name='name' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
		                                    </div>
		                                </div>
<!--
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Minimum Leave Balance *</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="min_leave_bal" placeholder="Minimum Number Leave in Days per Year" name='min_leave_bal' value='<?php echo !empty($data)?htmlspecialchars($data['min_leave_balance']):''; ?>' required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Maximum Leave Balance </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="max_leave_bal" placeholder="Maximum Number of Leave in Days per Year" name='max_leave_bal' value='<?php echo !empty($data)?htmlspecialchars($data['max_leave_balance']):''; ?>' >
                                        </div>
                                    </div>                    

                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Maximum Credit Amount </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="max_credit_amount" placeholder="Maximum Days Credited Into Cash" name='max_credit_amount' value='<?php echo !empty($data)?htmlspecialchars($data['max_credit_amount']):''; ?>'>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <div class="col-sm-9">
                                            <label class="col-sm-4 control-label">
                                              <?php //!empty($data['with_pay'])?$checked='checked="checked"':$checked='' ?>
                                                <input type="checkbox" id="with_pay" name="with_pay" value='Yes' <?php echo $checked; ?>/>
                                                With Pay
                                            </label>
                                            <label class="col-sm-4 control-label">
                                              <?php //!empty($data['is_accrue'])?$checked='checked="checked"':$checked='' ?>
                                                <input type="checkbox" id="is_accrue" name="is_accrue" value='Yes' <?php echo $checked; ?>/>
                                                Is Accrued
                                            </label>
                                            <label class="col-sm-4 control-label">
                                              <?php //!empty($data['is_carried_forward'])?$checked='checked="checked"':$checked='' ?>
                                                <input type="checkbox" id="is_carried_forward" name="is_carried_forward" value='Yes' <?php echo $checked; ?>/>
                                                Is Carried Forward
                                            </label>
                                        </div>
                                    </div> <br>
-->
          		                    <div class="form-group">
            		                      <div class="col-sm-9 col-md-offset-2 text-center">
              		                      	<a href='leave_type.php' class='btn btn-default'>Cancel</a>
              		                        <button type='submit' class='btn btn-success'>Save </button>
            		                      </div>
          		                    </div>

		                          </form>	
                	       </div>
                      </div><!-- /.row -->
                  </div><!-- /.box-body -->
              </div><!-- /.box -->
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