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
  makeHead("Change Shift Requests");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Change Shift Requests
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
                      <a href='frm_shift_request.php' class='btn btn-success'> File Change Shift Request <span class='fa fa-plus'></span> </a>
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
  $redirect_page="shift_request.php";
  require_once("include/modal_query.php");
?>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "ajax":"ajax/shift_request.php"
                
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