<?php
  require_once("support/config.php");
   if(!isLoggedIn()){
    toLogin();
    die();
   }

     if(!AllowUser(array(1,2))){
         redirect("index.php");
     }
// select tbl_a.`id` AS `id`,`e`.`id` AS `employee_id`,`e`.`code` AS `code`,concat(`e`.`last_name`,', ',`e`.`first_name`,' ',`e`.`middle_name`) AS `employee_name`,tbl_a.`supervisor_id` AS `supervisor_id`,(select concat(`e`.`last_name`,', ',`e`.`first_name`,' ',`e`.`middle_name`) from `hrisv2db`.`employees` where (`hrisv2db`.`employees`.`id` = tbl_a.`supervisor_id`)) AS `supervisor`,tbl_a.`supervisor_date_action` AS `supervisor_date_action`,(select concat(`e`.`last_name`,', ',`e`.`first_name`,' ',`e`.`middle_name`) from `hrisv2db`.`employees` where (`hrisv2db`.`employees`.`id` = `d`.`approver_id`)) AS `final_approver`,`d`.`approver_id` AS `final_approver_id`,tbl_a.`final_approver_date_action` AS `final_approver_date_action`,tbl_a.`status` AS `status` from ((`hrisv2db`.`employees_adjustments` tbl_a join `hrisv2db`.`employees` `e` on((tbl_a.`employees_id` = `e`.`id`))) join `hrisv2db`.`departments` `d` on((`e`.`department_id` = `d`.`id`)))
  $data=$con->myQuery("SELECT 
    id,
    code,
    employee_name,
    supervisor,
    final_approver,
    status,
    date_from,
    date_to,
    orig_in_time,
    orig_out_time,
    adj_in_time,
    adj_out_time,
    shift_reason,
    date_filed  

    FROM vw_employees_change_shift
    WHERE CASE 
    when status='Supervisor Approval' then supervisor_id 
    when status='Final Approver Approval' then final_approver_id
    end 
    =:employee_id
    ",array("employee_id"=>$_SESSION[WEBAPP]['user']['employee_id']));
  makeHead("Change Shift Approval");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Change Shift Approval
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
                              <th class='text-center date-td'>Date Filed</th>
                              <th class='text-center date-td'>Start Date</th>
                              <th class='text-center date-td'>End Date</th>
                              <th class='text-center date-td'>Original Time In</th>
                              <th class='text-center date-td'>Original Time Out</th>
                              <th class='text-center date-td'>Adjusted Time In</th>
                              <th class='text-center date-td'>Adjusted Time Out</th>
                              <th class='text-center'>Reason</th>
                              <th class='text-center'>Supervisor</th>
                              <th class='text-center'>Final Approver</th>
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
                                <td><?php echo htmlspecialchars($row['date_filed'])?></td>
                                <td><?php echo htmlspecialchars($row['date_from'])?></td>
                                <td><?php echo htmlspecialchars($row['date_to'])?></td>
                                <td><?php echo htmlspecialchars($row['orig_in_time'])?></td>
                                <td><?php echo htmlspecialchars($row['orig_out_time'])?></td>
                                <td><?php echo htmlspecialchars($row['adj_in_time'])?></td>
                                <td><?php echo htmlspecialchars($row['adj_out_time'])?></td>
                                <td><?php echo htmlspecialchars($row['shift_reason'])?></td>
                                <td><?php echo htmlspecialchars($row['supervisor'])?></td>
                                <td><?php echo htmlspecialchars($row['final_approver'])?></td>
                                <td><?php 
                                    echo htmlspecialchars($row['status'].($row['status']==""))
                                  ?></td>
                                <td class='text-center'>
                                  <form method="post" action='move_approval.php' style='display: inline' onsubmit='return confirm("Approve This Request?")'>
                                  <input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
                                  <input type='hidden' name='type' value='shift'>
                                  <button class='btn btn-sm btn-success' name='action' value='approve' title='Approve Request'><span class='fa fa-check'></span></button>
                                  </form>
                                  <button class='btn btn-sm btn-info'  title='Query Request' onclick='query("<?php echo $row['id'] ?>")'><span  class='fa fa-question'></span></button>
                                  <button class='btn btn-sm btn-danger'  title='Reject Request' onclick='reject("<?php echo $row['id'] ?>")'><span class='fa fa-times'></span></button>
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
    $request_type="shift";
    $redirect_page="shift_approval.php";
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