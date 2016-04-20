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

		$errors="";
		if (empty($inputs['assigned_to'])){
			//$errors.="Assigned To <br/>";
		}
		if (empty($inputs['fname'])){
			$errors.="First Name. <br/>";
		}
		if (empty($inputs['lname'])){
			$errors.="Last Name <br/>";
		}
		if (empty($inputs['department_id'])){
			$errors.="Department <br/>";
		}
		if (empty($inputs['mobile_phone'])){
			$errors.="Mobile Phone. <br/>";
		}
		if (empty($inputs['email'])){
			$errors.="Email Address. <br/>";
		}
		if (empty($inputs['dob'])){
			$errors.="Date of Birth. <br/>";
		}
		if (empty($inputs['description'])){
			
		}
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
     		$errors.="Enter a valid email address. <br/>";
		}
		$test_arr  = explode('-', $inputs['dob']);
		if (count($test_arr) == 3) 
		{
    		if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) 
    		{
        	// valid date ...
    			
    		} 
    		else 
    		{
        // problem with dates ...
    			$errors.= 'Enter a valid date2. <br/>'.$inputs['dob'].$test_arr;
    		}
		} 
		else 
		{
    	// problem with input ...
			$errors.= 'Enter a valid date3. <br/>'.$inputs['dob'].$test_arr;
		}
		//$date = DateTime::createFromFormat('m/d/Y', $inputs['dob']);
		//$date_errors = DateTime::getLastErrors();
		//if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
			//$errors. = $date.'<br/>';
    		//$errors.= 'Enter a valid date.'.$inputs['dob'].' <br/>';
    		//$errors = $date_errors['error_count'].'<br/>';
    		//$errors = $date_errors['warning_count'].'<br/>';
		//}

		if($errors!=""){

			Alert("Notice: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_contacts.php");
			}
			else{
				redirect("frm_contacts.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				$inputs=$_POST;
				
				//$inputs['name']=$_POST['name'];
				unset($inputs['id']);
				$inputs['pangalan']=$inputs["fname"]." ".$inputs["lname"]."'s Birthday";
				// var_dump($inputs['pangalan']);
				// die;

				if(0 == filesize($_FILES['file']['tmp_name'])){
					$name="Default.jpg";
				}
				else{
				$file_id=$_POST['lname']. ", " . $_POST['fname'];

				$name=$file_id.getFileExtension($_FILES['file']['name']);
				//$tmp_name = $_FILES['file']['tmp_name'];
				
				move_uploaded_file($_FILES['file']['tmp_name'],"uploads/".$name);
				}

				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['date_created']=$now->format('Y-m-d H:i:s a');
				$inputs['dob']=strtotime($inputs['dob']);
				$inputs['mon']=date('m', $inputs['dob']);
				$inputs['day']=date('d', $inputs['dob']);

				//$inputs['bday']=$inputs['dob'];
				
				$inputs['mon']=date('m', $inputs['dob']);
				$inputs['day']=date('d', $inputs['dob']);
				$inputs['yr']=date("Y");
				$inputs['bday']=$inputs['yr'].'-'.$inputs['mon'].'-'.$inputs['day'];
				$inputs['activity_type']=7;
				$inputs['allDay']=true;

				
				unset($inputs['fname']);
				unset($inputs['lname']);
				unset($inputs['department_id']);
				unset($inputs['email']);
				unset($inputs['office_phone']);
				unset($inputs['home_phone']);
				unset($inputs['mobile_phone']);
				unset($inputs['profile_pic']);
				unset($inputs['dob']);
				unset($inputs['mon']);
				unset($inputs['day']);
				unset($inputs['yr']);
				unset($inputs['org_id']);


				var_dump($inputs);
				die;


				$con->myQuery("INSERT INTO contacts(fname, lname, office_phone, home_phone, mobile_phone, org_id, department_id, email, dob, assigned_to, description, profile_pic) VALUES (:fname,:lname,:office_phone,:home_phone,:mobile_phone,:org_id,:department_id,:email,:dob,:assigned_to,:description, '$name')", $inputs);	
				
				$con->myQuery("INSERT INTO events(subject,assigned_to,start_date,end_date,activity_type,description,date_created,allDay) VALUES(:subject,:assigned_to,:bday,:bday,:activity_type,:description,:date_created,:allDay)",$opp_inputs);		

				//$file_id=$con->lastInsertId();
				//$filename=$file_id.getFileExtension($_FILES['file']);
				//move_uploaded_file($_FILES['file']['tmp_name'],"uploads/".$filename);
				//$con->myQuery("UPDATE contacts SET profile_pic=? WHERE id=?",array($filename,$file_id));
					//$activity_input['admin_id']=$_SESSION[WEBAPP]['user']['id'];
					//$activity_input['user_id']=$_SESSION[WEBAPP]['user']['id'];
					//$activity_input['category_type_id']=2;
					//$activity_input['notes']="Quantity (".$inputs['quantity'].")";
					//$activity_input['item_id']=$con->lastInsertId();
				

				//$con->myQuery("INSERT INTO activities(admin_id,user_id,action,action_date,category_type_id,item_id,notes) VALUES(:admin_id,:user_id,'Consumable Created',NOW(),:category_type_id,:item_id,:notes)",$activity_input);
				
				
				$testing = error_reporting(E_ALL);
				Alert("Save succesful","success");
				
			}
			else{
				//Update
				//date_default_timezone_set('Asia/Manila');
				//$now = new DateTime();
				//$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs=$_POST;
				
				if(0 == filesize($_FILES['file']['tmp_name'])){
					$con->myQuery("UPDATE contacts SET fname=:fname,lname=:lname,office_phone=:office_phone,mobile_phone=:mobile_phone,home_phone=:home_phone,org_id=:org_id,department_id=:department_id, email=:email, dob=:dob, assigned_to=:assigned_to, description=:description WHERE id=:id",$inputs);
				}
				else{
				$file_id=$_POST['lname']. ", " . $_POST['fname'];

				$name=$file_id.getFileExtension($_FILES['file']['name']);
				//$tmp_name = $_FILES['file']['tmp_name'];
				
				move_uploaded_file($_FILES['file']['tmp_name'],"uploads/".$name);
				
				$con->myQuery("UPDATE contacts SET fname=:fname,lname=:lname,office_phone=:office_phone,mobile_phone=:mobile_phone,home_phone=:home_phone,org_id=:org_id,department_id=:department_id, email=:email, dob=:dob, assigned_to=:assigned_to, description=:description, profile_pic='$name' WHERE id=:id",$inputs);
				}
					
				
				
				Alert("Update successful","success");
			}

			
			redirect("contacts.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>