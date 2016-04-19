<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

	$data="";
	if(!empty($_GET['id'])){
  		$data=$con->myQuery("SELECT id,name,location,topic,training_date,bond_months FROM trainings WHERE is_deleted=0 AND id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  		if(empty($data)){
  			Modal("Invalid Record Selected");
  			redirect("trainings.php");
  			die;
  		}
	}

	makeHead("Training Form");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Training Form
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
                	<div class='col-md-12'>
		              	<form class='form-horizontal' action='save_trainings.php' method="POST">
		              		<input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:''; ?>'>
		              		<div class="form-group">
		                      <label for="name" class="col-sm-2 control-label">Training Name *</label>
		                      <div class="col-sm-10">
		                        <input type="text" class="form-control" id="name" placeholder="Training Name" name='name' value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>'>
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <label for="location" class="col-sm-2 control-label">Location *</label>
		                      <div class="col-sm-10">
		                        <input type="text" class="form-control" id="location" placeholder="Location" name='location' value='<?php echo !empty($data)?htmlspecialchars($data['location']):''; ?>'>
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <label for="topic" class="col-sm-2 control-label">Topic *</label>
		                      <div class="col-sm-10">
		                        <input type="text" class="form-control" id="topic" placeholder="Topic" name='topic' value='<?php echo !empty($data)?htmlspecialchars($data['topic']):''; ?>'>
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <label for="bond_months" class="col-sm-2 control-label">Bond (Months)</label>
		                      <div class="col-sm-10">
		                        <input type="number" class="form-control" id="bond_months"  name='bond_months' min='0' step='1' placeholder="Number of Months" value='<?php echo !empty($data)?htmlspecialchars($data['bond_months']):''; ?>'>
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <label for="training_date" class="col-sm-2 control-label">Training Date *</label>
		                      <div class="col-sm-10">
		                        <input type="date" class="form-control" id="training_date"  name='training_date' value='<?php echo !empty($data)?$data['training_date']:''; ?>'>
		                      </div>
		                    </div>
		                    <div class="form-group">
		                      <div class="col-sm-10 col-md-offset-2 text-center">
		                      	<a href='trainings.php' class='btn btn-default'>Cancel</a>
		                        <button type='submit' class='btn btn-success'>Save </button>
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