<?php


	function ms_connect($setting= false)
	{

	$user='sduser';
	$pass='password';
	$database="PeopleVox.OneBusinessPortal.SurfdomeQa2536";
	$serverName = 'AMAZONA-3N9JDND';

		$connectioninfro = array("Database" => $database, "UID" => $user,"PWD" => $pass);

		$__sqlsrvLINK__ = sqlsrv_connect($serverName, $connectioninfro);

		if ($__sqlsrvLINK__) 
		{
			//die('Something went wrong while connecting to MSSQL');
		}
		else
		{
			echo print_r(sqlsrv_errors(), true);
		}

		$GLOBALS['ms_connect']=$__sqlsrvLINK__;

		return $__sqlsrvLINK__;
	}

	/**
	 * ms_doquery()
	 *
	 * @param mixed $query
	 * @param bool $cmdln
	 * @return
	 */
	function ms_doquery($query, $cmdln=false)
	{
		global $ms_connect;

		$result = sqlsrv_query($ms_connect, $query);

		return $result;
	}


	function ms_dberror($sql, $error, $user,$ip_address)
	{
		echo "Query failed : " . $error;
		$arr_err['error']		=  sqlsrv_real_escape_string($error);
		$arr_err['program']		=  sqlsrv_real_escape_string($_SERVER['PHP_SELF']);
		$arr_err['query']		=  sqlsrv_real_escape_string($sql);
		$arr_err['user']		=  sqlsrv_real_escape_string($user);
		$arr_err['createdate']  = date('Y-m-d H:i:s', time());
		$arr_err['ip']			=  sqlsrv_real_escape_string($ip_address);
		//ms_dbinsert('st_dberrorlog',$arr_err);
	}


	/**
	 * dbinsert()
	 *
	 * @param mixed $file
	 * @param mixed $keysandvals
	 * @return
	 */
	function ms_dbinsert($file,$keysandvals)
	{
		$newArr = array();
		foreach($keysandvals as $key=>$val)
			$newArr[$key] =  sqlsrv_real_escape_string($val);
		
		
		$keysandvals = $newArr;

		$vals  = implode("\",\"",array_values($keysandvals));
		$cols  = implode(",",array_keys($keysandvals));
		$query = "insert into " . $file . " (" . $cols . ") values (\"" . $vals . "\")";

		//$lin   = connect();


		ms_doquery($query);
	}


	/**
	 * dbreplace()
	 *
	 * @param mixed $file
	 * @param mixed $keysandvals
	 * @return
	 */
	function ms_dbreplace($file,$keysandvals)
	{
		$vals  = implode("\",\"",array_values($keysandvals));
		$cols  = implode(",",array_keys($keysandvals));
		$query = " replace into " . $file . " (" . $cols . ") values (\"" . $vals . "\")";

		$lin=connect();

		ms_doquery($query);

		//  sqlsrv_close($lin);
	}

	/**
	* Takes a SQL query and returns a single value
	*
	* @param	string	query string to execute
	*
	* @return	mixed	value, NULL if no records found
	*/
	function ms_query_value($str_query) {
		$result = ms_doquery($str_query);
		$row    = sqlsrv_fetch_array( $result, SQLSRV_FETCH_NUMERIC);
		return ($row) ? $row[0] : null;
	}

	/**
	* Takes a SQL query and returns a single row as an array
	*
	* @param	string	query string to execute
	*
	* @return	mixed	assoc array by field name if record found, NULL otherwise
	*/
	function ms_query_row($str_query) {

		$result = ms_doquery($str_query);
		$row    =  sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC);
	
		return ($row) ? $row : null;
	}

	/**
	* Takes a SQL query and returns the full dataset as an indexed array of associative arrays
	*
	* @param	string	query string to execute
	*
	* @returns	array	indexed array of associative arrays (representing each row)
	*/
	function ms_query_all($str_query) {

		$result =  ms_doquery($str_query);

		$arr_rows = array();
		while($row =  sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			$arr_rows[] = $row;
		}

		return ($arr_rows) ? $arr_rows : Array();

	}

	
	/**
	* Takes a SQL query and returns full dataset as an associative array with the first field in the row behind used as the key
	*
	* @param	string	query string to execute
	*
	* @returns	array	associative array of associative arrays (representing each row)
	*/
	function ms_query_all_assoc($str_query) {

		$result = ms_doquery($str_query);

		$arr_rows = array();
		while($row =  sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			$k = reset($row);
			$arr_rows[$k][] = $row;
		}

		return ($arr_rows) ? $arr_rows : Array();

	}


	/**
	* Takes a SQL query and returns an array of the first column values
	*
	* @param string	$query_text		the SQL to execute
	*
	* @returns Array
	*/
	function ms_query_col($query_text) {

		$result = ms_doquery($query_text);

		$arr_rows = Array();
		while($row =  sqlsrv_fetch_array( $result)) {
			$arr_rows[] = $row[0];
		}
		return $arr_rows;
	}


	/**
	* Takes a SQL query with two fields and returns an associtive array of the first field => second field
	*
	* @param string	$query_text		the SQL to execute
	*
	* @returns Array
	*/


	function ms_query_col_assoc($query_text) {

		$result = ms_doquery($query_text);

		$arr_rows = Array();
		while ($row =  sqlsrv_fetch_array( $result)) {
			$arr_rows[$row[0]] = $row[1];
		}

		return $arr_rows;
	}


	/**
	* Returns a single value escaped and wrapped in quotes
	*
	* @param string		$str		the value you want to quote
	*
	* @returns string
	*/
	function ms_quote($str) {
		return "'".$str."'";
	}


	/**
	* Takes a array of values to quote and returns a string suitable for an IN () statement
	*
	* @param Array		$arr		the list of values you want to quoted
	*
	* @returns string
	*/
	function ms_quote_list($arr) {

		$sql = '';
		foreach ($arr as $str) {
			$sql .= "'".$str."',";
		}
		return substr($sql, 0, -1);

	}



?>
