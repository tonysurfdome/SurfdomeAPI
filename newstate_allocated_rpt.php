<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{


			$sql = "SELECT 
						CONVERT(CHAR(10),so.RequestedDeliveryDate,120) as RequestedDeliveryDate ,
						so.SalesOrderNumber,
						sos.name as status,
						so.ChannelName,
						soip.QuantityOrdered,
						IT.ItemCode,
						IT.Name,
						IT.Attribute1
					FROM 
						[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[SalesOrderItem] soi
					  join 
						SalesOrder so on (soi.SalesOrderId= so.SalesOrderId)
					  join 
						[SalesOrderItemPicking] soip on (soi.SalesOrderItemId = soip.SalesOrderItemId)
					  join 
						ItemType IT on (soi.ItemTypeId =IT.ItemTypeId)
						join [SalesOrderStatus] sos on (so.statusid =sos.SalesOrderStatusId)
					 WHERE 
						so.StatusId in (7,8,1,0) and SO.SalesOrderNumber not LIKe 'STUDIO%' and  QuantityAllocated = 0;";

			$arr_tmp = ms_query_all($sql);



			foreach($arr_tmp as $tmp)
			{
				//get 
				$arr = array();
				$arr_test = $tmp;
				$sql = "SELECT 
							COALESCE(SUM(ni.Quantity),0)
					FROM 
							[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] IT 
						LEFT  JOIN                           
							[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[NonSerializedInventoryItem] NI on (IT.ItemTypeId = NI.ItemTypeId)
							join 
								Holder on (ni.HolderId = Holder.HolderId)
							JOIN 
								Location ON (Holder.HolderId = Location.HolderId)
					WHERE 
							ItemCode = '".$tmp['ItemCode']."'
							AND Location.LocationUseTypeId !=4
							and Holder.HolderTypeId =1";

				$qty = ms_query_value($sql);
				$arr_test['qty'] = $qty;
		
				$arr_data[] = $arr_test;
			}




	}

	$rtndata = base64_encode(serialize($arr_data));
	echo $rtndata;