<?php
	require_once("support/config.php");
		if(!isLoggedIn()){
			toLogin();
			die();
		}

	if(!empty($_GET['id'] && !empty($_GET['type']))){
		if(is_numeric($_GET['id'])){
			switch ($_GET['type']) {
				case 'e':
					$sql="SELECT file_name,file_location FROM employees_files WHERE id=? AND is_deleted=0";
					if(AllowUser(array(1))){
						$file=$con->myQuery($sql,array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
					}
					else{
						$sql.=" AND employee_id=?";
						$file=$con->myQuery($sql,array($_GET['id'],$_SESSION[WEBAPP]['user']['employee_id']))->fetch(PDO::FETCH_ASSOC);
					}
					//var_dump($file);
					if(empty($file)){
						redirect('index.php');
						die;
					}
					$location="emp_files/";
					//die;
					//$fp = fopen($location.$file['file_location'], 'rb');
					 header("Content-Type: application/octet-stream");
					header("Content-Disposition: attachment; filename=".$file['file_name']);
					header("Content-Length: " . filesize($location.$file['file_location']));
					readfile($location.$file['file_location']);

					break;
				case 'c':
					# code...
					$sql="SELECT file_name,file_location FROM company_files WHERE id=? AND is_deleted=0";
					$file=$con->myQuery($sql,array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
					if(empty($file)){
						die;
					}
					$location="comp_files/";
					//die;
					//$fp = fopen($location.$file['file_location'], 'rb');
					 header("Content-Type: application/octet-stream");
					header("Content-Disposition: attachment; filename=".$file['file_name']);
					header("Content-Length: " . filesize($location.$file['file_location']));
					readfile($location.$file['file_location']);
					break;
				default:
					# code...
					break;
			}
		}
	}
?>