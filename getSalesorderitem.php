<?php


	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		if (isset($_REQUEST['line']))
		{
			$sql = "SELECT  SalesOrderNumber, ItemCode, QuantityOrdered,Line,  SalePrice FROM SalesOrderItem 
join SalesOrder on (SalesOrderItem.SalesOrderId = SalesOrder.SalesOrderId)
join ItemType on (SalesOrderItem.ItemTypeId = ItemType.ItemTypeId)
WHERE statusid !=6 and  Line ='".$_REQUEST['line']."'";

//echo $sql;
			$arr_data =  ms_query_row($sql);
		}
	}


	$data = base64_encode(serialize($arr_data));

	echo $data;