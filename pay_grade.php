<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
     toLogin();
     die();
    }

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

  $data=$con->myQuery("SELECT id,level FROM pay_grade WHERE is_deleted=0");
    makeHead("Pay Grade");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Pay Grade
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
                        <div class='col-ms-9 text-right'>
                          <a href='frm_pay_grade.php' class='btn btn-success'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Level</th>
                              <th class='text-center'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['level'])?></td>
                                <td class='text-center'>
                                 
                                  <a href='frm_pay_grade.php?id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
                                  <a href='delete.php?t=pg&id=<?php echo $row['id']?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
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

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
</script>

<?php
  Modal();
    makeFoot();
?>