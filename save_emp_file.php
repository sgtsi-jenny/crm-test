<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

     if(empty($_POST['employee_id'])){
		Modal("Invalid Record Selected");
		redirect("employees.php");
	}

	if(!empty($_POST)){
		//Validate form inputs
		//$inputs=$_POST;
		// echo "<pre>";
		// print_r($inputs);
		// print_r($_FILES);
		// echo "</pre>";
		//die;
		if(empty($_FILES['file']['name'])){
			Alert("No file selected.","danger");
			redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}&ee_id={$inputs['id']}");
			die();
		}
		elseif($_FILES['file']['error']<>0){
			Alert("Invalid file selected.","danger");
			redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}&ee_id={$inputs['id']}");
			die;
		}


		try {  

		$con->beginTransaction();
		$inputs['employee_id']=$_POST['employee_id'];
		$inputs['file_name']=$_FILES['file']['name'];

		$con->myQuery("INSERT INTO employees_files(employee_id,file_name,date_modified) VALUES(:employee_id,:file_name,NOW())",$inputs);
		$file_id=$con->lastInsertId();

		$filename=$file_id.getFileExtension($_FILES['file']['name']);
		move_uploaded_file($_FILES['file']['tmp_name'],"emp_files/".$filename);
		$con->myQuery("UPDATE employees_files SET file_location=? WHERE id=?",array($filename,$file_id));

		$emp=getEmpDetails($inputs['employee_id']);
		insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Uploaded ({$inputs['file_name']}) to ({$emp['last_name']}, {$emp['first_name']} {$emp['middle_name']}).");

		Alert("File Added","success");
		$tab=7;

		$con->commit();
		redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}&ee_id={$inputs['id']}");
		die;
		} catch (Exception $e) {
		  $con->rollBack();
//		  echo "Failed: " . $e->getMessage();
		  Alert("Upload failed. Please try again.","danger");
		  redirect("frm_employee.php"."?id={$inputs['employee_id']}&tab={$tab}&ee_id={$inputs['id']}");
		  die;
		}
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>