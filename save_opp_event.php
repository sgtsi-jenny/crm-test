<?php
	require_once 'support/config.php';
	
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
		var_dump($inputs);
				die;
		$errors="";

		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_event.php");
			}
			else{
				redirect("frm_event.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				

				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();

				//$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['date_created']=$now->format('Y-m-d H:i:s a');
				$inputs['sdate']=$inputs['start_date'].' '.$inputs['start_time'].':00';
				$inputs['edate']=$inputs['end_date'].' '.$inputs['end_time'].':00';
				// var_dump($inputs);
				// die;
				unset($inputs['start_date']);
				unset($inputs['start_time']);
				unset($inputs['end_date']);
				unset($inputs['end_time']);
				//var_dump($inputs['sdate']);
				//var_dump($inputs['edate']);
				var_dump($inputs);
				//die;
				$con->myQuery("INSERT INTO events(subject,assigned_to,start_date,end_date,event_stat,activity_type,location_id,priority,description,date_created) VALUES(:subject,:assigned_to,:sdate,:edate,:status,:type,:location,:priority,:description,:date_created)",$inputs);								

				Alert("Save successful","success");
				redirect("events.php");
			}
			else{
				//Update
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$con->myQuery("UPDATE organizations SET org_name=:org_name,phone_num=:phone_num,email=:email,address=:address,industry=:industry,rating=:rating,org_type=:org_type,annual_revenue=:annual_revenue,assigned_to=:assigned_to,date_modified=:date_modified,description=:description WHERE id=:id",$inputs);
				Alert("Update successful","success");
				redirect("organizations.php");
			}
			
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>