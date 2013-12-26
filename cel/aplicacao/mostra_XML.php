<?php

session_start();
include("funcoes_genericas.php");
include("httprequest.inc");
require_once '../Functions/check_User.php';

check_User("index.php");

$bd_recupera = bd_connect() or die("Erro ao conectar ao SGBD");

//Scenario -  Generate XML report

//Objective: Allows the administrator generate a XML report of a project identify by date
//Contexto:	Manager wants to generate a report for one of project that he is an administrator
//Actors:	  Administrator
//Episodes: Generating a report from data of registered project with sucess, the system  
//			provides to administrator a screen of visualization of the created XML report 

$qq = "SELECT * FROM publication WHERE id_project = $id_project AND version = $version";
$qrr = mysql_query($qq) or die("Erro ao enviar a query");
$row = mysql_fetch_row($qrr);
$xml_bank = $row[3];

echo $xml_bank;

?>
