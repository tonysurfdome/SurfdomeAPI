<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{

	$num = (isset($_REQUEST['num'])) ? $_REQUEST['num'] : "";

	$itemcode = 0;
	if ($num != "")
	{

			$sql ="SELECT 
		ItemCode 
		FROM ItemType
		where Barcode ='".$num."'";

		//echo $sql;

		$itemcode_tmp = ms_query_value($sql);
		if(isset($itemcode_tmp))
		{
			$itemcode = $itemcode_tmp;
		}
	}

  echo $itemcode;


	
}

?>








