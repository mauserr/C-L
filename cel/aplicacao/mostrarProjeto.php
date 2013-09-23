<?php

include("funcoes_genericas.php");
include("httprequest.inc");

//Scenario  -  Select project

//Objective:    Allows a ADM/User to select a project
//Context:      The ADM/User wants to select a project
//Actors:       Administrator, User
//Episodes:     Caso o Usuario selecione da lista de projetos um projeto da qual ele seja administrador,
//              ver ADMINISTRADOR ESCOLHE PROJETO.
//              Caso contrário, ver USUÁRIO ESCOLHE PROJETO.
   
$connect = bd_connect() or die("Erro ao conectar ao SGBD");

$id_project ='';
$version ='';
$row ='';
$xml_banco ='';

$query_select_sql = "select * from publication where id_project = $id_project AND version = $version";
$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query");
$row = mysql_fetch_row($query_result_sql);
$xml_banco = $row[3];

echo $xml_banco;
	
?>
