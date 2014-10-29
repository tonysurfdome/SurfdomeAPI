<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');




	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		$salerordersql ="";
		$saleordercol = "";

		
		if (isset($_REQUEST['salesorder']) && $_REQUEST['salesorder'] ==1)
			{
			$salerordersql = "LEFT  JOIN 
								Pick P ON (P.HolderId = H.HolderId OR H.HolderId = h1.HolderId)
							  LEFT  JOIN 
								SalesOrder S ON P.SalesOrderId = S.SalesOrderId";
			
			$saleordercol = ",S.SalesOrderNumber AS SalesOrderNumber";
		}


		$arr_item = explode (',',$_REQUEST['itemcode']);
		$itemsql = "";
		if (count($arr_item) > 1)
		{
			$itemsql = "IT.ItemCode in ('". implode("','", $arr_item) ."')";
		}
		else
		{
			$itemsql = "IT.ItemCode = '".$_REQUEST['itemcode']."'";
		}

		


		//$item =$_REQUEST['itemcode'];

		$sql ="
			SELECT
				IM.NonSerializedInventoryItemMovementHistoryId
				,IM.Quantity
				,IM.Comments
				,CONVERT(CHAR(198),im.DATETIMESTAMP,120) as DATETIMESTAMP
				,IT.ItemCode as ItemCode
				,IT.name
				,IT.Barcode
				,H.Name as toloc
				,h1.Name as fromloc
				,u.displayname as [user]
				".$saleordercol ."
			FROM 
				NonSerializedInventoryItemMovementHistory as IM
			 left  JOIN 
				ItemType as IT on (IM.ItemTypeId = IT.ItemTypeId)
			 left  JOIN 
				[User] as u on  (IM.userid = u.userid)
			 left  JOIN 
				Holder as H on (H.HolderId = IM.ToHolderId)
			   LEFT JOIN 
				Holder as h1  on (H1.HolderId = IM.FromHolderId)
				".$salerordersql."	
			WHERE 
				".$itemsql ."
			ORDER BY CONVERT(CHAR(198),im.DATETIMESTAMP,120) DESC";
		//echo $sql;
		
		$arr_data = ms_query_all($sql);




	}

	$data = base64_encode(serialize($arr_data));

	echo $data;