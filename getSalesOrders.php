<?php


	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$arr_so = array();


	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		$sql = "SELECT 
					CustomerPurchaseOrderReferenceNumber
		  FROM
				SalesOrder
		  WHERE
		 StatusId not in (5,6,9)";

			$arr_so = ms_query_col($sql);
	}


	$rtndata = base64_encode(serialize($arr_so));
	echo $rtndata;


 