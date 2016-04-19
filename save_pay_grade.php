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
			$errors.="Enter Pay Grade Name. <br/>";
		}


		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_pay_grade.php");
			}
			else{
				redirect("frm_pay_grade.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				
				$con->myQuery("INSERT INTO pay_grade(level) VALUES(:name)",$inputs);
			}
			else{
				//Update
				
				$con->myQuery("UPDATE pay_grade SET level=:name WHERE id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("pay_grade.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>