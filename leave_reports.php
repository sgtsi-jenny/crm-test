<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

//    if(!AllowUser(array(1))){
//        redirect("index.php");
//    }

 //$val=$_SESSION[WEBAPP]['user']['employee_id'];
 // $disp=htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}");

  //var_dump($val." <br> ".$disp);
  //die();


  $employees=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',code,')') as employee_name FROM employees WHERE is_deleted=0 AND is_terminated=0 ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['date_from']) && !empty($_GET['date_to'])){
    
    $inputs['date_from']=$_GET['date_from'];
    $inputs['date_to']=$_GET['date_to'];

    $query="SELECT 
            eld.id                                                        AS id,
            eld.employees_leaves_id                                       AS leave_id,
            e.id                                                          AS employees_id,
            e.code                                                        AS code,
            CONCAT(e.first_name,' ',e.last_name)                          AS employee_name, 
            el.leave_id                                                   AS leave_id,
            IFNULL((SELECT NAME FROM LEAVES WHERE id=el.leave_id ),0)     AS leave_type,
            eld.date_leave                                                AS leave_date,
            el.reason                                                     AS reason,
            el.status                                                     AS status,
            el.supervisor_date_action                                     AS supervisor_action,
            el.approver_date_action                                       AS approver_action,
            el.date_filed                                                 AS date_filed
        FROM employees_leaves_date eld
        INNER JOIN employees_leaves el
          ON el.id=eld.employees_leaves_id
        INNER JOIN employees e
          ON e.id=el.employee_id
           WHERE eld.date_leave BETWEEN :date_from AND :date_to";

    if(!empty($_GET['employees_id']) && $_GET['employees_id']!='NULL'){
      $query.=" AND e.id=:employees_id ORDER BY eld.date_leave";
      $inputs['employees_id']=$_GET['employees_id'];
    }
    else
    {
      $query.=" ORDER BY e.id ";
    }

    $data=$con->myQuery($query,$inputs)->fetchAll(PDO::FETCH_ASSOC); 
    
  }

	makeHead("Leave Report");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Leave Report
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
						else:
						?>
						<input type='hidden' name='employees_id' value='<?php echo $_SESSION[WEBAPP]['user']['employee_id'];?>'>
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
                    <table class='table table-bordered table-striped' id='ResultTable'>
                      <thead>
                        <th class='text-center'>Employee Code</th>
                        <th class='text-center'>Employee Name</th>
                        <th class='text-center'>Leave Type</th>
                        <th class='text-center date-td'>Date of Leave</th>
                        <th class='text-center'>Reason</th>
                        <th class='text-center'>Status</th>
                        <th class='text-center'>Date Modified</th>
                      </thead>
                      <tbody>
                        <?php
                          foreach ($data as $row):
                        ?>
                          <tr>
                            <td><?php echo htmlspecialchars($row['code']) ?></td>
                            <td><?php echo htmlspecialchars($row['employee_name']) ?></td>
                            <td><?php echo htmlspecialchars($row['leave_type']) ?></td>
                            <td><?php echo htmlspecialchars($row['leave_date']) ?></td>
                            <td><?php echo htmlspecialchars($row['reason']) ?></td>
                            <td><?php echo htmlspecialchars($row['status']) ?></td>
                            <td><?php
                                $res="";
                              if ($row['approver_action'] == "0000-00-00" || $row['approver_action'] == NULL){
                                if ($row['supervisor_action'] == "0000-00-00" || $row['supervisor_action'] == NULL) {
                                  $res="0000-00-00";
                                }
                                else
                                {
                                  $res=$row['supervisor_action'];
                                }
                              }
                              else
                              {
                                $res=$row['approver_action'];
                              }
                             echo htmlspecialchars($res) 
                             ?></td>
                          </tr>
                        <?php
                          endforeach;
                        ?>
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
<?php
  if(!empty($_GET)):
?>
<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
          "scrollX": true,
          searching:false,
          lengthChange:false
          <?php if(!empty($data)):?>
           ,dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File "
                    }
                    ]
          <?php endif; ?>
        });
      });
</script>
<?php
  endif;
?>
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
	makeFoot();
?>