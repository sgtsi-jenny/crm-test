<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1,2))){
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
			"date_from"=>"Enter Date and Time Start. <br/>",
			"date_to"=>"Enter Date and Time End. <br/>",
			"no_hours"=>"Invalid No of Hours. <br/>",
			"worked_done"=>"Enter Worked Done. <br/>"
			);
		$errors="";

		$startdate = strtotime($inputs['date_from']);
		$enddate = strtotime($inputs['date_to']);

		if ($enddate <= $startdate) {
		$errors .= 'Please check dates seleceted. <br/>';
		}

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
			redirect("frm_overtime_request.php");
			
			die;
		}
		else{
			// echo "<pre>";
			// print_r($inputs);
			// echo "</pre>";
			// die;

			//IF id exists update ELSE insert
			if(empty($inputs['id'])){

				$supervisor=$con->myQuery("SELECT e.supervisor_id FROM employees e WHERE e.id=?",array($_SESSION[WEBAPP]['user']['employee_id']))->fetch(PDO::FETCH_ASSOC);

				$s=$supervisor['supervisor_id'];
		
				if ($s == 0) {
					$st = "Final Approver Approval";
				}else
				{
					$st = "Supervisor Approval";
				}



				//Insert
				unset($inputs['id']);

				$inputs['employees_id']=$_SESSION[WEBAPP]['user']['employee_id'];
				$inputs['supervisor_id']=$con->myQuery("SELECT e.supervisor_id FROM employees e WHERE e.id=?",array($inputs['employees_id']))->fetchColumn();
				$inputs['final_approver_id']=$con->myQuery("SELECT d.approver_id FROM departments d INNER JOIN employees e ON d.id=e.department_id WHERE e.id=?",array($inputs['employees_id']))->fetchColumn();
			
				$inputs['date_from']=date_format(date_create($inputs['date_from']), 'Y-m-d H:i:s');
				$inputs['date_to']=date_format(date_create($inputs['date_to']), 'Y-m-d H:i:s');
				$inputs['st']=$st;

				$con->myQuery("INSERT INTO employees_ot(
					employees_id,
					supervisor_id,
					ot_approver_id,
					date_from,
					date_to,
					no_hours,
					status,
					date_filed,
					worked_done
					) VALUES(
					:employees_id,
					:supervisor_id,
					:final_approver_id,
					:date_from,
					:date_to,
					:no_hours,
					:st,
					NOW(),
					:worked_done
					)",$inputs);
				
				$supervisor=getEmpDetails($inputs['supervisor_id']);
				$employees=getEmpDetails($inputs['employees_id']);

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"{$employees['first_name']} {$employees['last_name']} filed an overtime request. From {$inputs['date_from']} To {$inputs['date_to']} for {$inputs['no_hours']} Hours. Worked to be done:{$inputs['worked_done']}");
				$email_settings=getEmailSettings();
				//var_dump($supervisor);
				if(!empty($supervisor) && !empty($email_settings)){
					$header="New Overtime Request For Your Approval";
					$message="Hi {$supervisor['first_name']},<br/> You have a new overtime request from {$employees['last_name']}, {$employees['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
					$message=email_template($header,$message);
					// var_dump($email_settings);
					 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
					emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($supervisor['private_email'],$supervisor['work_email'])),"Overtime Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
				}else
				{
					$final_approver=getEmpDetails($inputs['final_approver_id']);
					if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

						$header="New Overtime Request For Your Approval";
						$message="Hi {$final_approver['first_name']},<br/> You have a new overtime request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
						$message=email_template($header,$message);
						// var_dump($email_settings);
						 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
						emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Overtime Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
					}
				}
			}
			else{
				//Update
				
				
			}
			
			Alert("Save succesful","success");
			redirect("overtime_request.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>