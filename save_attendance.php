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

		$required_fieds=array(
			"in_time"=>"Enter Time in. <br/>",
			"out_time"=>"Enter Time out. <br/>"
			);
		if(empty($inputs['id'])){
			$required_fieds['employees_id']="Select an employee. <br/>";
		}
		$errors="";

		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}else{
				#CUSTOM VALIDATION
			}
		}
		$tab=6;
		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_attendance.php");
			}
			else{
				redirect("frm_attendance.php"."?id={$inputs['id']}");
			}
			die;
		}
		else{
			// echo "<pre>";
			// print_r($inputs);
			// echo "</pre>";
			// die;
			$inputs['in_time']=date_format(date_create($inputs['in_time']), 'Y-m-d H:i:s');
			$inputs['out_time']=date_format(date_create($inputs['out_time']), 'Y-m-d H:i:s');
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				$con->myQuery("INSERT INTO attendance(
					employees_id,
					in_time,
					out_time
					) VALUES(
					:employees_id,
					:in_time,
					:out_time
					)",$inputs);
				// die('ins');
				
				$employee=getEmpDetails($inputs['employees_id']);
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Added attendance for {$employee['first_name']} {$employee['last_name']}. Time in: {$inputs['in_time']}. Time out: {$inputs['out_time']} ");
			}
			else{
				//Update
				
				$audit_details=$con->myQuery("SELECT in_time,out_time,e.first_name,e.last_name FROM attendance a JOIN employees e ON a.employees_id=e.id WHERE a.id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
				$con->myQuery("UPDATE attendance SET
					in_time=:in_time,
					out_time=:out_time
					WHERE id=:id
					",$inputs);

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Modified attendance of {$audit_details['first_name']} {$audit_details['last_name']}. From {$audit_details['in_time']} - {$audit_details['out_time']} To {$inputs['in_time']} - {$inputs['out_time']} ");

				// var_dump($inputs);
				// die('upd');

			}

			Alert("Save succesful","success");
			redirect("monitor_attendance.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>