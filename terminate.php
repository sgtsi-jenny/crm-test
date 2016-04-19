<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1))){
        redirect("index.php");
    }
	if(empty($_GET['id'])){
		redirect('index.php');
		die;
	}
	else{
		$con->myQuery("UPDATE employees SET is_terminated=1,termination_date=NOW() WHERE id=?",array($_GET['id']));
		// die;
		$employees=getEmpDetails($_GET['id']);
		insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Terminated employee {$employees['first_name']} {$employees['last_name']}.");

		Alert("Employee Terminated.");
		redirect("employees.php");
	}
?>