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


	makeHead("Change Shift Request");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Change Shift Request
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
		              	<form class='form-horizontal' action='save_shift_request.php' method="POST" onsubmit='return validate(this)'>
		              		<div class='form-group'>
                        <label for="date_from" class="col-sm-3 control-label">Date Start *</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control date_picker" id="date_from"  name='date_from' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="date_to" class="col-sm-3 control-label">Date End *</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control date_picker" id="date_to"  name='date_to' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="orig_in_time" class="col-sm-3 control-label">Original In Time *</label>
                          <div class="col-sm-9">
                            <div class="input-group bootstrap-timepicker timepicker">
                              <input type="text" class="form-control time_picker" id="orig_in_time"  name='orig_in_time' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                            </div>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="orig_out_time" class="col-sm-3 control-label">Original Out Time *</label>
                          <div class="col-sm-9">
                          <div class="input-group bootstrap-timepicker timepicker">
                              <input type="text" class="form-control time_picker" id="orig_out_time"  name='orig_out_time' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                            </div>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="adj_in_time" class="col-sm-3 control-label">Requested In Time *</label>
                          <div class="col-sm-9">
                            <div class="input-group bootstrap-timepicker timepicker">
                              <input type="text" class="form-control time_picker" id="adj_in_time"  name='adj_in_time' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                            </div>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="adj_out_time" class="col-sm-3 control-label">Requested Out Time *</label>
                          <div class="col-sm-9">
                            <div class="input-group bootstrap-timepicker timepicker">
                              <input type="text" class="form-control time_picker" id="adj_out_time"  name='adj_out_time' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                            </div>
                          </div>
                      </div>
		              		
                      <div class="form-group">
                          <label for="shift_reason" class="col-sm-3 control-label">Reason * </label>
                          <div class="col-sm-9">
                            <textarea class='form-control' name='shift_reason' id='shift_reason' rows='5' required=""></textarea>
                          </div>
                      </div>

		                    <div class="form-group">
		                      <div class="col-sm-10 col-md-offset-2 text-center">
		                      	<a href='shift_request.php' class='btn btn-default'>Cancel</a>
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

    if($("#date_from").val() > $("#date_to").val()){
      alert("Start date cannot be greater than end date.");
      return false;
    }
    
    if($("#orig_in_time").val() == $("#orig_out_time").val()){
      alert("Time out and time in should be different.")
      return false;
    }

    if($("#adj_in_time").val() == $("#adj_out_time").val()){
      alert("Time out and time in should be different.")
      return false;
    }

    return true;
  }
</script>

<?php
	makeFoot();
?>