<?php

session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';


check_User("index.php");        

// Scenario - User chooses project

// Objective:  Allows a user to choose a project
// Context:    User wants to choose a project
// Actors:     User
// Episodes:   User select from the list a project that he isn't a administrator 
//             The user can
//               - Update scenario:
//               - Update lexicon.

if( isset( $_GET['id_project']))
{
	//$id_project = null;
	$id_project = $_GET['id_project'];
}

?>

<script language="javascript1.3">

function getIDProject() {
    var select = document.forms[0].id_project; 
    var index = select.selectedIndex; 
    var id_project = select.options[index].value;
    return id_project;

}

function updateMenu() {   
   
    if (!(document.forms[0].id_project.options[0].selected))
    {
          top.frames['code'].location.replace('C-L/cel/aplicacao/User/code.php?id_project=' + getIDProject());
          top.frames['text'].location.replace('main.php?id_project=' + getIDProject());


          location.replace('heading.php?id_project=' + getIDProject());
    } else {

        location.reload();
    }
    return false;
}

<?php
if (isset($id_project)) {   

    //Do a check of security,because of the data passed throug javascript
    check_project_permanent($_SESSION['id_usuario_corrente'], $id_project) or die("Permissao negada");
?>

function setProjectSelected() {
    var select = document.forms[0].id_project;
    for (var i = 0; i < select.length; i++) {
        if (select.options[i].value == <?=$id_project?>) {
            select.options[i].selected = true;
            i = select.length;
        }
    }
}

<?php
}
?>

function newScenario() {
 <?php

// Scenario - Update Scenario

//Objective:	Allows inclusion, alteration and exclusion of a scenario by a user
//Actors:	User, Project Manager
//Episodes:	User click on the button on the option:
//                If user clicks on "Incluir", so include a new Scenario

				             if (isset($id_project))
				             {
				             ?>
				               var url = 'add_scenario.php?id_project=' + '<?=$id_project?>';
				             <?php
				             }
				             else
				             {
				             ?>
				              var url = 'add_scenario.php?'
				             <?php
				             }

            ?>


    var where = '_blank';
    var window_spec = 'dependent,height=600,width=550,resizable,scrollbars,titlebar';
    open(url, where, window_spec);
}

function novoLexico() {
 <?php

//Scenarios-  Update Lexicon

//Objective:	Allows inclusion, alteration and exclusion of a lexicon by a user
//Actors: User, Project Manager
//Episodes:	User click on the button on the option:
//                If user clicks on "Incluir", so include a new Lexicon
				             if (isset($id_project))
				             {
				             ?>
				                var url = 'add_lexico.php?id_project=' + '<?=$id_project?>';
				             <?php
				             }
				             else
				             {
				             ?>
				               var url = 'add_lexico.php';
				             <?php
				             }

            ?>

    var where = '_blank';
    var window_spec = 'dependent,height=573,width=570,resizable,scrollbars,titlebar';
    open(url, where, window_spec);
}

function projectInfo(idprojeto) {
    top.frames['text'].location.replace('main.php?id_project=' + idprojeto);
}

</script>

<html>
    <style>
    a
    {
        font-weight: bolder;
        color: Blue;
        font-family: Verdana, Arial;
        text-decoration: none
    }
    a:hover
    {
        font-weight: bolder;
        color: Tomato;
        font-family: Verdana, Arial;
        text-decoration: none
    }
    </style>
    <body bgcolor="#ffffff" text="#000000" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" <?=(isset($id_project)) ? "onLoad=\"setProjectSelected();\"" : ""?>>
        <form onSubmit="return atualizaMenu();">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr bgcolor="#E0FFFF">
                   <td width="294" height="79" > <!--<img src="Images/Logo.jpg"></td>-->
<img src="Images/Logo_C.jpg" width="190" height="100"></td>
                    <td align="right" valign="top">
                        <table>
                            <tr>
                                <td align="right" valign="top"> <?php 

   if (isset($id_project)){
   	
   	$id_user = $_SESSION['id_usuario_corrente'];
   	
   	$ret = verifyManager($id_user, $id_project);
   	  
        if ( $ret != 0 ){
	
	
                                        
?>
                                <font color="#FF0033">Administrador</font>
                            
                            
<?php
        }
        else{  
       
?>                               <font color="#FF0033">Usuário normal</font>
                                    

<?php
        }
     }   
     else{         
?>        
                                
<?php
    }     
?>      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Projeto:&nbsp;&nbsp;
                                
                                    <select name="id_project" size="1" onChange="updateMenu();">
                                        <option>-- Selecione um Projeto --</option>
                                                                                

<?php

// Scenario: Login
//Actors: User
// Episodes: The system shows user a option of register a new project 		
// or uptade a existing one


$connect = bd_connect() or die("Erro ao conectar ao SGBD");


$query_select_sql = "SELECT p.id_project, p.name, pa.manager
      FROM user u, participates pa, project p
      WHERE u.id_user = pa.id_user
      AND pa.id_project = p.id_project
      AND pa.id_user = " . $_SESSION["id_usuario_corrente"] . "
      ORDER BY p.name";

$query_result_sql = mysql_query($query_select_sql) or die("Erro ao executar query");

while ($result = mysql_fetch_array($query_result_sql)) {   
?>
<option value="<?=$result['id_project']?>"><?=($result['manager'] == 1) ? "*" : ""?>  <?=$result['name']?></option>

<?php
}
?>          

                                    </select>&nbsp;&nbsp;
                                    <input type="submit" value="Atualizar">
                                </td>
                            </tr>
                             <tr bgcolor="#E0FFFF" height="15">
                            
                            <tr bgcolor="#E0FFFF" height="30">
                                
            <td align="right" valign=MIDDLE> <?php
if (isset($id_project)) {   


// Scenario - Administrador choose project

// Objective: Allows the administrator to choose a project
// Context:   O Administrator wants to choose a project
// Actors:    Administrator
// Episodes: Shows the menus of: 
//            -Add Scenario; 
//            -Add Lexicon; 
//            -Info; 
//            -Add Project; 
//            -Alter cadastre.


?> <a href="#" onClick="newScenario();">Adicionar Cenario</a>&nbsp;&nbsp;&nbsp; 
              <a href="#" onClick="novoLexico();">Adicionar Simbolo</a>&nbsp;&nbsp;&nbsp; 
              <a href="#" title="Informações sobre o Projeto" onClick="projectInfo(<?=$id_project?>);">Info</a>&nbsp;&nbsp;&nbsp; 
              <?php
}
?> <?php

//Scenario  -  Register a new Project

//Objective:    Allows a user include a new project
//Context:    User wants to include a new project
//Actors:      User
//Episï¿½dios:   User clicks on add project, on the top of the screen

?> <a href="#" onClick="window.open('User/add_project.php', '_blank', 'dependent,height=313,width=550,resizable,scrollbars,titlebar');">Adicionar 
              Projeto</a>&nbsp;&nbsp;&nbsp; <?php


//Scenario  -   Remove new project

//Objetivo:    Allows the administrator remove a new project
//Context:     Administrator wants to remove a project of the data base
//Actors:      Administrator
//Episodes:    The administrator clicks on "remover projeto" on the top of the screen


 if (isset($id_project)){
   	
   	$id_user = $_SESSION['id_usuario_corrente'];
   	
   	$ret = verifyManager($id_user, $id_project);
   	  
        if ( $ret != 0 ){
?> <a href="#" onClick="window.open('remove_project.php', '_blank', 'dependent,height=300,width=550,resizable,scrollbars,titlebar');">Remover 
              Projeto</a>&nbsp;&nbsp;&nbsp; <?php
        }
 }       



?> <a href="#" onClick="window.open('Call_UpdUser.php', '_blank', 'dependent,height=300,width=550,resizable,scrollbars,titlebar');">Alterar 
              Cadastro</a>&nbsp;&nbsp;&nbsp; 
              
              
              
<a href="mailto:per@les.inf.puc-rio.br">Fale Conosco&nbsp;&nbsp;&nbsp;</a>


              <?php

?> <a href="logout.php" target="_parent");">Sair</a>&nbsp;&nbsp;&nbsp; <a href="ajuda.htm" target="_blank"> 
              Ajuda</a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr height="33" bgcolor="#00359F" background="Images/FrameTop.gif">
                    <td background="Images/TopLeft.gif" width="294" valign="baseline"></td>
                    <td background="Images/FrameTop.gif" valign="baseline"></td>
                </tr>
            </table>
        </form>
    </body>
</html>
