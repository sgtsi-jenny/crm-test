<?php
	require_once("support/config.php");
	 if(!isLoggedIn())
	 {
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1,2)))
     {
         redirect("index.php");
     }

	if(!empty($_POST))
	{
		//Validate form inputs
		$inputs=$_POST;
		$employee=$_SESSION[WEBAPP]['user']['employee_id'];

		$required_fieds=array(
	//		"leave_id"=>"Select Type of Leave. <br/>",
			"date_start"=>"Enter Start Date of Leave. <br/>",
			"date_end"=>"Enter End Date of Leave. <br/>",
			"reason"=>"Enter Reason"
			);
		$errors="";

		foreach ($required_fieds as $key => $value)
		{
			if(empty($inputs[$key]))
			{
				$errors.=$value;
			}
		}

#CHECK DATES

	#START DATE LESS THAN END DATE
		$startdate = strtotime($inputs['date_start']);
		$enddate = strtotime($inputs['date_end']);
		if ($enddate < $startdate)
		{
			$errors .= 'Please check dates seleceted. <br/>';
		}

	#NO REPITITION IN THE DATABASE
		$validate_date=$con->myQuery("SELECT eld.id, eld.employees_leaves_id, el.employee_id, el.status, eld.date_leave as date_leave FROM employees_leaves_date eld INNER JOIN employees_leaves el ON el.id=eld.employees_leaves_id WHERE el.employee_id=? AND el.status<>'Cancelled' AND el.status<>'Rejected (Supervisor)' AND el.status<>'Rejected (Final Approver)'",array($employee));

		$s_date=new datetime($inputs['date_start']);
		$e_date=new datetime($inputs['date_end']);
		$e_date = $e_date->modify( '+1 day' ); 

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($s_date, $interval ,$e_date);
		$woweekends=0;
		$echo="";

		foreach($daterange as $date)
		{
			$weekday=$date->format("w");
			if($weekday != 0 && $weekday != 6)
			{
		        $dates=$date->format("Y-m-d");
		        while($row = $validate_date->fetch(PDO::FETCH_ASSOC))
		        {
		        	if($row['date_leave']==$dates)
		        	{
		        		$errors="Please check date/s. Selected date/s already exist in the database.";
		        		break;
		        	}
		        }
		       	//echo $dates.$echo."<br/>";
		        $woweekends++; 
		    }
		}
	
	#MAX NUMBER OF LEAVE
		if($inputs['leave_id']<>0)
		{
			$bal_count=$con->myQuery("SELECT id, balance_per_year FROM employees_available_leaves WHERE is_cancelled=0 AND is_deleted=0 AND leave_id=? AND employee_id=?",array($inputs['leave_id'],$employee))->fetch(PDO::FETCH_ASSOC);		

			$begin = new DateTime($inputs['date_start']);
			$end = new DateTime($inputs['date_end']);
			$end = $end->modify( '+1 day' ); 

			$int = new DateInterval('P1D');
			$drange = new DatePeriod($begin, $int ,$end);
			$count_days=0;

			foreach($drange as $day){
			    $weeks=$day->format("w");
			    if($weeks != 0 && $weeks != 6)
			    {
			    	$count_days++;	
			    }
			}

			if($count_days > $bal_count['balance_per_year'])
			{
				$errors="Please check date/s. You exceed number of leave balance.";
			}
		}

	
		if($errors!="")
		{
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id']))
			{
				redirect("frm_leave_request.php");
			}
			else
			{
				redirect("frm_leave_request.php?id=".urlencode($inputs['id']));
			}
			die;	
		}
		else
		{

			//IF id exists update ELSE insert
			if(empty($inputs['id']))
			{
				//Insert
				$supervisor=$con->myQuery("SELECT e.supervisor_id FROM employees e WHERE e.id=?",array($employee))->fetch(PDO::FETCH_ASSOC);
				$final_approver=$con->myQuery("SELECT d.approver_id FROM departments d INNER JOIN employees e ON d.id=e.department_id WHERE e.id=?",array($employee))->fetch(PDO::FETCH_ASSOC);
				$inputs['final_approver_id']=$con->myQuery("SELECT d.approver_id FROM departments d INNER JOIN employees e ON d.id=e.department_id WHERE e.id=?",array($employee))->fetchColumn();
				
				unset($inputs['id']);
				
				$s=$supervisor['supervisor_id'];
		
				if ($s == 0) {
					$st = "Final Approver Approval";
				}else
				{
					$st = "Supervisor Approval";
				}
				

				$params=array(
					"employee"=>$employee,
					"l_id"=>$inputs['leave_id'],
					"date_start"=>$inputs['date_start'],
					"date_end"=>$inputs['date_end'],
					"supervisor"=>$supervisor['supervisor_id'],
					"final_approver"=>$final_approver['approver_id'],
					"r"=>$inputs['reason'],
					"stats"=>$st
					);

				$con->myQuery("INSERT INTO 
								employees_leaves(
									employee_id,
									leave_id,
									date_start,
									date_end,
									supervisor_id,
									final_approver_id,
									date_filed,
									reason,
									status
								) VALUES(
									:employee,
									:l_id,
									DATE_FORMAT(:date_start,'%Y-%m-%d'),
									DATE_FORMAT(:date_end,'%Y-%m-%d'),
									:supervisor,
									:final_approver,
									CURDATE(),
									:r,
									:stats
								)",$params);


				$employee_leave_id=$con->lastInsertId();

				$s_date=new datetime($inputs['date_start']);
				$e_date=new datetime($inputs['date_end']);
	//			$interval=$e_date->diff($s_date);
				$e_date = $e_date->modify( '+1 day' ); 

				$interval = new DateInterval('P1D');
				$daterange = new DatePeriod($s_date, $interval ,$e_date);
				$woweekends=0;

				foreach($daterange as $date)
				{
					$weekday=$date->format("w");
					if($weekday != 0 && $weekday != 6)
					{ # 0=Sunday and 6=Saturday
				        $dates=$date->format("Y-m-d");
				        $emp_l=array(
							"employee_leave_id"=>$employee_leave_id,
						    "date_leave"=>$dates
						);
				        $con->myQuery("INSERT INTO 
				        				employees_leaves_date(
				        					employees_leaves_id,
				        					date_leave) 
				        				VALUES(
				        					:employee_leave_id,
				        					:date_leave)",
				        				$emp_l);
				        //echo $dates." <br>";
				        $woweekends++; 
				    }
				}
			}

			//die;
				$leave_name=$con->myQuery("SELECT name FROM leaves WHERE id=?",array($inputs['leave_id']))->fetchColumn();
				if(empty($leave_name))
				{
					$leave_name="Leave Without Pay";
				}
				$audit_message="From ".date("Y-m-d",strtotime($inputs['date_start']))." to ".date("Y-m-d",strtotime($inputs['date_end'])).". Reason: {$inputs['reason']}";
				$supervisor=getEmpDetails($supervisor['supervisor_id']);
				$employees=getEmpDetails($employee);
				$email_settings=getEmailSettings();

				insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"{$employees['first_name']} {$employees['last_name']} filed a leave ($leave_name) request. {$audit_message}");

					if(!empty($supervisor) && !empty($email_settings))
					{
						$header="New Leave Request For Your Approval";
						$message="Hi {$supervisor['first_name']},<br/> You have a new leave request from {$employees['last_name']}, {$employees['first_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
						$message=email_template($header,$message);
						
						emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($supervisor['private_email'],$supervisor['work_email'])),"Leave Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
					}else
					{
						$final_approver=getEmpDetails($inputs['final_approver_id']);
						if(!empty($final_approver['private_email']) || !empty($final_approver['work_email'])){

							$header="New Leave Request For Your Approval";
							$message="Hi {$final_approver['first_name']},<br/> You have a new leave request from {$employees['first_name']} {$employees['last_name']}. For more details please login to the Spark Global Tech Systems Inc HRIS.";
							$message=email_template($header,$message);
							// var_dump($email_settings);
							 //emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465
							emailer($email_settings['username'],decryptIt($email_settings['password']),"info@hris.com",implode(",",array($final_approver['private_email'],$final_approver['work_email'])),"Leave Request (For Approval)",$message,$email_settings['host'],$email_settings['port']);
						}
					}
				

				Alert("Save succesful".$employee_id,"success");
				redirect("employee_leave_request.php");
		}
		die;
	}
	else
	{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>