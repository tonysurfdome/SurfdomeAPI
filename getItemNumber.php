<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{
		
		$saleOrderNumber = (isset($_REQUEST['SalesOrderNumber'])) ? $_REQUEST['SalesOrderNumber'] :0;
		$ItemCode		= (isset($_REQUEST['ItemCode'])) ? $_REQUEST['ItemCode'] :0;


		if ($ItemCode != "0" && $saleOrderNumber !="0")
		{
			$sql ="SELECT 
						soi.* 
				   FROM 
					SalesOrderItem soi
				  JOIN 
					SalesOrder so on (so.SalesOrderId =soi.SalesOrderId)
				  JOIN 
					ItemType it on (soi.ItemTypeId = it.ItemTypeId)
				  WHERE 
					so.SalesOrderNumber = '".$saleOrderNumber."' 
				  AND 
					it.ItemCode ='".$ItemCode."'";

			$arr_data = ms_query_row($sql);

		}

	}


	$rtndata = base64_encode(serialize($arr_data));
	echo $rtndata;


?>