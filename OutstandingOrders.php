<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		$sql = "SELECt
			  [SalesOrderNumber]
			,CONVERT(CHAR(10),[RequestedDeliveryDate],120) as orderDate
			  ,[CustomerPurchaseOrderReferenceNumber] as webOrderNumber
			  ,sos.Name as status
			  ,[Email]
			  ,[ContactName]
			  ,st.Name as ShippingType
			  ,[ChannelName]
		  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder] so
		  join SalesOrderStatus sos on (so.StatusId =sos.SalesOrderStatusId)
		  join ServiceType st on (so.ServiceTypeId = st.ServiceTypeId)
		  where StatusId not in (5,6)";


		  $arr_data =ms_query_all($sql);
	}

	$rtndata = base64_encode(serialize($arr_data));
	echo $rtndata;


?>