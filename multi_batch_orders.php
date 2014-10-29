<?php


	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	ini_set("memory_limit","999999999M");

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		$date ="";
		if (isset($_REQUEST['date']))
		{
			$date = " AND CONVERT(CHAR(10),so.RequestedDeliveryDate,120) >= '". $_REQUEST['date']."'";
		}
	
		$Priority = "";
		if (isset($_REQUEST['Priority']) && !empty($_REQUEST['Priority']))
		{
			$Priority = " and ActionGroup.Priority =". $_REQUEST['Priority'];
		}


		$locked = "";
		if (isset($_REQUEST['locked']) &&$_REQUEST['locked'] == 1)
		{
			$locked = " and ActionGroup.LockedByUserId is not null";
		}

		$unlocked = "";
		if (isset($_REQUEST['unlocked']) &&$_REQUEST['unlocked'] == 1)
		{
			$locked = " and ActionGroup.LockedByUserId is  null";
		}

		$sql ="SELECT 
				count(*)
			  FROM 
				[ActionGroup]
			  where  
				 [ActionGroup].ActionTypeId =1 
				and 
				 CompletedTimestamp is null";
			  
		 
		$arr_data['numactions'] = ms_query_value($sql);


		$sql = "SELECT 
					ActionGroup.Priority as Priority,
					count(*) as PriorityCount
				FROM 
					[ActionGroup]
				where   
					[ActionGroup].ActionTypeId =1 and CompletedTimestamp is null
				GROUP BY 
					ActionGroup.Priority";

		$arr_data['actionPriority'] = ms_query_all($sql);

		$sql = "SELECT 
					ActionGroup.Priority as Priority,
					count(*) as PriorityCount
				FROM 
					[ActionGroup]
				WHERE 
					[ActionGroup].ActionTypeId =1 and CompletedTimestamp is null
				GROUP BY 
					ActionGroup.Priority";

		$arr_data['actionPriority1'] = ms_query_col_assoc($sql);


		$sql = "SELECT 
					COUNT(actiongroupid)  
				FROM 
					actiongroup 
				WHERE 
						[ActionGroup].ActionTypeId =1 
					AND 
						CompletedTimestamp is null 
					AND 
						LockedByUserId is not null";

		$arr_data['lockedbatches'] = ms_query_value($sql);


		$sql = "SELECT 
					COUNT(actiongroupid)   
				FROM 
					actiongroup 
				WHERE 
					  [ActionGroup].ActionTypeId =1 
					AND 
					   CompletedTimestamp is null 
					AND 
					   LockedByUserId is  null";

		$arr_data['unlockedbatches'] = ms_query_value($sql);


		$sql = "SELECT 
					ActionGroup.Name as action, 
					max(SalesOrder.SalesOrderNumber) as SalesOrderNumber,
					min(CONVERT(CHAR(10),SalesOrder.RequestedDeliveryDate,120)) as date,
					max([ChannelName]) as ChannelName,
					MAX(SalesOrderStatus.name) as statusname,
					MAX(ActionGroup.Priority) as Priority,
					MAX([USER].DisplayName) as DisplayName
				FROM 
					[ActionGroup]
				  join 
					SalesOrderActionGroup on (ActionGroup.ActionGroupId = SalesOrderActionGroup.ActionGroupId)
				  join 
					SalesOrder on (SalesOrderActionGroup.SalesOrderId = SalesOrder.SalesOrderId)
				  join 
					SalesOrderStatus ON (SalesOrder.StatusId = SalesOrderStatus.SalesOrderStatusId)
				  left  outer  join 
					[User] on (ActionGroup.LockedByUserId = [user].UserID)
				 WHERE 
					   [ActionGroup].ActionTypeId =1 
					  and 
					   CompletedTimestamp is null ".$Priority  .$locked. "
				 GROUP BY 
					ActionGroup.Name
				 ORDER BY 
					min(SalesOrder.RequestedDeliveryDate)
		";


		$arr_data['actions'] = ms_query_all($sql);


		$sql = "select 
ActionGroup.Name,
SalesOrder.SalesOrderNumber  as  SalesOrderNumber,
SalesOrder.ChannelName as ChannelName,
SalesOrder.RequestedDeliveryDate
from
ActionGroup
  join SalesOrderActionGroup on (ActionGroup.ActionGroupId = SalesOrderActionGroup.ActionGroupId)
  join SalesOrder on (SalesOrderActionGroup.SalesOrderId = SalesOrder.SalesOrderId)
  where ActionGroup.Name in 
  (SELECT ActionGroup.Name
  FROM [ActionGroup]
  join SalesOrderActionGroup on (ActionGroup.ActionGroupId = SalesOrderActionGroup.ActionGroupId)
  join SalesOrder on (SalesOrderActionGroup.SalesOrderId = SalesOrder.SalesOrderId)
  where   [ActionGroup].ActionTypeId =1 and CompletedTimestamp is null
  group by ActionGroup.Name
  )
  order by SalesOrder.RequestedDeliveryDate";

  $sql = "select 
ActionGroup.Name,
SalesOrder.SalesOrderNumber  as  SalesOrderNumber,
SalesOrder.ChannelName as ChannelName,
SalesOrder.RequestedDeliveryDate,
ItemTypeGroup.Name as cat
from
ActionGroup
  join SalesOrderActionGroup on (ActionGroup.ActionGroupId = SalesOrderActionGroup.ActionGroupId)
  join SalesOrder on (SalesOrderActionGroup.SalesOrderId = SalesOrder.SalesOrderId)
  join SalesOrderItem on (SalesOrder.SalesOrderId = SalesOrderItem.SalesOrderId)
  join ItemType on (SalesOrderItem.ItemTypeId = ItemType.ItemTypeId)
   join ItemTypeGroup on (ItemType.ItemTypeGroupId = ItemTypeGroup.ItemTypeGroupId)
 
  where ActionGroup.Name in 
  (SELECT ActionGroup.Name
  FROM [ActionGroup]
  join SalesOrderActionGroup on (ActionGroup.ActionGroupId = SalesOrderActionGroup.ActionGroupId)
  join SalesOrder on (SalesOrderActionGroup.SalesOrderId = SalesOrder.SalesOrderId)
  where CompletedTimestamp is null and  [ActionGroup].ActionTypeId =2
  group by ActionGroup.Name
  )
  order by SalesOrder.RequestedDeliveryDate";


  $arr_data['action_orders'] = ms_query_all_assoc($sql);

  $sql ="SELECT 
	ag.Name
   	,left(h.barcode,1) as zone   
     FROM [ActionDetail] ad
  join ActionGroup ag on (ad.ActionGroupId = ag.ActionGroupId)
  join Holder h on (ad.FromHolderId =h.HolderId)
  where ad.ActionGroupId in (
  SELECT ActionGroup.ActionGroupId
  FROM [ActionGroup]
  join SalesOrderActionGroup on (ActionGroup.ActionGroupId = SalesOrderActionGroup.ActionGroupId)
  join SalesOrder on (SalesOrderActionGroup.SalesOrderId = SalesOrder.SalesOrderId)
  where  [ActionGroup].ActionTypeId =1 and CompletedTimestamp is null
  group by ActionGroup.ActionGroupId
  )
  and Sequence =0
  order by ag.Name, Sequence";

 $arr_data['action_zone'] = ms_query_col_assoc($sql);


		
	}


	$data = base64_encode(serialize($arr_data));

	echo $data;

?>