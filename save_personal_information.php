<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

		if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// die;


		$required_fieds=array(
			"code"=>"Enter Employee Code. <br/>",
			"first_name"=>"Enter First Name. <br/>",
			"last_name"=>"Enter Last Name. <br/>",
			"nationality"=>"Enter Nationality. <br/>",
			"birthday"=>"Enter Date of Birth. <br/>",
			"gender"=>"Select Gender. <br/>",
			"civil_status"=>"Select Civil Status. <br/>",
			"employment_status_id"=>"Select Employment Status. <br/>",
			"job_title_id"=>"Select Job Title. <br/>",
			"pay_grade_id"=>"Select Pay Grade. <br/>",
			"address1"=>"Enter Address. <br/>",
			"city"=>"Enter City. <br/>",
			"province"=>"Enter Province. <br/>",
			"country"=>"Enter Country. <br/>",
			"contact_no"=>"Enter Contact No. <br/>",
			"private_email"=>"Enter Email. <br/>",
			"joined_date"=>"Enter Joined Date. <br/>",
			"department_id"=>"Select Department. <br/>",
			"basic_salary"=>"Enter Basic Salary. <br/>",
			"tax_status_id"=>"Select Tax Status. <br/>"
			);
		$errors="";

		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}else{
				if($key=='basic_salary'){
					if(!is_numeric($inputs[$key])){
						$errors.="Invalid Basic Salary. <br/>";
					}
				}
				elseif ($key=='code') {
					if(!empty($inputs['id'])){
						$count=$con->myQuery("SELECT COUNT(id) FROM employees WHERE code=? AND id <> ? AND is_deleted=0",array($inputs['code'],$inputs['id']))->fetchColumn();
					}
					else{						
						$count=$con->myQuery("SELECT COUNT(id) FROM employees WHERE code=? AND is_deleted=0",array($inputs['code']))->fetchColumn();
					}
					if(!empty($count)){
						$errors.="Employee Code already exists. <br/>";
					}
				}
			}
		}

		if(empty($inputs['supervisor_id'])){
			$inputs['supervisor_id']=0;
		}
		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_employee.php");
			}
			else{
				redirect("frm_employee.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			// echo "<pre>";
			// print_r($inputs);
			// echo "</pre>";
			// var_dump($_FILES);
			// die;
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO employees(
					code,
					first_name,
					middle_name,
					last_name,
					nationality,
					birthday,
					gender,
					civil_status,
					sss_no,
					tin,
					philhealth,
					pagibig,
					employment_status_id,
					job_title_id,
					pay_grade_id,
					address1,
					address2,
					city,
					province,
					country,
					postal_code,
					contact_no,
					work_contact_no,
					private_email,
					work_email,
					joined_date,
					department_id,
					supervisor_id,
					basic_salary,
					tax_status_id,
					acu_id,
					bond_date
					) VALUES(
					:code,
					:first_name,
					:middle_name,
					:last_name,
					:nationality,
					:birthday,
					:gender,
					:civil_status,
					:sss_no,
					:tin,
					:philhealth,
					:pagibig,
					:employment_status_id,
					:job_title_id,
					:pay_grade_id,
					:address1,
					:address2,
					:city,
					:province,
					:country,
					:postal_code,
					:contact_no,
					:work_contact_no,
					:private_email,
					:work_email,
					:joined_date,
					:department_id,
					:supervisor_id,
					:basic_salary,
					:tax_status_id,
					:acu_id,
					:bond_date
					)",$inputs);

				$file_sql="";
				
				$employee_id=$con->lastInsertId();
				if(!empty($_FILES['image']['name'])){
					$filename=$employee_id.getFileExtension($_FILES['image']['name']);
					move_uploaded_file($_FILES['image']['tmp_name'],"employee_images/".$filename);
					$file_sql="image=:image";
					$insert['image']=$filename;
					$insert['id']=$employee_id;
					$con->myQuery("UPDATE employees SET {$file_sql} WHERE id=:id",$insert);
				}
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Created New Employee ({$inputs['first_name']} {$inputs['last_name']}).");
			}
			else{
				//Update
				
				$file_sql="";
				if(!empty($_FILES['image']['name'])){
					$filename=$inputs['id'].getFileExtension($_FILES['image']['name']);
					move_uploaded_file($_FILES['image']['tmp_name'],"employee_images/".$filename);
					$file_sql=",image=:image";
					$inputs['image']=$filename;
					// var_dump($inputs);
				}
				
				$con->myQuery("UPDATE employees SET
					code=:code,
					first_name=:first_name,
					middle_name=:middle_name,
					last_name=:last_name,
					nationality=:nationality,
					birthday=:birthday,
					gender=:gender,
					civil_status=:civil_status,
					sss_no=:sss_no,
					tin=:tin,
					philhealth=:philhealth,
					pagibig=:pagibig,
					employment_status_id=:employment_status_id,
					job_title_id=:job_title_id,
					pay_grade_id=:pay_grade_id,
					address1=:address1,
					address2=:address2,
					city=:city,
					province=:province,
					country=:country,
					postal_code=:postal_code,
					contact_no=:contact_no,
					work_contact_no=:work_contact_no,
					private_email=:private_email,
					work_email=:work_email,
					joined_date=:joined_date,
					department_id=:department_id,
					supervisor_id=:supervisor_id,
					basic_salary=:basic_salary,
					tax_status_id=:tax_status_id,
					acu_id=:acu_id,
					bond_date=:bond_date{$file_sql}
					WHERE id=:id
					",$inputs);
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Modified Employee Personal Information ({$inputs['first_name']} {$inputs['last_name']}).");
				
				$employee_id=$inputs['id'];
			}
			// echo "<pre>";
			// print_r($inputs);
			// echo "</pre>";
			// die;
			Alert("Save succesful","success");
			redirect("frm_employee.php?id=".urlencode($employee_id));
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>