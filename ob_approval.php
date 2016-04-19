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
    status,
    date_from,
    date_to,
    destination,
    purpose,
    date_filed
    FROM vw_employees_ob
    WHERE CASE 
    when status='Supervisor Approval' then supervisor_id 
    when status='Final Approver Approval' then final_approver_id
    end 
    =:employee_id
    ",array("employee_id"=>$_SESSION[WEBAPP]['user']['employee_id']));

  makeHead("Official Business Approval");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Official Business Approval
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
                              <th class='text-center date-time-td'>From</th>
                              <th class='text-center date-time-td'>To</th>
                              <th class='text-center'>Destination</th>
                              <th class='text-center'>Purpose</th>
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
                                <td><?php echo htmlspecialchars($row['destination'])?></td>
                                <td><?php echo htmlspecialchars($row['purpose'])?></td>
                                <td><?php echo htmlspecialchars($row['supervisor'])?></td>
                                <td><?php echo htmlspecialchars($row['final_approver'])?></td>
                                <td><?php 
                                    echo htmlspecialchars($row['status'].($row['status']==""))
                                  ?></td>
                                <td class='text-center'>
                                  <form method="post" action='move_approval.php' style='display: inline' onsubmit='return confirm("Approve This Request?")'>
                                  <input type='hidden' name='id' value='<?php echo $row['id']; ?>'>
                                  <input type='hidden' name='type' value='official_business'>
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
    $request_type="official_business";
    $redirect_page="ob_approval.php";
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