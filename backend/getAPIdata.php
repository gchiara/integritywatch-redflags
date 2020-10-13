<?php

ini_set('max_execution_time', '0');
ini_set('memory_limit', '50G');

//With UK, from 2017
$query="CY%3D%5BEU%20or%20UK%5D%20AND%20TD%3D%5B7%20or%203%5D%20AND%20TV_CUR%3D%5BEUR%5D%20AND%20PD%3D%5B20170101%20%3C%3E%2020200924%5D";

$fields = "fields=&fields=AA&fields=AC&fields=CY&fields=DD&fields=DI&fields=DS&fields=DT&fields=MA&fields=NC&fields=ND&fields=OC&fields=OJ&fields=OL&fields=OY&fields=PC&fields=PD&fields=PR&fields=RC&fields=RN&fields=RP&fields=TD&fields=TVH&fields=TVL&fields=TY&fields=CONTENT&";
$resultsPerPage = 100;
$url = 'https://ted.europa.eu/api/v2.0/notices/search?'.$fields.'pageNum=1&pageSize='.$resultsPerPage.'&q='.$query.'&reverseOrder=false&scope=3&sortField=ND';

$response = file_get_contents($url);

//Get total results to calculate pages to loop
if($response !== false) {
	echo "Processing...<br />";
	$obj = json_decode($response);
	echo "Total results: ".$obj->total."<br />";
	$thisPageLength = count($obj->results);
	echo "This page entries: ".$thisPageLength."<br />";
	$totPages = intdiv($obj->total , $resultsPerPage) + 1;
	echo "Tot pages: ".$totPages."<br /><br />";
	file_put_contents('./api_response/1.json', json_encode($obj->results));
	flush();
	ob_flush();
	//Loop for the next queries
	for ($x = 2; $x <= $totPages; $x++) {
	  $url = 'https://ted.europa.eu/api/v2.0/notices/search?'.$fields.'pageNum='.$x.'&pageSize='.$resultsPerPage.'&q='.$query.'&reverseOrder=false&scope=3&sortField=ND';
	  $response = file_get_contents($url);
	  if($response !== false) {
		  $obj = json_decode($response);
		  $thisPageLength = count($obj->results);
		  echo "Page ".$x." entries: ".$thisPageLength."<br />";
		  file_put_contents('./api_response/'.$x.'.json', json_encode($obj->results));
	  }
	  echo "parsing page ".$x."<br />";
	  flush();
	  ob_flush();
	}
} else {
	echo 'First call failed';
}

?>