<?php
	$employment_status=$con->myQuery("SELECT id,name FROM employment_status WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	$job_titles=$con->myQuery("SELECT id,description FROM job_title WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	$pay_grades=$con->myQuery("SELECT id,level FROM pay_grade WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	$departments=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($employee)){
		$employees=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name) FROM employees WHERE is_deleted=0 AND is_terminated=0 AND id <> ?",array($employee['id']))->fetchAll(PDO::FETCH_ASSOC);
	}
	else{
		$employees=$con->myQuery("SELECT id,CONCAT(last_name,', ',first_name,' ',middle_name) FROM employees WHERE is_deleted=0 AND is_terminated=0")->fetchAll(PDO::FETCH_ASSOC);
	}
	$tax_status=$con->myQuery("SELECT id,code FROM tax_status WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
	Alert();
?>
<form class='form-horizontal' action='save_personal_information.php' method="POST" enctype="multipart/form-data">
	<input type='hidden' name='id' value='<?php echo !empty($employee)?$employee['id']:''; ?>'>
	<div class="form-group">

      
      <div class="col-sm-12 text-center col-md-2 col-md-offset-5">
      	<?php
        if(!empty($employee)){
        	
        	if(!empty($employee['image'])){
        		$image="employee_images/".$employee['image'];
        	}
        	else{
	            if($employee['gender']=='Male'){
	              $image="dist/img/user_male.png";
	            }
	            else{
	              $image="dist/img/user_female.png";
	            }
        	}
        	
        	
        }
        else{
          $image="dist/img/user_placeholder.png";
        }
      ?>

      	<img src="<?php echo $image;?>" class="user-image" alt="User Image" style='width:140px;'>
        <input type="file" id="image"  name='image' accept='image/*' class="filestyle" data-classButton="btn btn-primary" data-input="false" data-classIcon="icon-plus" data-buttonText=" &nbsp;Change Image">
      </div>
    </div>
	<div class="form-group">
      <label for="code" class="col-md-3 control-label">Employee Code *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="code" placeholder="Employee Code" name='code' value='<?php echo !empty($employee)?htmlspecialchars($employee['code']):''; ?>' required>
      </div>
    </div>
    <div class="form-group">
      <label for="first_name" class="col-md-3 control-label">First Name *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="first_name" placeholder="First Name" name='first_name' value='<?php echo !empty($employee)?htmlspecialchars($employee['first_name']):''; ?>'  required>
      </div>
    </div>
    <div class="form-group">
      <label for="middle_name" class="col-md-3 control-label">Middle Name </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="middle_name" placeholder="Middle Name" name='middle_name' value='<?php echo !empty($employee)?htmlspecialchars($employee['middle_name']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <label for="last_name" class="col-md-3 control-label">Last Name *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="last_name" placeholder="Last Name" name='last_name' value='<?php echo !empty($employee)?htmlspecialchars($employee['last_name']):''; ?>'  required>
      </div>
    </div>
    <div class="form-group">
      <label for="nationality" class="col-md-3 control-label">Nationality *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="nationality" placeholder="Nationality" name='nationality' value='<?php echo !empty($employee)?htmlspecialchars($employee['nationality']):''; ?>'  required>
      </div>
    </div>
    <div class="form-group">
      <label for="birthday" class="col-md-3 control-label">Date of Birth *</label>
      <div class="col-md-7">
        <input type="date" class="form-control" id="birthday" name='birthday' value='<?php echo !empty($employee)?htmlspecialchars($employee['birthday']):''; ?>'  required>
      </div>
    </div>
    <div class="form-group">
      <label for="gender" class="col-md-3 control-label">Gender *</label>
      <div class="col-md-7">
      	<select name='gender' class='form-control'  required>
      		<option value='' disabled="disabled" <?php echo empty($employee)?'selected="selected"':''; ?>>Select Gender</option>
      		<option value='Male' <?php echo !empty($employee) && $employee['gender']=='Male'?'selected="selected"':''; ?>>Male</option>
      		<option value='Female' <?php echo !empty($employee) && $employee['gender']=='Female'?'selected="selected"':''; ?>>Female</option>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="civil_status" class="col-md-3 control-label">Civil Status *</label>
      <div class="col-md-7">
      	<select name='civil_status' class='form-control'  required>
      		<option value='' disabled="disabled" <?php echo empty($employee)?'selected="selected"':''; ?>>Select Civil Status</option>
      		<?php 
      			foreach (array('Single','Married','Divorced','Widowed') as $value):
      		?>
          		<option value='<?php echo $value?>' <?php echo !empty($employee) && $employee['civil_status']==$value?'selected="selected"':''; ?>><?php echo $value?></option>
      		<?php
      			endforeach;
      		?>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="sss_no" class="col-md-3 control-label">SSS Number </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="sss_no" placeholder="SSS Number" name='sss_no' value='<?php echo !empty($employee)?htmlspecialchars($employee['sss_no']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <label for="tin" class="col-md-3 control-label">Tax Identification Number </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="tin" placeholder="Tax Identification Number" name='tin' value='<?php echo !empty($employee)?htmlspecialchars($employee['tin']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <label for="philhealth" class="col-md-3 control-label">Philhealth </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="philhealth" placeholder="Philhealth" name='philhealth' value='<?php echo !empty($employee)?htmlspecialchars($employee['philhealth']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <label for="pagibig" class="col-md-3 control-label">Pagibig </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="pagibig" placeholder="Pagibig" name='pagibig' value='<?php echo !empty($employee)?htmlspecialchars($employee['pagibig']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <label for="employment_status_id" class="col-md-3 control-label">Employment Status *</label>
      <div class="col-md-7">
      	<select name='employment_status_id' class='form-control select2' data-placeholder="Select Employment Status" <?php echo !(empty($employee))?"data-selected='".$employee['employment_status_id']."'":NULL ?> style='width:100%' required>
      		<?php
      			echo makeOptions($employment_status);
      		?>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="job_title_id" class="col-md-3 control-label">Job Title *</label>
      <div class="col-md-7">
      	<select name='job_title_id' class='form-control select2' data-placeholder="Select Job Title " <?php echo !(empty($employee))?"data-selected='".$employee['job_title_id']."'":NULL ?> style='width:100%' required>
      		<?php
      			echo makeOptions($job_titles);
      		?>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="pay_grade_id" class="col-md-3 control-label">Pay Grade *</label>
      <div class="col-md-7">
      	<select name='pay_grade_id' class='form-control select2' data-placeholder="Select Pay Grade " <?php echo !(empty($employee))?"data-selected='".$employee['pay_grade_id']."'":NULL ?> style='width:100%'  required>
      		<?php
      			echo makeOptions($pay_grades);
      		?>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="address1" class="col-md-3 control-label">Address 1 *</label>
      <div class="col-md-7">
      	<textarea class='form-control' name='address1' id='address1'  required><?php echo !empty($employee)?htmlspecialchars($employee['address1']):''; ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <label for="address2" class="col-md-3 control-label">Address 2 </label>
      <div class="col-md-7">
      	<textarea class='form-control' name='address2' id='address2' ><?php echo !empty($employee)?htmlspecialchars($employee['address2']):''; ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <label for="city" class="col-md-3 control-label">City *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="city" placeholder="City" name='city' value='<?php echo !empty($employee)?htmlspecialchars($employee['city']):''; ?>'  required>
      </div>
    </div>
    <div class="form-group">
      <label for="province" class="col-md-3 control-label">Province *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="province" placeholder="Province" name='province' value='<?php echo !empty($employee)?htmlspecialchars($employee['province']):''; ?>' required>
      </div>
    </div>
    <div class="form-group">
      <label for="country" class="col-md-3 control-label">Country *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="country" placeholder="Country" name='country' value='<?php echo !empty($employee)?htmlspecialchars($employee['country']):''; ?>'  required>
      </div>
    </div>
    <div class="form-group">
      <label for="postal_code" class="col-md-3 control-label">Postal Code </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="postal_code" placeholder="Postal Code" name='postal_code' value='<?php echo !empty($employee)?htmlspecialchars($employee['postal_code']):''; ?>' >
      </div>
    </div>
    <div class="form-group">
      <label for="contact_no" class="col-md-3 control-label">Contact No *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="contact_no" placeholder="Contact No" name='contact_no' value='<?php echo !empty($employee)?htmlspecialchars($employee['contact_no']):''; ?>' required>
      </div>
    </div>
    <div class="form-group">
      <label for="work_contact_no" class="col-md-3 control-label">Work Contact No </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="work_contact_no" placeholder="Work Contact No" name='work_contact_no' value='<?php echo !empty($employee)?htmlspecialchars($employee['work_contact_no']):''; ?>' >
      </div>
    </div>
    <div class="form-group">
      <label for="private_email" class="col-md-3 control-label">Email Address *</label>
      <div class="col-md-7">
        <input type="email" class="form-control" id="private_email" placeholder="Email Address" name='private_email' value='<?php echo !empty($employee)?htmlspecialchars($employee['private_email']):''; ?>' required>
      </div>
    </div>
    <div class="form-group">
      <label for="work_email" class="col-md-3 control-label">Work Email Address </label>
      <div class="col-md-7">
        <input type="email" class="form-control" id="work_email" placeholder="Email Address" name='work_email' value='<?php echo !empty($employee)?htmlspecialchars($employee['work_email']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <label for="joined_date" class="col-md-3 control-label">Join Date * </label>
      <div class="col-md-7">
        <input type="date" class="form-control" id="joined_date"  name='joined_date' value='<?php echo !empty($employee)?htmlspecialchars($employee['joined_date']):''; ?>' required>
      </div>
    </div>
    <div class="form-group">
      <label for="department_id" class="col-md-3 control-label">Department *</label>
      <div class="col-md-7">
      	<select name='department_id' class='form-control select2' data-placeholder="Select Department " <?php echo !(empty($employee))?"data-selected='".$employee['department_id']."'":NULL ?> style='width:100%' required>
      		<?php
      			echo makeOptions($departments);
      		?>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="supervisor_id" class="col-md-3 control-label">Supervisor </label>
      <div class="col-md-7">
      	<select name='supervisor_id' class='form-control select2' data-placeholder="Select Supervisor " <?php echo !(empty($employee))?"data-selected='".$employee['supervisor_id']."'":NULL ?> style='width:100%'>
      		<?php
      			echo makeOptions($employees);
      		?>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="basic_salary" class="col-md-3 control-label">Basic Salary * </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="basic_salary"  name='basic_salary' placeholder="0000.00" value='<?php echo !empty($employee)?htmlspecialchars($employee['basic_salary']):''; ?>' required>
      </div>
    </div>
    <div class="form-group">
      <label for="tax_status_id" class="col-md-3 control-label">Tax Status *</label>
      <div class="col-md-7">
      	<select name='tax_status_id' class='form-control select2' data-placeholder="Select Tax Status " <?php echo !(empty($employee))?"data-selected='".$employee['tax_status_id']."'":NULL ?> style='width:100%' required>
      		<?php
      			echo makeOptions($tax_status);
      		?>
      	</select>
      </div>
    </div>
    <div class="form-group">
      <label for="acu_id" class="col-md-3 control-label">Access Unit ID  </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="acu_id"  name='acu_id' placeholder="Access Unit ID" value='<?php echo !empty($employee)?htmlspecialchars($employee['acu_id']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <label for="bond_date" class="col-md-3 control-label">Bond Date </label>
      <div class="col-md-7">
        <input type="date" class="form-control" id="bond_date"  name='bond_date' value='<?php echo !empty($employee)?htmlspecialchars($employee['bond_date']):''; ?>'>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-10 col-md-offset-2 text-center">
      	<a href='employees.php' class='btn btn-default'>Back to Employees</a>
        <button type='submit' class='btn btn-success'>Save </button>
      </div>
    </div>
</form>