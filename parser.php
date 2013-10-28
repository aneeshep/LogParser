<?php

echo "Initializing the engine \n";

$file1 = "./access_log";
$data = array();
$months = array(
	'Jan' => array('count' => 0, 'data' => array()),
	'Feb' => array('count' => 0, 'data' => array()), 
	'Mar' => array('count' => 0, 'data' => array()), 
	'Apr' => array('count' => 0, 'data' => array()), 
	'May' => array('count' => 0, 'data' => array()), 
	'Jun' => array('count' => 0, 'data' => array()), 
	'Jul' => array('count' => 0, 'data' => array()), 
	'Aug' => array('count' => 0, 'data' => array()), 
	'Sep' => array('count' => 0, 'data' => array()), 
	'Oct' => array('count' => 0, 'data' => array()), 
	'Nov' => array('count' => 0, 'data' => array()), 
	'Dec' => array('count' => 0, 'data' => array())
);


//Parse the Log File
echo "Parsing the File\n";
$handle =  popen("grep '/img/blank.gif?op=studioLoad' " .$file1 , "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {

        //Find Year, Month
        $year = preg_match('/\[(.+?)\/(.+?)\/(.+?):.*\]/', $line, $matches);
        
        // Insert the Year in to the Result Array
        if( ! array_key_exists($matches[3], $data))
	        $data[$matches[3]] = $months;

	    //Add the Data to the curresponding Month.
	    array_push($data[$matches[3]][$matches[2]]['data'], $line);
	    $data[$matches[3]][$matches[2]]['count']++;


    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    pclose($handle);
}


//Display The Result
echo "Preparing the result\n";
echo "
	=============== Analytics Report ==================
	===================================================
	";

$grandTotal = 0;

foreach ($data as $year => $months) {
	
	echo "\n\tYear : ".  $year;
	echo "\n\n\tCounts:";

	echo "\n\t\t Month \t\t Count";
	echo "\n\t\t ----- \t\t -----";

	$totalForYear = 0;
	foreach ($months as $month => $value) {
		
		echo "\n\t\t ". $month . "\t\t ". $value['count'];
		$totalForYear += $value['count'];
	}
	echo "\n\t\t ----------------------";
	echo "\n\t\t Total: \t " . $totalForYear;

	$grandTotal += $totalForYear;

}

echo "\n\n\t ---------------------------------------------------";

echo "\n\t\t\t\t\t Grand Total : " . $grandTotal;
echo "\n\n\t================= Analytics End ====================
	====================================================";

?>
