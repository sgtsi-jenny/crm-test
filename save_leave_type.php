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
			$errors.="Enter Leave Type. <br/>";
		}
		// if (empty($inputs['min_leave_bal'])){
		// 	$errors.="Enter Maximum Leave per Year. <br/>";
		// }

	//	$chk_is_accrued =1; //(empty($inputs['is_accrue']))?0:1;
	//	$chk_is_carried =1; //(empty($inputs['is_carried_forward']))?0:1;
		// var_dump($_POST);
		if (!empty($inputs['is_accrue']) && $inputs['is_accrue']=="Yes") {
			$inputs['is_accrue']=1;
		}else{
			$inputs['is_accrue']=0;
		}
		if (!empty($inputs['is_carried_forward']) &&  $inputs['is_carried_forward']="Yes") {
			$inputs['is_carried_forward']=1;
		}else{
			$inputs['is_carried_forward']=0;
		}
		if (!empty($inputs['with_pay']) &&  $inputs['with_pay']="Yes") {
			$inputs['with_pay']=1;
		}else{
			$inputs['with_pay']=0;
		}

		if($errors!=""){
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_leave_type.php");
			}
			else{
				redirect("frm_leave_type.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			unset($inputs['with_pay']);
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
			// var_dump($inputs);
				
				// $con->myQuery("INSERT INTO 
				// 	leaves(
				// 		name,min_leave_balance,max_leave_balance,is_accrue,is_carried_forward,max_credit_amount) 
				// 	VALUES(
				// 		:name,:min_leave_bal,:max_leave_bal,:is_accrue,:is_carried_forward,:max_credit_amount)",$inputs);

				$con->myQuery("INSERT INTO 
						leaves(
							name,is_accrue,is_carried_forward) 
						VALUES(
							:name,:is_accrue,:is_carried_forward)",$inputs);
			}
			else{
				//Update
				
				// $con->myQuery("UPDATE leaves 
				// 	SET 
				// 		name=:name,
				// 		min_leave_balance=:min_leave_bal,
				// 		max_leave_balance=:max_leave_bal,
				// 		is_accrue=:is_accrue,
				// 		is_carried_forward=:is_carried_forward,
				// 		max_credit_amount=:max_credit_amount
				// 	WHERE id=:id",$inputs);

				$con->myQuery("UPDATE leaves 
				 	SET 
				 		name=:name,
				 		is_accrue=:is_accrue,
				 		is_carried_forward=:is_carried_forward

				 	WHERE id=:id",$inputs);
			}
			// die;
			Alert("Save succesful".$input['is_accrue'],"success");
			redirect("leave_type.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>