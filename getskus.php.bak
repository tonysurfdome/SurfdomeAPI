<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();

$arr_skus = array();


if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{

	$data = (isset($_REQUEST['data'])) ? $_REQUEST['data'] : "";

	if (!(empty($data)))
	{
		$arr_data = unserialize(base64_decode($data));
	
	
		foreach ($arr_data as $data)
		{
		
			$sql = "SELECT 
						[ItemCode]
					FROM 
						[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType]
				    WHERE 
						[ItemCode]= '".$data['new']."' or [ItemCode]like '".$data['new']."-%'";
			
			$arr_sku = ms_query_col($sql);

			foreach($arr_sku as $sku)
			{

				$break_sku = explode('-', $sku);
				$oldsku = $data['old'];

				if (isset($break_sku[1]))
				{
					$oldsku = 	$data['old'] ."-".$break_sku[1];
				}

				//$arr_skus[] = array('old' => $oldsku, 'new' => $sku);


				$sql  = "SELECT 
							SUM(ni.Quantity)
						 FROM 
							[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[NonSerializedInventoryItem] NI
						 JOIN
							 [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] IT on (NI.ItemTypeId = IT.ItemTypeId)
						 JOIN 
							[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Holder]  loc on (NI.HolderId = loc.HolderId)
						 WHERE 
							  IT.ItemCode ='".$sku."' 
							 AND 
							  loc.HolderTypeId = 1  
						 GROUP BY 
							IT.ItemCode";
				
				$qty = ms_query_value($sql);

				if ($qty == null)
				{
					$qty = 0;
				}

				$arr_skus[] = array ( 'old' => array('sku' => $oldsku,  'qty' => 0),
									'new' => array('sku' => $sku, 'qty' => $qty)
									);


			}
		}

		$rtndata = base64_encode(serialize($arr_skus));
		echo $rtndata;

	}
}