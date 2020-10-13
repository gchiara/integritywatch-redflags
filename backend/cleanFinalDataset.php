<?php

ini_set('max_execution_time', '0');
ini_set('memory_limit', '50G');

$file = file_get_contents("tenders_parsed.json");
$tenders = json_decode($file);

foreach($tenders as $key => $tender) {
    unset($tender -> OC);
	unset($tender -> AC);
	unset($tender -> RC);
	unset($tender -> RN);
	unset($tender -> DD);
	unset($tender -> DI);
	unset($tender -> DT);
	unset($tender -> MA);
	unset($tender -> OC);
	unset($tender -> OJ);
	unset($tender -> OY);
	unset($tender -> OL);
	unset($tender -> DS);
	unset($tender -> RC);
	unset($tender -> RN);
	unset($tender -> RP);
	unset($tender -> TY);
	unset($tender -> cb_type);
	echo 'Cleaned entry '.$key.'<br />';
	flush();
	ob_flush();
}
file_put_contents('tenders_cleaned.json', json_encode($tenders));
echo 'DONE<br />';

/*
AA used
AC already removed
CY used
DD documentation date, not present, can be removed
DI legal basis, not used
DS was used, replaced with PD so removed
DT deadline, not used
MA not used, removed
NC used
ND used, id
OC not sure what it is, removed
OJ not used, removed
OL original language, removed
OY not sure, removed
PC used
PD publication date, replaced DS with this
PR type of procedure, not used, but could be useful for later
RC NUTS code, not used
RN not sure, removed
RP market regulation, not used, removed
TD used, document type
TVH used
TVL used
TY type of bid, not used
*/

?>