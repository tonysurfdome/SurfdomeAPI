<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{
	$arr =array();
	$data = (isset($_REQUEST['data'])) ? $_REQUEST['data'] : "";

	if (!(empty($data)))
	{
		$arr_data = unserialize(base64_decode($data));
		
		foreach($arr_data as  $k => $v)
		{
			$sql = "UPDATE ItemType set Barcode = '".$v."' where ItemCode= '".$k."'";
			//echo $sql;	
			 ms_doquery($sql);
			$arr[] = $sql;
		}
	}

	$data = base64_encode(serialize($arr));
	echo $data;
}
