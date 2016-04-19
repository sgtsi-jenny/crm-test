<?php
  require_once("support/config.php");
   if(!isLoggedIn()){
    toLogin();
    die();
   }

     if(!AllowUser(array(1,2))){
         redirect("index.php");
     }

  makeHead("Overtime Request");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Overtime Request
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
                      <a href='frm_overtime_request.php' class='btn btn-success'> File New Overtime <span class='fa fa-plus'></span> </a>
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
                              <th class='text-center'>OT Hours</th>
                              <th class='text-center date-time-td'>OT Start</th>
                              <th class='text-center date-time-td'>OT End</th>
                              <th class='text-center'>Work To Do</th>
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
  $request_type="overtime";
  $redirect_page="overtime_request.php";
  require_once("include/modal_query.php");
?>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "ajax":"ajax/overtime_requests.php"
                
        });

        // $('#modal_comments').on('show.bs.modal', function (e) {
        //   $("#comment_table").load("ajax/comments.php");
        // })
      });

</script>

<?php
  Modal();
  makeFoot();
?>



