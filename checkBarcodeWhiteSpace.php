<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();


	$arr_so = array();

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{
	    $sql = "SELECT 
		       [ItemTypeId]
		      ,[ItemCode]
		      ,[Name]
		      ,Barcode
		  FROM
			[PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType]
		  WHERE 
			Barcode like ' %' OR Barcode like '% '";

		$arr_so = ms_query_all($sql);
	}

	$rtndata = base64_encode(serialize($arr_so));
	echo $rtndata;

?>