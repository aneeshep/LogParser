<?php


//Check Inputs
$cmdOptions = getopt('f:', array('file:'));
  if (empty($cmdOptions) ) { 
        exit("\nError: Input file is missing. \n\n Usage: $argv[0] --file filename\n\n"); 
         
    } 

echo "Initializing the engine \n";

$file = isset($cmdOptions['file']) ? $cmdOptions['file'] : $cmdOptions['f'];

//Validate the file
if(file_exists($file))
	if(is_readable($file))
		echo "Reading File.\n";
	else 
		exit( "Error: Cannot Read file: $file . Exiting. \n");
else
	exit("Error: File Does not Exist. Exiting. \n");


$data = array();
$months = array(
	'Jan' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()),
	'Feb' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Mar' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Apr' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'May' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Jun' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Jul' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Aug' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Sep' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Oct' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Nov' => array('totalCount' => 0, 'raw' => array(), 'counts' => array()), 
	'Dec' => array('totalCount' => 0, 'raw' => array(), 'counts' => array())
);

$types = array('studioLoad', 'TOMCAT', 'generateWar', 'CLOUD_JEE', 'WAR', 'EAR', 'CLOUD_FOUNDRY');

//Parse the Log File
echo "Parsing the File\n";
$handle =  popen("grep '/img/blank.gif?op=' " .$file , "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {

        //Find Year, Month
        preg_match('/\[(.+?)\/(.+?)\/(.+?):.*\]/', $line, $matches);
        
        // Insert the Year in to the Result Array
        if( ! array_key_exists($matches[3], $data))
	        $data[$matches[3]] = $months;



	    //Find the type 
	    preg_match('/op=(.*?)&/', $line, $type);

	    //Add the Data to the curresponding Month.
	    //array_push($data[$matches[3]][$matches[2]]['raw'], $line);
	    
	    if(in_array($type[1], $types))
	    {
	    	$data[$matches[3]][$matches[2]]['totalCount']++;
	    	if( ! array_key_exists($type[1], $data[$matches[3]][$matches[2]]['counts']))
	    		$data[$matches[3]][$matches[2]]['counts'][$type[1]] = 1;
	    	else
	    		$data[$matches[3]][$matches[2]]['counts'][$type[1]]++;
	    }
		



    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    pclose($handle);
}


//Display The Result
echo "Preparing the result\n\n";
echo "\t\t============================================== Analytics Report =====================================================\n"
	 ."\t\t=====================================================================================================================";

foreach ($data as $year => $months) {
	
	echo "\n\n\t\tYear : $year \n\n\n\t\t\t";

	foreach ($months as $monthName => $value) {
		
		echo "\t $monthName ";	

	}
	echo "\tTotal";
	echo "\n\t\t\t\t --- \t --- \t --- \t --- \t --- \t --- \t --- \t --- \t --- \t --- \t --- \t ---\t-----\n\t";

	
	foreach ($types as $type) {

		$totalForYear = 0;
		printf("\n\n\t\t%-10s", $type);
		foreach ($months as $month => $value) {

			if(isset($value['counts'][$type]))
			{
				echo "\t " . $value['counts'][$type];
				$totalForYear += $value['counts'][$type];
			}
			else
			{
				echo "\t 0";
			}
		
			
			
		}
		echo "\t$totalForYear";
		echo "\n\t";
	}
	
}

echo "\n\n\t\t-------------------------------------------------------------------------------------------------------------------";


echo "\n\t\t============================================== Analytics Ends =====================================================\n"
	 ."\t\t===================================================================================================================\n\n";

?>

