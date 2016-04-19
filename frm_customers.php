<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

	$organization="";
  if(!empty($_GET['id'])){
        $organization=$con->myQuery("SELECT id,org_name,phone_num,email,address,industry,rating,org_type,annual_revenue,assigned_to,description FROM organizations WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

        if(empty($organization)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Organization Selected");
            redirect("organizations.php");
            die();
        }
        //var_dump($organization);
        //die;
    }

    $org_industries=$con->myQuery("SELECT id,name FROM org_industry")->fetchAll(PDO::FETCH_ASSOC);
    $org_ratings=$con->myQuery("SELECT id,name FROM org_ratings")->fetchAll(PDO::FETCH_ASSOC);
    $org_types=$con->myQuery("SELECT id,name FROM org_types")->fetchAll(PDO::FETCH_ASSOC);
    $user=$con->myQuery("SELECT id, CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("Customer Form");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Customer
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
                        <form class='form-horizontal' method='POST' action='save_customer.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($organization)?$organization['id']:""?>'>
                          
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Organization Name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='org_name' placeholder='Enter Organization Name' value='<?php echo !empty($organization)?$organization['org_name']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Phone Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='phone_num' placeholder='Enter Phone Number' value='<?php echo !empty($organization)?$organization['phone_num']:"" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Email Address</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='email' placeholder='Enter Email Address' value='<?php echo !empty($organization)?$organization['email']:"" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Address</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='address' placeholder='Enter Address' value='<?php echo !empty($organization)?$organization['address']:"" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Industry</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control select2' name='industry' data-placeholder="Select a Industry" <?php echo!(empty($organization))?"data-selected='".$organization['industry']."'":NULL ?>>
                                                    <?php
                                                        echo makeOptions($org_industries);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='frm_industries.php' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Rating</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control select2' name='rating' data-placeholder="Select a Rating" <?php echo!(empty($organization))?"data-selected='".$organization['rating']."'":NULL ?>>
                                                    <?php
                                                        echo makeOptions($org_ratings);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='frm_org_ratings.php' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Type</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                                <select class='form-control select2' name='org_type' data-placeholder="Select a Organization Type" <?php echo!(empty($organization))?"data-selected='".$organization['org_type']."'":NULL ?>>
                                                    <?php
                                                        echo makeOptions($org_types);
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col-ms-1'>
                                                <a href='frm_org_types.php' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> User*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        
                                        <div class='row'>
                                            <div class='col-sm-11'>
                                             <select class='form-control select2' name='assigned_to' data-placeholder="Select a user" <?php echo!(empty($organization))?"data-selected='".$organization['assigned_to']."'":NULL ?> required>
                                                <?php
                                                    echo makeOptions($user);
                                                ?>
                                            </select>
                                            </div>
                                            <?php
                                                if($_SESSION[WEBAPP]['user']['user_type']==1):
                                            ?>
                                            <div class='col-ms-1'>
                                            <a href='frm_users.php' class='btn btn-sm btn-success'><span class='fa fa-plus'></span></a>
                                            </div>
                                            <?php
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Annual Revenue</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' placeholder='0.00' name='annual_revenue' value='<?php echo !empty($organization)?$organization['annual_revenue']:"0" ?>'>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control ' name='description' value=''><?php echo !empty($organization)?$organization['description']:"" ?></textarea>
                                    </div>
                                </div>                             
                                

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='customers.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-success'> <span class='fa fa-check'></span> Save</button>
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