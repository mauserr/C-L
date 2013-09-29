<?php

session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
include("httprequest.inc");

check_User("index.php");     

$XML = "";

?>
<html>
<body>
    <head>
        <title>Recuperar XML</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">        
    </head>

<?php

//Scenario - generate XML report 

//Objetivo: Allows the administrator generate a report of a project in XML format, identified by date 
//Contexto: The manager wants to generate a repot for a project of his administration
//Atores:     Administrator


$bd_recupera = bd_connect() or die("Erro ao conectar ao SGBD");

$erase = null;
$id_project = null;
$version = null;
if (isset($erase))
{
	if ( $erase )
	{
		$query_delete_sql = "DELETE FROM publicacao WHERE id_projeto = '$id_project' AND versao = '$version' ";
		$query_erase_result_sql = mysql_query($query_delete_sql);	
	}
}
$query_select_sql = "SELECT * FROM publicacao WHERE id_projeto = '$id_project'";
$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query");

?>

<h2>Recupera XML/XSL</h2><br>

<?php

while ( $result = mysql_fetch_row($query_result_sql) )
{
   $date  = $result[1];
   $version = $result[2];
   $XML    = $result[3];	
?>

<table>
   <tr>
                <th>Vers�o:</th><td><?=$version?></td>
                <th>Data:</th><td><?=$date?></td>
                <th><a href="mostraXML.php?id_projeto=<?=$id_projeto?>&versao=<?=$version?>">XML</a></th>
                <th><a href="recuperarXML.php?id_projeto=<?=$id_projeto?>&versao=<?=$version?>&erase=true">Apaga XML</a></th>
                
   </tr>


</table>

<?php
}

?>

<br><i><a href="showSource.php?file=recuperarXML.php">Veja o c�digo fonte!</a></i>
    
    </body>

</html>
