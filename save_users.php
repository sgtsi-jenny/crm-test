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

		$employee_user=$con->myQuery("SELECT * FROM users WHERE is_deleted=0 and employee_id=?",array($inputs['emp_id']));
		$uname=$con->myQuery("SELECT id,lcase(username) FROM users WHERE is_deleted=0 and username=?",array(strtolower($inputs['username'])));

		$errors="";

		if (empty($inputs['username'])){
			$errors.="Enter Username. <br/>";
		}
		if (empty($inputs['password'])){
			$errors.="Enter Password. <br/>";
		}
		if (empty($inputs['utype_id'])){
			$errors.="Select User Type. <br/>";
		}

		if(empty($inputs['get_id'])){
			if (empty($inputs['emp_id'])){
				$errors.="Select Employee. <br/>";
			}
			if ($employee_user->fetchcolumn() > 0) {
				$errors.="Selected Employee already has an Account. <br />";
			}
			if ($uname->fetchcolumn() > 0) {
				$errors.="Username is not Available. Please enter another one. <br />";
			}
		}

//			}else{
//				if($key=='basic_salary'){
//					if(!is_numeric($inputs[$key])){
//						$errors.="Invalid Basic Salary. <br/>";
//					}
//				}
//				elseif ($key=='code') {
//					if(!empty($inputs['id'])){
//						$count=$con->myQuery("SELECT COUNT(id) FROM employees WHERE code=? AND id <> ? AND is_deleted=0",array($inputs['code'],$inputs['id']))->fetchColumn();
//					}
//					else{						
//						$count=$con->myQuery("SELECT COUNT(id) FROM employees WHERE code=? AND is_deleted=0",array($inputs['code']))->fetchColumn();
//					}
//					if(!empty($count)){
//						$errors.="Employee Code already exists. <br/>";
//					}
//				}
//			}

		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_users.php");
			}
			else{
				redirect("frm_users.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			unset($inputs['get_id']);
			//IF id exists update ELSE insert
			$inputs['password']=encryptIt($inputs['password']);
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);

					
				$con->myQuery("INSERT INTO users(employee_id,username,password,user_type_id) VALUES(:emp_id,:username,:password,:utype_id)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE users SET username=:username,password=:password,user_type_id=:utype_id WHERE id=:id",$inputs);
			} 

			//die;
			Alert("Save succesful","success");
			redirect("users.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>