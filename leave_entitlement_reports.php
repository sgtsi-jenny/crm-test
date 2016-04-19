<?php
  require_once("support/config.php");
  if(!isLoggedIn()){
    toLogin();
    die();
  }

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

 //$val=$_SESSION[WEBAPP]['user']['employee_id'];
 // $disp=htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}");

  //var_dump($val." <br> ".$disp);
  //die();
    $query="";
  $employees=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',code,')') as employee_name FROM employees WHERE is_deleted=0 AND is_terminated=0 ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['year'])){
    
    $inputs['year']=$_GET['year'];

//echo $inputs['year'];
//die();

    $query="SELECT 
            e.id,
            e.code,
            CONCAT(e.last_name,' ',e.first_name,' ',e.middle_name) AS employee_name,
            (SELECT d.name FROM departments d WHERE d.id=e.department_id) AS department,
            IFNULL((SELECT COUNT(eld.id) FROM employees_leaves_date eld INNER JOIN employees_leaves el ON el.id=eld.employees_leaves_id WHERE el.status='Approved' AND el.remark='L' AND el.employee_id=e.id AND DATE_FORMAT(eld.date_leave,'%Y')=:year),0) AS annual_leave,
            IFNULL((SELECT SUM(eal.balance_per_year) FROM employees_available_leaves eal WHERE eal.employee_id=e.id AND eal.is_deleted=0 AND DATE_FORMAT(eal.date_added,'%Y')=:year GROUP BY eal.employee_id),0) AS balance
          FROM employees e WHERE e.is_deleted=0 AND e.is_terminated=0";


    if(!empty($_GET['employees_id']) && $_GET['employees_id']!='NULL'){
      $query.=" AND e.id=:employees_id";
      $inputs['employees_id']=$_GET['employees_id'];
    }

    $data=$con->myQuery($query,$inputs)->fetchAll(PDO::FETCH_ASSOC); 
    
  }

  makeHead("Leave Entitlement");
?>
  
<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Leave Entitlement
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
                    <form class='form-horizontal' action='' method="GET">
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
                        <div class="form-group">
                          <label for="year" class="col-sm-3 control-label">Year *</label>
                          <div class="col-sm-9">
                            <select class='form-control select2' name='year' data-placeholder='Year' style='width:100%'>
                            <?php
                                for ($current_year=date("Y"); $current_year>1999; $current_year--) {
                                  echo "<option value='".$current_year."'>" . $current_year . "</option> ";
                                }
                            ?>
                            </select>
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
                        <th class='text-center'>Department</th>
                        <th class='text-center'>Availed</th>
                        <th class='text-center'>Entitlement</th>
                        <th class='text-center'>Balance</th>
                      </thead>
                      <tbody>
                        <?php
                          foreach ($data as $row):
                        ?>
                          <tr>
                            <td><?php echo htmlspecialchars($row['code']) ?></td>
                            <td><?php echo htmlspecialchars($row['employee_name']) ?></td>
                            <td><?php echo htmlspecialchars($row['department']) ?></td>
                            <td><?php echo htmlspecialchars($row['annual_leave']) ?></td>
                            <td><?php echo intval($row['annual_leave'])+intval($row['balance']); ?></td>
                            <td><?php echo htmlspecialchars($row['balance']) ?></td>
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
<?php
  makeFoot();
?>