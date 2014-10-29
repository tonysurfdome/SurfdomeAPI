<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');




	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

		$qty =5;
		if (isset($_REQUEST['qty']) && $_REQUEST['qty'] > 0)
		{
			$qty = $_REQUEST['qty'];
		}

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{
	$sql = "SELECT  max(ItemType.ItemCode)as itemcode,
		max(ItemType.Name) as name,
		max(ItemType.Barcode) as barcode,
		max(ItemType.Attribute1) as  Attribute1,
		max(ItemType.Attribute2) as Attribute2,
		sum(NonSerializedInventoryItem.Quantity) as qty
	
  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType]
  join NonSerializedInventoryItem on (ItemType.ItemTypeId = NonSerializedInventoryItem.ItemTypeId)
  join Holder on (Holder.HolderId = NonSerializedInventoryItem.HolderId)
  where [ItemTypeGroupId] =5 and (Holder.Barcode like 'SD1.B%' OR Holder.Barcode like 'SD1.C%' OR Holder.Barcode like 'SD1.D%')
  group by ItemType.ItemTypeId having sum(NonSerializedInventoryItem.Quantity) > ".$qty." 
  order by qty desc";


    $arr_data['items']  = ms_query_all_assoc($sql);


	$arr_item_codes = array_keys($arr_data['items']);




	$sql = "select 
	ItemType.ItemCode as code, 
	Holder.name as locname,  
	NonSerializedInventoryItem.Quantity  as qty
	from NonSerializedInventoryItem
join Holder on (Holder.HolderId = NonSerializedInventoryItem.HolderId)
join ItemType on (ItemType.ItemTypeId = NonSerializedInventoryItem.ItemTypeId)
where  (Holder.Barcode like 'SD1.B%' OR Holder.Barcode like 'SD1.C%' OR Holder.Barcode like 'SD1.D%') and itemcode  in ('". implode("','", $arr_item_codes) ."')
order by Holder.name asc";


//echo $sql;
$arr_data['locqty']= ms_query_all_assoc($sql);

	}

	$data = base64_encode(serialize($arr_data));

	echo $data;
	exit;

//print_r($arr_item_locs);
