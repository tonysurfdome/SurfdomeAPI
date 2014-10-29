<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{

	$arr_data = array();
	$sql ="
	SELECT 
		  SUM([QuantityOrdered]) as outqty,
		  sum([QuantityOrdered]*  [SalePrice]) as outqtyval
	  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrderItem] s1
	   join
		[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrderHistory] s2 on (s1.[SalesOrderId] = s2.[SalesOrderId])
		
		 where
	  [StatusId] = 5
	  and
	  CONVERT(CHAR(10),s2.[DateTimestamp],120) = '2012-08-28'
	  ";


	$row =ms_query_row($sql);

	$arr_data['sales'] = $row;
	$sql ="  SELECT 
	  SUM(t3.Quantity) as inqty,
	SUM(t4.[RetailPrice] *t3.Quantity) as inqtyval

	  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[GoodsIn] t1
	  join 
	  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Consignment] t2 on (t1.GoodsInId = t2.GoodsInId)
	  join 
	  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ConsignmentItemType] t3 on (t2.[ConsignmentId] =t3.[ConsignmentId])
	  join
	  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] t4 on (t3.[ItemTypeId] = t4.[ItemTypeId])
	  where CONVERT(CHAR(10),t1.[DeliveryDateTime],120) =   '2012-08-28';

	";
	$row =ms_query_row($sql);
	$arr_data['goods-in'] = $row;




	  ///total in the warehouse


	 $sql ="SELECT sum([Quantity]) as total_items
	,SUM(t1.[Quantity] * t2.[RetailPrice]) as total_value
	  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[NonSerializedInventoryItem] t1
		join
	  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] t2 on (t1.[ItemTypeId] = t2.[ItemTypeId])";
	$row =ms_query_row($sql);
	$arr_data['total'] = $row;

	$data = base64_encode(serialize($arr_data));
	echo $data;
}
else
{
	echo false;
}
 ?>