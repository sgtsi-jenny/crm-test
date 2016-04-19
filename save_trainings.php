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
			$errors.="Enter Training Name. <br/>";
		}

		if (empty($inputs['location'])){
			$errors.="Enter Training Location. <br/>";
		}

		if (empty($inputs['topic'])){
			$errors.="Enter Training Topic . <br/>";
		}

		if (empty($inputs['training_date'])){
			$errors.="Enter Date of training . <br/>";
		}



		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_trainings.php");
			}
			else{
				redirect("frm_trainings.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				
				$con->myQuery("INSERT INTO trainings(name,location,topic,training_date,bond_months) VALUES(:name,:location,:topic,:training_date,:bond_months)",$inputs);
			}
			else{
				//Update
				
				$con->myQuery("UPDATE trainings SET name=:name,location=:location,topic=:topic,training_date=:training_date,bond_months=:bond_months WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("trainings.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>