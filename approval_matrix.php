<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
     toLogin();
     die();
    }

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

  $data=$con->myQuery("SELECT d.name, d.description, d.approver_id, (SELECT CONCAT(first_name,' ',last_name) FROM employees WHERE id=d.approver_id) AS final_approver FROM departments d WHERE is_deleted=0");
    makeHead("Approval Matrix");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Approval Matrix
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
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Code</th>
                              <th class='text-center'>Department Name</th>
                              <th class='text-center'>First Approver</th>
                              <th class='text-center'>Final Approver</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['name'])?></td>
                                <td><?php echo htmlspecialchars($row['description'])?></td>
                                <td>Employee's Immediate Supervisor</td>
                                <td><?php echo htmlspecialchars($row['final_approver'])?></td>
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