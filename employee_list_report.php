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
    
    $query="SELECT 
              e.id,
              e.code,
              CONCAT(e.last_name,', ',e.first_name,' ',e.middle_name) as employee_name,
              (SELECT description FROM job_title WHERE id=e.job_title_id) as job_title,
              (SELECT description FROM departments WHERE id=e.department_id) as department,
              CONCAT(e.private_email,' ',e.work_email) as email,
              CONCAT(e.contact_no,' ',e.work_contact_no) as contact

            FROM employees e ORDER BY e.id";

    $data=$con->myQuery($query,$inputs)->fetchAll(PDO::FETCH_ASSOC);  

  makeHead("Employee List Report");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Employee List Report
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-12'>
              <div class="box box-solid">
                <div class="box-body">
                  <div class="row">
                  <div class='col-md-12'>
                    <table class='table table-bordered table-striped' id='ResultTable'>
                      <thead>
                        <th class='text-center'>Employee Code</th>
                        <th class='text-center'>Employee Name</th>
                        <th class='text-center'>Job Title</th>
                        <th class='text-center'>Department</th>
                        <th class='text-center'>Email</th>
                        <th class='text-center'>Contact No</th>
                      </thead>
                      <tbody>
                        <?php
                          foreach ($data as $row):
                        ?>
                          <tr>
                            <td><?php echo htmlspecialchars($row['code']) ?></td>
                            <td><?php echo htmlspecialchars($row['employee_name']) ?></td>
                            <td><?php echo htmlspecialchars($row['job_title']) ?></td>
                            <td><?php echo htmlspecialchars($row['department']) ?></td>
                            <td><?php echo htmlspecialchars($row['email']) ?></td>
                            <td><?php echo htmlspecialchars($row['contact']) ?></td>
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
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>
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
  makeFoot();
?>