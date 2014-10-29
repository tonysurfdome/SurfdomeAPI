<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


$sku = $_REQUEST['sku'];

$user='sduser';
$pass='password';
$database="PeopleVox.OneBusinessPortal.Surfdome2536";
//$database ='Surfdome2536';
//$table='ActionCodes';


$serverName = 'AMAZONA-FSVEOGN';

$connectioninfro = array("Database" => $database, "UID" => $user,"PWD" => $pass);



$link = sqlsrv_connect($serverName, $connectioninfro);

if ($link)
{
    //die('Something went wrong while connecting to MSSQL');
}
else
{
	echo print_r(sqlsrv_errors(), true);
}


$sql = "SELECT 
		  IT.ItemCode as sku
		  ,loc.Barcode as location
		  ,NI.Quantity as qty
	  FROM 
	  NonSerializedInventoryItem as NI
	INNER JOIN
	  ItemType as IT on (NI.ItemTypeId = IT.ItemTypeId)
    INNER JOIN 
	  Holder as loc on (NI.HolderId = loc.HolderId)
	 Where
		 IT.ItemCode = '".$sku. "' 		
     ORDER BY 
		IT.ItemCode";


$result=sqlsrv_query($link, $sql) or sqlsrv_errors();

$arr_rtn = array();

while($arr_line_data=sqlsrv_fetch_array($result))
{
	$arr_rtn[$arr_line_data['sku']][] = array('location' => $arr_line_data['location'], 'qty' => $arr_line_data['qty']);

}

$data = base64_encode(serialize($arr_rtn));

//print_r($arr_rtn);

echo $data;

?>

