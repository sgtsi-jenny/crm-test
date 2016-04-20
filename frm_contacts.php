<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

	if(!empty($_GET['id'])){
        $account=$con->myQuery("SELECT assigned_to, fname, lname, department_id, org_id, home_phone, mobile_phone, office_phone, email, dob, description, profile_pic, id FROM contacts WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        $assigned_value=$con->myQuery("SELECT id, CONCAT(first_name,' ',middle_name,' ',last_name) as name FROM users WHERE id=?",array($account['assigned_to']))->fetch(PDO::FETCH_ASSOC);
        $dept_value=$con->myQuery("SELECT id, name FROM departments WHERE id=?",array($account['department_id']))->fetch(PDO::FETCH_ASSOC);
        $org_value=$con->myQuery("SELECT id, org_name FROM organizations WHERE id=?",array($account['org_id']))->fetch(PDO::FETCH_ASSOC);
        //$assigned_value=$con->myQuery("SELECT id, CONCAT(first_name,' ',middle_name,' ',last_name) as name FROM users where is_deleted=0 and id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
        if(empty($account)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Account Selected");
            redirect("contacts.php");
            die();
        }
    }

    $assigned=$con->myQuery("SELECT id, CONCAT(first_name,' ',middle_name,' ',last_name) as name FROM users where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $dep=$con->myQuery("SELECT id, name FROM departments where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $org=$con->myQuery("SELECT id, org_name FROM organizations where is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $user=$con->myQuery("SELECT id, CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("Contacts");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Contact
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-10 col-md-offset-1'>
				<?php
					Alert();
				?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='save_contact.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($account)?$account['id']:""?>'>
                                
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> </label>
                                    <div class='col-sm-12 col-md-9'>
                                        <img src='uploads/<?php echo !empty($account['profile_pic'])?$account['profile_pic']:"Default.jpg"?>' class='img-responsive' width='100px' height='100px'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Photo Upload</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='file' class='form-control' name='file' >
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Creator</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <select class='form-control' name='assigned_to' data-placeholder="Select assigned user" <?php echo!(empty($assigned))?"data-selected='".$assigned['id']."'":NULL ?>>
                                                    <option value='<?php echo !empty($account)?$account['assigned_to']:""?>'><?php echo !empty($assigned_value)?$assigned_value['name']:""?></option>
                                                    <?php
                                                        echo makeOptions($assigned);
                                                    ?>
                                        </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> First Name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='fname' placeholder='Enter First Name' value='<?php echo !empty($account)?$account['fname']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Last Name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='lname' placeholder='Enter Last Name' value='<?php echo !empty($account)?$account['lname']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Department*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <select class='form-control' name='department_id' data-placeholder="Select department" <?php echo!(empty($dep))?"data-selected='".$dep['id']."'":NULL ?> required>
                                                    <option value='<?php echo !empty($account)?$account['department_id']:""?>'><?php echo !empty($dept_value)?$dept_value['name']:""?></option>
                                                    <?php
                                                        echo makeOptions($dep);
                                                    ?>
                                        </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Company*</label>
                                    <div class='col-sm-12 col-md-9'>
                                       
                                        <select class='form-control' name='org_id' data-placeholder="Select organization" <?php echo!(empty($org))?"data-selected='".$org['org_id']."'":NULL ?>>
                                                    <option value='<?php echo !empty($account)?$account['org_id']:""?>'><?php echo !empty($dept_value)?$org_value['org_name']:""?></option required>
                                                    <?php
                                                        echo makeOptions($org);
                                                    ?>
                                        </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Home Phone</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='home_phone' placeholder='Enter Home Phone' value='<?php echo !empty($account)?$account['home_phone']:"" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Mobile Phone*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='mobile_phone' placeholder='Enter Mobile Phone' value='<?php echo !empty($account)?$account['mobile_phone']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Office Phone</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='office_phone' placeholder='Enter Office Phone' value='<?php echo !empty($account)?$account['office_phone']:"" ?>' >
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Email*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='email' placeholder='Enter Email Address' value='<?php echo !empty($account)?$account['email']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Date of Birth*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <?php
                                        $start_date="";
                                         if(!empty($maintenance)){
                                            $start_date=$maintenance['start_date'];
                                            if($start_date=="0000-00-00"){
                                                $start_date="";
                                            }
                                         }
                                        ?>
                                        <input type='date' class='form-control' name='dob'  value='<?php echo !empty($account)?$account['dob']:"" ?>' required>
                                    </div>
                                </div>

                               <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='description' placeholder="Write a short description."><?php echo !empty($account)?$account['description']:"" ?></textarea>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='contacts.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>                       
                        </form>
                      </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
</script>

<?php
	makeFoot();
?>