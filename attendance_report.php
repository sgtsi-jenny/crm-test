<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

  $employees=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',code,')') as employee_name FROM employees WHERE is_deleted=0 AND is_terminated=0 ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);

   

	makeHead("Attendance Report");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Attendance Report
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-12'>
				<?php
					Alert();
				?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                	<div class='col-md-12'>
		              	<form class='form-horizontal' action='' method="GET" onsubmit='return validate(this)'>
                      <?php
                        if(AllowUser(array(1))):
                      ?>
		              		<div class="form-group">
		                      <label for="employees_id" class="col-sm-3 control-label">Employee *</label>
		                      <div class="col-sm-9">
                            <select class='form-control select2' name='employees_id' data-placeholder="All Employees" <?php echo !(empty($_GET))?"data-selected='".$_GET['employees_id']."'":NULL ?> style='width:100%'>
                            <?php
                              echo makeOptions($employees,"All Employees");
                            ?>
                            </select>
		                      </div>
		                  </div>
                      <?php
                        endif;
                      ?>
                      <div class='form-group'>
                        <label for="date_from" class="col-sm-3 control-label">Date Start *</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control" id="date_from"  name='date_from' value='<?php echo !empty($_GET)?htmlspecialchars($_GET['date_from']):''; ?>' required>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="date_to" class="col-sm-3 control-label">Date End *</label>
                          <div class="col-sm-9">
                            <input type="date" class="form-control" id="date_to"  name='date_to' value='<?php echo !empty($_GET)?htmlspecialchars($_GET['date_to']):''; ?>' required>
                          </div>
                      </div>
                      
		                    <div class="form-group">
		                      <div class="col-sm-9 col-md-offset-3 text-center">
		                        <button type='submit' class='btn btn-success'>Filter </button>
		                      </div>
		                    </div>
		                </form>	
                	</div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <?php
                if(!empty($_GET)):
              ?>
              <div class="box box-solid">
                <div class="box-body">
                  <div class="row">
                  <div class='col-md-12'>
                    <form method='post' action='download_attendance_report.php' style='display: inline'>
                      <input type='hidden' name='employees_id' value='<?php echo !empty($_GET['employees_id'])?htmlspecialchars($_GET['employees_id']):'';?>'>
                      <input type='hidden' name='date_from' value='<?php echo !empty($_GET['date_from'])?htmlspecialchars($_GET['date_from']):''?>'>
                      <input type='hidden' name='date_to' value='<?php echo !empty($_GET['date_to'])?htmlspecialchars($_GET['date_to']):''?>'>
                      <button class='btn btn-sm btn-default'  title=''><span class='fa fa-download'></span> Download as Excel File </button>
                    </form>
                  </div>
                  <div class='col-md-12'>
                  <br/>
                    <table class='table table-bordered table-striped' id='ResultTable'>
                      <thead>
                        <th class='text-center'>Employee Code</th>
                        <th class='text-center'>Employee Name</th>
                        <th class='text-center date-td'>Date</th>
                        <th class='text-center date-time-td'>In Time</th>
                        <th class='text-center date-time-td'>Out Time</th>
                        <th class='text-center'>Overtime</th>
                        <th class='text-center'>Status</th>
                        <th class='text-center'>Note</th>
                      </thead>
                      <tbody>
                        
                     
                      </tbody>
                    </table>
                  </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <?php
                endif;
              ?>

            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>
<script type="text/javascript">
   function validate(frm) {

    if(Date.parse($("#date_from").val()) > Date.parse($("#date_to").val())){
      alert("Start Date cannot be greater than time out.");
      return false;
    }
    else if(Date.parse($("#date_from").val()) == Date.parse($("#date_to").val())){
      alert("End Date should be greater than time in.")
      return false;
    }

    return true;
  }
</script>
<?php
  if(!empty($_GET)):
?>
<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
          "processing": true,
                //"serverSide": true,
                "scrollX": true,
                "searching":false,
                "ajax":{
                  "url":"ajax/attendance_report.php",
                  "dataSrc":"",
                  "data":function(d){
                    d.date_from='<?php echo !empty($_GET['date_from'])?$_GET['date_from']:''; ?>';
                    d.date_to='<?php echo !empty($_GET['date_to'])?$_GET['date_to']:''; ?>';
                    d.employees_id='<?php echo !empty($_GET['employees_id'])?$_GET['employees_id']:''; ?>';
                  }
                },
                  columns: [
                      { data: 'code' },
                      { data: 'employee' },
                      { data: 'date' },
                      { data: 'in_time' },
                      { data: 'out_time' },
                      { data: 'ot' },
                      { data: 'status' },
                      { data: 'note' }
                  ]
        });
      });
</script>
<?php
  endif;
?>
<?php
	makeFoot();
?>