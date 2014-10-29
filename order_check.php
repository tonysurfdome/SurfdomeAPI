<?php


	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$arr_so = array();



	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		$data = (isset($_REQUEST['data'])) ? $_REQUEST['data'] : "";

		$sql = "SELECT 
				[SalesOrderNumber]
			  FROM 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder]
			  WHERE
				CONVERT(CHAR(10),[RequestedDeliveryDate],120) = '".$_REQUEST['data']."'
			  GROUP BY
				[SalesOrderNumber]";
		
		$arr_so = ms_query_col($sql);


	}


	$rtndata = base64_encode(serialize($arr_so));
	echo $rtndata;