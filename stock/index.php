<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'return_stock.php';

$json = "";

if (isset($_REQUEST['datetimestamp']) && !(empty($_REQUEST['datetimestamp'])))
{
	$ReturnStock = new ReturnStock();


	$itemcode = (isset($_REQUEST['sku']) && !(empty($_REQUEST['sku']))) ? $_REQUEST['sku'] : 0;
	$limit =  (isset($_REQUEST['limit']) && !(empty($_REQUEST['limit']))) ? $_REQUEST['limit'] : 10;
	$datetimestamp =  (isset($_REQUEST['datetimestamp']) && !(empty($_REQUEST['datetimestamp']))) ? $_REQUEST['datetimestamp'] : date("Y-m-d H:i:s",strtotime("-90 minutes")); 

	$ReturnStock->itemCode($itemcode);
	$ReturnStock->limit($limit);
	$ReturnStock->dateTimeStamp($datetimestamp);

	#echo $ReturnStock->itemCode;
	$json = $ReturnStock->run();

}

echo $json;
?>