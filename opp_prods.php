<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $tab="4";
    if(!empty($_GET['tab']) && !is_numeric($_GET['tab'])){
        redirect("org_opp.php".(!empty($employee)?'?id='.$employee['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("customers.php".(!empty($employee)?'?id='.$employee['id']:''));
            }
        }
    }
    if(empty($_GET['id'])){
        //Modal("No Account Selected");
        redirect("customers.php");
        die();
    }
    else{
        $opp=$con->myQuery("SELECT id,opp_name,org_id,org_name,cname,opp_type,users,sales_stage,forecast_amount,amount,tprice,description,product_set,date_created,date_modified FROM vw_opp WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($opp)){
            Modal("Invalid Opportunities Selected");
            redirect("opportunities.php");
            die;
        }
    }

    
    $data="";
    if(!empty($_GET['prod_id'])){
        $data=$con->myQuery("SELECT id,prod_name,prod_id,prod_based_price,prod_price,commission_rate,expected_close_date FROM opp_products WHERE is_deleted=0 AND id=? LIMIT 1",array($_GET['prod_id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            Modal("Invalid product selected");
            redirect("opp_prods.php");
            die;
        }
    }

    $products=$con->myQuery("SELECT id,product_name,unit_price FROM products WHERE is_deleted=0",array($opp['id']))->fetchAll(PDO::FETCH_ASSOC);
    $user=$con->myQuery("SELECT id,CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);
    

    makeHead("Opportunities");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
                                      <h1>
                                      <img src="uploads/summary_Oppurtunities.png" width="50" height="50" title="Organization" alt="Organization" />
                                      <?php echo htmlspecialchars($opp['opp_name']) ?>
                                      </h1>
        </section>
                               
         <section class="content-header">
        <br/>
          <a href='opportunities.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Opportunity List</a>
          <a href='org_opp.php?id=<?php echo $opp['org_id'] ?>' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back to My Opportunity</a>
          </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <?php
                        $no_employee_msg=' Personal Information must be saved.';
                    ?> -->
                    <li ><a href="opp_details.php?id=<?php echo $_GET['id'] ?>" >Opportunity Details</a>
                    </li>
                    <li> <a href="opp_events.php?id=<?php echo $_GET['id'] ?>">Activities</a>
                    </li>
                    <li> <a href="opp_contact_persons.php?id=<?php echo $_GET['id'] ?>">Contact Persons</a>
                    </li>
                    <li <?php echo $tab=="4"?'class="active"':''?>> <a href="">Products</a>
                    </li>
                    <li> <a href="opp_quotes.php?id=<?php echo $_GET['id'] ?>">Quotations</a>
                    </li>
                    <li> <a href="opp_purchases.php?id=<?php echo $_GET['id'] ?>">Purchase Orders</a>
                    </li>
                    <li> <a href="opp_invoices.php?id=<?php echo $_GET['id'] ?>">Invoices</a>
                    </li>
                    <li> <a href="opp_others.php?id=<?php echo $_GET['id'] ?>">Others</a>
                    </li>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                         <div class='panel-body'>
                                    <div class='col-md-12 text-right'>
                                        <div class='col-md-12 text-right'>
                                        <button class='btn btn-brand' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Add New Product <span class='fa fa-plus'></span> </button>
                                        </div>                                
                                    </div> 
                                </div>
                                <?php
                                Alert();
                                ?>

                <div id='collapseForm' class='collapse'>
                              <form class='form-horizontal' action='save_opp_prod.php' onsubmit="return validatePost(this)" method="POST" >
                                 <input type='hidden' name='opp_prod' value='<?php echo !empty($data)?$data['id']:""?>'>
                                 <input type='hidden' name='opp_id' value='<?php echo $opp['id']?>'>
                                      
                                      <div class='form-group'>
                                        <label for="" class="col-md-4 control-label">Product Name *</label>
                                        <div class="col-sm-6">
                                            <select class='form-control' id='prod_id' onchange='get_price()' name='prod_id' data-placeholder="Select a product" <?php echo!(empty($data))?"data-selected='".$data['prod_id']."'":NULL ?>style='width:100%' required>
                                            
                                                <?php
                                                    foreach ($products as $key => $row):
                                                ?>
                                                    <option data-price='<?php echo $row['unit_price'] ?>' placeholder="Select product" value='<?php echo $row['id']?>'><?php echo $row['product_name']?></option>
                                                    
                                                <?php
                                                    endforeach;
                                                ?>
                                                <input type='hidden' id='prod_name2' name='prod_name' value=''>
                                            </select>
                                        </div>  
                                      </div>
                                      <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Base Price</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name='prod_based_price' id="prod_based_price"  type="text" required>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label for="" class="col-md-4 control-label">Product Price *</label>
                                        <div class="col-sm-6">
                                            <input type='text' name='prod_price' id='prod_price' class='form-control' placeholder='0' value='<?php echo !empty($data)?$data['prod_price']:"" ?>'  required>
                                        </div>            
                                      </div>

                                      <div class="form-group">
                                        <label for="" class="col-md-4 control-label">Commission Rate</label>
                                        <div class="col-sm-6">
                                            <input type='text' name='commission_rate' id='commission_rate' class='form-control' placeholder='0' value='<?php echo !empty($data)?$data['commission_rate']:"" ?>'>
                                        </div>            
                                      </div>

                                    <div class='form-group'>
                                      <label class="col-md-4 control-label"> Expected Close Date*</label>
                                      <div class='col-sm-12 col-md-6'>
                                          <?php
                                          $expected_close_date="";
                                           if(!empty($opportunity)){
                                              $expected_close_date=$opportunity['expected_close_date'];
                                              if($expected_close_date=="0000-00-00"){
                                                  $expected_close_date="";
                                              }
                                           }
                                          ?>
                                          <?php
                                          $purchase_date="";
                                           if(!empty($asset)){
                                              $purchase_date=$asset['purchase_date'];
                                              if($purchase_date=="0000-00-00"){
                                                  $purchase_date="";
                                              }
                                           }
                                          ?>
                                          <input type='date' class='form-control' name='expected_close_date' value='<?php echo !empty($data)?$data['expected_close_date']:"" ?>' required>
                                      </div>
                                      </div>

                                    <!-- <div class='form-group'>
                                      <label class="col-md-4 control-label"> Users*</label>
                                      <div class="col-sm-6">
                                              <select id="disabledSelect" class='form-control' name='assigned_to' data-placeholder="Select a user" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['assigned_to']."'":"data-selected='".$_SESSION[WEBAPP]['user']['id']."'" ?> required>
                                                  <?php
                                                      echo makeOptions($user);
                                                  ?>
                                              </select>
                                      </div>
                                    </div> -->

                                  <div class="form-group">
                                    <div class="col-sm-10 col-md-offset-2 text-center">
                                      <button type='submit' class='btn btn-brand'>Save </button>
                                      <a href='opp_prods.php?id=<?php echo $_GET['id'] ?>' class='btn btn-default'>Cancel</a>
                                    </div>
                                  </div>
                              </form>
                            </div>
                            <br/>

                  <h2>List of Products</h2>
                   <br>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Product Name</th>
                                                <th class='text-center'>Based Price</th>
                                                <th class='text-center'>Unit Price</th>
                                                <th class='text-center'>Commission Rate</th>
                                                <th class='text-center' style='min-width:40px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                $opp2=$con->myQuery("SELECT prod_name,prod_based_price,prod_price,commission_rate,id FROM opp_products WHERE is_deleted=0 and opp_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($opp2 as $row):
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['prod_name']) ?></td>                                                    
                                                    <td class='text-right'><?php echo htmlspecialchars(number_format($row['prod_based_price'],2)) ?></td>
                                                    <td class='text-right'><?php echo htmlspecialchars(number_format($row['prod_price'],2)) ?></td>
                                                    <td class='text-right'><?php echo htmlspecialchars(number_format($row['commission_rate'],2)) ?></td>
                                                    <td>
                                                        <a href='opp_products.php?id=<?php echo $opp['id']?>&prod_id=<?php echo $row['id']?>' class='btn btn-sm btn-brand'><span class='fa fa-pencil'></span></a>
                                                        <!--<a href='' class='btn btn-sm btn-danger'><span class='fa fa-trash'></span></a>-->
                                                        <a class='btn btn-sm btn-danger' href='delete_prod.php?id=<?php echo $row['id']?>&t=oprod&opp_id=<?php echo $opp['opp_id']?>' onclick='return confirm("This product will be deleted.")'><span class='fa fa-trash'></span></a>
                                                    </td>
                                                </tr>
                                            <?php
                                                endforeach;
                                            ?>
                                        </tbody>
                        </table>


                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
               // dom: 'Bfrtip',
               //      buttons: [
               //          {
               //              extend:"excel",
               //              text:"<span class='fa fa-download'></span> Download as Excel File "
               //          }
               //          ]
        });
      });
</script>
<script type="text/javascript">
    function validatePost(post_form){
        console.log();
        var str_error="";
        $.each($(post_form).serializeArray(),function(index,field){
            console.log(field);
            if(field.value==""){
            
                switch(field.name){
                    case "prod_name":
                        str_error+="Please select product name. \n";
                        break;
                    case "prod_price":
                        str_error+="Please provide product price. \n";
                        break;
                }
                
            }

        });
        if(str_error!=""){
            alert("You have the following errors: \n" + str_error );
            return false;
        }
        else{
            return true
        }
    }

    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true
        });
    });

    function get_price(){
        
        $("#prod_based_price").val($("#prod_id option:selected").data("price"));
        
        $("#prod_name2").val($("#prod_id option:selected").html());
    }
    
</script>
<?php 
  if(!empty($data)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm').collapse({
      toggle: true
    })    
  });
</script>

<?php
  endif;
?>
<?php
    Modal();
    makeFoot();
?>