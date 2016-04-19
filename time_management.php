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
        redirect("my_calendar.php".(!empty($employee)?'?id='.$employee['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("my_calendar.php".(!empty($employee)?'?id='.$employee['id']:''));
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
    // }
    // else{
    //     if($tab>"1"){
    //         Modal("Personal Information must be saved.");
    //         redirect("frm_employee.php");
    //     }

    }
    

    makeHead("Calendar");
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
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <?php
                        $no_employee_msg=' Personal Information must be saved.';
                    ?> -->
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="frm_employee.php<?php echo !empty($employee)?"?id={$employee['id']}":''; ?>" >Calendar</a>
                    </li>
                    <li <?php echo $tab=="2"?'class="active"':''?> ><a href="?tab=2<?php echo !empty($employee)?"&id={$employee['id']}":''; ?>">Calendar List</a>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                    <?php
                        switch ($tab) {
                            case '1':
                                #PERSONAL INFORMATION
                                $form='my_cal.php';
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