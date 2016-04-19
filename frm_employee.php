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
        redirect("frm_employee.php".(!empty($employee)?'?id='.$employee['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("frm_employee.php".(!empty($employee)?'?id='.$employee['id']:''));
            }
        }
    }
    
    if(!empty($_GET['id'])){
        $employee=$con->myQuery("SELECT * FROM employees e WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($employee)){
            Modal("Invalid Record Selected");
            redirect("employees.php");
            die;
        }
    }
    else{
        if($tab>"1"){
            Modal("Personal Information must be saved.");
            redirect("frm_employee.php");
        }

    }
    

    makeHead("Employee Form");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Employee Form
          </h1>
          <br/>
          <a href='employees.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Employee list</a>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php
                        $no_employee_msg=' Personal Information must be saved.';
                    ?>
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="frm_employee.php<?php echo !empty($employee)?"?id={$employee['id']}":''; ?>" >Personal Information</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="2"?'class="active"':''?> ><a href="?tab=2<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Education</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="3"?'class="active"':''?>><a href="?tab=3<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Skills</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="4"?'class="active"':''?>><a href="?tab=4<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Employment History</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="5"?'class="active"':''?>><a href="?tab=5<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Trainings</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="6"?'class="active"':''?>><a href="?tab=6<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Certifications</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="9"?'class="active"':''?>><a href="?tab=9<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Emergency Contacts</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="7"?'class="active"':''?>><a href="?tab=7<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Files</a>
                    </li>
                    <li <?php echo empty($employee)?'class="disabled"':''; ?> <?php echo $tab=="8"?'class="active"':''?>><a href="?tab=8<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>" <?php echo empty($employee)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Leaves</a>
                    </li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                    <?php
                        switch ($tab) {
                            case '1':
                                #PERSONAL INFORMATION
                                $form='personal_information.php';
                                break;
                            case '2':
                                #EDUCATION
                                $form='education.php';
                                break;
                            case '3':
                                #SKILLS
                                $form='skills.php';
                                break;
                            case '4':
                                #EMP HISTORY
                                $form='employment_history.php';
                                break;
                            case '5':
                                #TRAININGS
                                $form='employee_trainings.php';
                                break;
                            case '6':
                                #CERTIFICATIONS
                                $form='certifications.php';
                                break;
                            case '7':
                                #FILES
                                $form='files.php';
                                break;
                            case '8':
                                #FILES
                                $form='leaves.php';
                                break;
                            case '9':
                                #FILES
                                $form='emergency_contacts.php';
                                break;
                            default:
                                $form='personal_information.php';
                                break;
                        }
                        require_once("admin/employee/".$form);
                    ?>
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