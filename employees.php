<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

  $data=$con->myQuery("SELECT 
e.id,e.code,CONCAT(e.last_name,', ',e.first_name,' ',IFNULL(e.middle_name,'')) as 'employee',e.private_email,e.contact_no, jt.description as 'job_title',d.name as 'department'
FROM employees e LEFT JOIN job_title jt ON e.job_title_id=jt.id LEFT JOIN departments d ON e.department_id=d.id WHERE e.is_deleted=0 AND e.is_terminated=0");
	makeHead("Employees");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Employees
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
                    <div class="col-sm-12">
                        <div class='col-ms-12 text-right'>
                          <a href='frm_employee.php' class='btn btn-success'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Employee Code</th>
                              <th class='text-center'>Employee</th>
                              <th class='text-center'>Job Title</th>
                              <th class='text-center'>Department</th>
                              <th class='text-center'>Email</th>
                              <th class='text-center'>Contact No</th>
                              <th class='text-center' style='min-width:150px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['code'])?></td>
                                <td><?php echo htmlspecialchars($row['employee'])?></td>
                                <td><?php echo htmlspecialchars($row['job_title'])?></td>
                                <td><?php echo htmlspecialchars($row['department'])?></td>
                                <td><?php echo htmlspecialchars($row['private_email'])?></td>
                                <td><?php echo htmlspecialchars($row['contact_no'])?></td>
                                <td class='text-center'>
                                  <a href='frm_employee.php?id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
                                  <a href='delete.php?t=e&id=<?php echo $row['id']?>' title='Delete Employee' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
                                  <a href='terminate.php?id=<?php echo $row['id']?>' title='Terminate Employee' onclick="return confirm('This employee will be terminated.')" class='btn btn-danger btn-sm'><span class='fa fa-user-times'></span></a>
                                  <a href='employee_details_report.php?employees_id=<?php echo $row['id']?>' class='btn btn-info btn-sm'><span class='fa fa-download'></span></a>
                                </td>
                              </tr>
                            <?php
                              endwhile;
                            ?>
                          </tbody>
                        </table>
                    </div><!-- /.col -->
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
            "scrollX": true,
            dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File "
                    }
                    ],

        });
      });
</script>

<?php
  Modal();
	makeFoot();
?>