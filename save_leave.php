<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }
     if(!AllowUser(array(1))){
         redirect("index.php");
     }
		if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		// echo "<pre>";
		// print_r($inputs);
		// echo "</pre>";
		// die;
		$required_fieds=array();
		if(empty($inputs['id'])){
			$required_fieds=array(
				"leave_id"=>"Select Leave. <br/>"
				);
		}
		if(empty($inputs['employee_id'])){
			Modal("Invalid Record Selected");
			redirect("employees.php");
		}
		$errors="";


/*		$leave_avail=$con->myQuery("SELECT leave_id,
											DATE_FORMAT(date_added,'%Y') AS date_added
									FROM employees_available_leaves 
									WHERE employee_id=? AND is_cancelled=0 AND is_deleted=0",array($inputs['id']));

		$count=count(leave_avail);

		echo $count;
		die();

*/
		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}else{
				#CUSTOM VALIDATION
				if($key=="leave_id"){
					if($inputs[$key]=="NULL"){
						$errors.=$value;
					}
				}
			}
		}
		$tab=8;
		
		if($errors!=""){
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}");
			}
			else{
				redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}&ee_id={$inputs['id']}");
			}
			die;
		}
		else{
			 //echo $inputs['id'];
			// print_r($inputs);
			// echo "</pre>";
			//die;
			//IF id exists update ELSE insert
			$ur="";
			if(empty($inputs['id']))
			{
				//Insert
				unset($inputs['id']);				
				$params = array(
						'employee_id' => $inputs['employee_id'],
						'leave_id' => $inputs['leave_id'],
						'balance_per_year' => $inputs['balance_per_year'] 
						);

				$con->myQuery("INSERT INTO employees_available_leaves(
							employee_id,
							leave_id,
							total_leave,
							balance_per_year,
							date_added
							) VALUES(
							:employee_id,
							:leave_id,
							:balance_per_year,
							:balance_per_year,
							CURDATE()
							)",$params);

			}
			else
			{
				$ur = $inputs['ur'];
				//die();
				#IF RESET
				if($ur==1)
				{
					if($inputs['date_added']==date('Y'))
					{
						//echo 'still in the current year';
						//die();
						Alert("You have the following errors: <br/> Unable to Reset.","danger");
						redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}");	
						die();
					}else
					{
						$con->myQuery("INSERT INTO employees_available_leaves(
							employee_id,
							leave_id,
							total_leave,
							balance_per_year,
							date_added
							) VALUES(?,?,?,?,
							CURDATE()
							)",array($inputs['employee_id'],$inputs['l_id'],$inputs['balance_per_year'],$inputs['balance_per_year']));  
						//die();	
						//echo 'proceed';
						$con->myQuery("UPDATE employees_available_leaves SET
						is_cancelled=1
						WHERE id=?",
						array($inputs['id']));
					}
					
				}else
				{
					//Update
					$new_total_bal = $inputs['balance_per_year']-$inputs['total'];
					$new_avail = $inputs['balance']+$new_total_bal;
					//echo $new_avail;
					//die();

					if ($new_avail<0) {
						Alert("You have the following errors: <br/> Unable to Save.","danger");
						redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}");	
						die();
					}
					else
					{
						$params = array(
							'id' => $inputs['id'],
							'employee_id' => $inputs['employee_id'],
							'balance_per_year' => $inputs['balance_per_year'],
							'new_avail' => $new_avail 
							);
						//var_dump($params);
						//die();
						$con->myQuery("UPDATE employees_available_leaves SET
							employee_id=:employee_id,
							total_leave=:balance_per_year,
							balance_per_year=:new_avail
							WHERE id=:id
							",$params);
					}
				}
			}
			
			Alert("Save succesful","success");
			redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}");
			die;
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>