<?php
/*************************************************************
 * File: remove_projeto.php
 * purpose: removes a project from the system and the BD.
 * 
 ************************************************************/
session_start();

include("funcoes_genericas.php");
include("httprequest.inc");

//Scenario  -  Remove project

//Objective:  Allows a project administrator to remove the project
//Contexto:	  The project administrator wants to remove a prject from the data base
//Actors:	   Administrator

?>
<html>
    <head>
        <title>Remover Projeto</title>
    </head>
<?php
      
        $id_projeto = $_SESSION['id_projeto_corrente'];
        $id_usuario = $_SESSION['id_usuario_corrente'];
      
        $connect = bd_connect() or die("Erro ao conectar ao SGBD");  
        $query_select_sql = "SELECT * FROM projeto WHERE id_projeto = '$id_project' "; 
        $query_result_sql = mysql_query($qv) or die("Erro ao enviar a query de select no projeto");        
        $resultArrayProject = mysql_fetch_array($qvr);
        $project_Name       = $resultArrayProjeto[1];
        $data_Project       = $resultArrayProjeto[2];
        $project_Description= $resultArrayProjeto[3];  
  
        
        
?>    
    <body>
        <h4>Remover Projeto:</h4>
        
<p><br>
</p>
<table width="100%" border="0">
  <tr> 
    <td width="29%"><b>Nome do Projeto:</b></td>
    <td width="29%"><b>Data de cria&ccedil;&atilde;o</b></td>
    <td width="42%"><b>Descri&ccedil;&atilde;o</b></td>
  </tr>
  <tr> 
    <td width="29%"><?php echo $project_Name; ?></td>
    <td width="29%"><?php echo $data_Project; ?></td>
    <td width="42%"><?php echo $project_Description; ?></td>
  </tr>
</table>
<br><br>
<center><b>Cuidado!O projeto ser� apagado para todos seus usu�rios!</b></center>
<p><br>
  <center><a href="remove_projeto_base.php">Apagar o projeto</a></center> 
</p>
<p>
  <i><a href="showSource.php?file=remove_projeto.php">Veja o c�digo fonte!</a></i> 
</p>
</body>
</html>

