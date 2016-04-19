<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

//	 if(!AllowUser(array(1,2))){
//         redirect("index.php");
//     }
	if(empty($_POST['type'])){
		Modal("Invalid Record Selected");
		redirect("index.php");
		die;
	}
	else{
		if(!in_array($_POST['type'],array('overtime','official_business','adjustment','leave','shift'))){
			Modal("Invalid Record Selected");
			redirect("index.php");
			die;
		}
	}

	function validate($fields)
	{
		global $page;
		$inputs=$_POST;
		$errors="";
		foreach ($fields as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
				//var_dump($inputs[$key]);
			}else{
				#CUSTOM VALIDATION
			}
		}
		if($errors!=""){
			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect($page);
			return false;
			die;
		}
		else{
			return true;
		}


	}
	$inputs=$_POST;
	$required_fieds=array();
	$page='index.php';
	
	switch ($inputs['type']) {
		case 'overtime':
			$page="overtime_approval.php";
			break;
		case 'leave':
			$page="leave_approval.php";
			break;
		case 'adjustment':
			$page="adjustments_approval.php";
			break;
		case 'official_business':
			$table="employees_ob";
			$page="ob_approval.php";
			break;
		case 'shift':
			$table="employees_change_shift";
			$page="shift_approval.php";
			break;
		default:
			redirect("index.php");
			break;
		
	}

	if(empty($_POST['id'])){
		Modal("Invalid Record Selected");
		redirect($page);
		die;
	}
	else{
		try {
			switch ($inputs['type']) {
				case 'overtime':
					$audit_details=$con->myQuery("SELECT employee_name,date_from,date_to,worked_done,no_hours FROM vw_employees_ot WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);

					$current=$con->myQuery("SELECT status,supervisor_id,final_approver_id,employee_id FROM  vw_employees_ot WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
		
					switch ($current['status']) {
						case 'Supervisor Approval':
							switch ($inputs['action']) {
								case 'approve':
										$con->myQuery("UPDATE employees_ot SET status ='Final Approver Approval',reason='',supervisor_date_action=NOW() WHERE id=?",array($inputs['id']));
										$supervisor=getEmpDetails($current['supervisor_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$employees=getEmpDetails($current['employee_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Approved {$employees['first_name']} {$employees['last_name']}'s overtime request. From {$audit_details['date_from']} To {$audit_details['date_to']} for {$audit_details['no_hours']} Hours. Worked to be done:{$audit_details['worked_done']}");
										
										if((!empty($employees['private_email']) || !empty($employees['work_email'])) && !empty($email_settings)){
											$header="Overtime Request Approved by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);

											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Overtime Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);

											if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

											$header="New Overtime Request For Your Approval";
											$message="Hi {$final_approver['first_name']},<br/> You have a new overtime request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Overtime Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
											}
										}



										// die;
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE employees_ot SET status ='Rejected (Supervisor)',reason=?,supervisor_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));
										
										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employee_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Rejected {$employees['first_name']} {$employees['last_name']}'s overtime request. The reason given is '{$inputs['reason']}. From {$audit_details['date_from']} To {$audit_details['date_to']} for {$audit_details['no_hours']} Hours. Worked to be done:{$audit_details['worked_done']}");

										//var_dump($supervisor);
										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Overtime Request Rejected by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. The reason given is '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Overtime Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;
							}
							break;
						case 'Final Approver Approval':
							switch ($inputs['action']) {
								case 'approve':
										$con->myQuery("UPDATE employees_ot SET status ='Approved',reason='',ot_approver_date_action=NOW() WHERE id=?",array($inputs['id']));
										
										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employee_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);
										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Overtime Request has been Approved";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Overtime Request (Approved)",$message,$email_settings['host'],$email_settings['port']);
										}
										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Approved {$employees['first_name']} {$employees['last_name']}'s overtime request. From {$audit_details['date_from']} To {$audit_details['date_to']} for {$audit_details['no_hours']} Hours. Worked to be done:{$audit_details['worked_done']}");
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE employees_ot SET status ='Rejected (Final Approver)',reason=?,ot_approver_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));
										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employee_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Rejected {$employees['first_name']} {$employees['last_name']}'s overtime request. The reason given is '{$inputs['reason']}'. From {$audit_details['date_from']} To {$audit_details['date_to']} for {$audit_details['no_hours']} Hours. Worked to be done:{$audit_details['worked_done']}");
										//var_dump($supervisor);
										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Overtime Request Rejected by Final Approver";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. The reason given is '{$inputs['reason']}'.  For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Overtime Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;
							}
							break;
					}
					break;
		#LEAVE		
				case 'leave':
					$audit_details=$con->myQuery("SELECT employee_name,leave_type,date_start,date_end,reason FROM vw_employees_leave WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
					$current=$con->myQuery("SELECT id,status,supervisor_id,final_approver_id,employee_id FROM employees_leaves WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
					if(empty($audit_details['leave_type'])){
						$audit_details['leave_type']="Leave Without Pay";
					}
					// die;
					switch ($current['status']) {
				#FOR SUPERVISOR	
						case 'Supervisor Approval':
							switch ($inputs['action']) {
							#APPROVED
								case 'approve':
									$con->myQuery("UPDATE employees_leaves SET status ='Final Approver Approval',supervisor_date_action=CURDATE() WHERE id=?",array($inputs['id']));
									Alert("Approved Leave!","success");

									$supervisor=getEmpDetails($current['supervisor_id']);
									$final_approver=getEmpDetails($current['final_approver_id']);
									$employees=getEmpDetails($current['employee_id']);
									$email_settings=getEmailSettings();

									insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Approved {$employees['first_name']} {$employees['last_name']}'s leave ({$audit_details['leave_type']}) request. From {$audit_details['date_start']} To {$audit_details['date_end']}. Reason for leave: {$audit_details['reason']}");
									//var_dump($supervisor);
									if((!empty($employees['private_email']) || !empty($employees['work_email'])) && !empty($email_settings)){
										$header="Leave Request Approved by Supervisor";
										$message="Hi {$employees['first_name']},<br/> Your request has been approved by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
										$message=email_template($header,$message);
										
										emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Leave Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);

										if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

										$header="New Leave Request For Your Approval";
										$message="Hi {$final_approver['first_name']},<br/> You have a new leave request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
										$message=email_template($header,$message);
										
										emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Leave Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
										}
									}

									break;
							#REJECTED
								case 'reject':
									$con->myQuery("UPDATE employees_leaves SET status ='Rejected (Supervisor)',comment=?,supervisor_date_action=CURDATE() WHERE id=?",array($inputs['reason'],$inputs['id']));

									$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employee_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Rejected {$employees['first_name']} {$employees['last_name']}'s leave ({$audit_details['leave_type']}) request. The reason given is '{$inputs['reason']}'. From {$audit_details['date_start']} To {$audit_details['date_end']}. Reason for leave: {$audit_details['reason']}");

										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Leave Request Rejected by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. The reason given is '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Leave Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}

									break;
							#CANCELLED
								case 'cancel':
									//$con->myQuery("UPDATE employees_leaves SET status ='Reject (Supervisor)',comment=?,supervisor_date_action=CURDATE() WHERE id=?",array($inputs['reason'],$inputs['id']));
									echo confirm("Are you sure to cancel?");
									break;
							}
							break;

				#FOR FINAL APPROVER
						case 'Final Approver Approval':
							switch ($inputs['action']) {
							#APPROVED
								case 'approve':
									$employee_leave=$con->myQuery("SELECT id,employee_id,balance_per_year FROM employees_available_leaves WHERE leave_id=? AND employee_id=? AND is_cancelled=0 AND is_deleted=0 ",array($inputs['leave_id'],$inputs['emp_id']))->fetch(PDO::FETCH_ASSOC);
									
									if(!empty($employee_leave))
									{
										#WITH PAY

									/*	$count_leave=$con->myQuery("SELECT COUNT(eld.id) AS count FROM employees_leaves_date eld 
																	INNER JOIN employees_leaves el ON el.id=eld.employees_leaves_id 
										            				WHERE el.status='Approved' AND el.remark='L' AND el.employee_id=? AND el.leave_id=? AND DATE_FORMAT(eld.date_leave,'%Y')=DATE_FORMAT(CURDATE(),'%Y')",array($inputs['emp_id'],$inputs['leave_id']))->fetch(PDO::FETCH_ASSOC);
									*/
										$remark='L';
										$leave_balance=$employee_leave['balance_per_year'];
										$leave_deduct=0;

										$datetime1 = new DateTime($inputs['date_start']);
										$datetime2 = new DateTime($inputs['date_end']);
										$woweekends = 0;

										if($datetime1==$datetime2)
										{
											$woweekends=1;
										}else
										{
											$interval = $datetime1->diff($datetime2);
											for($i=0; $i<=$interval->d; $i++){
											    $modif = $datetime1->modify('+1 day');
											    $weekday = $datetime1->format('w');
											    if($weekday != 0 && $weekday != 1){ # 0=Sunday and 6=Saturday
											        $woweekends+=1;  
											    }
											    echo $weekday."<br>";
											}
										}
										//echo "<br><br> W/O WEEKEND COUNT - ".$woweekends."<br> LEAVE BALANCE FROM DB - ".$leave_balance;
										//die();
										$leave_deduct=$leave_balance-$woweekends;

										$con->myQuery("UPDATE employees_available_leaves SET balance_per_year=? WHERE leave_id=? AND employee_id=? AND is_cancelled=0",array($leave_deduct,$inputs['leave_id'],$inputs['emp_id']));
										
									}
									else
									{
										#WITHOUT PAY
										$remark='A';
									}
									
										$con->myQuery("UPDATE employees_leaves SET status ='Approved',approver_date_action=CURDATE(),remark=? WHERE id=?",array($remark,$inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employee_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Approved {$employees['first_name']} {$employees['last_name']}'s leave ({$audit_details['leave_type']}) request. From {$audit_details['date_start']} To {$audit_details['date_end']}. Reason for leave: {$audit_details['reason']}");

										//var_dump($supervisor);
										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Leave Request has been Approved";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);

											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Leave Request (Approved)",$message,$email_settings['host'],$email_settings['port']);
										}

									Alert("Approved Leave!","success");
									break;
							#REJECTED
								case 'reject':
									$con->myQuery("UPDATE employees_leaves SET status ='Rejected (Final Approver)',comment=?,approver_date_action=CURDATE() WHERE id=?",array($inputs['reason'],$inputs['id']));

									$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employee_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();
										
										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Rejected {$employees['first_name']} {$employees['last_name']}'s leave ({$audit_details['leave_type']}) request. The reason given is '{$inputs['reason']}'. From {$audit_details['date_start']} To {$audit_details['date_end']}. Reason for leave: {$audit_details['reason']}");

										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Leave Request Rejected by Final Approver";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. The reason given is '{$inputs['reason']}'.  For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);

											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Attendance Adjustment Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									break;
							}
							break;
						default:
							# code...
							break;
					}
					break;
				case 'adjustment':

					$audit_details=$con->myQuery("SELECT employee_name,adjustment_reason,orig_in_time,orig_out_time,adj_in_time,adj_out_time FROM vw_employees_adjustments WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
					$current=$con->myQuery("SELECT status,supervisor_id,final_approver_id,employees_id FROM  employees_adjustments WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
					if($audit_details['orig_in_time']=="0000-00-00 00:00:00"){
						$audit_message="Add {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']}";
					}
					else{

						$audit_message="From {$audit_details['orig_in_time']}-{$audit_details['orig_out_time']} to {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']}. Adjustment Reason:{$audit_details['adjustment_reason']}";
					}

					switch ($current['status']) {
						case 'Supervisor Approval':
							switch ($inputs['action']) {
								case 'approve':
										$con->myQuery("UPDATE employees_adjustments SET status ='Final Approver Approval',reason='',supervisor_date_action=NOW() WHERE id=?",array($inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$employees=getEmpDetails($current['employees_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);
										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Approved {$employees['first_name']} {$employees['last_name']}'s attendance adjustment request. {$audit_message}");

										if((!empty($employees['private_email']) || !empty($employees['work_email'])) && !empty($email_settings)){
											$header="Attendance Adjustment Request Approved by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Attendance Adjustment Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);

											if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

											$header="New Attendance Adjustment Request For Your Approval";
											$message="Hi {$final_approver['first_name']},<br/> You have a new attendance adjustment request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Attendance Adjustment Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
											}
										}
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE employees_adjustments SET status ='Rejected (Supervisor)',reason=?,supervisor_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Rejected {$employees['first_name']} {$employees['last_name']}'s attendance adjustment request. The reason given is '{$inputs['reason']}'. {$audit_message}");
										//var_dump($supervisor);
										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Attendance Adjustment Request Rejected by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. The reason given is '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Attendance Adjustment Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;
							}
							break;
						case 'Final Approver Approval':
							switch ($inputs['action']) {
								case 'approve':
										try {
											$con->beginTransaction();
												$con->myQuery("UPDATE employees_adjustments SET status ='Approved',reason='',final_approver_date_action=NOW() WHERE id=?",array($inputs['id']));
												$current=$con->myQuery("SELECT adj_in_time,adj_out_time,attendance_id,supervisor_id,employees_id,final_approver_id,adjustment_reason FROM employees_adjustments WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
												if($audit_details['orig_in_time']=="0000-00-00 00:00:00"){
													$con->myQuery("INSERT INTO attendance (in_time,out_time,employees_id,note) VALUES(:adj_in_time,:adj_out_time,:employees_id,:note)",array("adj_in_time"=>$current['adj_in_time'],"adj_out_time"=>$current['adj_out_time'],"employees_id"=>$current['employees_id'],"note"=>$current['adjustment_reason']));
												}
												else{
													$con->myQuery("UPDATE attendance SET in_time=:adj_in_time,out_time=:adj_out_time,note=:note WHERE id=:attendance_id",array("adj_in_time"=>$current['adj_in_time'],"adj_out_time"=>$current['adj_out_time'],"attendance_id"=>$current['attendance_id'],"note"=>$current['adjustment_reason']));
												}
												//die;
											// die;
											$con->commit();
											
										} catch (Exception $e) {
											$con->rollback();
											Alert("Save Failed.","danger");
											redirect("adjustments_approval.php");
											die;
										}
										// var_dump($current);
										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Approved {$employees['first_name']} {$employees['last_name']}'s attendance adjustment request. {$audit_message}");
										//var_dump($supervisor);
										if((!empty($employees['private_email']) || !empty($employees['work_email'])) && !empty($email_settings)){
											$header="Attendance Adjustment Request has been Approved";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);

											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Attendance Adjustment Request (Approved)",$message,$email_settings['host'],$email_settings['port']);
										}
										
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE employees_adjustments SET status ='Rejected (Final Approver)',reason=?,final_approver_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();
										
										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Rejected {$employees['first_name']} {$employees['last_name']}'s attendance adjustment request. The reason given is '{$inputs['reason']}'. {$audit_message}");

										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Attendance Adjustment Request Rejected by Final Approver";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. The reason given is '{$inputs['reason']}'.  For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);

											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Attendance Adjustment Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;
							}
							break;
					}
					break;
				case 'official_business':
					$audit_details=$con->myQuery("SELECT employee_name,destination,purpose,date_from,date_to FROM vw_employees_ob WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);

					$audit_message="Destination: {$audit_details['destination']}. Purpose: {$audit_details['purpose']} during ".date("Y-m-d",strtotime($audit_details['date_from']))." - ".date("Y-m-d",strtotime($audit_details['date_to']));

					$current=$con->myQuery("SELECT status,supervisor_id,final_approver_id,employees_id FROM  {$table} WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
				
					switch ($current['status']) {
						case 'Supervisor Approval':
							switch ($inputs['action']) {
								case 'approve':
										$con->myQuery("UPDATE {$table} SET status ='Final Approver Approval',reason='',supervisor_date_action=NOW() WHERE id=?",array($inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$employees=getEmpDetails($current['employees_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Approved {$employees['first_name']} {$employees['last_name']}'s official business request. {$audit_message}");
										//var_dump($supervisor);
										if((!empty($employees['private_email']) || !empty($employees['work_email'])) && !empty($email_settings)){
											$header="Official Business Request Approved by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Official Business Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);

											if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

											$header="New Official Business Request For Your Approval";
											$message="Hi {$final_approver['first_name']},<br/> You have a new official business request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Official Business Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
											}
										}
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE {$table} SET status ='Rejected (Supervisor)',reason=?,supervisor_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Rejected {$employees['first_name']} {$employees['last_name']}'s official business request. The reason given is '{$inputs['reason']}'. {$audit_message}");
										//var_dump($supervisor);
										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Official Business Request Rejected by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. The reason given is '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Official Business Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;

							}
							break;
						case 'Final Approver Approval':
							switch ($inputs['action']) {
								case 'approve':
										$con->myQuery("UPDATE {$table} SET status ='Approved',reason='',final_approver_date_action=NOW() WHERE id=?",array($inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Approved {$employees['first_name']} {$employees['last_name']}'s official business request. {$audit_message}");

										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Official Business Request has been Approved";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);

											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Official Business Request (Approved)",$message,$email_settings['host'],$email_settings['port']);
										}
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE {$table} SET status ='Rejected (Final Approver)',reason=?,final_approver_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Rejected {$employees['first_name']} {$employees['last_name']}'s official business request. The reason given is '{$inputs['reason']}'. {$audit_message}");

										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Official Business Request Rejected by Final Approver";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. The reason given is '{$inputs['reason']}'.  For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Official Business Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;
							}
							break;
					}
					break;
				case 'shift':
					$audit_details=$con->myQuery("SELECT employee_name,orig_in_time,orig_out_time,adj_in_time,adj_out_time,date_from,date_to FROM vw_employees_change_shift WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);

					$audit_message="From {$audit_details['orig_in_time']}-{$audit_details['orig_out_time']} to {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']} during ".date("Y-m-d",strtotime($audit_details['date_from']))." - ".date("Y-m-d",strtotime($audit_details['date_to']));

					$current=$con->myQuery("SELECT status,supervisor_id,final_approver_id,employees_id FROM  {$table} WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
				
					switch ($current['status']) {
						case 'Supervisor Approval':
							switch ($inputs['action']) {
								case 'approve':
										$con->myQuery("UPDATE {$table} SET status ='Final Approver Approval',reason='',supervisor_date_action=NOW() WHERE id=?",array($inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$employees=getEmpDetails($current['employees_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Approved {$employees['first_name']} {$employees['last_name']}'s change shift request. {$audit_message}");

										//var_dump($supervisor);
										if((!empty($employees['private_email']) || !empty($employees['work_email'])) && !empty($email_settings)){
											$header="Change Shift Request Approved by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Change Shift Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);

											if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

											$header="New Change Shift Request For Your Approval";
											$message="Hi {$final_approver['first_name']},<br/> You have a new change shift request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Change Shift Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
											}
										}
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE {$table} SET status ='Rejected (Supervisor)',reason=?,supervisor_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);
										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Supervisor) Rejected {$employees['first_name']} {$employees['last_name']}'s change shift request. The reason given is '{$inputs['reason']}'. {$audit_message}");

										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Change Shift Request Rejected by Supervisor";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by your supervisor, {$supervisor['first_name']} {$supervisor['last_name']}. The reason given is '{$inputs['reason']}'. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Change Shift Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;

							}
							break;
						case 'Final Approver Approval':
							switch ($inputs['action']) {
								case 'approve':

										$con->myQuery("UPDATE {$table} SET status ='Approved',reason='',final_approver_date_action=NOW() WHERE id=?",array($inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();

										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Approved {$employees['first_name']} {$employees['last_name']}'s change shift request. {$audit_message}");

										//var_dump($supervisor);
										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Change Shift Request has been Approved";
											$message="Hi {$employees['first_name']},<br/> Your request has been approved by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);

											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Change Shift Request (Approved)",$message,$email_settings['host'],$email_settings['port']);
										}
									break;
								case 'reject':
								$required_fieds=array(
									"reason"=>"Enter Reason for rejection. <br/>"
									);
									if(validate($required_fieds)){
										$con->myQuery("UPDATE {$table} SET status ='Rejected (Final Approver)',reason=?,final_approver_date_action=NOW() WHERE id=?",array($inputs['reason'],$inputs['id']));

										$supervisor=getEmpDetails($current['supervisor_id']);
										$employees=getEmpDetails($current['employees_id']);
										$final_approver=getEmpDetails($current['final_approver_id']);
										$email_settings=getEmailSettings();
										//var_dump($supervisor);
										insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"(Final Approver) Rejected {$employees['first_name']} {$employees['last_name']}'s change shift request. The reason given is '{$inputs['reason']}'. {$audit_message}");

										if((!empty($supervisor['private_email']) || !empty($supervisor['work_email'])) && !empty($email_settings)){
											$header="Change Shift Request Rejected by Final Approver";
											$message="Hi {$employees['first_name']},<br/> Your request has been rejected by the final approver, {$final_approver['last_name']} {$final_approver['first_name']}. The reason given is '{$inputs['reason']}'.  For more details please login to the Spark Global Tech Systems Inc HRIS.";
											$message=email_template($header,$message);
											// var_dump($email_settings);
											 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
											emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($employees['private_email'],$employees['work_email'])),"Change Shift Request (Rejected)",$message,$email_settings['host'],$email_settings['port']);
										}
									}
									break;
							}
							break;
					}
					break;
			}
			
			Alert("Save Succesful","success");

			redirect($page);
		} catch (Exception $e) {
			die($e);
		}
	}
	
	if(!empty($page)){
		redirect($page);
	}
	
     redirect('index.php');
?>