<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

	if(!AllowUser(array(1,2))){
	     redirect("index.php");
	}

    $data=$con->myQuery("SELECT id,file_name,date_modified FROM company_files WHERE is_deleted=0 ")->fetchAll(PDO::FETCH_ASSOC);
	

	makeHead("Company Files");
?>
<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Company Files
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
                    <?php if(AllowUser(array(1))): ?>
                       <form class='form-horizontal' action='save_comp_file.php' method="POST" enctype="multipart/form-data">
                          <div class="form-group">
                            <label for="certification_id" class="col-md-1 col-md-offset-8 control-label">File *</label>
                            <div class="col-md-1">
                              <input type='file' name='file' class="filestyle" data-classButton="btn btn-primary" data-input="false" data-classIcon="icon-plus" data-buttonText=" &nbsp;Select File">
                            </div>
                            <div class="col-md-2 text-center">
                              <button type='submit' class='btn btn-success'>Upload </button>
                            </div>
                          </div>
                      </form>

                    <br/>
                    <?php endif; ?>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                      <thead>
                        <tr>
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
                            <td class='text-center'><?php echo htmlspecialchars($row['file_name'])?></td>
                            <td class='text-center'><?php echo htmlspecialchars($row['date_modified'])?></td>
                            <td class='text-center'>
                              <a href='download_file.php?id=<?php echo $row['id']?>&type=c' class='btn btn-default'><span class='fa fa-download'></span></a>
                              <?php
                                if(AllowUser(array(1))):
                              ?>
                              <a href='delete.php?t=cf&id=<?php echo $row['id']?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
                              <?php
                                endif;
                              ?>
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