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
        $org=$con->myQuery("SELECT id,org_name,reg_name,trade_name,tin_num,tel_num,phone_num,email,industry,address,org_type,rating,annual_revenue,users,description,date_created,date_modified FROM vw_org WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($org)){
            Modal("Invalid Organization Selected");
            redirect("opportunities.php");
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
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="" >Customer Details</a>
                    </li>
                    <li> <a href="org_opp.php?id=<?php echo $_GET['id'] ?>">Opportunities</a>
                    </li>
                    <li> <a href="org_contact_persons.php?id=<?php echo $_GET['id'] ?>">Contact Persons</a></li>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                  <br/>
                    <table class='table table-bordered table-condensed'>
                            <tr>
                                <th>Corporate name:</th>
                                <td><?php echo htmlspecialchars($org['org_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Registered name:</th>
                                <td><?php echo htmlspecialchars($org['reg_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Trade name:</th>
                                <td><?php echo htmlspecialchars($org['reg_name']) ?></td>
                            </tr>
                            <tr>
                                <th>TIN Number:</th>
                                <td><?php echo htmlspecialchars($org['tin_num']) ?></td>
                            </tr>
                            <tr>
                                <th>Telephone Number:</th>
                                <td><?php echo htmlspecialchars($org['tel_num']) ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td><?php echo htmlspecialchars($org['phone_num']) ?></td>
                            </tr>
                            <tr>
                                <th>Legal Address:</th>
                                <td><?php echo htmlspecialchars($org['address']) ?></td>
                            </tr>
                            <tr>
                                <th>Email Address:</th>
                                <td><?php echo htmlspecialchars($org['email']) ?></td>
                            </tr>
                            <tr>
                                <th>Industry:</th>
                                <td><?php echo htmlspecialchars($org['industry']) ?></td>
                            </tr>
                            <tr>
                                <th>Ratings:</th>
                                <td><?php echo htmlspecialchars($org['rating']) ?></td>
                            </tr>
                            <tr>
                                <th>Annual Revenue:</th>
                                <td><?php echo htmlspecialchars(number_format($org['annual_revenue'],2)) ?></td>
                            </tr>
                            <tr>
                                <th>Assigned To:</th>
                                <td><?php echo htmlspecialchars($org['users']) ?></td>
                            </tr>
                            <tr>
                                <th>Date Created:</th>
                                <td><?php echo htmlspecialchars($org['date_created']) ?></td>
                            </tr>
                            <tr>
                                <th>Date Modified:</th>
                                <td><?php echo htmlspecialchars($org['date_modified']) ?></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><?php echo htmlspecialchars($org['description']) ?></td>
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