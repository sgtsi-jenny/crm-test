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
		// print_r($inputs);
		// echo "</pre>";
		// die;

		if(empty($inputs['employee_id'])){
			Modal("Invalid Record Selected");
			redirect("employees.php");
		}

		$required_fieds=array(
			"first_name"=>"Enter First Name. <br/>",
			"last_name"=>"Enter Last Name. <br/>",
			"contact_no"=>"Enter Contact Number. <br/>"
			);
		$errors="";

		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}else{
				#CUSTOM VALIDATION
			}
		}
		$tab=9;
		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}");
			}
			else{
				redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}&eec_id={$inputs['id']}");
			}
			die;
		}
		else{
			// echo "<pre>";
			// print_r($inputs);
			// echo "</pre>";
			// die;

			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO employees_emergency_contacts(
					employee_id,
					first_name,
					middle_name,
					last_name,
					contact_no,
					address,
					remarks
					) VALUES(
					:employee_id,
					:first_name,
					:middle_name,
					:last_name,
					:contact_no,
					:address,
					:remarks
					)",$inputs);
				$emp=getEmpDetails($inputs['employee_id']);

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Added Emergency Contact ({$inputs['first_name']} {$inputs['last_name']}) to ({$emp['last_name']}, {$emp['first_name']} {$emp['middle_name']}).");
			}
			else{
				//Update
				
				$con->myQuery("UPDATE employees_emergency_contacts SET
					employee_id=:employee_id,
					first_name=:first_name,
					middle_name=:middle_name,
					last_name=:last_name,
					contact_no=:contact_no,
					address=:address,
					remarks=:remarks
					WHERE id=:id
					",$inputs);
				// var_dump($inputs);
				// die;
				$emp=getEmpDetails($inputs['employee_id']);

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Modified an Emergency Contact ({$inputs['first_name']} {$inputs['last_name']}) of ({$emp['last_name']}, {$emp['first_name']} {$emp['middle_name']}).");
			}
			// die;
			Alert("Save succesful","success");
			redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>