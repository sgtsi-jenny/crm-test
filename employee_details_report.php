<?php
  require_once("support/config.php");
  if(!isLoggedIn()){
    toLogin();
    die();
  }

  if(!AllowUser(array(1))){
      redirect("index.php");
  }

  $employees=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name,' (',code,')') as employee_name FROM employees WHERE is_deleted=0 AND is_terminated=0 ORDER BY last_name")->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($_GET['employees_id']) && $_GET['employees_id']!='NULL')
  {
    $inputs['employees_id']=$_GET['employees_id'];

#EMPLOYEES
      $query="SELECT
                e.id,
                e.code as code, 
                e.first_name as fname,
                e.middle_name as mname,
                e.last_name as lname,
                e.nationality,
                e.gender,
                e.birthday,
                e.civil_status,
                IFNULL(e.sss_no,'-') AS sss_no, 
                (SELECT description FROM tax_status WHERE id=e.tax_status_id AND is_deleted=0) as tax_status,
                IFNULL(e.tin,'-') AS tin, 
                IFNULL(e.philhealth,'-') AS philhealth, 
                IFNULL(e.pagibig,'-') AS pagibig,
                (SELECT name FROM employment_status WHERE id=e.employment_status_id AND is_deleted=0) as employment_status,
                (SELECT description FROM job_title WHERE id=e.job_title_id AND is_deleted=0) as job_title,
                (SELECT level FROM pay_grade WHERE id=e.pay_grade_id AND is_deleted=0) as pay_grade,
                CONCAT(e.address1,' ',e.address2,' ',e.city,' ',e.province,' ',e.country,' ',e.postal_code) as full_address,
                e.contact_no, 
                IFNULL(e.work_contact_no,'-') as work_contact_no, 
                e.private_email, 
                IFNULL(e.work_email,'-') AS work_email, 
                e.joined_date,
                (SELECT description FROM departments WHERE id=e.department_id AND is_deleted=0) as dept_name
                
              FROM employees e
              WHERE e.id=:employees_id";
      $data=$con->myQuery($query,$inputs)->fetch(PDO::FETCH_ASSOC); 
      if(empty($data)){
        Alert("Invalid Employee Selected","danger");
        redirect("employee_details_report.php");
        die;
      }

#EMPLOYEE_EDUCATION
      $employee_education=$con->myQuery("SELECT
                                          ee.id,
                                          ee.employee_id,
                                          (SELECT description FROM education_level WHERE id=ee.educ_level_id) AS education_level,
                                          ee.institute,
                                          ee.course,
                                          ee.date_start,
                                          ee.date_end
                                        FROM employees_education ee
                                        WHERE ee.employee_id=:employees_id",$inputs)->fetchAll(PDo::FETCH_ASSOC);
      $emp_educ_count=count($employee_education);

#EMPLOYEES_SKILLS
      $employee_skill=$con->myQuery("SELECT es.id, es.employee_id, (SELECT NAME FROM skills WHERE id=es.skills_id) AS skills FROM employees_skills es WHERE employee_id=:employees_id",$inputs)->fetchAll(PDO::FETCH_ASSOC);
      $emp_skill_count=count($employee_skill);

#EMPLOYMENT HISTORY
      $emp_history=$con->myQuery("SELECT eeh.id, eeh.employee_id, eeh.company, eeh.position, eeh.department, eeh.date_start, eeh.date_end FROM employees_employment_history eeh WHERE employee_id=:employees_id",$inputs)->fetchAll(PDO::FETCH_ASSOC);
      $emp_history_count=count($emp_history);

#EMPLOYEE TRAINING
      $emp_training=$con->myQuery("SELECT et.id, et.employee_id, et.training_id, t.name, t.location, t.topic, t.training_date FROM employees_trainings et INNER JOIN trainings t ON t.id=et.training_id WHERE et.employee_id=:employees_id",$inputs)->fetchAll(PDO::FETCH_ASSOC);
      $emp_training_count=count($emp_training);

#EMPLOYEE CERTIFICATION
      $emp_cert=$con->myQuery("SELECT ec.id, ec.employee_id, (SELECT CONCAT(description,' (',NAME,')') FROM certifications WHERE id=ec.certification_id) AS certification, ec.institute, ec.date_given FROM employees_certifications ec WHERE ec.employee_id=:employees_id",$inputs)->fetchAll(PDO::FETCH_ASSOC);
      $emp_cert_count=count($emp_cert);

#EMPLOYEE LEAVES
      $emp_leave=$con->myQuery("SELECT eal.id, eal.employee_id, (SELECT NAME FROM LEAVES WHERE id=eal.leave_id) AS leave_type, eal.balance_per_year, eal.total_leave FROM employees_available_leaves eal WHERE eal.is_cancelled=0 AND eal.is_deleted=0 AND eal.employee_id=:employees_id",$inputs)->fetchAll(PDO::FETCH_ASSOC);
      $emp_leave_count=count($emp_leave);



      
  //echo $emp_history_count;
  //die();

   $head=array("Employee Code:" => htmlspecialchars($data['code']),          
              "First Name:" => htmlspecialchars($data['fname']),             
              "Middle Name:" => htmlspecialchars($data['mname']),
              "Last Name:" => htmlspecialchars($data['lname']),
              "Nationality:" => htmlspecialchars($data['nationality']),            
              "Gender:" => htmlspecialchars($data['gender']),
              "Birthday:" => htmlspecialchars($data['birthday']),
              "Civil Status:" => htmlspecialchars($data['civil_status']),
              "SSS Number:" => htmlspecialchars($data['sss_no']),
              "Tax Status:" => htmlspecialchars($data['tax_status']),
              "TIN:" => htmlspecialchars($data['tin']),                    
              "Philhealth Number:" => htmlspecialchars($data['philhealth']),
              "PAGIBIG Number:" => $data['pagibig'],
              "Employment Status:" => $data['employment_status'],
              "Job Title:" => $data['job_title'],
              "Pay Grade:" => $data['pay_grade'],              
              "Full Address:" => $data['full_address'], 
              "Contact Number:" => $data['contact_no'],
              "Work Contact Number:" => $data['work_contact_no'],
              "Email Address:" => $data['private_email'],
              "Work Email Address:" => $data['work_email'],
              "Joined Date:" => $data['joined_date'],
              "Department:" => $data['dept_name']
         
              );
  $headcount=count($head);  
//var_dump($headcount);
//die();
  }


  makeHead("Employee Details Report");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Employee Details Report
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
                    <form action="download_employee_details.php" method="post" name="export_excel">
                      <div class="control-group">
                        <div class="controls">
                          <input type='hidden' name='employees_id' value=<?php echo $inputs['employees_id']; ?>>

                          <button type="submit" id="export" name="export" class="btn btn-primary button-loading" data-loading-text="Loading...">Download to Excel File</button>

                        </div>
                      </div>
                    </form> 
                    <br>
                    <table class='table table-bordered table-striped' id='RTable'>
                      
                        <th class='text-center' colspan='5'>PERSONAL INFORMATION</th>
                        <!--    <td></td><td></td><td></td><td></td> -->      
                        <?php
                          foreach ($head as $key => $value):
                        ?>
                          <tr>
                            <th style="width: 20%"><?php echo $key; ?></th>
                            <td style="width: 20%"><?php echo $value; ?></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                          </tr>
                        <?php
                          endforeach;
                        ?>

        <!-- EMPLOYEE EDUCATION -->   
                                 
                          <?php
                            if($emp_educ_count <> 0 || $emp_educ_count <> NULL):
                          ?>
                            <tr><td colspan='5'></td></tr>
                              <th class='text-center' colspan='5'>EDUCATION BACKGROUND</th>
                        <!--    <td></td><td></td><td></td><td></td> -->  
                              <tr>
                                <th class='text-center'>Education Level</th>
                                <th class='text-center'>Institute</th>
                                <th class='text-center'>Course</th>
                                <th class='text-center'>Date Start</th>
                                <th class='text-center'>Date End</th>
                              </tr>
                                <?php
                                  foreach ($employee_education as $row):
                                ?>
                                  <tr>
                                    <td><?php echo htmlspecialchars($row['education_level']) ?></td>
                                    <td><?php echo htmlspecialchars($row['institute']) ?></td>
                                    <td><?php echo htmlspecialchars($row['course']) ?></td>
                                    <td><?php echo htmlspecialchars($row['date_start']) ?></td>
                                    <td><?php echo htmlspecialchars($row['date_end']) ?></td>
                                  </tr>
                                <?php
                                  endforeach;
                                  endif;
                                ?>
          <!-- EMPLOYMENT HISTORY -->      
                              
                          <?php
                            if($emp_history_count <> 0 || $emp_history_count <> NULL):
                          ?>
                          <tr><td colspan='5'></td></tr>
                              <th class='text-center' colspan='5'>EMPLOYMENT HISTORY</th>
                        <!--    <td></td><td></td><td></td><td></td> -->  
                              <tr>
                                <th class='text-center'>Date Start</th>
                                <th class='text-center'>Date End</th>
                                <th class='text-center'>Company</th>
                                <th class='text-center'>Department</th>
                                <th class='text-center'>Position</th>
                              </tr>
                                <?php
                                  foreach ($emp_history as $row):
                                ?>
                                  <tr>
                                    <td><?php echo htmlspecialchars($row['date_start']) ?></td>
                                    <td><?php echo htmlspecialchars($row['date_end']) ?></td>
                                    <td><?php echo htmlspecialchars($row['company']) ?></td>
                                    <td><?php echo htmlspecialchars($row['department']) ?></td>
                                    <td><?php echo htmlspecialchars($row['position']) ?></td>
                                  </tr>
                                <?php
                                  endforeach;
                              endif;
                            ?>

        <!-- EMPLOYEE TRAINING --> 
                                 
                          <?php
                            if($emp_training_count <> 0 || $emp_training_count <> NULL):
                          ?>
                          <tr><td colspan='5'></td></tr> 
                             <th class='text-center' colspan='5'>TRAININGS</th>
                        <!--    <td></td><td></td><td></td><td></td> --> 
                              <tr>
                                <th class='text-center'>Training Name</th>
                                <th class='text-center'>Location</th>
                                <th class='text-center'>Topic</th>
                                <th class='text-center'>Training Date</th>
                                <th></th>
                              </tr>
                                <?php
                                  foreach ($emp_training as $row):
                                ?>
                                  <tr>
                                    <td><?php echo htmlspecialchars($row['name']) ?></td>
                                    <td><?php echo htmlspecialchars($row['location']) ?></td>
                                    <td><?php echo htmlspecialchars($row['topic']) ?></td>
                                    <td><?php echo htmlspecialchars($row['training_date']) ?></td>
                                    <td></td>
                                  </tr>
                                <?php
                                  endforeach;
                              endif;
                            ?>
              <!-- EMPLOYEE CERTIFICATION -->   
                                 
                          <?php
                            if($emp_cert_count <> 0 || $emp_cert_count <> NULL):
                          ?>
                          <tr><td colspan='5'></td></tr>
                              <th class='text-center' colspan='5'>CERTIFICATIONS</th>
                        <!--    <td></td><td></td><td></td><td></td> -->
                              <tr>
                                <th class='text-center'>Certifications</th>
                                <th class='text-center'>Institute</th>
                                <th class='text-center'>Date Given</th>
                                <th></th><th></th>
                              </tr>
                              <?php
                                foreach ($emp_cert as $row):
                              ?>
                                <tr>
                                  <td><?php echo htmlspecialchars($row['certification']) ?></td>
                                  <td><?php echo htmlspecialchars($row['institute']) ?></td>
                                  <td><?php echo htmlspecialchars($row['date_given']) ?></td>
                                  <td></td><td></td>
                                </tr>
                              <?php
                                endforeach;
                              endif;
                            ?>  

              <!-- EMPLOYEE LEAVES -->          
                          
                          <?php
                            if($emp_leave_count <> 0 || $emp_leave_count <> NULL):
                          ?>
                          <tr><td colspan='5'></td></tr>
                              <th class='text-center' colspan='5'>LEAVES</th>
                        <!--    <td></td><td></td><td></td><td></td> -->
                              <tr>
                                <th class='text-center'>Leave Type</th>
                                <th class='text-center'>Total Leave</th>
                                <th class='text-center'>Available Leave</th>
                                <th></th><th></th>
                              </tr>
                              <?php
                                foreach ($emp_leave as $row):
                              ?>
                                <tr>
                                  <td><?php echo htmlspecialchars($row['leave_type']) ?></td>
                                  <td><?php echo htmlspecialchars($row['total_leave']) ?></td>
                                  <td><?php echo htmlspecialchars($row['balance_per_year']) ?></td>
                                  <td></td><td></td><td></td>
                                </tr>
                              <?php
                                endforeach;
                              endif;
                            ?>

                    </table>


<?php
/*  $obj = PHPExcel_IOFactory::load("template.xls");
  $obj->setActiveSheetIndex(0);  //set first sheet as active
   
  $obj->getActiveSheet()->setCellValue('C2', date('Y-m-d'));  //set C1 to current date
  $obj->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //C1 is right-justified
   
  $obj->getActiveSheet()->setCellValue('C3', stripslashes($_POST['txtName']));
  $obj->getActiveSheet()->setCellValue('C4', stripslashes($_POST['txtMessage']));
   
  $obj->getActiveSheet()->getStyle('C4')->getAlignment()->setWrapText(true);  //set wrapped for some long text message
   
  $obj->getActiveSheet()->getColumnDimension('C')->setWidth(40);  //set column C width
  $obj->getActiveSheet()->getRowDimension('4')->setRowHeight(120);  //set row 4 height
  $obj->getActiveSheet()->getStyle('A4:C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); //A4 until C4 is vertically top-aligned
   
  //prepare download
  $filename=mt_rand(1,100000).'.xls'; //just some random filename
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$filename.'"');
  header('Cache-Control: max-age=0');   */

?>


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