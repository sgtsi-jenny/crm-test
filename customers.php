<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

  $data=$con->myQuery("SELECT org_name,phone_num,email,rating,org_type,users,description,id FROM vw_org where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    makeHead("Customer");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Customer
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
                        <div class='col-ms-12 text-right'>
                          <a href='frm_customers.php' class='btn btn-brand'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Organization Name</th>
                                                <th class='text-center'>Phone Number</th>
                                                <th class='text-center'>Email Address</th>
                                                <th class='text-center'>Ratings</th>
                                                <th class='text-center'>Type</th>
                                                <th class='text-center'>Creator</th>
                                                <th class='text-center'>Description</th>
                              <th class='text-center' style='min-width:100px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                                                $organizations=$con->myQuery("SELECT org_name,phone_num,email,rating,org_type,users,description,id FROM vw_org where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($organizations as $row):
                                            ?>
                              <tr>
                                                        <!-- <td>
                                                        <input type="checkbox" name="select_org" value="<?php echo $organization["id"];?>" />
                                                        </td> -->
                                                        <?php
                                                            foreach ($row as $key => $value):                                                            
                                                        ?>                                                          <?php
                                                            if($key=='org_name'):
                                                        ?> 
                                                            <td>
                                                                <a href='org_details.php?id=<?= $row['id']?>'><?php echo htmlspecialchars($value)?></a>
                                                            </td>                                                        
                                                        <?php
                                                            elseif($key=='users'):
                                                        ?>
                                                            <td>
                                                                <?php echo htmlspecialchars($value)?>
                                                            </td>                                                         
                                                         
                                                        <?php
                                                            elseif($key=='id'):
                                                        ?>                                                                          
                                                            <td>
                                                                <a class='btn btn-sm btn-brand' href='frm_customers.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                                <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=org' onclick='return confirm("This customer will be deleted.")'><span class='fa fa-trash'></span></a>
                                                            </td>
                                                        <?php
                                                            else:
                                                        ?>

                                                            <td>
                                                                <?php
                                                                    echo htmlspecialchars($value);
                                                                ?>
                                                            </td>
                                                        <?php
                                                            endif;
                                                            endforeach;
                                                        ?>
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
        $('#ResultTable').DataTable({
            "scrollX": true
            // ,
            // dom: 'Bfrtip',
            //     buttons: [
            //         {
            //             extend:"excel",
            //             text:"<span class='fa fa-download'></span> Download as Excel File "
            //         }
            //         ],

        });
      });
</script>

<?php
  Modal();
    makeFoot();
?>