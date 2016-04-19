<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
     toLogin();
     die();
    }

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

  $data=$con->myQuery("SELECT
                      el.id as id,
                      (SELECT CODE FROM employees WHERE id=el.employee_id) AS 'employee_no',
                      (SELECT CONCAT(first_name,' ',last_name) FROM employees WHERE id=el.employee_id) AS 'name',
                      (SELECT NAME FROM LEAVES WHERE id=el.leave_id) AS 'leave_type',
                      (SELECT CONCAT(first_name,' ',last_name) FROM employees WHERE id=el.supervisor_id) AS 'supervisor',
                      (SELECT CONCAT(first_name,' ',last_name) FROM employees WHERE id=el.final_approver_id) AS 'final_approver',
                      DATE_FORMAT(el.date_filed,'%M-%d-%Y') AS 'date_filed',
                      el.supervisor_date_action AS 'supervisor_date_action',
                      el.approver_date_action AS 'approver_date_action',
                      el.reason AS 'reason',
                      el.status AS 'status'
                    FROM employees_leaves el
                    WHERE el.employee_id=?",array($_SESSION[WEBAPP]['user']['employee_id']));
    makeHead("Leave Filed");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Leave Filed
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
                          <a href='frm_leave_request.php' class='btn btn-success'> File New Leave <span class='fa fa-plus'></span> </a>
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Employee Number</th>
                              <th class='text-center'>Name</th>
                              <th class='text-center'>Type of Leave</th>
                              <th class='text-center date-td'>Date Start</th>
                              <th class='text-center date-td'>Date End</th>
                              <th class='text-center date-td'>Date Filed</th>
                              <th class='text-center'>Reason</th>
                              <th class='text-center'>Status</th>
                              <th class='text-center'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            //  while($row = $data->fetch(PDO::FETCH_ASSOC)):
                              while ('x'<>'x'):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['employee_no'])?></td>
                                <td><?php echo htmlspecialchars($row['name'])?></td>
                                <td><?php 
                                    echo htmlspecialchars($row['leave_type']);
                                    

                                  ?></td>
                                <td><?php echo htmlspecialchars($row['date_filed'])?></td>
                                <td><?php echo htmlspecialchars($row['reason'])?></td>
                                <td><?php echo htmlspecialchars($row['status'])?></td>
                                <td class='text-center'>

                                <?php
                                    echo $row['status'];
                                    die();

                                    if (!empty($row['status'])) {
                                      if ($row['status']=='Query (Supervisor)' || $row['status']=='Query (Final Approver)') 
                                      {
                                        echo "<button class='btn btn-sm btn-info' title='Return to Supervisor' onclick='query(".$row['id'].")'><span  class='fa fa-question'></span></button>";
                                      }
                                    }
                                ?>

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

<?php
    $request_type="leave";
    $redirect_page="employee_leave_request.php";
    require_once("include/modal_query.php");
?>


<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          "ajax":"ajax/leave_requests.php"
        });
      });

   // function query(id){
   // $('#modal_query').modal('show');
   // $('#query_id').val(id);
  //};
</script>

<?php
  Modal();
    makeFoot();
?>