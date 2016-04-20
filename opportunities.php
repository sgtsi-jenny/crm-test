<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

  $data=$con->myQuery("SELECT opp_name,org_name,sales_stage,tprice,users,cname,id FROM vw_opp where utype=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
    makeHead("Opportunities");

?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Opportunities
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
                        <!-- <div class='col-ms-12 text-right'>
                          <a href='frm_customers.php' class='btn btn-success'> Create New <span class='fa fa-plus'></span> </a>
                        </div> -->
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Opportunity Name</th>
                                                <th class='text-center'>Organization Name</th>
                                                <th class='text-center'>Sales Stage</th>
                                                <th class='text-center'>Amount</th>
                                                <th class='text-center'>Creator</th>
                                                <th class='text-center'>Contact Name</th>
                              <th class='text-center' style='min-width:100px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php
                                            $opportunities=$con->myQuery("SELECT opp_name,org_name,sales_stage,tprice,users,cname,id FROM vw_opp where utype=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($opportunities as $row):
                                            
                                        ?>

                                                <tr>
                                                        
                                                        <?php
                                                            foreach ($row as $key => $value):                                                            
                                                        ?> 
                                                        <?php
                                                            if($key=='opp_name'):
                                                        ?> 
                                                            <td>
                                                                <a href='opp_details.php?id=<?= $row['id']?>'><?php echo htmlspecialchars($value)?></a>
                                                            </td> 
                                                        <?php
                                                            elseif($key=='tprice'):
                                                        ?>  
                                                            <td>
                                                                <?php echo htmlspecialchars(number_format($row['tprice'],2))?></a>
                                                            </td>                                                         
                                                        <?php
                                                            elseif($key=='id'):
                                                        ?>                                                                          
                                                            <td>
                                                                <a class='btn btn-sm btn-brand' href='frm_opportunities.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                                <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=opp' onclick='return confirm("This opportunity will be deleted.")'><span class='fa fa-trash'></span></a>
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
            "scrollX": true,
            dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File "
                    }
                    ],

        });
      });
</script>

<?php
  Modal();
    makeFoot();
?>