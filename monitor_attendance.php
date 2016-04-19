<?php
  require_once("support/config.php");
   if(!isLoggedIn()){
    toLogin();
    die();
   }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

  //$has_record=$con->myQuery("SELECT COUNT(id) FROM attendance WHERE employees_id=? AND out_time='0000-00-00 00:00:00' LIMIT 1",array($_SESSION[WEBAPP]['user']['employee_id']))->fetchColumn();
  makeHead("Attendance");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Attendance
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
                      <a href='frm_attendance.php' class='btn btn-success'>Add New <span class='fa fa-plus'></span></a>
                    </div>
                    <br/>
                    <br/>
                    <div class="col-sm-12">
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Employee Code</th>
                              <th class='text-center'>Employee Name</th>
                              <th class='text-center'>Time In</th>
                              <th class='text-center'>Time Out</th>
                              <th class='text-center' style='max-width: 60px'>Action</th>
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
    

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
                
                "processing": true,
                "serverSide": true,
                "ajax":"ajax/monitor_attendance.php",
                "aoColumnDefs": [
                  { "sClass": "text-center", "aTargets": [ -1 ] }
                ],
                "order": [[ 2, "desc" ]]
                
        });

        // $('#modal_comments').on('show.bs.modal', function (e) {
        //   $("#comment_table").load("ajax/comments.php");
        // })
      });

  function adjustment(id){
            $('#modal_adjustment').modal('show');
        }

</script>

<?php
  Modal();
  makeFoot();
?>