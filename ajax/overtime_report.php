<?php
require_once("../support/config.php"); 

$primaryKey = 'id';
$index=-1;
$columns = array(
    array( 'db' => 'code','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'employee_name','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'date_filed','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'no_hours','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'date_from','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'date_to','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'worked_done','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'supervisor','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'final_approver','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'status','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    })
);
 

require( '../support/ssp.class.php' );


$limit = SSP::limit( $_GET, $columns );
$order = SSP::order( $_GET, $columns );

$where = SSP::filter( $_GET, $columns, $bindings );
$whereAll="";
$whereResult="";
if(AllowUser(array(1))){
    if(!empty($_GET['employees_id']) && $_GET['employees_id']!="NULL")
    $data=$con->myQuery("SELECT 
    id,
    code,
    employee_name,
    supervisor,
    final_approver,
    no_hours,
    worked_done,
    status,
    date_from,
    date_to,
    date_filed
    FROM vw_employees_ot WHERE date_from BETWEEN :date_from AND :date_to AND employee_id=:employee_id
    ",array("date_from"=>$_GET['date_from'],"date_to"=>$_GET['date_to'],"employee_id"=>$_GET['employees_id']));
}else{

// ["date_from"]=>
//   string(10) "2016-03-01"
//   ["date_to"]=>
//   string(10) "2016-03-31"
//   ["employees_id"]=>
//   string(4) "NULL"
  $data=$con->myQuery("SELECT 
    id,
    code,
    employee_name,
    supervisor,
    final_approver,
    no_hours,
    worked_done,
    status,
    date_from,
    date_to
    FROM vw_employees_ot WHERE date_from BETWEEN :date_from AND :date_to
    ",array("date_from"=>$_GET['date_from'],"date_to"=>$_GET['date_to']));
}

$whereAll=" employee_id=:employee_id";
$bindings[]=array('key'=>'employee_id','val'=>$_SESSION[WEBAPP]['user']['employee_id'],'type'=>0);
function jp_bind($bindings)
{
    $return_array=array();
    if ( is_array( $bindings ) ) {
            for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
                //$binding = $bindings[$i];
                // $stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
                $return_array[$bindings[$i]['key']]=$bindings[$i]['val'];
            }
        }

        return $return_array;
}
$where.= !empty($where) ? " AND ".$whereAll:"WHERE ".$whereAll;



$bindings=jp_bind($bindings);
$complete_query="SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", SSP::pluck($columns, 'db'))."`
             FROM `vw_employees_ot` {$where} {$order} {$limit}";
            // echo $complete_query;
             //var_dump($bindings);

$data=$con->myQuery($complete_query,$bindings)->fetchAll();
$recordsFiltered=$con->myQuery("SELECT FOUND_ROWS();")->fetchColumn();

$recordsTotal=$con->myQuery("SELECT COUNT(id) FROM `vw_employees_ot` {$where};",$bindings)->fetchColumn();


$json['draw']=isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
$json['recordsTotal']=$recordsFiltered;
$json['recordsFiltered']=$recordsFiltered;
$json['data']=SSP::data_output($columns,$data);

echo json_encode($json);
die;