SELECT SUM(t4.[RetailPrice])
  FROM [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[GoodsIn] t1
  join 
  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[Consignment] t2 on (t1.GoodsInId = t2.GoodsInId)
  join 
  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ConsignmentItemType] t3 on (t2.[ConsignmentId] =t3.[ConsignmentId])
  join
  [PeopleVox.OneBusinessPortal.Surfdome2536].[dbo].[ItemType] t4 on (t3.[ItemTypeId] = t4.[ItemTypeId])
  where CONVERT(CHAR(10),t1.[DeliveryDateTime],120) =   '2012-08-28';