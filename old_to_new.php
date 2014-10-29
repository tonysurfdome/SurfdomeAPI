<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();

$arr_qty = array();


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
						[ItemCode]= '".$data['old']."' or ItemCode like '".$data['old']."-%'";
			
			$arr_sku = ms_query_col($sql);

			foreach($arr_sku as $sku)
			{

				
				$arr_qty['data'][$sku] = 0;

				$break_sku = explode('-', $sku);
				$newsku = $data['new'];

				if (isset($break_sku[1]))
				{
					$newsku = 	$data['new'] ."-".$break_sku[1];
				}


				 $sql = "UPDATE [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] SET [ItemCode] = '".$newsku."'  WHERE [ItemCode]= '".$sku."'";
				 ms_doquery($sql);
				 $arr_qty['sql'][] = $sql;
				// echo $sql;	
				 

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

				$arr_qty['data'][$newsku] =  $qty;
	  
			}

		}

		$rtndata = base64_encode(serialize($arr_qty));
		echo $rtndata;

	}
	else
	{
		echo "please send some fecking data";
	}

	

}
else
{
	echo "please feck off";
}