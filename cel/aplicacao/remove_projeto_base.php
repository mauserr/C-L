<?php

session_start();

include("funcoes_genericas.php");
include_once("CELConfig/CELConfig.inc");



//scenario  -  Remove base project

//Objetivo:	   Remove a project of data base
//Contexto:	   The administrator needs to remove a project of data base
//Actors:	   Administrator
//Recursos:	   System, data of project, data base
//Episode:     The system remove all the data of the project from the data base 


        $id_project = $_SESSION['current_id_projeto'];
        
        removeProject($id_project);    
?>
<html>
<script language="javascript1.3">
function logoff()
{		
   location.href = "http://<?php print( CELConfig_ReadVar("HTTPD_ip") . "/" . CELConfig_ReadVar("CEL_dir_relativo") ); ?>index.php";
}
</script>
    <head>
        <title>Remover Projeto</title>
    </head>  

 <body>
 <center><b>Projeto apagado com sucesso.</b></center>   
 <p>
    <a href="javascript:logoff();">Clique aqui para Sair</a>
 </p>
<p>
  <i><a href="showSource.php?file=remove_projeto_base.php">Veja o código fonte!</a></i> 
</p>
</body>
</html>

