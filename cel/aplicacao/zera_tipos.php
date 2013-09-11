<?php
/*************************************************************
 * File: zera_tipos.php
 * purpose: create a Mysql query that update Lexico´s type
 * 
 ************************************************************/
        include 'bd.inc';

	$link = bd_connect();

	
	$query_Mysql = "update lexico set tipo =  NULL;";
	$result_Mysql = mysql_query($query_Mysql) or die("A consulta � BD falhou : " . mysql_error());
	
	mysql_close($link);

?>