

 <?php


error_reporting(E_ALL);
ini_set('display_errors', '1');

include('ms-dbfunc.php');

$ms_connect = ms_connect();
$arr = array();


if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "getData")
{


$sql = "
SELECT 
	SUM(pvx_onhand) as Welli,
	SUM(Welli_allocated) as Welli_allocated,
	SUM(Welli_avi) as Welli_avi
FROM 
(

SELECT
							IT.itemcode,
							SUM(COALESCE(N.Quantity,0))  AS 'pvx_onhand'
							,COALESCE(MAX(SOIP.QuantityAllocated) - MAX(SOIP.QuantityDespatched),0) AS  'Welli_allocated'
								,(
				CASE 
				WHEN SUM(COALESCE(N.Quantity,0)) - COALESCE(MAX(SOIP.QuantityAllocated) - MAX(SOIP.QuantityDespatched),0) > 0 
					THEN SUM(COALESCE(N.Quantity,0)) - COALESCE(MAX(SOIP.QuantityAllocated) - MAX(SOIP.QuantityDespatched),0)
				ELSE
					0
				END) AS 'Welli_avi'

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
					group by it.ItemCode
					) g";

$arr = ms_query_row($sql);



$sql = "SELECT sum(m.Quantity)
  FROM NonSerializedInventoryItem m
  where HolderId in (
SELECT  h.holderid
  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Location] l
 left join Holder h on (l.HolderId =h.HolderId)
  where [LocationUseTypeId] = 4 and  (h.Barcode not like 'SD2%')
  )";

  $arr['pvx_onhand'] = ms_query_value($sql);
  $arr['pvx_allocated'] =0;
$arr['pvx_avl'] = 0;


  
  
  $sql = "SELECT sum(m.Quantity)
  FROM NonSerializedInventoryItem m
  where HolderId in (
SELECT  h.holderid
  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Location] l
  join Holder h on (l.HolderId =h.HolderId)
  where   h.Barcode  like 'SD2%'
  )";


    $arr['Studio'] = ms_query_value($sql);
	$arr['move_date'] = date('Y-m-d');

}

$data = base64_encode(serialize($arr));

echo $data;

