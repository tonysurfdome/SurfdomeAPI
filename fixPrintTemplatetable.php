

<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');


echo "FFF";

include('ms-dbfunc.php');

$ms_connect = ms_connect();

 $sql = "SELECT
			[PrintTemplateId]
			,[Url]
		FROM 
			PrintTemplate";



$arr_data = ms_query_all($sql);


foreach($arr_data as $data)
{
		$url = $data['Url'];

		echo $url."<br>";

		$patterns = '/www.stpvx.com/';
		$replacements = '54.247.191.157';



		$url = preg_replace($patterns, $replacements, $url);
		
		echo $url."<br>";

		$sql = "Update 
					PrintTemplate
				Set 
					url = '".$url."'
				WHERE
				[PrintTemplateId] = ".$data['PrintTemplateId'];
		ms_doquery($sql);
					



}


$sql = "Truncate table [PrintRequest]";
ms_doquery($sql);


$sql = "insert into [PrintTerminal]
  (
  [PrintTypeId]
      ,[Name]
      ,[Online]
        ,[Active]
     )
     values
     (3,'TonyDesk-ZebraBigZM600', 0, 1)";


ms_doquery($sql);




?>