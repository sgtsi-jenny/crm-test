<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }

    $tab="1";
    if(!empty($_GET['tab']) && !is_numeric($_GET['tab'])){
        redirect("org_details.php".(!empty($employee)?'?id='.$employee['id']:''));
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
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="" >Opportunity Details</a>
                    </li>
                    <li> <a href="opp_events.php?id=<?php echo $_GET['id'] ?>">Activities</a>
                    </li>
                    <li> <a href="opp_contact_persons.php?id=<?php echo $_GET['id'] ?>">Contact Persons</a>
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
                  <br/>
                    <table class='table table-bordered table-condensed'>
                            <tr>
                                <th>Opportunity Name:</th>
                                <td><?php echo htmlspecialchars($opp['opp_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Customer's Name:</th>
                                <td><?php echo htmlspecialchars($opp['org_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td><?php echo htmlspecialchars($opp['opp_type']) ?></td>
                            </tr>
                            <tr>
                                <th>Sales Stage:</th>
                                <td><?php echo htmlspecialchars($opp['sales_stage']) ?></td>
                            </tr>
                            <tr>
                                <th>Forecast Amount:</th>
                                <td><?php echo htmlspecialchars(number_format($opp['forecast_amount'],2)) ?></td>
                            </tr>
                            <tr>
                                <th>Amount:</th>
                                <td><?php echo htmlspecialchars(number_format($opp['tprice'],2)) ?></td>
                            </tr>
                            <tr>
                                <th>Assigned To:</th>
                                <td><?php echo htmlspecialchars($opp['users']) ?></td>
                            </tr>
                            <tr>
                                <th>Date Created:</th>
                                <td><?php echo htmlspecialchars($opp['date_created']) ?></td>
                            </tr>
                            <tr>
                                <th>Date Modified:</th>
                                <td><?php echo htmlspecialchars($opp['date_modified']) ?></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><?php echo htmlspecialchars($opp['description']) ?></td>
                            </tr>
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
               dom: 'Bfrtip',
                    buttons: [
                        {
                            extend:"excel",
                            text:"<span class='fa fa-download'></span> Download as Excel File "
                        }
                        ]
        });
      });
</script>

<?php
    Modal();
    makeFoot();
?>