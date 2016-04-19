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


	makeHead("Official Business Request");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Official Business Request
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
		              	<form class='form-horizontal' action='save_ob_request.php' method="POST" onsubmit="return validate(this)">
		              		<div class='form-group'>
                        <label for="date_from" class="col-sm-3 control-label">Date and Time Start *</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control date_time_picker" id="date_from"  name='date_from' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="date_to" class="col-sm-3 control-label">Date and Time End *</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control date_time_picker" id="date_to"  name='date_to' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                          </div>
                      </div>
		              		<div class="form-group">
		                      <label for="destination" class="col-sm-3 control-label">Destination *</label>
		                      <div class="col-sm-9">
		                        <input type="text"  class="form-control" id="destination"  name='destination' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
		                      </div>
		                  </div>
                      <div class="form-group">
                          <label for="purpose" class="col-sm-3 control-label">Purpose * </label>
                          <div class="col-sm-9">
                            <textarea class='form-control' name='purpose' id='purpose' rows='5' required=""></textarea>
                          </div>
                      </div>

		                    <div class="form-group">
		                      <div class="col-sm-10 col-md-offset-2 text-center">
		                      	<a href='ob_request.php' class='btn btn-default'>Cancel</a>
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

    if(Date.parse($("#date_from")).val() > Date.parse($("#date_to").val())){
      alert("Start time cannot be greater than end time.");
      return false;
    }
    else if(Date.parse($("#date_from").val()) == Date.parse($("#date_to").val())){
      alert("End time should be greater than start time.")
      return false;
    }

    return true;
  }
</script>

<?php
	makeFoot();
?>