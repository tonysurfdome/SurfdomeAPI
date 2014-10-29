<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');




include('ms-dbfunc.php');

$ms_connect = ms_connect();

if (isset($_REQUEST['listsku']) && $_REQUEST['listsku'] != "")
{

	$arr_sku = unserialize(base64_decode($_REQUEST['listsku']));
	//$arr_sku = array('1363-77');

	//$sku = $_REQUEST['sku'];


	$str_sku = "('". implode("','", $arr_sku) ."')";

	$sql = "SELECT 
		   ItemCode
		  ,Name
		  ,Barcode

	  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType]
	  where ItemCode in  ". $str_sku . " and [Barcode] NOT LIKE 'BC%'";


	$arr_res =  ms_query_all_assoc($sql);


	$arr_in_PVX = array();

	foreach($arr_sku as $sku)
	{
		
			
		$arr_in_PVX[$sku] = 0;

		if (isset($arr_res[$sku]))
		{
			//$b = substr($arr_res[$sku]['Barcode'], 0,2)
			//if ($b != 'BC')
			//{
				$arr_in_PVX[$sku] = 1;
			//}
		}
		
	}


	$data = base64_encode(serialize($arr_in_PVX));

	echo $data;

}

?>










