<?php
/*************************************************************
 * File: remove_projeto.php
 * purpose: removes a project from the system and the BD.
 * 
 ************************************************************/
session_start();

require_once '/Functions/project_Functions.php';
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
      
        $id_project = $_SESSION['current_id_project'];
        $id_user = $_SESSION['id_usuario_corrente'];
      
        $connect = bd_connect() or die("Erro ao conectar ao SGBD");  
        $query_select_sql = "SELECT * FROM project WHERE id_project = '$id_project' "; 
        $query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query de select no projeto");        
        $resultArrayProject = mysql_fetch_array($query_result_sql);
        $project_Name       = $resultArrayProject[1];
        $data_Project       = $resultArrayProject[2];
        $project_Description= $resultArrayProject[3];  
  
        
        
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
<center><b>Cuidado!O projeto será apagado para todos seus usuários!</b></center>
<p><br>
  <center><a href="remove_project_base.php">Apagar o projeto</a></center> 
</p>
<p>
  <i><a href="showSource.php?file=remove_projeto.php">Veja o código fonte!</a></i> 
</p>
</body>
</html>

