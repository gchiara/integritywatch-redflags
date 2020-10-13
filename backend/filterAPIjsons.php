<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
ini_set('max_execution_time', '0');
ini_set('memory_limit', '50G');

$totPages = 6773;
$tendersAll = [];
$EUprog = 0;
$EUinst = 0;
$AA5 = 0;

for ($x = 1; $x <= $totPages; $x++) {
	$file = file_get_contents('./api_response/'.$x.'.json');
	$tenders = json_decode($file);
	//Loop through tenders to discard non EU (funds or institutions) related ones
	foreach($tenders as $key => $tender) {
		$tendercontent = base64_decode($tender -> content);
		$toKeep = false;
		if(strpos($tendercontent, '<EU_PROGR_RELATED>') !== false) {
			$EUprog ++;
			$toKeep = true;
		}
		if(strpos($tendercontent, '<CA_TYPE VALUE="EU_INSTITUTION"/>') !== false) {
			$EUinst ++;
			$toKeep = true;
		}
		if($tender -> AA == 5) {
			$AA5 ++;
		}
		if($toKeep === true) {
			array_push($tendersAll, $tender);
			echo $key.' kept <br />';
		}
		flush();
		ob_flush();
	}
}

//Output some debug info
echo 'EU prog: '.$EUprog.'<br />';
echo 'CA Type = EU_INSTITUTION: '.$EUinst.'<br />';
echo 'AA 5: '.$AA5.'<br />';
echo 'Final length: '.count($tendersAll).'<br />';
flush();
ob_flush();
//Save full list as json file
file_put_contents('tenders_eu.json', json_encode($tendersAll));

?>