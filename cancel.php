  <?php
  	require_once("support/config.php");

  	if(empty($_GET['id']))
  	{
		Modal("Invalid Record Selected");
		redirect("index.php");
		die;
	}
	else{
		$id=$_GET['id'];

		switch ($_POST['type']) {
			case 'leave':
				$table="employees_leaves";
				$page="employee_leave_request.php";


				$audit_details=$con->myQuery("SELECT employee_name,leave_type,date_start,date_end,reason FROM vw_employees_leave WHERE id=?",array($id))->fetch(PDO::FETCH_ASSOC);
				if(empty($audit_details['leave_type'])){
						$audit_details['leave_type']="Leave Without Pay";
					}
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Cancelled leave ({$audit_details['leave_type']}) request. From {$audit_details['date_start']} To {$audit_details['date_end']}. Reason for leave: {$audit_details['reason']}");

				break;
			case 'overtime':
				$table="employees_ot";
				$page="overtime_request.php";

				$audit_details=$con->myQuery("SELECT employee_name,date_from,date_to,worked_done,no_hours FROM vw_employees_ot WHERE id=?",array($id))->fetch(PDO::FETCH_ASSOC);

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Cancelled overtime request. From {$audit_details['date_from']} To {$audit_details['date_to']} for {$audit_details['no_hours']} Hours. Worked to be done:{$audit_details['worked_done']}");
				break;
			case 'shift':
				$table="employees_change_shift";
				$page="shift_request.php";

				$audit_details=$con->myQuery("SELECT employee_name,adjustment_reason,orig_in_time,orig_out_time,adj_in_time,adj_out_time FROM vw_employees_adjustments WHERE id=?",array($id))->fetch(PDO::FETCH_ASSOC);

				$audit_message="From {$audit_details['orig_in_time']}-{$audit_details['orig_out_time']} to {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']}. Adjustment Reason:{$audit_details['adjustment_reason']}";
				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Cancelled attendance adjustment request. {$audit_message}");
				break;
			case 'ob':
				$table="employees_ob";
				$page="ob_request.php";

				$audit_details=$con->myQuery("SELECT employee_name,destination,purpose,date_from,date_to FROM vw_employees_ob WHERE id=?",array($id))->fetch(PDO::FETCH_ASSOC);

				$audit_message="Destination: {$audit_details['destination']}. Purpose: {$audit_details['purpose']} during ".date("Y-m-d",strtotime($audit_details['date_from']))." - ".date("Y-m-d",strtotime($audit_details['date_to']));

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Cancelled official business request. {$audit_message}");
				break;
			case 'adjustment':
				$table="employees_adjustments";
				$page="adjustment_request.php";

				$audit_details=$con->myQuery("SELECT employee_name,adjustment_reason,orig_in_time,orig_out_time,adj_in_time,adj_out_time FROM vw_employees_adjustments WHERE id=?",array($id))->fetch(PDO::FETCH_ASSOC);
				if($audit_details['orig_in_time']=="0000-00-00 00:00:00"){
						$audit_message="Add {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']}";
				}
				else{
					$audit_message="From {$audit_details['orig_in_time']}-{$audit_details['orig_out_time']} to {$audit_details['adj_in_time']}-{$audit_details['adj_out_time']}. Adjustment Reason:{$audit_details['adjustment_reason']}";
				}

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Cancelled attendance adjustment request. {$audit_message}");

				break;
			default:
				redirect("index.php");
				break;
		}

		$con->myQuery("UPDATE {$table} SET status ='Cancelled',date_cancelled = NOW() WHERE id=?",array($id));
		// die;
		Alert("Save Succesful","success");
		redirect($page);
	}

	

  ?>