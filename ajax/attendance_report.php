<?php
require_once("../support/config.php"); 

if(!AllowUser(array(1,2))){
        redirect("index.php");
    }


$employees=array();
//var_dump($_GET);
if(!empty($_GET['employees_id'])){
    if($_GET['employees_id']=='NULL' && AllowUser(array(1))){
        $employees=$con->myQuery("SELECT id,code,CONCAT(last_name,', ',first_name,' ',middle_name) as employee FROM employees WHERE is_deleted=0 AND is_terminated=0")->fetchAll(PDO::FETCH_ASSOC);
    }else{
        if(AllowUser(array(1))){
            if(is_numeric($_GET['employees_id'])){
            $employees=$con->myQuery("SELECT id,code,CONCAT(last_name,', ',first_name,' ',middle_name) as employee FROM employees WHERE is_deleted=0 AND is_terminated=0 AND id=?",array($_GET['employees_id']))->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        else{
            $employees=$con->myQuery("SELECT id,code,CONCAT(last_name,', ',first_name,' ',middle_name) as employee FROM employees WHERE is_deleted=0 AND is_terminated=0 AND id=?",array($_SESSION[WEBAPP]['user']['employee_id']))->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
else{
		if(AllowUser(array(1))){
			$employees=$con->myQuery("SELECT id,code,CONCAT(last_name,', ',first_name,' ',middle_name) as employee FROM employees WHERE is_deleted=0 AND is_terminated=0")->fetchAll(PDO::FETCH_ASSOC);
		}
		else{
			
        $employees=$con->myQuery("SELECT id,code,CONCAT(last_name,', ',first_name,' ',middle_name) as employee FROM employees WHERE is_deleted=0 AND is_terminated=0 AND id=?",array($_SESSION[WEBAPP]['user']['employee_id']))->fetchAll(PDO::FETCH_ASSOC);
		}
}

//$limit=$_GET['length'];
try {
    $date_start=new DateTime($_GET['date_from']);
    $date_end=new DateTime($_GET['date_to']);
    $period = new DatePeriod(
         $date_start,
         new DateInterval('P1D'),
         $date_end
    );

    $data=array();
    $index=count($data);
    

    foreach ($employees as $employee) {
        $use_ot=array();
        foreach ($period as $key => $date) {
            $data[$index]['code']=$employee['code'];
            $data[$index]['code']=$employee['code'];
            $data[$index]['employee']=$employee['employee'];
            $data[$index]['ot']=0;
            $data[$index]['date']=$date->format("Y-m-d");
            $data[$index]['status']='Regular Day';

            $time_ins=$con->myQuery("SELECT in_time,out_time,id,note FROM `attendance` WHERE employees_id=? AND DATE(in_time)=? ORDER BY in_time ASC LIMIT 1",array($employee['id'],$date->format("Y-m-d")))->fetch(PDO::FETCH_ASSOC);
            $time_outs=$con->myQuery("SELECT in_time,out_time,id,note FROM `attendance` WHERE employees_id=? AND DATE(in_time)=? ORDER BY out_time DESC LIMIT 1",array($employee['id'],$date->format("Y-m-d")))->fetch(PDO::FETCH_ASSOC);

            $data[$index]['in_time']=!empty($time_ins['in_time'])?$time_ins['in_time']:'';
            $data[$index]['out_time']=!empty($time_outs['out_time'])?$time_outs['out_time']:'';
            $data[$index]['note']=(!empty($time_ins['note'])?"Time in: ".$time_ins['note']:''). (!empty($time_outs['note'])?" Time out: ".$time_outs['note']:'');

            $leaves=$con->myQuery("SELECT id,remark FROM `employees_leaves` WHERE employee_id=? AND ? BETWEEN date_start AND date_end AND status='Approved'",array($employee['id'],$date->format("Y-m-d")))->fetch(PDO::FETCH_ASSOC);
            if(!empty($leaves)){

                $data[$index]['status']=$leaves['remark']=="L"?"Leave":"Leave Without Pay";
            }

            $ots=$con->myQuery("SELECT id,no_hours FROM employees_ot WHERE employees_id=? AND ? BETWEEN date(date_from) AND date(date_to) AND status='Approved'".(!empty($use_ot)?" AND id NOT IN (".implode(",",$use_ot) .")":''),array($employee['id'],$date->format("Y-m-d")))->fetchAll(PDO::FETCH_ASSOC);

            foreach ($ots as $key => $ot) {
                $data[$index]['ot']+=$ot['no_hours'];
                $use_ot[]=$ot['id'];
            }
            
            $obs=$con->myQuery("SELECT COUNT(id) FROM employees_ob WHERE employees_id=? AND ? BETWEEN date(date_from) AND date(date_to) AND status='Approved'",array($employee['id'],$date->format("Y-m-d")))->fetchColumn();
            if(!empty($obs)){
                $data[$index]['status']='Official Business';
            }
            //echo $date->format("Y-m-d");
            $index++;
        }
    }

} catch (Exception $e) {
    //echo $e;
    $data=array();
}

echo json_encode($data);
die;
$bindings=jp_bind($bindings);
$complete_query="SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", SSP::pluck($columns, 'db'))."`,`employees_id`
             FROM `attendance` {$where} {$order} {$limit}";
            // echo $complete_query;
             //var_dump($bindings);
$data=$con->myQuery($complete_query,$bindings)->fetchAll();
$recordsFiltered=$con->myQuery("SELECT FOUND_ROWS();")->fetchColumn();

$recordsTotal=$con->myQuery("SELECT COUNT(id) FROM `attendance` {$where};",$bindings)->fetchColumn();

$json['draw']=isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
$json['recordsTotal']=$recordsFiltered;
$json['recordsFiltered']=$recordsFiltered;
$json['data']=SSP::data_output($columns,$data);

echo json_encode($json);
die;