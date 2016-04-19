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
                                el.id,
                                (SELECT e.code FROM employees e WHERE e.id=el.employee_id) AS code,
                                el.employee_id as employee_id,
                                (SELECT CONCAT(e.first_name,' ',e.last_name) FROM employees e WHERE e.id=el.employee_id) AS employee_name,
                                el.leave_id AS leave_id,
                                IFNULL((SELECT name FROM leaves WHERE id=el.leave_id),'Leave w/o pay') as leave_type,
                                (SELECT CONCAT(e.first_name,' ',e.last_name) FROM employees e WHERE e.id=el.supervisor_id) AS supervisor,
                                (SELECT CONCAT(e.first_name,' ',e.last_name) FROM employees e WHERE e.id=el.final_approver_id) AS final_approver,
                                DATE_FORMAT(el.date_start,'%M-%d-%Y') as date_start,
                                DATE_FORMAT(el.date_end,'%M-%d-%Y') as date_end,
                                DATE_FORMAT(el.date_filed,'%M-%d-%Y') as date_filed,
                                el.reason,
                                el.status
                            FROM employees_leaves el
                            WHERE CASE 
                                when status='Supervisor Approval' then supervisor_id 
                                when status='Final Approver Approval' then final_approver_id
                                end 
                                =:employee_id",
                            array("employee_id"=>$_SESSION[WEBAPP]['user']['employee_id']));
  makeHead("Leave Approval");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Leave Approval
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
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Employee Code</th>
                              <th class='text-center'>Employee</th>
                              <th class='text-center'>Leave Type</th>
                              <th class='text-center date-time-td'>Leave Start</th>
                              <th class='text-center date-time-td'>Leave End</th>
                              <th class='text-center date-td'>Date Filed</th>
                              <th class='text-center date-time-td'>Reason</th>
                              <th class='text-center'>Status</th>
                              <th class='text-center' style='min-width:100px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['code'])?></td>
                                <td><?php echo htmlspecialchars($row['employee_name'])?></td>
                                <td><?php echo htmlspecialchars($row['leave_type'])?></td>
                                <td><?php echo htmlspecialchars($row['date_start'])?></td>
                                <td><?php echo htmlspecialchars($row['date_end'])?></td>
                                <td><?php echo htmlspecialchars($row['date_filed'])?></td>
                                <td><?php echo htmlspecialchars($row['reason'])?></td>
                                <td><?php echo htmlspecialchars($row['status'])?></td>
                                <td class='text-center'>
                               <form method='post' action='move_approval.php' style='display: inline' onsubmit='return confirm("Approve This Request?")'>

                                <input type='hidden' name='id' value=<?php echo $row['id']; ?>>
                                <input type='hidden' name='leave_id' value=<?php echo $row['leave_id']; ?>>
                                <input type='hidden' name='emp_id' value=<?php echo $row['employee_id']; ?>>
                                <input type='hidden' name='date_start' value=<?php echo $row['date_start']; ?>>
                                <input type='hidden' name='date_end' value=<?php echo $row['date_end']; ?>>
                                
                                <input type='hidden' name='type' value='leave'>
                                <button class='btn btn-sm btn-success' name='action' value='approve' title='Approve Request'><span class='fa fa-check'></span></button>
                              </form>
                                <button class='btn btn-sm btn-info' title='Query Request' onclick='query( <?php echo $row['id']; ?> )'><span  class='fa fa-question'></span></button>
                                <button class='btn btn-sm btn-danger' title='Reject Request' onclick='reject(<?php echo $row['id']; ?>)'><span class='fa fa-times'></span></button>   

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
    $redirect_page="leave_approval.php";
    require_once("include/modal_reject.php");
    require_once("include/modal_query.php");
  ?>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({"scrollX": true});
      });

</script>

<?php
  Modal();
	makeFoot();
?>