
<?php


	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$arr = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{
		$sql="SELECT 
				CONVERT(CHAR(10),DateTimestamp,120) as date, 
				COUNT(SalesOrderNumber) as ordersDespatched
			  FROM 
					[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder] as SO
				inner join 
				[SalesOrderHistory] as SOH on (so.SalesOrderId = SOH.SalesOrderId)
			  WHERE 
				soh.StatusId = 5 
					And 	CONVERT(CHAR(10),DateTimestamp,120) >='".$_REQUEST['date']."'
			  GROUP BY CONVERT(CHAR(10),DateTimestamp,120)
				  order by CONVERT(CHAR(10),DateTimestamp,120)";

				$arr = ms_query_all_assoc($sql);
	}
	else if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData_Date")
	{

		$sql="SELECT 
					CustomerPurchaseOrderReferenceNumber
			  FROM 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder] as SO
				inner join [SalesOrderHistory] as SOH on (so.SalesOrderId = SOH.SalesOrderId)
			  WHERE soh.StatusId = 5  and CONVERT(CHAR(10),DateTimestamp,120) ='".$_REQUEST['date']."'";

		$arr = ms_query_col($sql);

	}
	else if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getdespatchOrders")
	{

		$sql="SELECT 
				COALESCE(COUNT(SalesOrderNumber),0) as ordersDespatched
			  FROM 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder] as SO
				inner join [SalesOrderHistory] as SOH on (so.SalesOrderId = SOH.SalesOrderId)
			  WHERE 
			  SO.SalesOrderNumber Not like 'STUDIO%'
				AND
			  soh.StatusId = 5  and CONVERT(CHAR(10),DateTimestamp,120) ='".$_REQUEST['date']."'";

		$arr = ms_query_value($sql);
	}
	else if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getdespatchOrdersItems")
	{
		$sql="SELECT 
				COALESCE(SUM(SOI.QuantityOrdered),0) AS despatchitems
			  FROM 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder] as SO
			 inner join 
				[SalesOrderHistory] as SOH on (so.SalesOrderId = SOH.SalesOrderId)
			 inner join 
				[SalesOrderItem] as SOI on (SO.SalesOrderId = soi.SalesOrderId)
			 WHERE 
				SO.SalesOrderNumber Not like 'STUDIO%'
				AND
				soh.StatusId = 5  
			  AND 
				CONVERT(CHAR(10),DateTimestamp,120)  ='".$_REQUEST['date']."'";
		$arr = ms_query_value($sql);
	}
	else if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getdespatchOrders_studio")
	{

		$sql="SELECT 
				COALESCE(COUNT(SalesOrderNumber),0) as ordersDespatched
			  FROM 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder] as SO
				inner join [SalesOrderHistory] as SOH on (so.SalesOrderId = SOH.SalesOrderId)
			  WHERE 
			  SO.SalesOrderNumber  like 'STUDIO%'
				AND
			  soh.StatusId = 5  and CONVERT(CHAR(10),DateTimestamp,120) ='".$_REQUEST['date']."'";

		$arr = ms_query_value($sql);
	}
	else if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getdespatchOrdersItems_studio")
	{
		$sql="SELECT 
				COALESCE(SUM(SOI.QuantityOrdered),0) AS despatchitems
			  FROM 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrder] as SO
			 inner join 
				[SalesOrderHistory] as SOH on (so.SalesOrderId = SOH.SalesOrderId)
			 inner join 
				[SalesOrderItem] as SOI on (SO.SalesOrderId = soi.SalesOrderId)
			 WHERE 
				SO.SalesOrderNumber  like 'STUDIO%'
				AND
				soh.StatusId = 5  
			  AND 
				CONVERT(CHAR(10),DateTimestamp,120)  ='".$_REQUEST['date']."'";
		$arr = ms_query_value($sql);


	}

	$rtndata = base64_encode(serialize($arr));
	echo $rtndata;

?>