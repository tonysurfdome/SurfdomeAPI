<?php



  error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set("memory_limit","999999999M");
include('ms-dbfunc.php');

$ms_connect = ms_connect();
$arr_pvx = array();

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{


	$sql ="SELECT
							IT.itemcode,
							SUM(COALESCE(N.Quantity,0))  AS 'OnHand'
							,COALESCE(MAX(SOIP.QuantityAllocated) - MAX(SOIP.QuantityDespatched),0) AS  'Allocated'
								,(
				CASE 
				WHEN SUM(COALESCE(N.Quantity,0)) - COALESCE(MAX(SOIP.QuantityAllocated) - MAX(SOIP.QuantityDespatched),0) > 0 
					THEN SUM(COALESCE(N.Quantity,0)) - COALESCE(MAX(SOIP.QuantityAllocated) - MAX(SOIP.QuantityDespatched),0)
				ELSE
					0
				END) AS 'Available'

						FROM (ItemType IT 
							INNER JOIN ItemTypeGroup ITG
							ON ITG.ItemTypeGroupId = IT.ItemTypeGroupId
							LEFT OUTER JOIN UnitOfMeasure U1
							ON U1.UnitOfMeasureId = IT.UnitOfMeasureId
							LEFT OUTER JOIN UnitOfMeasure U2
							ON U2.UnitOfMeasureId = IT.WeightMeasureId
							LEFT OUTER JOIN UnitOfMeasure U3
							ON U3.UnitOfMeasureId = IT.DimensionMeasureId
							LEFT OUTER JOIN PickPolicy PP
							ON PP.PickPolicyId = IT.PickPolicyId)
							LEFT OUTER JOIN NonSerializedInventoryItem AS N ON N.ItemTypeId = IT.ItemTypeId
								AND not  EXISTS (SELECT 1
								FROM RemovedHolderView AS T
								WHERE N.HolderId = T.HolderId
								AND TableTypeId = 0)
							LEFT OUTER JOIN (
			SELECT SUM(QuantityPicked) AS QuantityPicked, SUM(QuantityDespatched) AS QuantityDespatched, 
				SUM(QuantityAllocated) AS QuantityAllocated, SUM(QuantityOrdered) AS QuantityOrdered,
				ItemTypeId
				FROM SalesOrderItemPicking
				WHERE SalesOrderItemId IN (
					SELECT SalesOrderItemId
					FROM SalesOrderItem
					WHERE SalesOrderId IN (
						SELECT SalesOrderId
						FROM SalesOrder
						WHERE StatusId <> 6)) 
				GROUP BY ItemTypeId
			 ) AS SOIP ON SOIP.ItemTypeId = IT.ItemTypeId
						WHERE 
								IT.Active = 1
							AND  not EXISTS (
								SELECT *
								FROM RemovedHolderView AS T
								WHERE (N.HolderId = T.HolderId)
								AND TableTypeId = 0) 
					group by it.ItemCode";



		$arr_pvx_all = ms_query_all($sql);
		foreach($arr_pvx_all as $pvx)
		{
			$sku = $pvx['itemcode'];
			$arr_pvx[$sku] = $pvx['Available'];
		}
/*
// remove good return locations
		$sql = "SELECT  
				it.ItemCode,
				sum(n.Quantity)
			  FROM Holder h 
			  join NonSerializedInventoryItem n on (h.HolderId = n.HolderId)
			  join ItemType it on (n.ItemTypeId = it.ItemTypeId) 
			  where h.Barcode like 'RTG%'
			  group by it.ItemCode";


			$arr_pvx_rtn = ms_query_col_assoc($sql);

			foreach ($arr_pvx_rtn as $k  => $v)
			{
				
				//echo $k ." before ".$arr_pvx[$k] ." and ";
				if (isset($arr_pvx[$k]))
				{
					$tmp = 	$arr_pvx[$k] - $v;
					$av = ($tmp >0) ? $tmp : 0;
					$arr_pvx[$k] = 	$av;
				}

				//echo "after ".$arr_pvx[$k] ."<br> ";

			}
			*/
/*

// remove default locations
		$sql = "SELECT
					it.ItemCode as sku ,
					sum(n.Quantity)
				  FROM NonSerializedInventoryItem n
					join ItemType it on (n.ItemTypeId = it.ItemTypeId)
					join Holder h on (h.holderid = n.holderid)
				 WHERE 
					(n.holderid =374516 or n.holderid = 3)
				GROUP BY it.ItemCode";


			$arr_pvx_dft = ms_query_col_assoc($sql);

			foreach ($arr_pvx_dft as $k  => $v)
			{
								//echo $k ." before ".$arr_pvx[$k] ." and ";

				if (isset($arr_pvx[$k]))
				{
					$tmp = 	$arr_pvx[$k] - $v;
					$av = ($tmp >0) ? $tmp : 0;
					$arr_pvx[$k] = 	$av;
				}
								//echo "after ".$arr_pvx[$k] ."<br> ";

			}
*/

		//print_r($arr_pvx);
}

$data = base64_encode(serialize($arr_pvx));
echo $data;					
?>