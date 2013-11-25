<?php

include("funcoes_genericas.php");
include("httprequest.inc");

//Scenario  -  Select project

//Objective:    Allows a ADM/User to select a project
//Context:      The ADM/User wants to select a project
//Actors:       Administrator, User
//Episodes:     In the case of the administrator select the list of projects,
//              see ADMINISTRATOR CHOOSE PROJECT.
//              in the other hand, see USER CHOOSE PROJECT.
   
$connect = bd_connect() or die("Erro ao conectar ao SGBD");

$id_project ='';
$version ='';

$query_select_sql = "select * from publication where id_project = $id_project AND version = $version";

	assert ($query_select_sql != NULL);

$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query");
$row = mysql_fetch_row($query_result_sql);

	assert ($row != NULL);

$xml_banco = $row[3];

	assert ($xml_banco != NULL);

echo $xml_banco;
	
?>
