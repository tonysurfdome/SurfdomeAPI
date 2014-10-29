<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();



if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{

	$data = (isset($_REQUEST['data'])) ? $_REQUEST['data'] : "";

	if (!(empty($data)))
	{
		$arr_data = unserialize(base64_decode($data));

		$arr_qty = array();

		foreach($arr_data as $sku)
		{

			$sql = "SELECT 
					SUM(ni.Quantity)
				  FROM 
					[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[NonSerializedInventoryItem] NI
				  join
					[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] IT on (NI.ItemTypeId = IT.ItemTypeId)
				  join 
					[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Holder]  loc on (NI.HolderId = loc.HolderId)
				  WHERE 
					IT.ItemCode ='".$sku."'
				  AND 
					loc.HolderTypeId = 1  
				  GROUP BY 
					IT.ItemCode ";

			$qty = ms_query_value($sql);

			if ($qty == null)
			{
				$qty = 0;
			}

			$arr_qty[$sku] =  $qty;
		}

		$data = base64_encode(serialize($arr_qty));

		echo $data;

	}
}