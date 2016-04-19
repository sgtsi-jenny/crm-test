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
		

		$required_fieds=array(
			"email_username"=>"Enter Username. <br/>",
			"email_password"=>"Enter Password. <br/>",
			"email_host"=>"Enter Host. <br/>",
			"email_port"=>"Enter Port"
			);
		
		$errors="";

		foreach ($required_fieds as $key => $value) {
			if(empty($inputs[$key])){
				$errors.=$value;
			}else{
				#CUSTOM VALIDATION
			}
		}
		
		
		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			redirect("settings.php");
			die;
		}
		else{
			if(empty($inputs['time_in_module'])){
				$inputs['time_in_module']=0;
			}
			$inputs['email_password']=encryptIt($inputs['email_password']);
			
			$con->myQuery("UPDATE settings SET email_username=:email_username,email_password=:email_password,email_host=:email_host,email_port=:email_port,time_in_module=:time_in_module",$inputs);
			insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Modified settings.");
			Alert("Save succesful","success");
			redirect("settings.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>