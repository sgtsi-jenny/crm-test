<?php
  require_once("support/config.php");
   if(!isLoggedIn()){
    toLogin();
    die();
   }

     if(!AllowUser(array(1,2))){
         redirect("index.php");
     }

  // $data=$con->myQuery("SELECT 
  //   id,
  //   code,
  //   employee_name,
  //   supervisor,
  //   final_approver,
  //   no_hours,
  //   worked_done,
  //   status,
  //   date_from,
  //   date_to 
  //   FROM vw_employees_ot
  //   WHERE employee_id=:employee_id AND 'x'='y'
  //   ",array("employee_id"=>$_SESSION[WEBAPP]['user']['employee_id']));
  makeHead("Adjustment Request");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Adjustment Request
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
                    <div class='col-md-12 text-right'>
                      <a href='frm_adjustment_request.php' class='btn btn-success'> File New Adjustment <span class='fa fa-plus'></span> </a>
                    </div>
                    <br/>
                    <br/>
                    <div class="col-sm-12">
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Employee Code</th>
                              <th class='text-center'>Employee</th>
                              <th class='text-center date-td'>Date Filed</th>
                              <th class='text-center date-time-td'>Time In</th>
                              <th class='text-center date-time-td'>Time Out</th>
                              <th class='text-center date-time-td'>Adjusted Time In</th>
                              <th class='text-center date-time-td'>Adjusted Time Out</th>
                              <th class='text-center'>Reason</th>
                              <th class='text-center'>Supervisor</th>
                              <th class='text-center'>Final Approver</th>
                              <th class='text-center'>Status</th>
                              <th class='text-center' style='min-width:100px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              //while($row = $data->fetch(PDO::FETCH_ASSOC)):
                              while('x'<>'x'):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['code'])?></td>
                                <td><?php echo htmlspecialchars($row['employee_name'])?></td>
                                <td><?php echo htmlspecialchars($row['no_hours'])?></td>
                                <td><?php echo htmlspecialchars($row['date_from'])?></td>
                                <td><?php echo htmlspecialchars($row['date_to'])?></td>
                                <td><?php echo htmlspecialchars($row['worked_done'])?></td>
                                <td><?php echo htmlspecialchars($row['supervisor'])?></td>
                                <td><?php echo htmlspecialchars($row['final_approver'])?></td>
                                <td><?php 
                                    echo htmlspecialchars($row['status'])
                                  ?></td>
                                <td class='text-center'>
                                  <?php
                                    if($row['status']=="Query (Final Approver)" || $row['status']=="Query (Supervisor)"):
                                  ?>
                                  <button class='btn btn-sm btn-info'  title='Query Request' onclick='query("<?php echo $row['id'] ?>")'><span  class='fa fa-question'></span></button>
                                  <?php
                                    endif;
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
  $request_type="adjustment";
  $redirect_page="adjustment_request.php";
  require_once("include/modal_query.php");
?>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "ajax":"ajax/adjustment_request.php"
                
        });

      });

</script>

<?php
  Modal();
  makeFoot();
?>