<?php

session_start();

include("funcoes_genericas.php");
include("httprequest.inc");

check_User("index.php");

$XML = "";

?>
<html>
<body>
    <head>
        <title>Gerar Grafo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">        
    </head>

<?php

// Scenario - Generate Graph
// Purpose: Allow the administrator to generate the graph of a project
// Context: Manager to generate a graph for one of the versions of XML
// Actors: Administrator
// Resource: System, XML, registered design data, database.
// Episdios: restriction: Owning a generated XML project

$bd_recupera = bd_connect() or die("Erro ao conectar ao SGBD");
$q = "SELECT * FROM publication WHERE id_project = '$id_project'";
$qrr = mysql_query($q) or die("Erro ao enviar a query");
?>
<h2>Gerar Grafo</h2><br>
<?php
while ( $result = mysql_fetch_row($qrr) )
{
   $date   = $result[1];
   $version = $result[2];
   $XML    = $result[3];	
	?>
	<table>
	   <tr>
			<th>Vers�o:</th><td><?=$version?></td>
			<th>Data:</th><td><?=$date?></td>
			<th><a href="mostraXML.php?id_projeto=<?=$id_project?>&versao=<?=$versao?>">XML</a></th>
			<th><a href="grafo\show_Graph.php?versao=<?=$version?>&id_projeto=<?=$id_project?>">Gerar Grafo</a></th>
	                
	   </tr>
	</table>

	<?php
}
?>

<br><i><a href="showSource.php?file=recuperarXML.php">Veja o c�digo fonte!</a></i>
    
</body>

</html>
