<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	if(empty($_GET['id']) || empty($_GET['t'] || !is_numeric($_GET['id']))){
		redirect('index.php');
		die;
	}
	else
	{

		$table="";
		switch ($_GET['t']) {
			case 't':
				$table='trainings';
				$page='trainings.php';
			break;
			case 'u':
				$table="users";
				$page="users.php";
				break;
			case 'dep':
				$table="departments";
				$page="departments.php";
				break;
			case 'ltyp':
				$table="leave_type";
				$page="leave_type.php";
				break;
			case 'skl':
				$table="skills";
				$page="skills.php";
				break;
			case 'educL':
				$table="education_level";
				$page="education_level.php";
				break;
			case 'estat':
				$table="employment_status";
				$page="employment_status.php";
				break;
			default:
				redirect("index.php");
				break;
		}
		$con->myQuery("UPDATE {$table} SET is_deleted=1 WHERE id=?",array($_GET['id']));
		Alert("Delete Successful.","success");
		redirect($page);

		die();

	}
?>