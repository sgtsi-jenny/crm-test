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
		//var_dump("".$inputs['opp_event']);
		
		$errors="";

		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['event_id'])){
				redirect("frm_event.php");
			}
			else{
				redirect("frm_event.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			
			if(empty($inputs['opp_event'])){

				//Insert
				unset($inputs['opp_event']);
				// var_dump($inputs);
				// die;
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();

				//$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs['date_created']=$now->format('Y-m-d H:i:s a');
				$inputs['sdate']=$inputs['start_date'].' '.$inputs['start_time'].':00';
				$inputs['edate']=$inputs['end_date'].' '.$inputs['end_time'].':00';
				$inputs['allDay']='false';
				$inputs['assigned_to']=$_SESSION[WEBAPP]['user']['id'];

				// var_dump($inputs);
				// die;
				unset($inputs['start_date']);
				unset($inputs['start_time']);
				unset($inputs['end_date']);
				unset($inputs['end_time']);

				//unset($inputs['opp_id']);
				//var_dump($inputs['sdate']);
				//var_dump($inputs['edate']);
				// var_dump($inputs);
				// die;

				$con->myQuery("INSERT INTO events(subject,assigned_to,start_date,end_date,event_stat,activity_type,date_created,allDay,opp_id) VALUES(:subject,:assigned_to,:sdate,:edate,:status,:type,:date_created,:allDay,:event_id)",$inputs);								

				Alert("Save successful","success");
				redirect("opp_events.php"."?id={$inputs['event_id']}");
			}
			else{
				
				//Update
				//var_dump("UPDATE");
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_modified']=$now->format('Y-m-d H:i:s a');

				$inputs['sdate']=$inputs['start_date'].' '.$inputs['start_time'].':00';
				$inputs['edate']=$inputs['end_date'].' '.$inputs['end_time'].':00';
				$inputs['allDay']='false';
				//$inputs['is_related']=$inputs['opp_id'];

				unset($inputs['start_date']);
				unset($inputs['start_time']);
				unset($inputs['end_date']);
				unset($inputs['end_time']);
				$inputs['assigned_to']=$_SESSION[WEBAPP]['user']['id'];

				// var_dump($inputs);
				// die;

				$con->myQuery("UPDATE events SET subject=:subject,assigned_to=:assigned_to,start_date=:sdate,end_date=:edate,event_stat=:status,activity_type=:type,date_modified=:date_modified,allDay=:allDay,opp_id=:event_id WHERE id=:opp_event",$inputs);
				Alert("Update successful","success");
				redirect("opp_events.php"."?id={$inputs['event_id']}");
			}
			
		}
		//redirect("opp_events.php"."?id={$inputs['opp_id']}");
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>