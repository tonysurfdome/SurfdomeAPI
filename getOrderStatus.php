<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$arr_so = array();
	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{
		$salesordernumber =  $_REQUEST['salesordernumber'];

		$sql = "SELECT 
					SalesOrderNumber,
					sos.Name as pvxstatus,
					CustomerPurchaseOrderReferenceNumber		
				FROM 
					SalesOrder so 
				join 
					SalesOrderStatus sos on (so.statusid =sos.SalesOrderStatusId)
			    WHERE 
					so.CustomerPurchaseOrderReferenceNumber ='".$salesordernumber."'";

		$arr_so = ms_query_row($sql);
	}

	$rtndata = base64_encode(serialize($arr_so));
	echo $rtndata;


?>