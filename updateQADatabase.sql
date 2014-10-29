<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include('ms-dbfunc.php');

	$ms_connect = ms_connect();

SELECT 'ALTER TABLE ' + '[' + OBJECT_NAME(f.parent_object_id)+ ']'+
' DROP  CONSTRAINT ' + '[' + f.name  + ']'
FROM .sys.foreign_keys AS f
INNER JOIN .sys.foreign_key_columns AS fc
ON f.OBJECT_ID = fc.constraint_object_id;
