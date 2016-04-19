<?php
	require_once 'support/config.php';
	if(!empty($_POST)){

		$user=$con->myQuery("SELECT users.id,users.user_type_id as 'user_type',users.first_name,users.middle_name,users.last_name FROM `users` WHERE username=? AND password=? AND users.is_deleted=0 LIMIT 1",array($_POST['username'],encryptIt($_POST['password'])))->fetch(PDO::FETCH_ASSOC);
		if(empty($user)){
			Alert("Invalid Username/Password","danger");
			redirect('frmlogin.php');
		}
		else{
			$_SESSION[WEBAPP]['user']=$user;
			redirect("index.php");	
		}
		die;
	}
	else{
		Modal("Invalid Username/Password");
		redirect('frmlogin.php');
		die();
	}
	redirect('frmlogin.php');
?>