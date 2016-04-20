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

    $data=$con->myQuery("SELECT profile_pic, CONCAT(lname, ', ', fname) As contact_name, CONCAT(users.last_name, ', ', users.first_name) As assigned_name, contacts.assigned_to, departments.name, department_id, organizations.org_name, contacts.org_id, dob, contacts.email, home_phone, mobile_phone, office_phone, contacts.description, contacts.id FROM contacts left join users on contacts.assigned_to=users.id inner join departments on departments.id=contacts.department_id inner join organizations on organizations.id=contacts.org_id where contacts.is_deleted=0 and contacts.assigned_to=?",array($_SESSION[WEBAPP]['user']['id']))->fetchAll(PDO::FETCH_ASSOC);

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
                    <li ><a href="org_opp.php?id=<?php echo $_GET['id'] ?>">Opportunities</a>
                    </li>
                    <li <?php echo $tab=="3"?'class="active"':''?>><a href="">Contact Persons</a></li>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                  <div class='col-ms-12 text-right'>
                          <a href='frm_org_opps.php?id=<?php echo $org['id']?>' class='btn btn-brand'> Add New Contact Person<span class='fa fa-plus'></span> </a>
                        </div>
                    <h2>List of Contact Persons</h2>
                   <br>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                                                <th class='text-center'>Photo</th>
                                                <th class='text-center'>Name</th>
                                                <!-- <th class='text-center'>Creator</th> -->
                                                <th class='text-center'>Department</th>
                                                <th class='text-center'>Company</th>
                                                <th class='text-center'>Birth date</th>
                                                <th class='text-center'>Email</th>
                                                <th class='text-center'>Home Phone</th>
                                                <th class='text-center'>Mobile Phone</th>
                                                <th class='text-center'>Office Phone</th>
                                                <th class='text-center'>Description</th>
                                                <th class='text-center' style='min-width:50px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach($data as $row):
                      ?>
                        <tr>
                          <td><a href='uploads/<?php echo $row['profile_pic'] ?>'>
                            <img src='uploads/<?php echo $row['profile_pic'];?>' class='img-responsive' width='40px' height='40px'></a>
                          </td>
                          <td class='text-center'><?php echo htmlspecialchars($row['contact_name'])?></td>
                          <!-- <td class='text-center'><?php echo htmlspecialchars($row['assigned_name'])?></td> -->
                          <td class='text-center'><?php echo htmlspecialchars($row['name'])?></td>
                          <td class='text-center'><?php echo htmlspecialchars($row['org_name'])?></td>
                          <td class='text-center'><?php echo htmlspecialchars($row['dob'])?></td>
                          <td class='text-center'><?php echo htmlspecialchars($row['email'])?></td>
                          <td class='text-center'><?php echo htmlspecialchars($row['home_phone'])?></td>
                          <td class='text-center'><?php echo htmlspecialchars($row['mobile_phone'])?></td>
                          <td class='text-center'><?php echo htmlspecialchars($row['office_phone'])?></td>
                          <td class='text-center'><?php echo htmlspecialchars($row['description'])?></td>
                          <?php
                          foreach ($row as $key => $value):
                            if($key=='id'):
                          ?>
                          <td>
                                                                <a class='btn btn-sm btn-brand' href='frm_contacts.php?id=<?php echo $value;?>'><span class='fa fa-pencil'></span></a>
                                                                <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $value?>&t=co' onclick='return confirm("This contact will be deleted.")'><span class='fa fa-trash'></span></a>
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