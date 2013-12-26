<?php

session_start();

include("funcoes_genericas.php");
include("httprequest.inc");
require_once '/Functions/check_User.php';

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

//Cen�rio -  Gerar Grafo 

//Objetivo:   Permitir ao administrador gerar o grafo de um projeto
//Contexto:   Gerente deseja gerar um grafo para uma das vers�es de XML
//Atores:     Administrador
//Recursos:   Sistema, XML, dados cadastrados do projeto, banco de dados.
//Epis�dios:  Restri��o: Possuir um XML gerado do projeto

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
			<th><a href="mostraXML.php?id_projeto=<?=$id_project?>&versao=<?=$version?>">XML</a></th>
			<th><a href="grafo\show_Graph.php?versao=<?=$version?>&id_projeto=<?=$id_project?>">Gerar Grafo</a></th>
	                
	   </tr>
	</table>

	<?php
}
?>

<br><i><a href="showSource.php?file=recuperarXML.php">Veja o c�digo fonte!</a></i>
    
</body>

</html>
