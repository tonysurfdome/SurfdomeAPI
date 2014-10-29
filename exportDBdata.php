<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	ini_set("memory_limit","99999M");
	ini_set('MAX_EXECUTION_TIME', -1);

	$mystart =microtime(true);

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$debug = '';
	$return =0;

	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
	{
		$arr_table = array (
							'NonSerializedInventoryItem',
							'Holder',
							'Location',
							'LocationGroup',
							'LocationGroupType',
							'ItemType',
							'ItemTypeGroup',
							'ItemTypeSupplier',
							'Supplier',
							'Account',
							);
		

		foreach ($arr_table as $table)
		{
			$start = microtime(true);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://www.stpvx.com/peoplevox/SurfdomeApi/dumpData.php');
			$encoded = "mode=getData&table=".$table;
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 2000);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ff =curl_exec($ch);

			$return = $ff;

			if ($return == 0)
			{
				echo $return;
				exit;
			}

			$loadtime = microtime(true) - $start;
			$debug = $table." has finished" .  $loadtime."<br>";
			
			$download_time = microtime(true);
			$download_url = "http://www.stpvx.com/peoplevox/FileStore/datadump/".$table.".csv";
			
			$data = file_get_contents($download_url);
			$filename  = "D:\\test\\".$table.".csv";
			$fp        = fopen($filename, 'w');
			fwrite($fp,$data);

			$loadtime = microtime(true) - $download_time;
			$debug = $table." has finished downloading" .  $loadtime."<br>";	
		}
	}

	$loadtime = microtime(true) - $mystart;
	$debug = "Total time " .  $loadtime."<br>";
	

	if (isset($_REQUEST['debug']) && $_REQUEST['debug'] ==1)
	{
		echo $debug;
	}

	echo $return;
	exit;
?>