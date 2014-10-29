
<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{
			
			$data = $_REQUEST['data'];
			$arr_orders = unserialize(base64_decode($data));


			$sql = "SELECT 
						SalesOrderNumber 
					FROM 
						SalesOrder 
					WHERE 
						SalesOrderNumber IN ('". implode("','", $arr_orders) ."')";
			$arr_data = ms_query_col($sql);			
	}

	$rtndata = base64_encode(serialize($arr_data));
	echo $rtndata;



?>