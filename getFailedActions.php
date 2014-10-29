<?php


	error_reporting(E_ALL);
	ini_set('display_errors', '1');


	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{

		$sql = "SELECT 
					it.ItemTypeId,
					it.ItemCode,
					it.Name as itemname,
					h.Name as locname,
					h.Barcode,
					ActionGroup.Name as actionGroup,
					CONVERT(CHAR(18),ActionDetail.CompletedTimeStamp,120) as faileddate,
					ActionDetail.Reason
			   FROM 
					[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ActionDetail]
				  join 
					ActionGroup on (ActionDetail.ActionGroupId = ActionGroup.ActionGroupId)
				  join 
					Holder  h on ([ActionDetail].FromHolderId = h.HolderId)
				  join 
					ItemType it on (ActionDetail.ItemTypeId = it.ItemTypeId)
			  WHERE 
				Handled=0 
			   and 
			    Reason is not null 
			   and 
			     ActionGroup.Name like 'ACT%' 
			   and 
			     it.ItemCode not like '~D%'";

		$arr_failed = ms_query_all($sql);


		$arr_data = array();
		foreach($arr_failed as $failed)
		{
			$arr_tmp = array();
			$arr_tmp = $failed;
			$sql = "select  
						SalesOrder.SalesOrderNumber 
					from   
						SalesOrder   
					  join 
						SalesOrderItem on (SalesOrder.SalesOrderId = SalesOrderItem.SalesOrderId)
					where 
						StatusId !=5 and SalesOrderItem.ItemTypeId = " .$failed['ItemTypeId'];
			$arr_failed_so = ms_query_col($sql);


			$arr_tmp['salesnumber'] = implode(",", $arr_failed_so);

			$str_sql =  "('".implode("','", $arr_failed_so)."')";



					$sql = "select  
						count(*)
					from   
						SalesOrder   
					  join 
						SalesOrderActionGroup on (SalesOrder.SalesOrderId = SalesOrderActionGroup.SalesOrderId)
					where 
						SalesOrderNumber in ". $str_sql;

			$arr_tmp['released'] = ms_query_value($sql);
						

			$arr_data[] = $arr_tmp;

		}


		//print_r($arr_data);

		
		
		$data = base64_encode(serialize($arr_data));

		echo $data;

	

	}
	

	?>