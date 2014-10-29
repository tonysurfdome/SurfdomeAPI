<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();
	$basePath   = 'D:\\log';

	$date  = date('Ymd');

	$testpath =  $basePath ."\\test\\".$date;
	$livepath =  $basePath ."\\live\\".$date;

	$filename = $testpath."\\tables.csv";

	$arr_test_tables = array();
	$handle = fopen($filename , "r");
	if ($handle) {
	    while (($line = fgets($handle)) !== false) 
		{
			$arr_test_tables[] = trim($line);
	    }
	} else {
	    // error opening the file.
	}
	fclose($handle);

	$filename = $livepath."\\tables.csv";

	$arr_live_tables = array();
	$handle = fopen($filename , "r");
	if ($handle) {
	    while (($line = fgets($handle)) !== false) 
		{
			$arr_live_tables[] = trim($line);
	    }
	} else {
	    // error opening the file.
	}
	fclose($handle);



	$arr_del =array();
	foreach ($arr_live_tables as $table)
	{
		if (in_array($table, $arr_test_tables ) === false)
		{
			$arr_del[] = $table;
		}
	}


	print_r($arr_del);
require_once './class.Diff.php';

	$arr_new =array();
	foreach ($arr_test_tables as $table)
	{
		if (in_array($table, $arr_live_tables ) === false)
		{
			$arr_new[] = $table;
		}
	}




	if (count($arr_live_tables) <  count($arr_test_tables))
	{
		echo "New tables have been added";
	}




echo count($arr_live_tables) . "---" . count($arr_test_tables);

$arr_result = array();
foreach ($arr_test_tables as $table)
{

	$testfilename = $testpath."\\tables\\".$table.".csv";
	$livefilename = $livepath."\\tables\\".$table.".csv";

	$live =0;
	$test =0;

	$handle = fopen($testfilename , "r");
	if ($handle) {
		$test =1;
	}
	else
	{
		$arr_result[$table][] = "Table not in test database";
	}
	fclose($handle);

	$handle = fopen($livefilename , "r");
	if ($handle) {
		$live =1;
	}
	else
	{
		$arr_result[$table][] = "Table not in live database";
	}
	fclose($handle);

	
	if ($test ==1 && $live ==1)
	{
		$arr_diff = Diff::compareFiles($testfilename, $livefilename);


		foreach ($arr_diff as $diff)
		{
			if ($diff[1] > 0)
			{
				$arr_result[$table][] = $diff;
			}
		}
	}

}


print_r($arr_result);

