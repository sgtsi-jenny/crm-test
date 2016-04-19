<?php
ob_start();

require_once("./support/config.php");
require_once("./support/PHPExcel.php"); 


if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
$employees=array();

if(!empty($_POST['employees_id'])){
    if($_POST['employees_id']=='NULL' && AllowUser(array(1))){
        $employees=$con->myQuery("SELECT id,code,CONCAT(last_name,', ',first_name,' ',middle_name) as employee FROM employees WHERE is_deleted=0 AND is_terminated=0")->fetchAll(PDO::FETCH_ASSOC);
    }else{
        if(AllowUser(array(1))){
            if(is_numeric($_POST['employees_id'])){
            $employees=$con->myQuery("SELECT id,code,CONCAT(last_name,', ',first_name,' ',middle_name) as employee FROM employees WHERE is_deleted=0 AND is_terminated=0 AND id=?",array($_POST['employees_id']))->fetchAll(PDO::FETCH_ASSOC);
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
//$limit=$_POST['length'];
try {
    $date_start=new DateTime($_POST['date_from']);
    $date_end=new DateTime($_POST['date_to']);
    $period = new DatePeriod(
         $date_start,
         new DateInterval('P1D'),
         $date_end
    );

    $data=array();
    $data[]=array("Employee Code","Employee Name","Date","In Time","Out Time","Overtime","Status","Note");
    $index=count($data);
    
    
    $objPHPExcel = new PHPExcel();
    // Set properties
$objPHPExcel->getProperties()->setCreator("SGTSI Customer Relationship Management")
                                 ->setTitle("Attendance Report");
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);
    // Rename sheet
    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
    // $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $row=2;
    $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');
    foreach ($employees as $employee) {
        $use_ot=array();
        foreach ($period as $key => $date) {

            $data=array();
            $data[$index]['code']=$employee['code'];
            $data[$index]['employee']=$employee['employee'];
            $data[$index]['date']=PHPExcel_Shared_Date::PHPToExcel(strtotime($date->format("Y-m-d")));
            

            $time_ins=$con->myQuery("SELECT in_time,out_time,id,note FROM `attendance` WHERE employees_id=? AND DATE(in_time)=? ORDER BY in_time ASC LIMIT 1",array($employee['id'],$date->format("Y-m-d")))->fetch(PDO::FETCH_ASSOC);
            $time_outs=$con->myQuery("SELECT in_time,out_time,id,note FROM `attendance` WHERE employees_id=? AND DATE(in_time)=? ORDER BY out_time DESC LIMIT 1",array($employee['id'],$date->format("Y-m-d")))->fetch(PDO::FETCH_ASSOC);

            $data[$index]['in_time']=!empty($time_ins['in_time'])?PHPExcel_Shared_Date::PHPToExcel(strtotime($time_ins['in_time'])):'';
            $data[$index]['out_time']=!empty($time_outs['out_time'])?PHPExcel_Shared_Date::PHPToExcel(strtotime($time_outs['out_time'])):'';

            $leaves=$con->myQuery("SELECT id,remark FROM `employees_leaves` WHERE employee_id=? AND ? BETWEEN date_start AND date_end AND status='Approved'",array($employee['id'],$date->format("Y-m-d")))->fetch(PDO::FETCH_ASSOC);
            

            $ots=$con->myQuery("SELECT id,no_hours FROM employees_ot WHERE employees_id=? AND ? BETWEEN date(date_from) AND date(date_to) AND status='Approved'".(!empty($use_ot)?" AND id NOT IN (".implode(",",$use_ot) .")":''),array($employee['id'],$date->format("Y-m-d")))->fetchAll(PDO::FETCH_ASSOC);
            $data[$index]['ot']=0;

            foreach ($ots as $key => $ot) {
                $data[$index]['ot']+=$ot['no_hours'];
                $use_ot[]=$ot['id'];
            }
            
            $obs=$con->myQuery("SELECT COUNT(id) FROM employees_ob WHERE employees_id=? AND ? BETWEEN date(date_from) AND date(date_to) AND status='Approved'",array($employee['id'],$date->format("Y-m-d")))->fetchColumn();
            

            $data[$index]['status']='Regular Day';
            if(!empty($leaves)){

                $data[$index]['status']=$leaves['remark']=="L"?"Leave":"Leave Without Pay";
            }
            if(!empty($obs)){
                $data[$index]['status']='Official Business';
            }
            //echo $date->format("Y-m-d");
            $data[$index]['note']=(!empty($time_ins['note'])?"Time in: ".$time_ins['note']:''). (!empty($time_outs['note'])?"Time out_time: ".$time_outs['note']:'');
            //echo $row;
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // echo "A".$row;
             $objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A'.$row,true);
            //$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Invoice');
            $objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('yyyy-mm-dd h:mm:ss');
            $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('yyyy-mm-dd h:mm:ss');
            $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

            $index++;
            $row++;
        }
    }
  


} catch (Exception $e) {
    //echo $e;
    $data=array();
}
//$objPHPExcel->getActiveSheet()->fromArray($data,NULL,'A1');

// echo "<pre>";
// print_r($data);
// echo "</pre>";
// die;

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Attendance Report-'.date("Y-m-d").'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
die;
ob_end_clean();
?>
