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
    array(
        'db'        => 'id',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {
            $action_buttons="";
            
            $action_buttons.="<a class='btn btn-xs btn-success' href='frm_attendance.php?id={$row['id']}' title='Edit' ><span  class='fa fa-pencil'></span></a>";
            


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


  
// $whereAll=" employees_id= :employees_id";
// $bindings[]=array('key'=>'employees_id','val'=>$_SESSION[WEBAPP]['user']['employee_id'],'type'=>0);
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
$where.= !empty($where) ? " AND ".$whereAll:(empty($whereAll))?"":"WHERE ".$whereAll;



$bindings=jp_bind($bindings);
$complete_query="SELECT * FROM vw_employees_attendance {$where} {$order} {$limit}";
            // echo $complete_query;
             //var_dump($bindings);
$data=$con->myQuery($complete_query,$bindings)->fetchAll();
$recordsFiltered=$con->myQuery("SELECT FOUND_ROWS();")->fetchColumn();
// die($where);

$recordsTotal=$con->myQuery("SELECT COUNT(id) FROM vw_employees_attendance {$where};",$bindings)->fetchColumn();


$json['draw']=isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
$json['recordsTotal']=$recordsFiltered;
$json['recordsFiltered']=$recordsFiltered;
$json['data']=SSP::data_output($columns,$data);

echo json_encode($json);
die;