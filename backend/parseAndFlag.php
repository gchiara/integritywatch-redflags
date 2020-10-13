<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
ini_set('max_execution_time', '0');
ini_set('memory_limit', '50G');

$file = file_get_contents("tenders_eu.json");
$tenders = json_decode($file);

//Extracting title from base64 encoded full xml content
$missingEnTenders = [];
$missingInfoTenders = [];
$xmlType1 = 0;
$i = 0;
$en_form_content = NULL;
$o_form_content = NULL;

//Saving institutions, cities and addresses for geocoding
$institutions = [];
$parsedInstitutions = [];
$cities = [];

//City names streamlining
$namesFix = array(
	"Bruxelles" => "Brussels",
	"Valetta" => "Valletta",
	"Ispra (VA)" => "Ispra",
	"Prague 7" => "Prague",
	"Copenhagen K" => "Copenhagen",
	"Warszawa" => "Warsaw",
	"Paris La Défense Cedex" => "Paris",
	"Dublin 2" => "Dublin",
	"Luxemburg" => "Luxembourg",
	"Grand Harbour Valletta" => "Valletta",
	"Belgium, Brussels 1046, BE100" => "Brussels",
	"Co. Dublin" => "Dublin",
	"Brussels (Evere)" => "Brussels",
	"Co Dublin" => "Dublin",
	"Paris la Défense CEDEX" => "Paris",
	"Luxemburg-Kirchberg" => "Luxemburg",
	"Paris La Defense Cedex" => "Paris",
	"Brussels — Evere" => "Brussels",
	"Strasbourg Cedex" => "Strasbourg",
	"Angers Cedex 2" => "Angers",
	"Angers Cédex 2" => "Angers",
	"Valleta" => "Valletta",
	"Brussel" => "Brussels",
	"Évreux Cedex" => "Évreux",
	"Lisboa" => "Lisbon",
	"Colmar Cedex" => "Colmar",
	"Roma"  => "Rome",
	"Paris 07 SP" => "Paris",
	"Paris Cedex 07" => "Paris",
	"Sint-Gillis" => "Saint-Gilles",
	"Brussles" => "Brussels",
	"Frankfurt am Main" => "Frankfurt",
	"Frankfurt-am-Main" => "Frankfurt",
	"Saint-Avold Cedex 1" => "Saint-Avold",
	"Clermont-Ferrand Cedex 1" => "Clermont-Ferrand",
	"Chambéry Cedex" => "Chambéry",
	"Laon Cedex" => "Laon",
	"Chambery Cedex" => "Chambéry",
	"Dublin 2 D02" => "Dublin",
	"Brussels 1046" => "Brussels",
	"Luksemburga" => "Luxembourg",
	"Luxemburgo" => "Luxembourg",
	"Lussemburgo" => "Luxembourg",
	"Den Haag" => "The Hague",
	"Wien" => "Vienna",
	"Ispra VA" => "Ispra",
	"Ispra (Varese)" => "Ispra",
	"ISPRA" => "Ispra",
	"München" => "Munich"
);

$HAmeaning = array(
	"PA" => "European Parliament",
	"CL" => "Council of the European Union",
	"other" => "European Institutions",
	"EC" => "European Commission",
	"CJ" => "Court of Justice of the European Union",
	"BC" => "European Central Bank",
	"CA" => "European Court of Auditors",
	"EA" => "European External Action Service",
	"ES" => "European Economic and Social Committee",
	"CR" => "European Committee of the Regions",
	"BI" => "European Investment Bank",
	"FI" => "European Investment Fund",
	"OP" => "Publications Office of the European Union",
	"AG" => "Agencies",
	"OB" => "European Patent Office",
	"BR" => "European Bank of Reconstruction and Development",
	"TX" => "External aid and European Development Fund",
	"AP" => "External aid programmes"
);

//Fix institutions names for institutions lists (cities dataset)
function fixInstitutionName($iname) {
	if(is_array($iname) || $iname == null) {
		echo "ISSUE WITH INSTITUTION NAME";
		return $iname;
	} 
	//$iname = json_decode($iname);
	//echo "INST NAME: ".$iname."<br />";
	$dashes = array("—", "–");
	$quotes = array("“", "”", "\u201c", "\u201d", "\u2018", "\u2019", "\"", "‘", "’");
	$fixedIname = str_replace($dashes, "-", $iname);
	$fixedIname = str_replace($quotes, "'", $fixedIname);
	//$fixedIname = strtoupper($iname);
	$fixedIname = mb_strtoupper($fixedIname, 'UTF-8');
	echo 'INSTITUTION FIXED NAME: '.$fixedIname.'<br />';
	return $fixedIname;
}

function isAssociative(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}


function checkDurationParameters($desc,$maxDuration) {
	//Check if duration exists, else check if start and end exist
	if(array_key_exists('DURATION', $desc)) {
		//echo 'Duration: '.$desc['DURATION'].'<br />';
		if($desc['DURATION'] > $maxDuration || $desc['DURATION'] == '' || $desc['DURATION'] == null ) {
			return true;
		}
	} else {
		if(array_key_exists('DATE_START', $desc) && array_key_exists('DATE_END', $desc)) {
			//echo $desc['DATE_START'].' - '.$desc['DATE_END'].'<br />';
			//Calc months difference
			$datetime1 = date_create($desc['DATE_START']);
			$datetime2 = date_create($desc['DATE_END']);
			$interval = date_diff($datetime1, $datetime2);
			//echo 'Date difference: '.$interval->format('%m months').'<br />';
			if(intval($interval->format('%m')) > $maxDuration) {
				return true;
			}
		} else if(array_key_exists('DATE_START', $desc)) {
			//echo $desc['DATE_START'].' - END MISSING<br />';
			return true;
		} else if(array_key_exists('DATE_END', $desc)) {
			//echo $desc['DATE_END'].' - START MISSING<br />';
		} else {
			return true;
		}
	}
}

//FLAG A: DURATION
function flagDuration_A($tender, $desc) {
	//Notice is form_section_en
	$sectors = $tender -> PC;
	$sectorsToExclude = ["66000000","66100000","66110000","66113000","66113100","66114000","66517100"];
	foreach ($sectors as $s) {
		if (in_array($s, $sectorsToExclude)) {
			return false;
		}
	}
	$maxDuration = 48;
	$IsDurationFlaggable1 = checkDurationParameters($desc,$maxDuration);
	$IsDurationFlaggable2 = true;
	if(array_key_exists('AC', $desc)) {
		$IsDurationFlaggable2 = checkDurationParameters($desc['AC'],$maxDuration);
	}
	if($IsDurationFlaggable1 && $IsDurationFlaggable2) {
		echo 'FLAGGED A: DURATION<br />';
		return true;
	}
	return false;
}

//FLAG B: LOW NUMBER OF TENDERS
function flagTendersNumLow_B($contract) {
	$minTenders = 3;
	//$contract is $notice['AWARDED_CONTRACT']
	// "AWARDED_CONTRACT" > "TENDERS" >  "NB_TENDERS_RECEIVED"
	if(array_key_exists('TENDERS', $contract) ) {
		if(array_key_exists('NB_TENDERS_RECEIVED', $contract['TENDERS'])) {
			$tenderNum = $contract['TENDERS']['NB_TENDERS_RECEIVED'];
			if($tenderNum == Null || $tenderNum == "" || $tenderNum < $minTenders) {
				echo "FLAGGED B: LOW TENDERS NUMBER (".$tenderNum.")<br />";
				return true;
			}
		} else {
			//Tenders received number missing
			echo "FLAGGED B: LOW TENDERS NUMBER (NB_TENDERS_RECEIVED missing)<br />";
			return true;
		}
	} else {
		echo "PROBLEM: NB_TENDERS_RECEIVED DOES NOT EXIST<br />";
	}
	return false;
}

//FLAG C: DIFFERENCE BETWEEN FINAL VALUE AND ESTIMATED VALUE and FLAG D HIGH TOTAL WITHOUT ESTIMATE
function flagValue_C($contract) {
	$maxDiff = 50;
	$maxTotal = 5000000;
	//$contract is $notice['AWARDED_CONTRACT']
	// "AWARDED_CONTRACT" > "TENDERS" >  "NB_TENDERS_RECEIVED"
	if(array_key_exists('VALUES', $contract)) {
		$values = $contract['VALUES'];
		if(array_key_exists('VAL_TOTAL', $values) && array_key_exists('VAL_ESTIMATED_TOTAL', $values)) {
			//Normal values
			echo "BOTH VALUES<br />";
			$x = $values['VAL_ESTIMATED_TOTAL'];
			$y = $values['VAL_TOTAL'];
			$diffCalc = ($y - $x)/$x*100;
			echo 'est: '.$x.', tot: '.$y.', Diff: '.$diffCalc.'%<br />';
			if($diffCalc > 50 || $diffCalc < -50) {
				echo "FLAGGED C<br />";
				return 'c';
			}
		} else if(array_key_exists('VAL_ESTIMATED_TOTAL', $values) && array_key_exists('VAL_RANGE_TOTAL', $values)) {
			//Estimated normal value, final range min max
			echo "VAL_ESTIMATED_TOTAL AND VAL_RANGE_TOTAL<br />";
			$x = $values['VAL_ESTIMATED_TOTAL'];
			$yHigh = $values['VAL_RANGE_TOTAL']['HIGH'];
			$yLow = $values['VAL_RANGE_TOTAL']['LOW'];
			$diffCalcHigh = ($yHigh - $x)/$x*100;
			$diffCalcLow = ($yLow - $x)/$x*100;
			if($diffCalcHigh > 50 || $diffCalcLow < -50) {
				echo "FLAGGED C<br />";
				return 'c';
			}
		} else if(array_key_exists('VAL_ESTIMATED_TOTAL', $values)) {
			echo "VAL_ESTIMATED_TOTAL ONLY<br />";
			print_r($values);
			echo '<br />';
		} else if(array_key_exists('VAL_TOTAL', $values)) {
			echo "VAL_TOTAL ONLY<br />";
			print_r($values);
			echo '<br />';
			if($values['VAL_TOTAL'] > $maxTotal) {
				echo "FLAGGED D<br />";
				return 'd';
			}
		} else if(array_key_exists('VAL_RANGE_TOTAL', $values)) {
			echo "VAL_RANGE_TOTAL ONLY<br />";
			print_r($values);
			echo '<br />';
			if($values['VAL_RANGE_TOTAL']['HIGH'] > $maxTotal) {
				echo "FLAGGED D<br />";
				return 'd';
			}
		} else {
			echo "PROBLEM: VALUES EMPTY<br />";
			print_r($values);
			echo '<br />';
		}
	} else {
		echo "NO VALUES<br />";
	}
	return false;
}

//FLAG E: NOT AWARDED WITHOUT REASON
function flagNotAwarded_E($notAwarded) {
	if(count($notAwarded) < 1) {
		echo "FLAGGED E: NO REASON FOR NOT BEING AWARDED: ".$notAwarded;
		return true;
	}
	return false;
}

//FLAG F: NOCONTRACT DATE
function flagNoContractDate_F($contract) {
	//$contract is $notice['AWARDED_CONTRACT']
	if(array_key_exists('DATE_CONCLUSION_CONTRACT', $contract)) {
		//echo 'Date contract: '.$contract['DATE_CONCLUSION_CONTRACT'].'<br />';
		if($contract['DATE_CONCLUSION_CONTRACT'] == "" || $contract['DATE_CONCLUSION_CONTRACT'] == Null) {
			echo "FLAGGED F: NO CONTRACT DATE<br />";
			return true;
		}
	} else {
		echo "FLAGGED F: NO CONTRACT DATE<br />";
		return true;
	}
	return false;
}

//FLAG G: SUCCESSFUL WITHOUT PUBLICATION
function flagNoPublication_G($coded) {
	//coded is coded data section
	if(array_key_exists('CODIF_DATA', $coded) && array_key_exists('PR_PROC', $coded['CODIF_DATA'])) {
		if($coded['CODIF_DATA']['PR_PROC'] == "Contract award without prior publication") {
			echo "FLAGGED G: NO PUBLICATION<br />";
			return true;
		}
	} else {
		echo "PROBLEM: PR_PROC MISSING<br />";
	}
	return false;
}

//FLAG H: FRAMEWORK AGREEMENT WITH LIMITED TENDERER
function agreementLimitedTenderer_H($notice) {
	//Notice is form_section_en
	if(array_key_exists('PROCEDURE', $notice) && array_key_exists('FRAMEWORK', $notice['PROCEDURE']) && array_key_exists('SINGLE_OPERATOR', $notice['PROCEDURE']['FRAMEWORK'])) {
		echo "FLAGGED H: SINGLE_OPERATOR<br />";
		return true;
	}
	return false;
}

//FLAG J: FRAMEWORK AGREEMENT LONG PERIOD WITHOUT REASON
function agreementLongPeriod_J($desc,$notice) {
	//Notice is form_section_en
	$maxDuration = 48;
	if(array_key_exists('PROCEDURE', $notice) && array_key_exists('FRAMEWORK', $notice['PROCEDURE'])) {
		$IsDurationFlaggable1 = checkDurationParameters($desc,$maxDuration);
		$IsDurationFlaggable2 = true;
		if(array_key_exists('AC', $desc)) {
			$IsDurationFlaggable2 = checkDurationParameters($desc['AC'],$maxDuration);
		}
		if($IsDurationFlaggable1 && $IsDurationFlaggable2) {
			if(!array_key_exists('JUSTIFICATION', $notice['PROCEDURE']['FRAMEWORK'])) {
				echo 'FLAGGED J: DURATION<br />';
				return true;
			}
		}
	}
	return false;
}

//FLAG K: HIGH ESTIMATED VALUE OF FRAMEWORK AGREEMENT
function agreementHighValue_K($notice,$coded) {
	//Notice is form_section_en, coded is coded_data_section
	if(array_key_exists('PROCEDURE', $notice) && array_key_exists('FRAMEWORK', $notice['PROCEDURE'])) {
		if(array_key_exists('NOTICE_DATA', $coded) && array_key_exists('VALUES', $coded['NOTICE_DATA']) && array_key_exists('VALUE', $coded['NOTICE_DATA']['VALUES'])) {
			$value = $coded['NOTICE_DATA']['VALUES']['VALUE'];
			if($value > 5000000) {
				echo "FLAGGED K: HIGH VALUE FRAMEWORK<br />";
				return true;
			}
		}
	}
	return false;
}

//FLAG L: ECONOMIC NO MINIMUM REQUIREMENTS
function econimicNoMinimum_L($notice) {
	//Notice is form_section_en
	if(array_key_exists('LEFTI', $notice) && !array_key_exists('ECONOMIC_CRITERIA_DOC', $notice['LEFTI'])) {
		if(!array_key_exists('ECONOMIC_FINANCIAL_INFO', $notice['LEFTI'])) {
			echo "FLAGGED L: ECONOMIC_CRITERIA_DOC NOT PRESENT<br />";
			return true;
		} else {
			$info = $notice['LEFTI']['ECONOMIC_FINANCIAL_INFO'];
			echo 'ECONOMIC_FINANCIAL_INFO INFO:<br />';
			print_r($info);
			echo '<br />';
			if(!array_key_exists('P', $info) || (is_array($info['P']) && count($info['P']) === 0) || (!is_array($info['P']) && $info['P'] == "")) {
				echo "FLAGGED L: ECONOMIC_CRITERIA_DOC NOT PRESENT<br />";
				return true;
			}
		}
	}
	return false;
}

//FLAG M: CRITERIA FOR CAPITAL LEVELS
function econimicCriteria_M($notice) {
	//Notice is form_section_en
	if(array_key_exists('LEFTI', $notice) && array_key_exists('ECONOMIC_CRITERIA_DOC', $notice['LEFTI']) && array_key_exists('ECONOMIC_FINANCIAL_MIN_LEVEL', $notice['LEFTI']['ECONOMIC_CRITERIA_DOC'])) {
		echo "FLAGGED M: ECONOMIC_FINANCIAL_MIN_LEVEL EXISTS<br />";
		return true;
	}
	return false;
}

//FLAG N: USE OF ACCELERATED PROCEDURE WITHOUT JUSTIFICATION
function acceleratedProcedure_N($notice) {
	//Notice is form_section_en
	if(array_key_exists('PROCEDURE', $notice) && array_key_exists('ACCELERATED_PROC', $notice['PROCEDURE'])) {
		$info = $notice['PROCEDURE']['ACCELERATED_PROC'];
		echo 'ACCELERATED_PROC INFO:<br />';
		print_r($info);
		echo '<br />';
		if(!array_key_exists('P', $info) || (is_array($info['P']) && count($info['P']) === 0) || (!is_array($info['P']) && $info['P'] == "")) {
			echo "FLAGGED N: ACCELERATED PROCEDURE<br />";
			return true;
		}
	}
	return false;
}

//FLAG O: USE OF COMPETITIVE NEGOTIATED PROCEDURE WITHOUT JUSTIFICATION
function competitiveProcedure_O($notice) {
	//Notice is form_section_en
	if(array_key_exists('PROCEDURE', $notice) && array_key_exists('PT_COMPETITIVE_NEGOTIATION', $notice['PROCEDURE'])) {
		$info = $notice['PROCEDURE']['PT_COMPETITIVE_NEGOTIATION'];
		echo 'PT_COMPETITIVE_NEGOTIATION INFO:<br />';
		print_r($info);
		echo '<br />';
		if(!array_key_exists('P', $info) || (is_array($info['P']) && count($info['P']) === 0) || (!is_array($info['P']) && $info['P'] == "")) {
			echo "FLAGGED O: COMPETITIVE NEGOTIATED PROCEDURE<br />";
			return true;
		}
	}
	return false;
}

//FLAG P: DEADLINE FOR BIDS DIFFERS FROM DATE OF OPENING
function deadlineDifference_P($notice) {
	//Notice is form_section_en
	if(array_key_exists('PROCEDURE', $notice) && array_key_exists('DATE_RECEIPT_TENDERS', $notice['PROCEDURE']) && array_key_exists('OPENING_CONDITION', $notice['PROCEDURE']) && array_key_exists('DATE_OPENING_TENDERS', $notice['PROCEDURE']['OPENING_CONDITION']) ) {
		if($notice['PROCEDURE']['DATE_RECEIPT_TENDERS'] !== $notice['PROCEDURE']['OPENING_CONDITION']['DATE_OPENING_TENDERS']) {
			echo "FLAGGED P: DEADLINE FOR BIDS DIFFERS FROM DATE OF OPENING: ".$notice['PROCEDURE']['DATE_RECEIPT_TENDERS']." - ".$notice['PROCEDURE']['OPENING_CONDITION']['DATE_OPENING_TENDERS']."<br />";
			return true;
		}
	}
	return false;
}

//FLAG Q: SEVERAL BIDDERS, LOW PARTICIPANTS
function biddersLowParticipants_Q($notice) {
	//Notice is form_section_en, coded is coded_data_section
	if(array_key_exists('PROCEDURE', $notice) && array_key_exists('FRAMEWORK', $notice['PROCEDURE']) && array_key_exists('SEVERAL_OPERATORS', $notice['PROCEDURE']['FRAMEWORK'])) {
		if(array_key_exists('NB_PARTICIPANTS', $notice['PROCEDURE']['FRAMEWORK']) && $notice['PROCEDURE']['FRAMEWORK']['NB_PARTICIPANTS'] < 3) {
			echo 'FLAGGED Q: SEVERAL BIDDERS, LOW PARTICIPANTS '.$notice['PROCEDURE']['FRAMEWORK']['NB_PARTICIPANTS'].'<br />';
			return true;
		}
	}
	return false;
}

//FLAG R: RESTRICTED PROCEDURE, LOW CANDIDATES
function restrictedProcedure_R($tender, $desc) {
	//desc is object_descr
	$pr = $tender -> PR;
	//echo 'PR: '.$pr.'<br />';
	if($pr == 2 || $pr == 3) {
		if(array_key_exists('NB_ENVISAGED_CANDIDATE', $desc) && $desc['NB_ENVISAGED_CANDIDATE'] < 5) {
			echo 'FLAGGED R: RESTRICTED PROCEDURE, LOW CANDIDATES<br />';
			return true;
		}
	}
	return false;
}

//FLAG S: NEGOTIATED PROCEDURE, LOW CANDIDATES
function negotiatedProcedure_S($tender, $desc) {
	//desc is object_descr
	$pr = $tender -> PR;
	if($pr == 4 || $pr == 6 || $pr == 'C' || $pr == 'T') {
		echo 'PR: '.$pr.'<br />';
		if(array_key_exists('NB_ENVISAGED_CANDIDATE', $desc)) {
			if($desc['NB_ENVISAGED_CANDIDATE'] < 3) {
				echo 'FLAGGED S: NEGOTIATED PROCEDURE, LOW CANDIDATES<br />';
				return true;
			}
		}
	}
	return false;
}

//FLAG T: CAN BE RENEWED FOR A LONG TIME OR SEVERAL TIMES
function renewableForLong_T($desc) {
	//desc is object_descr
	if(array_key_exists('RENEWAL', $desc)) {
		if(array_key_exists('RENEWAL_DURATION', $desc) && $desc['RENEWAL_DURATION'] > 48) {
			echo 'FLAGGED T: CAN BE RENEWED FOR A LONG TIME OR SEVERAL TIMES<br />';
			return true;
		}
		if(array_key_exists('RENEWAL_COUNT', $desc) && $desc['RENEWAL_COUNT'] > 2) {
			echo 'FLAGGED T: CAN BE RENEWED FOR A LONG TIME OR SEVERAL TIMES<br />';
			return true;
		}
		if(array_key_exists('RENEWAL_DESCR', $desc) && array_key_exists('P', $desc['RENEWAL_DESCR'])) {
			$renewalString = $desc['RENEWAL_DESCR']['P'];
			if(is_array($renewalString)) {
				$renewalString = implode(" ", $renewalString);
			}
			//More strings should be added, more present, example: "may be renewed up to maximum 3 (three) times"
			$stringsToMatch = ["may be renewed three times", "may be renewed four times", "may be renewed five times", "may be renewed 3 times", "may be renewed 4 times", "may be renewed 5 times"]; 
			foreach($stringsToMatch as $s) {
				$pos = strpos($renewalString, $s);
				if ($pos !== false) {
					echo 'FLAGGED T: CAN BE RENEWED FOR A LONG TIME OR SEVERAL TIMES<br />';
					return true;
				}
			}
		}
	}
	return false;
}

//FLAG U: CAN BE RENEWED WITHOUT EXTRA INFO
function renewableNoInfo_U($desc) {
	//desc is object_descr
	if(array_key_exists('RENEWAL', $desc)) {
		if(!array_key_exists('RENEWAL_COUNT', $desc) && !array_key_exists('RENEWAL_DURATION', $desc) && !array_key_exists('RENEWAL_DESCR', $desc)) {
			echo 'FLAGGED U: CAN BE RENEWED WITHOUT EXTRA INFO<br />';
			return true;
		}
	}
	return false;
}

//FLAG V: TOO FEW EVALUATION CRITERIA
function fewEvalCriteria_V($desc) {
	//desc is object_descr
	if(array_key_exists('AC', $desc)) {
		if(array_key_exists('AC_PROCUREMENT_DOC', $desc['AC'])) {
			return false;
		} else if(count($desc['AC']) < 2) {
			echo 'FLAGGED V: TOO FEW EVALUATION CRITERIA<br />';
			return true;
		}
	}
	return false;
}

//if(array_key_exists('AC', $desc)) {

//CHECK FLAGS FOR NOTICES
function flagNotice($tender,$notice,$coded) {
	//notice is form_section_en, coded is coded_data_section
	/* Flags:
	a: Term of contract (long or indefinite)
	h: Framework agreement with limited tenderer
	j: Framework agreement concluded for long period without justification
	k: Estimated value of framework agreement (high)
	l: Economic & financial ability – no minimum requirements
	m: Economic & financial ability – criteria for captial levels
	n: Use of accerated procedure without justification
	o: Use of competitive negotiated procedure without justification 
	p: Deadline for bids differ from date of openining 
	q: Framework agreement with several bidders - number of participants too low
	r: Restricted procedures - number of candidates low 
	s: Negotiated procedures - number of candidates low 
	t: Contract can be renewed (for a long time or several times)
	u: Contract can be renewed without any information
	v: Too few evaluation criteria 
	*/
	$flags = [];
	//Flag A and J for duration
	//Check if normal array or key->value. If it's a normal array it means there are multiple entries to check
	if(isAssociative($notice['OBJECT_CONTRACT']['OBJECT_DESCR'])) {
		if(flagDuration_A($tender,$notice['OBJECT_CONTRACT']['OBJECT_DESCR'])) {
			array_push($flags, 'a');
		}
		if(agreementLongPeriod_J($notice['OBJECT_CONTRACT']['OBJECT_DESCR'],$notice)) {
			array_push($flags, 'j');
		}
		//Flag R
		if(restrictedProcedure_R($tender,$notice['OBJECT_CONTRACT']['OBJECT_DESCR'])) {
			array_push($flags, 'r');
		}
		//Flag S
		if(negotiatedProcedure_S($tender,$notice['OBJECT_CONTRACT']['OBJECT_DESCR'])) {
			array_push($flags, 's');
		}
		//Flag T
		if(renewableForLong_T($notice['OBJECT_CONTRACT']['OBJECT_DESCR'])) {
			array_push($flags, 't');
		}
		//Flag U
		if(renewableNoInfo_U($notice['OBJECT_CONTRACT']['OBJECT_DESCR'])) {
			array_push($flags, 'u');
		}
		//Flag V
		if(fewEvalCriteria_V($notice['OBJECT_CONTRACT']['OBJECT_DESCR'])) {
			array_push($flags, 'v');
		}
	} else {
		foreach ($notice['OBJECT_CONTRACT']['OBJECT_DESCR'] as $desc) {
			if(flagDuration_A($tender,$desc)) {
				array_push($flags, 'a');
			}
			if(agreementLongPeriod_J($desc,$notice)) {
				array_push($flags, 'j');
			}
			//Flag R
			if(restrictedProcedure_R($tender,$desc)) {
				array_push($flags, 'r');
			}
			//Flag S
			if(negotiatedProcedure_S($tender,$desc)) {
				array_push($flags, 's');
			}
			//Flag T
			if(renewableForLong_T($desc)) {
				array_push($flags, 't');
			}
			//Flag U
			if(renewableNoInfo_U($desc)) {
				array_push($flags, 'u');
			}
			//Flag V
			if(fewEvalCriteria_V($desc)) {
				array_push($flags, 'v');
			}
		}
	}
	//Flag H
	if(agreementLimitedTenderer_H($notice)) {
		array_push($flags, 'h');
	}
	//Flag K - temporarely deactivated
	/*
	if(agreementHighValue_K($notice,$coded)) {
		array_push($flags, 'k');
	}
	*/
	//Flag L
	if(econimicNoMinimum_L($notice)) {
		array_push($flags, 'l');
	}
	//Flag M
	if(econimicCriteria_M($notice)) {
		array_push($flags, 'm');
	}
	//Flag N
	if(acceleratedProcedure_N($notice)) {
		array_push($flags, 'n');
	}
	//Flag O
	if(competitiveProcedure_O($notice)) {
		array_push($flags, 'o');
	}
	//Flag P
	if(deadlineDifference_P($notice)) {
		array_push($flags, 'p');
	}
	//Flag Q
	if(biddersLowParticipants_Q($notice)) {
		array_push($flags, 'q');
	}	
	return $flags;
}

//CHECK FLAGS FOR AWARD NOTICES
function flagAwardNotice($tender,$notice,$coded) {
	//$notice is $t['AWARD_CONTRACT']
	/* Flags:
	b: Number of tenders received low
	c: Difference between final value and estimated value
	d: High final value without estimated value
	e: Not awarded without reason
	f: Missing contract date
	g: Procedure succesful without prior publication
	w: Negotiated without a prior call for competition
	*/
	$flags = [];
	//Flag w
	if($tender -> PR == 'T') {
		echo 'FLAGGED W<br />';
		array_push($flags, 'w');
	}
	if(array_key_exists('AWARDED_CONTRACT', $notice)) {
		echo "AWARDED CONTRACT<br />";
		//Low number of tenders flag
		if(flagTendersNumLow_B($notice['AWARDED_CONTRACT'])) {
			array_push($flags, 'b');
		}
		//Values related flags - flag d temporarely deactivated
		$valueFlag = flagValue_C($notice['AWARDED_CONTRACT']);
		/*
		if($valueFlag == 'c' || $valueFlag == 'd') {
			array_push($flags, $valueFlag);
		}
		*/
		if($valueFlag == 'c') {
			array_push($flags, $valueFlag);
		}
		//No contract date flag
		if(flagNoContractDate_F($notice['AWARDED_CONTRACT'])) {
			array_push($flags, 'f');
		}
		//Successful without publication
		if(flagNoPublication_G($coded)) {
			array_push($flags, 'g');
		}
	} else if(array_key_exists('NO_AWARDED_CONTRACT', $notice)) {
		echo "NOT AWARDED CONTRACT<br />";
		//Not awarded without reason flag
		if(flagNotAwarded_E($notice['NO_AWARDED_CONTRACT'])) {
			array_push($flags, 'e');
		}
	} else {
		echo "PROBLEM: NEITHER AWARDED NOR NOT AWARDED<br />";
	}
	return $flags;
}

//CHECK MAIN FLAGGING SYSTEM
function flaggingSystem($tender,$t,$c) {
	//t is form_section_en, c is coded data section
	//Check if it's a contract award notice or an award notice
	if(array_key_exists('AWARD_CONTRACT', $t)) {
		echo "AWARD CONTRACT NOTICE <br />";
		//Check if normal array or key->value. If it's a normal array it means there are multiple entries to check
		if(isAssociative($t['AWARD_CONTRACT'])) {
			echo "single award entry <br />";
			$flags = flagAwardNotice($tender,$t['AWARD_CONTRACT'],$c);
			return $flags;
		} else {
			echo "multiple award entries <br />";
			//Loop through the entries and flag each, then remove duplicate flags
			$flags = [];
			foreach ($t['AWARD_CONTRACT'] as $award) {
				$thisFlags = flagAwardNotice($tender,$award,$c);
				$flags = array_merge($flags, $thisFlags);
			}
			return array_values(array_unique($flags));
		}
	} else {
		echo "CONTRACT NOTICE <br />";
		$flags = flagNotice($tender,$t,$c);
		return array_values(array_unique($flags));
	}
	return [];
}

//Loop through tenders to extract parameters needed for charts and table and save the separate full tender jsons
foreach($tenders as $key => $tender) {
	$uid = $tender -> ND;
	$en_form_content = NULL;
	$o_form_content = NULL;
	$tendercontent = base64_decode($tender -> content);
	$tendercontent = str_replace("xmlns=\"ted/R2.0.9.S02/publication\"", "xmlns=\"http://publications.europa.eu/resource/schema/ted/R2.0.9/publication\"", $tendercontent);
	$filepath = './xml/'.$uid.'.xml';
	file_put_contents($filepath, $tendercontent);
	$xml = simplexml_load_string($tendercontent) or die("Error: Cannot create object");
	//If it's not EU institution or EU funded, remove. Already done in previous step (other script) <CA_TYPE VALUE="EU_INSTITUTION"/> <EU_PROGR_RELATED>
	//Assign empty titles
	$tender -> title_en = null;
	$tender -> title_o = null;
	//Loop through translations, find english, get title and other useful values
	foreach($xml->FORM_SECTION->children() as $forms) {
		$langsNumber = $xml->FORM_SECTION->children()->count();
		$category = $forms ->attributes()-> CATEGORY;
		//echo "Language num: ".$langsNumber."<br />";
		$category = $forms ->attributes()-> CATEGORY;
		$lang = $forms ->attributes()-> LG;
		$enFound = false;
		if($lang == 'EN' || ($category == 'ORIGINAL' && $enFound == false)) {
			$title = '';
			if($forms -> OBJECT_CONTRACT -> TITLE) {
				//echo 'Title present';
				$title = (string) $forms -> OBJECT_CONTRACT -> TITLE -> P;
				//echo 'TITLE: '.$title.'<br />';
				$xmlType1 ++;
			} else {
				//Keep track of tender id if info can't be found, likely means the xml has a different structure
				echo 'Title not found';
				array_push($missingInfoTenders, $i);
			}
			//Save the form content so we can remove the other languages
			if($lang == 'EN') {
				$enFound = true;
				$en_form_content = json_decode(json_encode($forms),TRUE);
				$tender -> title_en = $title;
			} else {
				$o_form_content = json_decode(json_encode($forms),TRUE);
				$tender -> title_o = $title;
			}
			if($forms -> CONTRACTING_BODY) {
				$tender -> cb_name = (string) $forms -> CONTRACTING_BODY -> ADDRESS_CONTRACTING_BODY -> OFFICIALNAME;
				if($forms -> CONTRACTING_BODY -> CA_TYPE) {
					$tender -> cb_type = (string) $forms -> CONTRACTING_BODY -> CA_TYPE -> attributes()-> VALUE;
				}
				$tender -> cb_town = (string) $forms -> CONTRACTING_BODY -> ADDRESS_CONTRACTING_BODY -> TOWN;
				//Fix city name only if EU instutution or agency, for dots
				//if($forms -> CONTRACTING_BODY && $forms -> CONTRACTING_BODY -> CA_TYPE && $forms -> CONTRACTING_BODY -> CA_TYPE -> attributes()-> VALUE == "EU_INSTITUTION") {
				if($tender -> AA == 5) {
					if(array_key_exists($tender -> cb_town, $namesFix)) {
						$tender -> cb_town = $namesFix[$tender -> cb_town];
					}
				}
			}
			//PARSE FLAGS
			$codedDataSection = json_decode(json_encode($xml->CODED_DATA_SECTION),TRUE);
			if($en_form_content) {
				$flags = flaggingSystem($tender,$en_form_content,$codedDataSection);
			} else if($o_form_content) {
				$flags = flaggingSystem($tender,$o_form_content,$codedDataSection);
			} else {
				echo "PROBLEM: MISSING ANY FORM CONTENT, NO EN AND NO ORIGINAL";
			}
			$tender -> flags = json_decode(json_encode($flags),TRUE);
			print_r($flags);
			echo '<br />';
			//If eu institution save name and address
			//if($forms -> CONTRACTING_BODY && $forms -> CONTRACTING_BODY -> CA_TYPE && $forms -> CONTRACTING_BODY -> CA_TYPE -> attributes()-> VALUE == "EU_INSTITUTION") {
			$institutionName = (string) $xml -> CODED_DATA_SECTION -> CODIF_DATA -> INITIATOR;
			if($tender -> AA == 5 && array_key_exists($institutionName, $HAmeaning)) {
			//if($tender -> AA == 5) {
				//$institutionNameFull = fixInstitutionName($forms -> CONTRACTING_BODY -> ADDRESS_CONTRACTING_BODY -> OFFICIALNAME);
				echo 'INST NAME: '.$institutionName.'<br />';
				$institutionName = $HAmeaning[$institutionName];
				$tender -> cb_name_fixed = $institutionName;
				//Name fix for some towns
				$cityName = (string) $forms -> CONTRACTING_BODY -> ADDRESS_CONTRACTING_BODY -> TOWN;
				if(array_key_exists($cityName, $namesFix)) {
					$cityName = $namesFix[$cityName];
				}
				//$institutionAddress = $forms -> CONTRACTING_BODY -> ADDRESS_CONTRACTING_BODY -> ADDRESS;
				$institutionString = $institutionName." ".$cityName;
				//$institutionInfo = $forms -> CONTRACTING_BODY -> ADDRESS_CONTRACTING_BODY;
				$institutionInfo = (object)[];
				$institutionInfo -> name = $institutionName;
				//INSTITUTION DATASET
				if (!in_array($institutionString, $parsedInstitutions)) {
					//CITIES BASED DATASET
					//See if city already saved
					$cityKey = array_search($cityName, array_column($cities, 'city'));
					//echo "CITY KEY: ".$cityKey." CITY: ".$institutionTown."<br />";
					if($cityKey == false && $cityKey !== 0) {
						$newcity = (object) array(
							"city" => $cityName,
							"institutions" => [],
						);
						array_push($newcity -> institutions, json_decode(json_encode($institutionInfo),TRUE));
						array_push($cities, $newcity);
					} else {
						$instKey = array_search($institutionName, array_column($cities[$cityKey] -> institutions, 'OFFICIALNAME'));
						if($instKey == false && $instKey !== 0) {
							array_push($cities[$cityKey] -> institutions, json_decode(json_encode($institutionInfo),TRUE));
						}
					}
					//Save instutution as parsed
					array_push($parsedInstitutions, $institutionString);
					$institutionInfo -> city = $cityName;
					array_push($institutions, json_decode(json_encode($institutionInfo),TRUE));
				} else {
					//echo "EU INSTITUTION ALREADY PARSED<br />";
				}
			}
			flush();
			ob_flush();
		}
	}
	//Keep track of tender id if en info can't be found
	if($tender -> title_en == null && $tender -> title_o == null) {
		array_push($missingEnTenders, $uid);
	}
	//Remove xml info from main json
	unset($tender -> content);
	//Save full Xml
	$jsonToSaveFull = json_decode(json_encode($xml),TRUE);
	//file_put_contents('./full_xmls/'.$uid.'.json', json_encode($jsonToSaveFull));
	//Remove form section from xml/json to only keep EN version and make it lighter
	unset($xml->FORM_SECTION);
	//Save the xml content converted to json for specific tender, with uid name
	$jsonToSave = json_decode(json_encode($xml),TRUE);
	if($en_form_content) {
		$jsonToSave['lang'] = 'en';
		$jsonToSave['FORM_SECTION'] = $en_form_content;
	} else {
		$jsonToSave['lang'] = 'o';
		$jsonToSave['FORM_SECTION'] = $o_form_content;
	}
	//echo 'UID: '.$uid.'<br />';
	file_put_contents('./tenders_data/'.$uid.'.json', json_encode($jsonToSave));
	echo $i.") parsed tender ".$uid."<br /><br /><br />";
	flush();
	ob_flush();
	$i ++;
}

//Output some debug info
echo 'Final length: '.count($tenders).'<br />';
echo 'Missing EN info: '.count($missingEnTenders).'<br />';
echo 'EN found but likely different xml structure: '.count($missingInfoTenders).'<br />';
echo 'Xml type1: '.$xmlType1.'<br />';
//Clean tenders keys due to the unsets
$tenders = array_values($tenders);
//Save full list as json file and the ids lists for tenders with missing en or different xml structure
file_put_contents('tenders_parsed.json', json_encode($tenders));
file_put_contents('missing_en_tenders.json', json_encode($missingEnTenders));
file_put_contents('missing_info_tenders.json', json_encode($missingInfoTenders));
file_put_contents('institutions.json', json_encode($institutions));
file_put_contents('cities.json', json_encode($cities));

?>