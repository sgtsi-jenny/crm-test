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
    id,
    code,
    employee_name,
    supervisor,
    final_approver,
    no_hours,
    worked_done,
    status,
    date_from,
    date_to,
    date_filed 
    FROM vw_employees_ot
    WHERE CASE 
    when status='Supervisor Approval' then supervisor_id 
    when status='Final Approver Approval' then final_approver_id
    end 
    =:employee_id
    ",array("employee_id"=>$_SESSION[WEBAPP]['user']['employee_id']));
  makeHead("Overtime Approval");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Overtime Approval
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
                              <th class='text-center'>OT Hours</th>
                              <th class='text-center date-time-td'>OT Start</th>
                              <th class='text-center date-time-td'>OT End</th>
                              <th class='text-center date-time-td'>Work To Do</th>
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
                                <td><?php echo htmlspecialchars($row['no_hours'])?></td>
                                <td><?php echo htmlspecialchars($row['date_from'])?></td>
                                <td><?php echo htmlspecialchars($row['date_to'])?></td>
                                <td><?php echo htmlspecialchars($row['worked_done'])?></td>
                                <td><?php echo htmlspecialchars($row['supervisor'])?></td>
                                <td><?php echo htmlspecialchars($row['final_approver'])?></td>
                                <td><?php 
                                    echo htmlspecialchars($row['status'].($row['status']==""))
                                  ?></td>
                                <td class='text-center'>
                                  <form method="post" action='move_approval.php' style='display: inline' onsubmit='return confirm("Approve This Request?")'>
                                  <input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
                                  <input type='hidden' name='type' value='overtime'>
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
    $request_type="overtime";
    $redirect_page="overtime_approval.php";
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