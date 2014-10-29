<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');




	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$arr_data = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{


		$sql = "SELECT 
					CONVERT(CHAR(19),[DeliveryDateTime],120) as DeliveryDate  
				FROM 
					[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[GoodsIn]
			    WHERE 
					Reference = '".$_REQUEST['ref']."'";

		$date = ms_query_value($sql);
		echo $date;


	}
?>
