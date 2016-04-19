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
			"training_id"=>"Select Training. <br/>"
			);
		$errors="";

		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}else{
				#CUSTOM VALIDATION
			}
		}
		$tab=5;
		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}");
			}
			else{
				redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}&ee_id={$inputs['id']}");
			}
			die;
		}
		else{
			

			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO employees_trainings(
					employee_id,
					training_id
					) VALUES(
					:employee_id,
					:training_id
					)",$inputs);

				$skill_name=$con->myQuery("SELECT name FROM trainings WHERE id=?",array($inputs['training_id']))->fetchColumn();
				$emp=getEmpDetails($inputs['employee_id']);
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Added ({$skill_name}) training to ({$emp['last_name']}, {$emp['first_name']} {$emp['middle_name']}).");
			}
			else{
				//Update
				
				// $con->myQuery("UPDATE employees_education SET
				// 	employee_id=:employee_id,
				// 	training_id=:training_id,
				// 	WHERE id=:id
				// 	",$inputs);
			}
			
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