<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1))){
        redirect("index.php");
    }
	if(empty($_GET['id']) || empty($_GET['t'] || !is_numeric($_GET['id']))){
		redirect('index.php');
		die;
	}
	else
	{

		$table="";
		switch ($_GET['t']) {
			case 't':
				$table='trainings';
				$page='trainings.php';

				$audit_details=$con->myQuery("SELECT name FROM trainings WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['name']} from trainings.";

			break;
			case 'u':
				$table="users";
				$page="users.php";

				$audit_details=$con->myQuery("SELECT u.username as username FROM users u WHERE u.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['username']} from users.";

				break;
			case 'dep':
				$table="departments";
				$page="departments.php";

				$audit_details=$con->myQuery("SELECT name FROM departments WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['name']} from departments.";

				break;
			case 'ltyp':
				$table="leaves";
				$page="leave_type.php";

				$audit_details=$con->myQuery("SELECT name FROM leaves WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['name']} from leaves.";

				break;
			case 'skl':
				$table="skills";
				$page="skills.php";

				$audit_details=$con->myQuery("SELECT name FROM skills WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['name']} from skills.";				
				break;
			case 'educL':
				$table="education_level";
				$page="education_level.php";

				$audit_details=$con->myQuery("SELECT name FROM education_level WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['name']} from skills.";

				break;
			case 'estat':
				$table="employment_status";
				$page="employment_status.php";

				$audit_details=$con->myQuery("SELECT name FROM {$table} WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['name']} from employment status.";
				break;
			case 'jt':
				$table="job_title";
				$page="job_title.php";

				$audit_details=$con->myQuery("SELECT description FROM {$table} WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['description']} from job titles.";
				break;
			case 'cert':
				$table="certifications";
				$page="certifications.php";

				$audit_details=$con->myQuery("SELECT name FROM {$table} WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['name']} from certifications.";
				break;
			case 'taxS':
				$table="tax_status";
				$page="tax_status.php";

				$audit_details=$con->myQuery("SELECT description FROM {$table} WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['description']} from tax status.";
				break;
			case 'pg':
				$table="pay_grade";
				$page="pay_grade.php";

				$audit_details=$con->myQuery("SELECT level FROM {$table} WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['level']} from pay grade.";
				break;
			case 'e':
				$table="employees";
				$page="employees.php";

				$audit_details=$con->myQuery("SELECT CONCAT(first_name,' ',last_name) AS full_name FROM {$table} WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['full_name']} from employees.";

				break;
			case 'te':
				$table="employees";
				$page="terminated_employees.php";

				$audit_details=$con->myQuery("SELECT CONCAT(first_name,' ',last_name) AS full_name FROM {$table} WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted {$audit_details['full_name']} from terminated employees.";

				break;
			case 'ee':
				$table="employees_education";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";

				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,a.institute as name FROM {$table} a JOIN employees e ON e.id=a.employee_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (Education) {$audit_details['name']} from {$audit_details['full_name']}.";
				break;
			case 'es':
				$table="employees_skills";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";

				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,b.name FROM {$table} a JOIN employees e ON e.id=a.employee_id JOIN skills b ON b.id=a.skills_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (Skill) {$audit_details['name']} from {$audit_details['full_name']}.";
				break;
			case 'eeh':
				$table="employees_employment_history";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";

				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,a.company,a.position  FROM {$table} a JOIN employees e ON e.id=a.employee_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (Employment History) {$audit_details['position']} ({$audit_details['company']}) from {$audit_details['full_name']}.";
				break;
			case 'eec':
				$table="employees_emergency_contacts";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";

				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,CONCAT(a.first_name,' ',a.last_name) as contact FROM {$table} a JOIN employees e ON e.id=a.employee_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (Emergency Contact) {$audit_details['contact']} from {$audit_details['full_name']}.";
				break;
			case 'eal':
				$table="employees_available_leaves";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";

				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,b.name FROM {$table} a JOIN employees e ON e.id=a.employee_id JOIN leaves b ON b.id=a.leave_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (Leave) {$audit_details['name']} from {$audit_details['full_name']}.";

				break;
			case 'et':
				$table="employees_trainings";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";

				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,b.name FROM {$table} a JOIN employees e ON e.id=a.employee_id JOIN trainings b ON b.id=a.training_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (Training) {$audit_details['name']} from {$audit_details['full_name']}.";
				break;
			case 'ec':
				$table="employees_certifications";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";

				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,b.name FROM {$table} a JOIN employees e ON e.id=a.employee_id JOIN certifications b ON b.id=a.certification_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (Certifications) {$audit_details['name']} from {$audit_details['full_name']}.";
				break;
			case 'ef':
				$table="employees_files";
				$page="frm_employee.php?id={$_GET['e_id']}&tab={$_GET['tab']}";


				$audit_details=$con->myQuery("SELECT CONCAT(e.first_name,' ',e.last_name) AS full_name,a.file_name FROM {$table} a JOIN employees e ON e.id=a.employee_id WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted (File) {$audit_details['file_name']} from {$audit_details['full_name']}.";
				break;
			case 'cf':
				$table="company_files";
				$page="company_files.php";

				$audit_details=$con->myQuery("SELECT a.file_name FROM {$table} a  WHERE a.id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
				$audit_message="Deleted Company File {$audit_details['file_name']}.";
				
				break;
			default:
				redirect("index.php");
				break;
		}

		
		$con->myQuery("UPDATE {$table} SET is_deleted=1 WHERE id=?",array($_GET['id']));

		insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name'],$audit_message);
		Alert("Delete Successful.","success");
		redirect($page);

		die();

	}
?>