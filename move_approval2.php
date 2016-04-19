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
		if(!in_array($_POST['type'],array('overtime','offical_business','adjustment','leave','shift'))){
			Modal("Invalid Record Selected");
			redirect("index.php");
			die;
		}
	}
	function validate($fields)
	{
		global $page;
		$errors="";
		foreach ($fields as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
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
			echo 't';
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
			$page="leave_approval.php?tab=1";
			break;
		case 'query';
			$page="employee_leave_request.php";
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
			#LEAVE
				case 'leave':
					$current=$con->myQuery("SELECT id,status,supervisor_id,final_approver_id FROM employees_leaves WHERE id=?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);
					switch ($current['status']) {
				#FOR SUPERVISOR	
						case 'Supervisor Approval':
							switch ($inputs['action']) {
							#APPROVED
								case 'approve':
									$con->myQuery("UPDATE employees_leaves SET status ='Final Approver Approval',supervisor_date_action=CURDATE() WHERE id=?",array($inputs['id']));
									Alert("Approved Leave!","success");
									break;
							#QUERY
								case 'query':
									//$con->myQuery("UPDATE employees_leaves SET status ='Query (Supervisor)',comment=?,supervisor_date_action=CURDATE() WHERE id=?",array($inputs['reason'],$inputs['id']));
									//$con->myQuery("INSERT INTO query_leave(leave_id,comment,action_date) values(?,?,CURDATE())",array($current['id'],$inputs['reason']));
									break;
							#REJECTED
								case 'reject':
									$con->myQuery("UPDATE employees_leaves SET status ='Reject (Supervisor)',comment=?,supervisor_date_action=CURDATE() WHERE id=?",array($inputs['reason'],$inputs['id']));
									break;

							}
							break;

				#FOR FINAL APPROVER
						case 'Final Approver Approval':
							switch ($inputs['action']) {
							#APPROVED
								case 'approve':
									$con->myQuery("UPDATE employees_leaves SET status ='Approved',approver_date_action=CURDATE() WHERE id=?",array($inputs['id']));
									break;
							#QUERY
								case 'query':
									//$con->myQuery("UPDATE employees_leaves SET status ='Query (Final Approver)',comment=?,approver_date_action=CURDATE() WHERE id=?",array($inputs['reason'],$inputs['id']));
									//$con->myQuery("INSERT INTO query_leave(leave_id,comment,action_date) values(?,?,CURDATE())",array($current['id'],$inputs['reason']));
									break;
							#REJECTED
								case 'reject':
									$con->myQuery("UPDATE employees_leaves SET status ='Reject (Final Approver)',comment=?,approver_date_action=CURDATE() WHERE id=?",array($inputs['reason'],$inputs['id']));
									break;
							}
							break;
						default:
							# code...
							break;

				#QUERY - RETURN TO SUPREVISOR 
						case 'Query (Supervisor)':
							switch ($inputs['action']) {
								case 'query':
									$con->myQuery("UPDATE employees_leaves SET status ='Supervisor Approval',comment=? WHERE id=?",array($inputs['reason'],$inputs['id']));
									$con->myQuery("INSERT INTO query_leave(leave_id,comment,action_date) values(?,?,CURDATE())",array($current['id'],$inputs['reason']));
									break;
							}
							break;
					}
					break;
				
			}
			//die();
	//		Alert("Save Succesful","success");
			redirect($page);
		} catch (Exception $e) {
			die($e);
		}
	}
	
	
var_dump($_POST);
die;
     redirect('index.php');
?>