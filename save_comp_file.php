<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }



		//Validate form inputs
		//$inputs=$_POST;
		// echo "<pre>";
		// print_r($inputs);
		// print_r($_FILES);
		// echo "</pre>";
		//die;
		if(empty($_FILES['file']['name'])){
			Alert("No file selected.","danger");
			redirect("company_files.php");
			die();
		}
		elseif($_FILES['file']['error']<>0){
			Alert("Invalid file selected.","danger");
			redirect("company_files.php");
			die;
		}


		try {  

		$con->beginTransaction();
		$inputs['file_name']=$_FILES['file']['name'];

		$con->myQuery("INSERT INTO company_files(file_name,date_modified) VALUES(:file_name,NOW())",$inputs);
		$file_id=$con->lastInsertId();

		$filename=$file_id.getFileExtension($_FILES['file']['name']);
		move_uploaded_file($_FILES['file']['tmp_name'],"comp_files/".$filename);
		$con->myQuery("UPDATE company_files SET file_location=? WHERE id=?",array($filename,$file_id));
		Alert("File Added","success");
		
		
		insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Uploaded ({$inputs['file_name']}) to company files.");


		$con->commit();
		redirect("company_files.php");
		die;
		} catch (Exception $e) {
		  $con->rollBack();
//		  echo "Failed: " . $e->getMessage();
		  Alert("Upload failed. Please try again.","danger");
		  redirect("company_files.php");
		  die;
		}
	
	// redirect('index.php');
?>