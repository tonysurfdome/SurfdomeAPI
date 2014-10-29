<?php


	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$arr_so = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		$arr_data = unserialize(base64_decode($_REQUEST['data']));

		$str_so = "('".implode("','", $arr_data) ."')";

		$sql = "SELECT 
					SalesOrderNumber, 
					ss.name, 
					CONVERT(CHAR(10),[RequestedDeliveryDate],120) as date, 
					ChannelName, 
					Address.Region as shipping
				FROM 
					[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder]
				JOIN 
					SalesOrderStatus as ss on (SalesOrder.StatusId =ss.SalesOrderStatusId)
				JOIN 
					address on (Address.AddressId= SalesOrder.ShippingAddressId)
				WHERE 
				statusid not in (5,6)
				and 
					CustomerPurchaseOrderReferenceNumber in ".$str_so. "
				ORDER by CONVERT(CHAR(10),[RequestedDeliveryDate],120)";
		$arr_so = ms_query_all($sql);
	}

	$rtndata = base64_encode(serialize($arr_so));
	echo $rtndata;

?>