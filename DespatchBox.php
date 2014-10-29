<?php


include('ms-dbfunc.php');

$ms_connect = ms_connect();

if (isset($_REQUEST['password']) && $_REQUEST['password'] == "ynot")
{
	$sql ="SELECT
				h.name, 
				i.ItemCode, 
				i.Name as itemname, 
				actG.Name
		   FROM 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Holder] as h 
			  join 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[NonSerializedInventoryItem] as s on (h.HolderId =s.HolderId)
			  join 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] as i on (s.ItemTypeId = i.ItemTypeId)
			  join 
			  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ActionDetail] as act on (s.HolderId = act.[ToHolderId] and s.ItemTypeId = act.[ItemTypeId])
			  join 
				[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ActionGroup] as actG on (actG.ActionGroupId = act.ActionGroupId)
		  WHERE 
				h.Barcode Like 'SD1.BOX%'";

	$arr = ms_query_all_assoc($sql);

	foreach ($arr as $k => $items)

	{	
		echo  "<b><br><br>".$k."</b><br><br>";
		echo "<table border =\"1\" cellpadding=\"10\">";
		echo "<tr>";
		echo "<td>Item code</td>";
		echo "<td>Item Name</td>";
		echo "<td>Batch Name</td>";		
		echo "</tr>";



		foreach($items as $item)
		{
			echo "<tr>";
			echo "<td>".$item['ItemCode']."</td>";
			echo "<td>".$item['itemname']."</td>";
			echo "<td>".$item['Name']."</td>";		
			echo "</tr>";
		}
		echo "</table>";


	}
}
else
{
	echo "Wrong password";
}

?>
