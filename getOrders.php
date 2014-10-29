<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();



if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getOrders")
{
	$sql = "SELECT 
      [SalesOrderNumber]
  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder]
	WHERE 
		statusid = 2";
	
		$arr = ms_query_col($sql);

}
else if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getOrdersDetails")
{
	
	$arr_orders = unserialize(base64_decode($_REQUEST['data']));

	//echo $pvx_despatch;

	$str_orders = "('".implode("','", $arr_orders) ."')";
	$sql = "SELECT [SalesOrderId]
      ,[SalesOrderNumber]
      ,[InvoiceAddressId]
  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder]
  where
  SalesOrderNumber in" . $str_orders ;



	$arr =ms_query_all($sql);


}


	$rtndata = base64_encode(serialize($arr));
	echo $rtndata;
