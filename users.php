<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
     toLogin();
     die();
    }

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

  $data=$con->myQuery("SELECT u.id as id, e.code as employee_no, CONCAT(e.first_name,' ',e.last_name) AS full_name, u.username as username, e.work_email as email, e.contact_no as contact_no,password FROM users u INNER JOIN employees e ON e.id=u.employee_id WHERE u.is_deleted=0");
    makeHead("Users");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Users
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
                          <a href='frm_users.php' class='btn btn-success'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Employee Number</th>
                              <th class='text-center'>Employee Name</th>
                              <th class='text-center'>User Name</th>
                              <th class='text-center'>Email</th>
                              <th class='text-center'>Contact No.</th>
                              <th class='text-center'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['employee_no'])?></td>
                                <td><?php echo htmlspecialchars($row['full_name'])?></td>
                                <td><?php echo htmlspecialchars($row['username'])?></td>
                                <td><?php echo htmlspecialchars($row['email'])?></td>
                                <td><?php echo htmlspecialchars($row['contact_no']); //['contact_no'])?></td>
                                <td class='text-center'>
                                  <a href='frm_users.php?id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
                                  <a href='delete.php?t=u&id=<?php echo $row['id']?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
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
        $('#ResultTable').DataTable();
      });
</script>

<?php
  Modal();
    makeFoot();
?>