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


	makeHead("Adjustment Request");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Adjustment Request
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
		              	<form class='form-horizontal' action='save_adjustment.php' method="POST" onsubmit='return validate(this)'>
                      <div class="form-group">
                          <label for="adj_in_time" class="col-sm-4 control-label">Adjusted Time in * </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control date_time_picker"   name='adj_in_time' id='adj_in_time' value='' required>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="adj_out_time" class="col-sm-4 control-label">Adjusted Time Out * </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control date_time_picker"   name='adj_out_time' id='adj_out_time' value='' required>
                          </div>
                      </div>
                      <div class='form-group'>
                          <label class='col-md-4 control-label'>Enter Reason *</label>
                        <div class='col-md-8' >
                          <textarea name='reason' required="" class='form-control ' style='resize: none' rows='4'></textarea>
                        </div>
                      </div>
                      <div class='form-group '>
                        <div class='col-md-4 col-md-offset-8'>
                          <button type='submit' class='btn btn-success'>
                            Request for Adjustment
                          </button>
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

    if(Date.parse($("#adj_in_time").val()) > Date.parse($("#adj_out_time").val())){
      alert("Time in cannot be greater than time out.");
      return false;
    }
    else if(Date.parse($("#adj_in_time").val()) == Date.parse($("#adj_out_time").val())){
      alert("Time out should be greater than time in.")
      return false;
    }

    return true;
  }
</script>

<?php
	makeFoot();
?>