<?php

error_reporting(0);

/* Author: Victoria Zhong *
 * Date: April 2018       */

$db = mysql_connect('localhost', 'bigdata', 'bigdata');
mysql_select_db("ADKnowledgeBase", $db);

$query = $_GET['query'];
$querytype = $_GET['type'];
$order = $_GET['order'];

echo $_GET['type'].";".$_GET['query'];

if($querytype == "getInteractingGenes"){

	$getGenes = 'SELECT b FROM EntrezGeneSymbols INNER JOIN PPI ON EntrezGeneSymbols.entrez_id = PPI.a WHERE a = "'.$query.'";';

	//use dynamic programming to get all the n order interacting genes
	//store each visited gene in an array, before store, check to see if gene has been visited ie is in array already
	//for each order completed delimit with a | ie 2nd order would be 1,2,3,| 4,5,6 third would be 1,2,3|4,5,6| 7,8,9
	//then if not last order query all the genes until next |

	$queried = array();

	$rows = mysql_query($getGenes);

	while($rowData = mysql_fetch_row($rows)){
		for($i = 0; $i < count($rowData); $i++){
			array_push($queried, $rowData[$i]);
		}
	}
	echo '; <br /> <b>1st Order:</b> '; //br after the semicolon means the break is before the next item ie after the word "are"
	echo implode(';', $queried);

	$start = 0;
	$end = count($queried); 

	if($order > 1){
		//do the thing
		for ($i = 2; $i <= $order; $i++){
			//we do it for each order
			if ($i == 2){ echo '; <br /> <b> 2nd Order: </b> '; }
			else if ($i == 3){ echo '; <br /> <b>3rd Order:</b> '; }
			else{ echo '; <br /><b>'.$i.'th Order:</b> '; }
			//gets the ordinal numbers correct

			for($j = $start; $j < $end; $j++){ //each order occurs between [$start] and [$end]
				//echo $j; 
				$getGenes = 'SELECT b FROM EntrezGeneSymbols INNER JOIN PPI ON EntrezGeneSymbols.entrez_id = PPI.a WHERE a = "'.$queried[$j].'";';
				$rows = mysql_query($getGenes);

				while($rowData = mysql_fetch_row($rows)){
					for($k = 0; $k< count($rowData); $k++){
						array_push($queried, $rowData[$k]);
						//echo $rowData[$k];
					}
				}
			}
			$queried = array_unique($queried); //removes duplicates so will not requery
			//echo implode(';', $queried);
			$start = $end;
			echo $start;
			$end = count($queried);
			//next round will query the next order which is between where the last ended+1 and the actual end

			echo implode(';', array_slice($queried, $start)); //echos out all the values after end of last order

			if($start == $end){
				echo "; <br /> no more interacting genes";
				break; //if $start and $end are the same it means there are no more interacting genes 				
			}
		}
	}
}

if($querytype == "getPatientData"){
	$getPatient = 'SELECT age, gender, education FROM Patients WHERE patient_id = "'.$query.'";';
	//$getPatient = 'SELECT age, gender, education FROM Patients WHERE patient_id = "X764_130520";';

	$rows = mysql_query($getPatient);

	$rowData = mysql_fetch_row($rows);

	$output = ";".$rowData[0].";".$rowData[1].";".$rowData[2];
	echo $output;
}

?>
