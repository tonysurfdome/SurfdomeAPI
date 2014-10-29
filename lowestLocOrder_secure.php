<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');




include('ms-dbfunc.php');

$ms_connect = ms_connect();
$data       = "FALSE";


if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{
	if (isset($_REQUEST['listsku']) && $_REQUEST['listsku'] != "")
	{


			$arr_sku = unserialize(base64_decode($_REQUEST['listsku']));

			$str_sku = "('". implode("','", $arr_sku) ."')";

		$p =5;	

		$arr_p = array(6,7,8,9);
		$i = 0;

		$arr_zone_picks = array('B-C,D,E,F,G,M,N,O,P,I,J,S,T,U,R,X,K,L,A', 'E,F,G-B,C,D', 'I,J', 'R');

		foreach($arr_zone_picks as $zone_pick)
		{
				$arr = explode('-', $zone_pick);

				$arr_in = explode(',', $arr[0]);

				$str_in_zone ="( h.Barcode like '".implode("%' or h.Barcode like '", $arr_in)."%')";

				$str_sql = 	$str_in_zone;					

				
				if (count($arr) > 1)
				{
					$arr_not = explode(',', $arr[1]);

					
					if (count($arr_not) >0)
					{
						$str_not_in_zone ="(h.Barcode not like '".implode("%' or h.Barcode not like '", $arr_not)."%')";
						$str_sql =  $str_in_zone." AND " . $str_not_in_zone ;
					}
				}


	$sql = "SELECT 
				min(h.Barcode) as loc
							
						FROM 
							 NonSerializedInventoryItem ns
						  INNER JOIN
							  Holder H on (ns.HolderId = h.HolderId)
						  INNER JOIN
							 ItemType it on (ns.ItemTypeId = it.ItemTypeId)
						  INNER JOIN
							Location l on (l.HolderId = H.HolderId)
					   WHERE
						  it.ItemCode IN  ".$str_sku." 
						AND  
					  
						  H.HolderTypeId =1 
						 AND 
						  H.HolderId  <> 3
						 AND 
						  l.LocationUseTypeId = 3
						  AND
						     " . $str_sql. "  
					   GROUP BY 
							h.Barcode";

				$arr_data = ms_query_value($sql);



				if (count($arr_data) > 0)
				{
					//UPDATE ORDER HERE
					$p = $arr_p[$i];	
				}

				$i++;

		}

		$data = $p;

		echo $data;

	 }
	 else
	{
		 echo $data = base64_encode(serialize("DDDD"));
		 echo $echo;
	}

}


?>