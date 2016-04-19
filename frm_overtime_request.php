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


	makeHead("Overtime Request");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Overtime Request
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
		              	<form class='form-horizontal' action='save_overtime_request.php' method="POST" onsubmit='return validateOvertime(this)'>
		              		<div class='form-group'>
                        <label for="date_from" class="col-sm-3 control-label">Date and Time Start *</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control date_time_picker" id="date_from"  name='date_from' required>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="date_to" class="col-sm-3 control-label">Date and Time End *</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control date_time_picker" id="date_to"  name='date_to' required>
                          </div>
                      </div>
		              		<div class="form-group">
		                      <label for="no_hours" class="col-sm-3 control-label">No of Hours *</label>
		                      <div class="col-sm-9">
		                        <input type="number" step=".25" class="form-control" id="no_hours"  name='no_hours' min='1' required>
		                      </div>
		                  </div>
                      <div class="form-group">
                          <label for="worked_done" class="col-sm-3 control-label">Work to be done * </label>
                          <div class="col-sm-9">
                            <textarea class='form-control' id='worked_done' name='worked_done' rows='5' required=""></textarea>
                          </div>
                      </div>

		                    <div class="form-group">
		                      <div class="col-sm-10 col-md-offset-2 text-center">
		                      	<a href='overtime_request.php' class='btn btn-default'>Cancel</a>
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
  function validateOvertime(frm) {

    
    if($("#date_from").val() > $("#date_to").val()){
      alert("Start time cannot be greater than end time.");
      return false;
    }
    else if($("#date_from").val() == $("#date_to").val()){
      alert("End time should be greater than start time.")
      return false;
    }

    return true;
  }
</script>

<?php
	makeFoot();
?>