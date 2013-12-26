<?php

$xml_banco_file = "GrafoTmp.xml";

session_start();
require_once '/Functions/check_User.php';
include("../bd_class.php");
include("../funcoes_genericas.php");

check_User("index.php");

extract($_GET);
   
$bd_recupera = bd_connect() or die("Erro ao conectar ao SGBD");

$query_sql = "select * from publicacao where id_project = $id_project AND version = $version";
$query_result_sql = mysql_query($query_sql) or die("Erro ao enviar a query ao BD");
$row = mysql_fetch_row($qrr);
$xml_banco = $row[3];

$i = 1;
// If the file exists and isn't older than 5 minutes, then create another one
while ((file_exists($xml_banco_file)) && ( time() - filemtime($xml_banco_file) < 300 )) {
	$xml_banco_file = "GrafoTmp_".$i.".xml";
	$i++;
}

// If the file exists, then it's older than 5 minutes, we can delete it.
// Most of the time, only a few seconds will be necessary, as the file is only temporary
if (file_exists($xml_banco_file)) {
	unlink($xml_banco_file) or die("Unable to delete the old XML result file");
}

if (!($out = fopen($xml_banco_file , "w"))) {
    	die("I/O Error: Unable to create the XML input file");
}

// Salva o XML gerado pelo CEL em um arquivo
fwrite( $out , $xml_banco );

// Move to the PHP converter
header('Location: convertXML.php?file='.$xml_banco_file);
    
?> 