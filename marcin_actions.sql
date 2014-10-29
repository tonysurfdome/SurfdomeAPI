
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{

	$sql ="SELECT 
		SalesOrder.SalesOrderNumber
	  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ActionDetail]
		inner join SalesOrder on (ActionDetail.SalesOrderId = SalesOrder.SalesOrderId)
	  WHERE 
		Reason LIKE '%MARCIN%' 
		and  
		ActionDetail.SalesOrderId is not null
	  GROUP BY SalesOrder.SalesOrderNumber"

