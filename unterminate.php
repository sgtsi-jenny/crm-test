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
		$con->myQuery("UPDATE employees SET is_terminated=0,termination_date='0000-00-00' WHERE id=?",array($_GET['id']));

		$employees=getEmpDetails($_GET['id']);
		insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],"Restored terminated employee {$employees['first_name']} {$employees['last_name']}.");
		// die;
		Alert("Employee Restored.");
		redirect("terminated_employees.php");
	}
?>