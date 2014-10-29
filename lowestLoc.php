<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');




include('ms-dbfunc.php');

$ms_connect = ms_connect();

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{
	if (isset($_REQUEST['listsku']) && $_REQUEST['listsku'] != "")
	{


			$arr_sku = unserialize(base64_decode($_REQUEST['listsku']));

			$str_sku = "('". implode("','", $arr_sku) ."')";
	
			$sql = "SELECT 
				 max(it.ItemCode) as sku
				  
				  ,min(h.Barcode) as loc
			  FROM 
			  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[NonSerializedInventoryItem] ns
			  INNER JOIN
			  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Holder] h on (ns.[HolderId] = h.HolderId)
			  INNER JOIN
			  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] it on (ns.ItemTypeId = it.ItemTypeId)
			  WHERE
			  it.ItemCode IN ".$str_sku." AND H.HolderTypeId =1
			  GROUP BY it.ItemTypeId
			  order by  min(h.Barcode)";



			$arr_data = ms_query_col_assoc($sql);

			$data = base64_encode(serialize($arr_data));

			echo $data;

	 }
	 else
	{
		 echo $data = base64_encode(serialize("DDDD"));
		 echo $echo;
	}

}


