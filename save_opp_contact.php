<?php
	require_once("support/config.php");
	// if(!isLoggedIn()){
	// 	toLogin();
	// 	die();
	// }

    // if(!AllowUser(array(1))){
 //        redirect("index.php");
 //    }

                                                       

		if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;	

        $required_fieds=array(
			"c_id"=>"Select Contact Name. <br/>"
			);
		$errors="";

		// foreach ($required_fieds as $key => $value) {
		// 	if(empty($inputs[$key])){
		// 		$errors.=$value;
		// 	}else{
		// 		#CUSTOM VALIDATION
		// 	}
		// }
		
		//die;
		

		if($errors!=""){
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['c_id'])){
				redirect("opportunities.php");
			}
			else{
				redirect("opportunities.php");
			}
			die;
		}
		else{
				
			//IF id exists update ELSE insert
			//if(empty($inputs['opp_con'])){
				//Insert
				unset($inputs['opp_con']);
				// //unset($inputs['prod_id']);
				// var_dump($errors);
				// var_dump($inputs['opp_con']);
				// var_dump($inputs);
				// die;
				$con->myQuery("INSERT INTO opp_contacts(c_id,opp_id) VALUES(:c_id,:opp_id)",$inputs);
				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;
				$inputs['notes']="Added a contact (".$inputs['name'].")";
				unset($inputs['c_id']);
				// var_dump($inputs);
				// die;
				$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, action_date) VALUES (:opp_id, :user, :notes, NOW())", $inputs);	
				
				Alert("Save successful","success");
				redirect("opp_contact_persons.php"."?id={$inputs['opp_id']}");

				
			//}
			//else{
				//Update
				
				// $con->myQuery("UPDATE opp_products SET prod_name=:prod_name,prod_price=:prod_price,commission_rate=:commission_rate WHERE id=:prod_id",$inputs);
				// Alert("Update successful","success");
			//}

			
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>