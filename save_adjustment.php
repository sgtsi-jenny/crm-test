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
		// if(empty($inputs['id'])){
		// 	Modal("Invalid Record Selected");
		// 	redirect("time_management.php");
		// }

		$required_fieds=array(
			"adj_in_time"=>"Enter Time in. <br/>",
			"adj_out_time"=>"Enter Time out. <br/>",
			"reason"=>"Enter Reason"
			);
		
		$errors="";

		$startdate = strtotime($inputs['adj_in_time']);
		$enddate = strtotime($inputs['adj_out_time']);

		if ($enddate < $startdate) {
		$errors .= 'Please check datetime seleceted. <br/>';
		}



		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}else{
				#CUSTOM VALIDATION
			}
		}
		

		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect("frm_adjustment_request.php");
			die;
		}
		else{
			// echo "<pre>";
			// print_r($inputs);
			// echo "</pre>";
			// die;

			$inputs['adj_in_time']=date_format(date_create($inputs['adj_in_time']), 'Y-m-d H:i:s');
			$inputs['adj_out_time']=date_format(date_create($inputs['adj_out_time']), 'Y-m-d H:i:s');
			$inputs['employees_id']=$_SESSION[WEBAPP]['user']['employee_id'];

			$inputs['supervisor_id']=$con->myQuery("SELECT e.supervisor_id FROM employees e WHERE e.id=?",array($inputs['employees_id']))->fetchColumn();
			$inputs['final_approver_id']=$con->myQuery("SELECT d.approver_id FROM departments d INNER JOIN employees e ON d.id=e.department_id WHERE e.id=?",array($inputs['employees_id']))->fetchColumn();
			if(empty($inputs['supervisor_id'])){
				$status="Final Approver Approval";
			}
			else{
				$status="Supervisor Approval";
			}
			try {
				$con->beginTransaction();
				if(empty($inputs['id'])){
				unset($inputs['id']);
				unset($inputs['orig_in_time']);
				unset($inputs['orig_out_time']);
				$con->myQuery("INSERT INTO employees_adjustments(
					employees_id,
					adj_in_time,
					adj_out_time,
					supervisor_id,
					final_approver_id,
					adjustment_reason,
					status,
					date_filed,
					attendance_id
					
					) VALUES(
					:employees_id,
					:adj_in_time,
					:adj_out_time,
					:supervisor_id,
					:final_approver_id,
					:reason,
					'{$status}',
					NOW(),
					0
					)",$inputs);
					$page="adjustment_request.php";
					// var_dump($inputs);
					// die;
					$audit_message="Add {$inputs['adj_in_time']}-{$inputs['adj_out_time']}";
				}
				else{
					$current=$con->myQuery("SELECT in_time,out_time FROM attendance WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
					$inputs['orig_in_time']=$current['in_time'];
					$inputs['orig_out_time']=$current['out_time'];
				$con->myQuery("INSERT INTO employees_adjustments(
					employees_id,
					adj_in_time,
					adj_out_time,
					supervisor_id,
					final_approver_id,
					adjustment_reason,
					status,
					date_filed,
					attendance_id,
					orig_in_time,
					orig_out_time
					) VALUES(
					:employees_id,
					:adj_in_time,
					:adj_out_time,
					:supervisor_id,
					:final_approver_id,
					:reason,
					'{$status}',
					NOW(),
					:id,
					:orig_in_time,
					:orig_out_time
					)",$inputs);
					$page="time_management.php";
					$audit_message="From {$inputs['orig_in_time']}-{$inputs['orig_out_time']} to {$inputs['adj_in_time']}-{$inputs['adj_out_time']}";
				}

				

				$con->commit();
			} catch (Exception $e) {
				$con->rollBack();
				Alert("Save Failed.","danger");
				redirect($page);
			}
				
				// die('ins');
			$supervisor=getEmpDetails($inputs['supervisor_id']);
				$employees=getEmpDetails($inputs['employees_id']);
				
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"{$employees['first_name']} {$employees['last_name']} filed an attendance adjustment request. {$audit_message}");

				$email_settings=getEmailSettings();
				//var_dump($supervisor);
				if(!empty($supervisor) && !empty($email_settings)){
					$header="New Attendance Adjustment Request For Your Approval";
					$message="Hi {$supervisor['first_name']},<br/> You have a new attendance adjustment request from {$employees['last_name']}, {$employees['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
					$message=email_template($header,$message);
					// var_dump($email_settings);
					 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
					emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($supervisor['private_email'],$supervisor['work_email'])),"Attendance Adjustment Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
				}
				else{
					$final_approver=getEmpDetails($inputs['final_approver_id']);
					if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){
					

					$header="New Attendance Adjustment Request For Your Approval";
					$message="Hi {$final_approver['first_name']},<br/> You have a new attendance adjustment request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
					$message=email_template($header,$message);
					
					emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Attendance Adjustment Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
					}
				}
			
			Alert("Save succesful","success");
			redirect($page);
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>