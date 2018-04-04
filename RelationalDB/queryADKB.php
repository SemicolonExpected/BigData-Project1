<?php

$db = mysql_connect('localhost', 'bigdata', 'bigdata');
mysql_select_db("ADKnowledgeBase", $db);

$query = $_GET['query'];

$querytype = $_GET['type'];

echo $_GET['type'].";".$_GET['query'];

if($querytype == "getInteractingGenes"){

	$getGenes = 'SELECT b FROM EntrezGeneSymbols INNER JOIN PPI ON EntrezGeneSymbols.entrez_id = PPI.a WHERE a = "'.$query.'";';
	//echo ";hello";
	//echo ";goodbye";
	//echo ";". $getGenes;
	//$getGenes = 'SELECT b FROM PPI WHERE a = "17";';
	$rows = mysql_query($getGenes);

	while($rowData = mysql_fetch_row($rows)){
		//echo "hello";
		for($i = 0; $i < count($rowData); $i++){
			$output = ";".$rowData[$i];
			//echo ";hello";
			echo $output;
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
