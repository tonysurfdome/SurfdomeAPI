<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

	$sql = "SELECT 
			DISTINCT
				'ALTER TABLE ' + '[' + OBJECT_NAME(f.parent_object_id)+ ']'+
				' DROP  CONSTRAINT ' + '[' + f.name  + ']'
			FROM 
				.sys.foreign_keys AS f
			INNER JOIN 
				.sys.foreign_key_columns AS fc 	ON f.OBJECT_ID = fc.constraint_object_id";

	
	$arr_drop_fk_sql = ms_query_col($sql);


	//print_r($arr_drop_fk_sql);
	foreach($arr_drop_fk_sql as $fk_sql)
	{
		#ms_doquery($fk_sql);
		echo $fk_sql .";<br>";
	}

	$arr_table = array('Holder',
						'ItemType',
						'ItemTypeGroup',
						'ItemTypeSupplier',
						'Supplier',
						'Account',
						'Location',
						'LocationGroup',
						'LocationGroupType',
						'NonSerializedInventoryItem');

	foreach($arr_table as $table)
	{
		$sql = "TRUNCATE table [".$table."];";
		#ms_doquery($sql);

		echo $sql."<br>";

		$file ="'D:\\test\\".$table.".csv'";


		$sql = "BULK INSERT
					[".$table."]
				FROM ".$file." 
				WITH
				(
					FIELDTERMINATOR = '^',
					ROWTERMINATOR = '=',
					KEEPNULLS,
					KEEPIDENTITY
				);";

		#ms_doquery($sql);
			echo $sql."<br>";

	}

	#turn them back on 
	$sql = "SELECT 
			DISTINCT
				'ALTER TABLE [' + OBJECT_NAME(f.parent_object_id)+ ']' +
				' ADD CONSTRAINT ' + '[' +  f.name  +']'+ ' FOREIGN KEY'+'('+COL_NAME(fc.parent_object_id,fc.parent_column_id)+')'
				+'REFERENCES ['+OBJECT_NAME (f.referenced_object_id)+']('+COL_NAME(fc.referenced_object_id,
			fc.referenced_column_id)+')' as Scripts
			FROM 
				.sys.foreign_keys AS f
			INNER JOIN 
				.sys.foreign_key_columns AS fc 	ON f.OBJECT_ID = fc.constraint_object_id
				where referenced_column_id !=2";
	

	$arr_add_fk_sql = ms_query_col($sql);

	foreach($arr_add_fk_sql as $fk_sql)
	{
		#ms_doquery($fk_sql);
		echo $fk_sql .";<br>";
	}

?>