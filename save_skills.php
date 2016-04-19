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
			$errors.="Enter Skill Name. <br/>";
		}


		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_skills.php");
			}
			else{
				redirect("frm_skills.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				
				$con->myQuery("INSERT INTO skills(name) VALUES(:name)",$inputs);
			}
			else{
				//Update
				
				$con->myQuery("UPDATE skills SET name=:name WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("skills.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>