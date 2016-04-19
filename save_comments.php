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
		// print_r($_POST);
		// echo "</pre>";
		// die;

		if(empty($_POST['request_id']) || empty($_POST['request_type'])){
			Modal("Invalid Record Selected");
			redirect("index.php");
			die;
		}

		if(!in_array($_POST['request_type'], array("overtime","leave","official_business","shift","adjustment"))){
			Modal("Invalid Record Selected");
			redirect("index.php");
			die;
		}
		$required_fieds=array(
			"reason"=>"Enter Message. <br/>"
			);
		$errors="";
		$page=$inputs['redirect_page'];
		unset($inputs['redirect_page']);
		switch ($inputs['request_type']) {
			case 'overtime':
				//$page="overtime_approval.php";
				$table="employees_ot";
				$current=$con->myQuery("SELECT status,employees_id,supervisor_id,ot_approver_id as final_approver_id FROM  employees_ot WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);

				$audit_details=$con->myQuery("SELECT employee_name,date_from,date_to,worked_done,no_hours FROM vw_employees_ot WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="From {$audit_details['date_from']} To {$audit_details['date_to']} for {$audit_details['no_hours']} Hours. Worked to be done:{$audit_details['worked_done']}";

				$type="Overtime";
				break;
			case 'leave':
				$table="employees_leaves";
				$current=$con->myQuery("SELECT status,employee_id as employees_id,supervisor_id,final_approver_id FROM  employees_leaves WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);
				$type="Leave";

				$audit_details=$con->myQuery("SELECT employee_name,leave_type,date_start,date_end,reason FROM vw_employees_leave WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);
				if(empty($audit_details['leave_type'])){
						$audit_details['leave_type']="Leave Without Pay";
					}
				$audit_message="({$audit_details['leave_type']}) From {$audit_details['date_start']} To {$audit_details['date_end']}. Reason for leave: {$audit_details['reason']}";

				//$page="leave_approval.php";
				break;
			case 'adjustment':
				$table="employees_adjustments";
				$current=$con->myQuery("SELECT status,employees_id as employees_id,supervisor_id,final_approver_id FROM  {$table} WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);
				$audit_details=$con->myQuery("SELECT employee_name,adjustment_reason,orig_in_time,orig_out_time,adj_in_time,adj_out_time FROM vw_employees_adjustments WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="From {$audit_details['orig_in_time']}-{$audit_details['orig_out_time']} to {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']}. Adjustment Reason:{$audit_details['adjustment_reason']}";
				//$page="adjustments_approval.php";
				$type="Attendance Adjustment";
				break;
			case 'official_business':
				$table="employees_ob";
				$current=$con->myQuery("SELECT status,employees_id as employees_id,supervisor_id,final_approver_id FROM  {$table} WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);

				$audit_details=$con->myQuery("SELECT employee_name,destination,purpose,date_from,date_to FROM vw_employees_ob WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Destination: {$audit_details['destination']}. Purpose: {$audit_details['purpose']} during ".date("Y-m-d",strtotime($audit_details['date_from']))." - ".date("Y-m-d",strtotime($audit_details['date_to']));
				//$page="adjustments_approval.php";
				$type="Official Business";
				break;
			case 'shift':
				$table="employees_change_shift";
				$current=$con->myQuery("SELECT status,employees_id as employees_id,supervisor_id,final_approver_id FROM  {$table} WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);

				$audit_details=$con->myQuery("SELECT employee_name,orig_in_time,orig_out_time,adj_in_time,adj_out_time,date_from,date_to FROM vw_employees_change_shift WHERE id=?",array($inputs['request_id']))->fetch(PDO::FETCH_ASSOC);

				$audit_message="From {$audit_details['orig_in_time']}-{$audit_details['orig_out_time']} to {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']} during ".date("Y-m-d",strtotime($audit_details['date_from']))." - ".date("Y-m-d",strtotime($audit_details['date_to']));

				//$page="adjustments_approval.php";
				$type="Change Shift";
				break;
		}
		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}
		}

		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect($page);
			die;
		}
		else{
			// echo "<pre>";
			// print_r($inputs);
			// echo "</pre>";
			// die;
			switch ($current['status']) {
						case 'Supervisor Approval':
							$inputs['receiver_id']=$current['employees_id'];
							$status="Query (Supervisor)";
							$supervisor=getEmpDetails($current['supervisor_id']);
							$employees=getEmpDetails($current['employees_id']);
							$email_settings=getEmailSettings();
							//var_dump($supervisor);
							insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) queried {$employees['first_name']} {$employees['last_name']}'s {$type} request. Query:{$inputs['reason']}. {$audit_message}");

							if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
								$header="{$type} Request Queried by Supervisor";
								$message="Hi {$employees['first_name']},<br/> Your request has been queried by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. The message being '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
								$message=email_template($header,$message);
								// var_dump($email_settings);
								 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
								emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"{$type} Request (Query Supervisor)",$message,$email_settings['host'],$email_settings['port']);
							}
							break;
						case 'Final Approver Approval':
							$inputs['receiver_id']=$current['employees_id'];
							$status="Query (Final Approver)";

							$final_approver=getEmpDetails($current['final_approver_id']);
							$employees=getEmpDetails($current['employees_id']);
							$email_settings=getEmailSettings();

							insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) queried {$employees['first_name']} {$employees['last_name']}'s {$type} request. Query:{$inputs['reason']}. {$audit_message}");

							//var_dump($supervisor);
							if((!empty($employees['private_email']) || !empty($employees['work_email'])) && !empty($email_settings)){
								$header="{$type} Request Queried by Final Approver";
								$message="Hi {$employees['first_name']},<br/> Your request has been queried by the final approver, {$final_approver['first_name']} {$final_approver['last_name']}. The message being '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
								$message=email_template($header,$message);
								// var_dump($email_settings);
								 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
								emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"{$type} Request (Query Supervisor)",$message,$email_settings['host'],$email_settings['port']);
							}
							
							break;
						case 'Query (Supervisor)':
							$inputs['receiver_id']=$current['supervisor_id'];
							$status="Supervisor Approval";

							$supervisor=getEmpDetails($current['supervisor_id']);
							$employees=getEmpDetails($current['employees_id']);
							$email_settings=getEmailSettings();

							insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Answered (Supervisor) {$supervisor['first_name']} {$supervisor['last_name']}'s Query for {$type} request. Answer:{$inputs['reason']}.  {$audit_message}");

							//var_dump($supervisor);
							if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
								$header="{$type} Request For Approval";
								$message="Hi {$supervisor['first_name']},<br/> Your query has been answered by {$employees['first_name']} {$employees['last_name']}. The answer being '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
								$message=email_template($header,$message);
								// var_dump($email_settings);
								 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
								emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($supervisor['private_email'],$supervisor['work_email'])),"{$type} Request (Query Supervisor)",$message,$email_settings['host'],$email_settings['port']);
							}
							break;
						
						case 'Query (Final Approver)':
							$inputs['receiver_id']=$current['final_approver_id'];
							$status="Final Approver Approval";

							$final_approver=getEmpDetails($current['final_approver_id']);
							$employees=getEmpDetails($current['employees_id']);
							$email_settings=getEmailSettings();

							insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Answered (Final Approver) {$final_approver['first_name']} {$final_approver['last_name']}'s Query for {$type} request. Answer:{$inputs['reason']} . {$audit_message}");
							//var_dump($supervisor);
							if((!empty($final_approver['private_email']) || !empty($final_approver['work_email'])) && !empty($email_settings)){
								$header="{$type} Request For Approval";
								$message="Hi {$final_approver['first_name']},<br/> Your query has been answered by {$employees['first_name']} {$employees['last_name']}. The message being '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
								$message=email_template($header,$message);
								// var_dump($email_settings);
								 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
								emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"{$type} Request (Query Supervisor)",$message,$email_settings['host'],$email_settings['port']);
							}
							
							break;
					}
					
			$inputs['sender_id']=$_SESSION[WEBAPP]['user']['employee_id'];
			//IF id exists update ELSE insert
			try {
				//die("UPDATE {$table} SET status=? WHERE id=?");
				$con->myQuery("INSERT INTO comments(message,sender_id,receiver_id,request_type,request_id,date_sent) VALUES(:reason,:sender_id,:receiver_id,:request_type,:request_id,NOW())",$inputs);

				$con->myQuery("UPDATE {$table} SET status=? WHERE id=?",array($status,$inputs['request_id']));

			} catch (Exception $e) {
				Modal("Please try again.");
				redirect("index.php");
				die;
			}
			//die();
			Alert("Message Sent.","success");
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