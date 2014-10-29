<?php




	error_reporting(E_ALL);
	ini_set('display_errors', '1');

ini_set("memory_limit","999M");

$itemtype = file_get_contents('http://www.stpvx.com/peoplevox/SurfdomeApi/data/itemType.csv');

$filename  = "D:\\test\\itemType1.csv";
$fp        = fopen($filename, 'w');
fwrite($fp,$itemtype);


/*


$file = fopen("D:\\test\\itemType1.csv", "r");
$filename = "D:\\test\\tt.txt";
$fp        = fopen($filename, 'w');

while(!feof($file)){
    $line = fgets($file);


	$line1 =substr($line, 0, strrpos($line, '^', -1));


	str_replace("\n", '', $line1);
str_replace("\r", '', $line1);
str_replace("\r\n", '', $line1);




	if($line1 !='')
	{
		$line1 = $line1."^\n";
		fwrite($fp, $line1);
	}


}
  
fclose($file);
*/
fclose($fp);

echo "ok";

?>