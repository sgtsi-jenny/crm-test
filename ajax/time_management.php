<?php
require_once("../support/config.php"); 

$primaryKey = 'id';
$index=-1;

$columns = array(
    array( 'db' => 'in_time','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'out_time','dt' => ++$index ,'formatter'=>function($d,$row){
        if($d=="0000-00-00 00:00:00"){
            return "";
        }else{
            return htmlspecialchars($d);
        }
    }),
    array( 'db' => 'note','dt' => ++$index ,'formatter'=>function($d,$row){
            return htmlspecialchars($d);
    }),
    array(
        'db'        => 'id',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {
            $action_buttons="";
            if($row['out_time']!="0000-00-00 00:00:00"){
            
            $action_buttons.="<button class='btn btn-sm btn-info'  title='Adjustment Request' data-in-time='{$row['in_time']}' data-out-time='{$row['out_time']}' data-id='{$d}' onclick='adjustment(this)'><span  class='fa fa-clock-o'></span> Adjustment</button>";
            }


            return $action_buttons;
        }
    )
);
 

require( '../support/ssp.class.php' );


$limit = SSP::limit( $_GET, $columns );
$order = SSP::order( $_GET, $columns );

$where = SSP::filter( $_GET, $columns, $bindings );
$whereAll="";
$whereResult="";


  
$whereAll=" employees_id= :employees_id";
$bindings[]=array('key'=>'employees_id','val'=>$_SESSION[WEBAPP]['user']['employee_id'],'type'=>0);
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