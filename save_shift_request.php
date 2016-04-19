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
			"date_from"=>"Enter Date an Time Start. <br/>",
			"date_to"=>"Enter Date an Time End. <br/>",
			"orig_in_time"=>"Enter Original In Time. <br/>",
			"orig_out_time"=>"Enter Original Out Time. <br/>",
			"adj_in_time"=>"Enter Requested In Time. <br/>",
			"adj_out_time"=>"Enter Requested Out Time. <br/>",
			"shift_reason"=>"Enter Shift Reason. <br/>"
			);
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
			
			redirect("frm_shift_request.php");
			
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
				$inputs['employees_id']=$_SESSION[WEBAPP]['user']['employee_id'];
				$inputs['supervisor_id']=$con->myQuery("SELECT e.supervisor_id FROM employees e WHERE e.id=?",array($inputs['employees_id']))->fetchColumn();
				$inputs['final_approver_id']=$con->myQuery("SELECT d.approver_id FROM departments d INNER JOIN employees e ON d.id=e.department_id WHERE e.id=?",array($inputs['employees_id']))->fetchColumn();
			
				$inputs['date_from']=date_format(date_create($inputs['date_from']), 'Y-m-d H:i:s');
				$inputs['date_to']=date_format(date_create($inputs['date_to']), 'Y-m-d H:i:s');

				$inputs['orig_in_time']=date_format(date_create($inputs['orig_in_time']), 'H:i:s');
				$inputs['orig_out_time']=date_format(date_create($inputs['orig_out_time']), 'H:i:s');

				$inputs['adj_in_time']=date_format(date_create($inputs['adj_in_time']), 'H:i:s');
				$inputs['adj_out_time']=date_format(date_create($inputs['adj_out_time']), 'H:i:s');

				if(empty($inputs['supervisor_id'])){
					$status="Final Approver Approval";
				}
				else{
					$status="Supervisor Approval";
				}
				unset($inputs['hour']);
				unset($inputs['minute']);
				unset($inputs['meridian']);
				$con->myQuery("INSERT INTO employees_change_shift(
					employees_id,
					supervisor_id,
					final_approver_id,
					date_from,
					date_to,
					shift_reason,
					status,
					date_filed,
					orig_in_time,
					orig_out_time,
					adj_in_time,
					adj_out_time
					) VALUES(
					:employees_id,
					:supervisor_id,
					:final_approver_id,
					:date_from,
					:date_to,
					:shift_reason,
					'{$status}',
					NOW(),
					:orig_in_time,
					:orig_out_time,
					:adj_in_time,
					:adj_out_time
					)",$inputs);

				
				$audit_message="From {$inputs['orig_in_time']}-{$inputs['orig_out_time']} to {$inputs['adj_in_time']}-{$inputs['adj_out_time']} during ".date("Y-m-d",strtotime($inputs['date_from']))." - ".date("Y-m-d",strtotime($inputs['date_to']));
			}
			else{
				//Update
				
				
			}
			$supervisor=getEmpDetails($inputs['supervisor_id']);
				$employees=getEmpDetails($inputs['employees_id']);
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"{$employees['first_name']} {$employees['last_name']} filed a shift change request. {$audit_message}");
				$email_settings=getEmailSettings();
				//var_dump($supervisor);
				if(!empty($supervisor) && !empty($email_settings)){
					$header="New Change Shift Request For Your Approval";
					$message="Hi {$supervisor['first_name']},<br/> You have a new change shift request from {$employees['last_name']}, {$employees['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
					$message=email_template($header,$message);
					// var_dump($email_settings);
					 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
					emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($supervisor['private_email'],$supervisor['work_email'])),"Change Shift Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
				}else{
					$final_approver=getEmpDetails($inputs['final_approver_id']);
					
					if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

					$header="New Change Shift Request For Your Approval";
					$message="Hi {$final_approver['first_name']},<br/> You have a new change shift request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
					$message=email_template($header,$message);
					// var_dump($email_settings);
					 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
					emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Change Shift Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
					}
				}
			Alert("Save succesful","success");
			redirect("shift_request.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>