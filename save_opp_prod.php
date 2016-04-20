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
		

		$errors="";
		if (empty($inputs['prod_price'])){
			$errors.="Enter product price. <br/>";
		}
		//var_dump($inputs);
		//die;
		

		if($errors!=""){

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['prod_id'])){
				redirect("opportunities.php");
			}
			else{
				redirect("opportunities.php");
			}
			die;
		}
		else{

			
			//IF id exists update ELSE insert
			if(empty($inputs['opp_prod'])){
				//Insert
				unset($inputs['opp_prod']);
				//unset($inputs['prod_id']);
				//$inputs['prod_based_price'];
				
				$opp_inputs['assigned_to']=$inputs['assigned_to'];
				//unset($inputs['assigned_to']);
				//var_dump($opp_inputs['assigned_to']);
				//var_dump($inputs);
				//die;

				$con->myQuery("INSERT INTO opp_products(prod_id,opp_id,prod_name,prod_based_price,prod_price,commission_rate,expected_close_date,assigned_to) VALUES(:prod_id,:opp_id,:prod_name,:prod_based_price,:prod_price,:commission_rate,:expected_close_date,:assigned_to)",$inputs);
				
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				unset($inputs['prod_price']);
				unset($inputs['commission_rate']);
				$opp_inputs['subject']=$inputs['prod_name'];
				$opp_inputs['expected_close_date']=$inputs['expected_close_date'];
				$opp_inputs['date_created']=$now->format('Y-m-d H:i:s a');
				$opp_inputs['activity_type']=7;
				$opp_inputs['allDay']='true';
				$opp_inputs['opp_id']=$inputs['opp_id'];
				//var_dump($opp_inputs);
				//die;
				$con->myQuery("INSERT INTO events(subject,assigned_to,start_date,end_date,activity_type,date_created,allDay,opp_id) VALUES(:subject,:assigned_to,:expected_close_date,:expected_close_date,:activity_type,:date_created,:allDay,:opp_id)",$opp_inputs);	



				$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['user']=$userid;
				$inputs['notes']="Added a product (".$inputs['prod_name'].")";
				$inputs['opp_id'];
				$inputs['assigned_to']=$_SESSION[WEBAPP]['user']['id'];
					unset($inputs['subject']);
					unset($inputs['date_modified']);
					unset($inputs['description']);					
					unset($inputs['title']);
					unset($inputs['subject']);
					unset($inputs['expected_close_date']);
					unset($inputs['date_created']);
					unset($inputs['activity_type']);
					unset($inputs['allDay']);
					unset($inputs['prod_id']);
					unset($inputs['prod_name']);
					unset($inputs['prod_based_price']);
								


				// var_dump($inputs);
				// die;				

				$con->myQuery("INSERT INTO activities(opp_id, user_id, notes, action_date) VALUES (:opp_id, :user, :notes, NOW())", $inputs);							

				Alert("Save successful","success");

				redirect("opp_prods.php"."?id={$inputs['opp_id']}");

				
			}
			else{
				//Update
				//var_dump($inputs['opp_prod']);
				//unset($inputs['prod_price']);
				// var_dump($inputs);
				// die;
				$inputs['assigned_to']=$_SESSION[WEBAPP]['user']['id'];

				$con->myQuery("UPDATE opp_products SET prod_id=:prod_id,opp_id=:opp_id,prod_name=:prod_name,prod_based_price=:prod_based_price,prod_price=:prod_price,commission_rate=:commission_rate,expected_close_date=:expected_close_date,assigned_to=:assigned_to WHERE id=:opp_prod",$inputs);
				Alert("Update successful","success");

				redirect("opp_prods.php"."?id={$inputs['opp_id']}");
			}

			
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>