<?php
	session_start();
	date_default_timezone_set("Asia/Manila");
	define("WEBAPP", 'Customer Management System');
	//$_SESSION[WEBAPP]=array();
	// function __autoload($class)
	// {
	// 	require_once 'class.'.$class.'.php';
	// }
	function redirect($url)
	{
		header("location:".$url);
	}
	function getFileExtension($filename){
		return substr($filename, strrpos($filename,"."));
	}
// ENCRYPTOR
	function encryptIt( $q ) {
	    $cryptKey  = 'JPB0rGtIn5UB1xG03efyCp';
	    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );
	}
	function decryptIt( $q ) {
	    $cryptKey  = 'JPB0rGtIn5UB1xG03efyCp';
	    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	    return( $qDecoded );
	}
//End Encryptor
/* User FUNCTIONS */
	function isLoggedIn()
	{
		if(empty($_SESSION[WEBAPP]['user']))
		{
			return false;
		}
		else
		{
			return true; }
	}
	function toLogin($url=NULL)
	{
		if(empty($url))
		{
			Alert('Please Log in to Continue');
			header("location: frmlogin.php");
		}
		else{
			header("location: ".$url);
		}
	}
	function Login($user)
	{
		$_SESSION[WEBAPP]['user']=$user;
	}
/* End User FUnctions */
//HTML Helpers
	function makeHead($pageTitle=WEBAPP,$level=0)
	{
		require_once str_repeat('../',$level).'template/head.php';
		unset($pageTitle);
	}
	function makeFoot($pageTitle="Login",$level=0)
	{
		global $request_type;
		require_once 'template/foot.php';
		unset($pageTitle);
	}

	function makeOptions($array,$placeholder="",$val=NULL,$disable="",$checked_value=NULL){
		$options="";
		// if(!empty($placeholder)){
			$options.="<option value='{$val}'>{$placeholder}</option>";
		// }
		foreach ($array as $row) {
			list($value,$display) = array_values($row);
				if($checked_value!=NULL && $checked_value==$value){
					$options.="<option value='".htmlspecialchars($value)."' checked $disable>".htmlspecialchars($display)."</option>";
				}
				else
				{
					$options.="<option value='".htmlspecialchars($value)."' $disable>".htmlspecialchars($display)."</option>";
				}
		}
		return $options;
	}

//END HTML Helpers
/* BOOTSTRAP Helpers */
	function Modal($content=NULL,$title="Alert")
	{
		if(!empty($content))
		{
			$_SESSION[WEBAPP]['Modal']=array("Content"=>$content,"Title"=>$title);
		}
		else
		{
			if(!empty($_SESSION[WEBAPP]['Modal']))
			{
				include_once 'template/modal.php';
				unset($_SESSION[WEBAPP]['Modal']);
			}
		}
	}
	function Alert($content=NULL,$type="info")
	{
		if(!empty($content))
		{
			$_SESSION[WEBAPP]['Alert']=array("Content"=>$content,"Type"=>$type);
		}
		else
		{
			if(!empty($_SESSION[WEBAPP]['Alert']))
			{
				include_once 'template/alert.php';
				unset($_SESSION[WEBAPP]['Alert']);
			}
		}
	}
	function createAlert($content='',$type='info')
	{
		echo "<div class='alert alert-{$type}' role='alert'>{$content}</div>";
	}
/* End BOOTSTRAP Helpers */

/* SPECIFIC TO WEBAPP */
function getDepriciationDate($purchase_date,$terms){
	$purchase_date=new DateTime($purchase_date);
	$diff_terms=new DateInterval("P{$terms}M");
	return date_format(date_add($purchase_date,$diff_terms),'Y-m-d');
}

function AllowUser($user_type_id){
	if(array_search($_SESSION[WEBAPP]['user']['user_type'], $user_type_id)!==FALSE){
		return true;
	}
	return false;
}

function insertAuditLog($user,$action){
	#user,action,date
	if(file_exists("./audit_log.txt")){
		$user=htmlspecialchars($user);
		$action=htmlspecialchars($action);
		$new_input=json_encode(array($user,$action,date('Y-m-d H:i:s')),JSON_PRETTY_PRINT);
		$file = fopen("./audit_log.txt", "r+");
		fseek($file,-4,SEEK_END);
		fwrite($file, ",".$new_input."\n\t]\n}");
		fclose($file);
	}
	else{
		$file = fopen("./audit_log.txt", "w+");

		$data=json_encode(array("data"=>array(array("NONE","INITIAL START UP",date('Y-m-d H:i:s')))),JSON_PRETTY_PRINT);
		fwrite($file, $data);
		fclose($file);
	}
}

function emailer($username,$password,$from,$to,$subject,$body,$host='tls://smtp.gmail.com',$port=465){
	require_once "Mail.php"; 
	$headers=array(
			'From'=>$from,
			'To'=>$to,
			'Subject'=>$subject,
			'MIME-Version' => 1,
    		'Content-type' => 'text/html;charset=iso-8859-1'
		);
	$smtp=Mail::factory('smtp', array(
	        'host' => $host,
	        'port' => $port,
	        'auth' => true,
	        'username' => $username,
	        'password' => $password
	    ));
	//echo $to;
	$mail = $smtp->send($to, $headers, $body);
	if (PEAR::isError($mail)) {
		
		var_dump( $mail->getMessage());
	    return false;
	} else {
	    return true;
	}
	// $from = 'jpbalderas17@gmail.com';
	// $to = 'johnpaul.balderas@sparkglobaltech.com';
	// $subject = 'Hi!';
	// $body = "Hi,\n\nHow are you?";
	// $headers = array(
	//     'From' => $from,
	//     'To' => $to,
	//     'Subject' => $subject
	// );
	// $smtp = Mail::factory('smtp', array(
	//         'host' => 'tls://smtp.gmail.com',
	//         'port' => 465,
	//         'auth' => true,
	//         'username' => 'johnpaul.balderas@sparkglobaltech.com',
	//         'password' => 'Sp@rk1234'
	//     ));
	// $mail = $smtp->send($to, $headers, $body);
	// if (PEAR::isError($mail)) {
	//     echo('<p>' . $mail->getMessage() . '</p>');
	// } else {
	//     echo('<p>Message successfully sent!</p>');
	// }
}
function getEmpDetails($emp_id){
	global $con;
	return $con->myQuery("SELECT * FROM employees WHERE id=? LIMIT 1",array($emp_id))->fetch(PDO::FETCH_ASSOC);
}
function getEmailSettings(){
	global $con;
	return $con->myQuery("SELECT email_username as username,email_password as password,email_host as host,email_port as port FROM settings  LIMIT 1")->fetch(PDO::FETCH_ASSOC);
}
function email_template($header,$message){
return <<<html
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Spark Global Tech Systems Inc. HRIS</title>


<style type="text/css">
img {
max-width: 100%;
}
body {
-webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
}
body {
background-color: #f6f6f6;
}
@media only screen and (max-width: 640px) {
  body {
    padding: 0 !important;
  }
  h1 {
    font-weight: 800 !important; margin: 20px 0 5px !important;
  }
  h2 {
    font-weight: 800 !important; margin: 20px 0 5px !important;
  }
  h3 {
    font-weight: 800 !important; margin: 20px 0 5px !important;
  }
  h4 {
    font-weight: 800 !important; margin: 20px 0 5px !important;
  }
  h1 {
    font-size: 22px !important;
  }
  h2 {
    font-size: 18px !important;
  }
  h3 {
    font-size: 16px !important;
  }
  .container {
    padding: 0 !important; width: 100% !important;
  }
  .content {
    padding: 0 !important;
  }
  .content-wrap {
    padding: 10px !important;
  }
  .invoice {
    width: 100% !important;
  }
}
</style>
</head>

<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">

<table class="body-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
		<td class="container" width="600" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
			<div class="content" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
				<table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="alert alert-warning" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #348EDA; margin: 0; padding: 20px;" align="center" bgcolor="#348EDA" valign="top">
							{$header}
						</td>
					</tr><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
							<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
										{$message}
									</td>
								</tr><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
										
									</td>
								</tr>
								<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
										Thank you for choosing Spark Global Tech HRIS.
									</td>
								</tr>
								</table>
								</td>
					</tr></table><div class="footer" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
		</td>
		<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
	</tr></table></body>
</html>
html;
}

/* END SPECIFIC TO WEBAPP */
	require_once('class.myPDO.php');
	$con=new myPDO('customer_relation_management','root','');	
?>