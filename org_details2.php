<?php
  require_once("support/config.php");
  if(!isLoggedIn()){
    toLogin();
    die();
  }

  if(!AllowUser(array(1,2))){
     if(empty($_GET['id'])){
        Modal("No Account Selected");
        redirect("opportunities.php");
        die();
    }
    else{
        $org=$con->myQuery("SELECT id,org_name,phone_num,email,industry,address,org_type,rating,annual_revenue,users,description,date_created,date_modified FROM vw_org WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($org)){
            Modal("Invalid Customer Selected");
            redirect("customers.php");
            die;
        }
    }

      
  //echo $emp_history_count;
  //die();

   $head=array("First Name:" => htmlspecialchars($org['org_name']),             
              "Middle Name:" => htmlspecialchars($org['email']),
              "Last Name:" => htmlspecialchars($org['industry']),
              "Nationality:" => htmlspecialchars($org['address']),            
              "Gender:" => htmlspecialchars($org['org_type']),
              "Birthday:" => htmlspecialchars($org['rating']),
              "Civil Status:" => htmlspecialchars($org['annual_revenue']),
              "Tax Status:" => htmlspecialchars($org['users']),                   
              "Philhealth Number:" => htmlspecialchars($org['date_created']),                    
              "Philhealth Number:" => htmlspecialchars($org['date_midified']),
              "Description:" => htmlspecialchars($org['description'])

              // "PAGIBIG Number:" => $org['pagibig'],
              // "Employment Status:" => $org['employment_status'],
              // "Job Title:" => $org['job_title'],
              // "Pay Grade:" => $org['pay_grade'],              
              // "Full Address:" => $org['full_address'], 
              // "Contact Number:" => $org['contact_no'],
              // "Work Contact Number:" => $org['work_contact_no'],
              // "Email Address:" => $org['private_email'],
              // "Work Email Address:" => $org['work_email'],
              // "Joined Date:" => $org['joined_date'],
              // "Department:" => $org['dept_name']
         
              );
  }
//   $headcount=count($head);  
// var_dump($headcount);
// die();
  


  makeHead("Customer Details ");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Customer Details 
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

              <div class="box box-solid">
                <div class="box-body">
                  <div class="row">
                  <div class='col-md-12'>
                    <form action="download_employee_details.php" method="post" name="export_excel">
                      <div class="control-group">
                        <div class="controls">
                          <input type='hidden' name='employees_id' value=<?php echo $inputs['employees_id']; ?>>

                          <button type="submit" id="export" name="export" class="btn btn-primary button-loading" data-loading-text="Loading...">Download to Excel File</button>

                        </div>
                      </div>
                    </form> 
                    <br>
                    <table class='table table-bordered table-striped' id='RTable'>
                      
                        <th class='text-center' colspan='5'>CUSTOMER INFORMATION</th>
                        <!--    <td></td><td></td><td></td><td></td> -->      
                        <?php
                          foreach ($org as $key => $value):
                        ?>
                          <tr>
                            <th style="width: 20%"><?php echo $key; ?></th>
                            <td style="width: 20%"><?php echo $value; ?></td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                            <!-- <td style="width: 20%"></td> -->
                          </tr>
                        <?php
                          endforeach;
                        ?>      

                    </table>
                  </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>
<?php
  makeFoot();
?>