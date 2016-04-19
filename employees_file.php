<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

	if(!AllowUser(array(1))){
	     redirect("index.php");
	}

    $data=$con->myQuery("SELECT ef.id,file_name,date_modified,CONCAT(e.last_name,', ',e.first_name,' ',e.middle_name)as full_name FROM employees_files ef JOIN employees e ON ef.employee_id=e.id WHERE ef.is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("Employee Files");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Employee Files
          </h1>
          <br/>
        </section>

        <!-- Main content -->
        <section class="content">

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
                          <th class='text-center'>Employee</th>
                          <th class='text-center'>File</th>
                          <th class='text-center'>Date Uploaded</th>
                          <th class='text-center'>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          foreach($data as $row):
                        ?>
                          <tr>
                            <td class='text-center'><?php echo htmlspecialchars($row['full_name'])?></td>
                            <td class='text-center'><?php echo htmlspecialchars($row['file_name'])?></td>
                            <td class='text-center'><?php echo htmlspecialchars($row['date_modified'])?></td>
                            <td class='text-center'>
                              <a href='download_file.php?id=<?php echo $row['id']?>&type=e' class='btn btn-default'><span class='fa fa-download'></span></a>
                            </td>
                          </tr>
                        <?php
                          endforeach;
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
        $('#ResultTable').DataTable(<?php if(AllowUser(array(1))):?>{
               dom: 'Bfrtip',
                    buttons: [
                        {
                            extend:"excel",
                            text:"<span class='fa fa-download'></span> Download as Excel File "
                        }
                        ]
        }<?php endif;?>);
      });
</script>

<?php
    Modal();
	makeFoot();
?>