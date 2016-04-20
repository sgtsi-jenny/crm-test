<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $tab="2";
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
                redirect("my_cal.php".(!empty($employee)?'?id='.$employee['id']:''));
            }
        }
    }

    
    if(empty($_GET['id'])){
        Modal("No Account Selected");
        redirect("customers.php");
        die();
    }
    else{
        $org=$con->myQuery("SELECT id,org_name,phone_num,email,industry,address,org_type,rating,annual_revenue,users,description,date_created,date_modified FROM vw_org WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($org)){
            Modal("Invalid Opportunities Selected");
            redirect("customers.php");
            die;
        }
    }
    

    makeHead("Customers");
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
                                      <img src="uploads/summary_organizations.png" width="50" height="50" title="Organization" alt="Organization" />
                                      <?php echo htmlspecialchars($org['org_name']) ?>
                                      </h1>
        </section>
         <section class="content-header">
        <br/>
          <a href='customers.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Customer list</a>
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
                    <li><a href="org_details.php?id=<?php echo $_GET['id'] ?>" >Customer Details</a>
                    </li>
                    <li <?php echo $tab=="2"?'class="active"':''?> ><a href="">Opportunities</a>
                    </li>
                    <li><a href="org_contact_persons.php?id=<?php echo $_GET['id'] ?>">Contact Persons</a>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                  <div class='col-ms-12 text-right'>
                          <a href='frm_org_opps.php?id=<?php echo $org['id']?>' class='btn btn-brand'> Add New Opportunity<span class='fa fa-plus'></span> </a>
                        </div>
                  <h2>List of Opportunities</h2>
                   <br>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Opportunity Name</th>
                                                <th class='text-center'>Customer's Name</th>
                                                <th class='text-center'>Sales Stage</th>
                                                <th class='text-center'>Amount</th>
                                                <th class='text-center'>Creator</th>
                                                <th class='text-center'>Contact Name</th>
                                                <th class='text-center' style='min-width:100px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                                                $opportunities=$con->myQuery("SELECT opp_name,org_name,sales_stage,tprice,users,cname,id FROM vw_opp where utype=? and org_id=?",array($_SESSION[WEBAPP]['user']['id'],$_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($opportunities as $row):
                                            ?>
                              <tr>
                                                        <!-- <td>
                                                        <input type="checkbox" name="select_org" value="<?php echo $organization["id"];?>" />
                                                        </td> -->
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
                                                            <td class='text-center'>
                                                                <a class='btn btn-sm btn-brand' href='frm_opportunities.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                                <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=org_opp' onclick='return confirm("This opportunity will be deleted.")'><span class='fa fa-trash'></span></a>
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










                    <!-- <?php
                        switch ($tab) {
                            case '1':
                                #PERSONAL INFORMATION
                                $form='org_detailed.php';
                                break;
                            case '2':
                                #EDUCATION
                                $form='education.php';
                                break;
                            default:
                                $form='personal_information.php';
                                break;
                        }
                        //require_once("admin/employee/".$form);
                    ?> -->
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
    Modal();
    makeFoot();
?>