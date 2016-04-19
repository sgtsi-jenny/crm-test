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
		if (empty($inputs['approver_id'])){
			$errors.="Select Departent Approver. <br/>";
		}


		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_departments.php");
			}
			else{
				redirect("frm_departments.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				
				$con->myQuery("INSERT INTO departments(name,description,parent_id,approver_id) VALUES(:name,:description,:parent_id,:approver_id)",$inputs);
			}
			else{
				//Update
				///var_dump($inputs);

				$con->myQuery("UPDATE departments SET name=:name,description=:description,parent_id=:parent_id,approver_id=:approver_id WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("departments.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>