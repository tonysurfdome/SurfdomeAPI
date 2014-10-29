<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$rtn =0;
	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "updateData")
	{

		$newsku = (isset($_REQUEST['newsku'])&& !(empty($_REQUEST['newsku'])) ? $_REQUEST['newsku'] : 0;
		$oldsku = (isset($_REQUEST['oldsku'])&& !(empty($_REQUEST['oldsku'])) ? $_REQUEST['oldsku'] : 0;
		
		if ($newsku != 0 && $oldsku !=0)
		{
			$sql = "UPDATE ItemType SET ItemCode = '".$newsku."'  WHERE [ItemCode]= '".$oldsku."'";
			$res = ms_doquery($sql);
			if ($res != FALSE)
			{
				$rtn =1;
			}
		}
	}
	echo $rtn;
?>