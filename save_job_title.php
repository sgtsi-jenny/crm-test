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

		$errors="";
		if (empty($inputs['name'])){
			$errors.="Enter Education Level. <br/>";
		}
		if (empty($inputs['description'])){
			$errors.="Enter Description. <br/>";
		}


		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_job_title.php");
			}
			else{
				redirect("frm_job_title.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				
				$con->myQuery("INSERT INTO job_title(code,description) VALUES(:name,:description)",$inputs);
			}
			else{
				//Update
				
				$con->myQuery("UPDATE job_title SET code=:name,description=:description WHERE id=:id",$inputs);
			}
			//die();
			Alert("Save succesful","success");
			redirect("job_title.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>