
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();
$actions  = array();

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{

/*	$sql ="SELECT 
		SalesOrder.SalesOrderNumber
	  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ActionDetail]
		inner join SalesOrder on (ActionDetail.SalesOrderId = SalesOrder.SalesOrderId)
	  WHERE 
		Reason LIKE '%MARCIN%' 
		and  
		StatusId <>5
	  GROUP BY SalesOrder.SalesOrderNumber";
*/


$sql ="  select SalesOrder.SalesOrderNumber from ActionDetail  
  inner join [SalesOrderActionGroup] on (ActionDetail.ActionGroupId = [SalesOrderActionGroup].ActionGroupId)
  inner join SalesOrderItem on  ( ActionDetail.ItemTypeId = SalesOrderItem.ItemTypeId)
  inner join SalesOrder on (SalesOrderItem.SalesOrderId = SalesOrder.SalesOrderId)
   where Reason LIKE '%MARCIN%' and Handled =0 and SalesOrder.StatusId  <>5
  group by SalesOrder.SalesOrderNumber";
	$actions = ms_query_col($sql);



}

$rtndata = base64_encode(serialize($actions));
echo $rtndata;

