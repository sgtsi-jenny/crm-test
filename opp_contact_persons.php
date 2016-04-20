<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $tab="3";
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

    
    $data=$con->myQuery("SELECT oc.id,CONCAT(c.fname,' ', c.lname) AS name,c.office_phone,c.email,c.description FROM opp_contacts oc JOIN contacts c ON oc.c_id=c.id WHERE oc.is_deleted=0  AND oc.opp_id=?",array($opp['id']))->fetchAll(PDO::FETCH_ASSOC);
          if(!empty($_GET['c_id'])){
    $record=$con->myQuery("SELECT id,c_id FROM opp_contacts oc WHERE opp_id=? AND id=? LIMIT 1",array($opp['opp_id'],$_GET['c_id']))->fetch(PDO::FETCH_ASSOC);
  }
    $contacts=$con->myQuery("SELECT id,concat(fname,' ', lname) as name,office_phone,home_phone,mobile_phone,email,dob,assigned_to,description FROM contacts WHERE id NOT IN (SELECT c_id FROM opp_contacts WHERE opp_id=? and is_deleted=0)AND is_deleted=0",array($opp['id']))->fetchAll(PDO::FETCH_ASSOC);
    

    makeHead("Opportunities");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       <!--  <section class="content-header">
          <h1>
            Employee Form
          </h1>
          <br/>
          <a href='employees.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Employee list</a>
        </section> -->

        <!-- Main content -->
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
                    <li <?php echo $tab=="3"?'class="active"':''?>> <a href="">Contact Persons</a>
                    </li>
                    <li> <a href="opp_prods.php?id=<?php echo $_GET['id'] ?>">Products</a>
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
                                        <button class='btn btn-brand' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Add New Contact <span class='fa fa-plus'></span> </button>
                                        </div>                                
                                    </div> 
                                </div>
                                <?php
                                Alert();
                                ?>

                <div id='collapseForm' class='collapse'>
                  <form class='form-horizontal' action='save_opp_contact.php' method="POST" >
                    <input type='hidden' name='opp_con' value='<?php echo !empty($opp)?$opp['id']:""?>'>
                    <input type='hidden' name='opp_id' value='<?php echo $opp['id']?>'>
                                    <div class='form-group'>
                                        <label for="" class="col-md-4 control-label">Contact Name * </label>
                                        <div class="col-sm-5">
                                            <!--<select class='form-control' id='c_id' onchange='get_con()' name='c_id' data-placeholder="Select a contact" <?php echo!(empty($data))?"data-selected='".$data['name']."'":NULL ?>>
                                            <?php
                                                foreach ($contacts as $key => $row):
                                                    ?>
                                                    <option data-phone='' placeholder="Select contact" value='<?php echo $row['id']?>'><?php echo $row['name']?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                                <input type='hidden' id='name' name='name' value=''>
                                            -->
                                            <select name='c_id' class='form-control select2' data-placeholder="Select Contact Name" <?php echo !(empty($record))?"data-selected='".$record['c_id']."'":NULL ?> style='width:100%' required>
                                                <?php
                                                  echo makeOptions($contacts);
                                                ?>
                                            </select>
                                        </div>  
                                      </div>
                          <div class="form-group">
                            <div class="col-sm-10 col-md-offset-2 text-center">
                              <button type='submit' class='btn btn-success brand-bg'>Add </button>
                            <a href='opp_contact_persons.php?id=<?php echo $opp['id'] ?>' class='btn btn-default'>Cancel</a>
                              </div>
                          </div>    
                  </form>
                </div>                            
                  <h2>List of Contact Persons</h2>
                   <br>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Contact Name</th>
                                                <th class='text-center'>Office Number</th>
                                                <th class='text-center'>Email</th>
                                                <th class='text-center'>Description</th>
                                                <th class='text-center' style='min-width:40px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                              foreach($data as $row):
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['name']) ?></td>
                                                    <td><?php echo htmlspecialchars($row['office_phone']) ?></td>
                                                    <td><?php echo htmlspecialchars($row['email']) ?></td>
                                                    <td><?php echo htmlspecialchars($row['description']) ?></td>
                                                    <td align="center">
                                                        <!--<a href='' class='btn btn-sm btn-success'><span class='fa fa-pencil'></span></a>
                                                        -->
                                                        <a href='delete.php?id=<?php echo $row['id']?>&t=ocon&opp_id=<?php echo $opp['id']?>' class='btn btn-sm btn-danger' onclick='return confirm("This contact will be deleted.")'><span class='fa fa-trash'></span></a>
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
<?php 
  if(!empty($eve)):
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
  if(!empty($to_do)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm2').collapse({
      toggle: true
    })    
  });
</script>
<?php
  endif;
?>
<script type="text/javascript">
  $(function(){
   $('#collapseForm').on('show.bs.collapse', function () {
    $('#collapseForm2').collapse('hide')
    });

   $('#collapseForm2').on('show.bs.collapse', function () {
      $('#collapseForm').collapse('hide')
    });

  });
</script>

<?php
    Modal();
    makeFoot();
?>