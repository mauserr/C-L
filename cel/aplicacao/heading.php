<?php

session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';


check_User("index.php");        

// Scenario - User chooses project

// Objective:  Allows a user to choose a project
// Context:  User wants to choose a project
// Actors:    User
// Episodes:  User select from the list a project that he isn't a administrator 
//            The user can
//              - Update scenario:
//              - Update lexicon.

if( isset( $_GET['id_projeto']))
{
	$id_project = $_GET['id_projeto'];
}

?>

<script language="javascript1.3">

function getIDProject() {
    var select = document.forms[0].id_projeto; 
    var index = select.selectedIndex; 
    var id_projeto = select.options[index].value;
    return id_projeto;

}

function updateMenu() {   
   
    if (!(document.forms[0].id_projeto.options[0].selected))
    {
          top.frames['code'].location.replace('code.php?id_projeto=' + getIDProject());
          top.frames['text'].location.replace('main.php?id_projeto=' + getIDProject());


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
    var select = document.forms[0].id_projeto;
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

//Objetivo:	Allows inclusion, alteration and exclusion of a scenario by a user
//Actors:	User, manager of project
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

//Cen�rios -  Atualizar L�xico

//Objetivo:	Permitir Inclus�o, Altera��o e Exclus�o de um L�xico por um usu�rio
//Contexto:	Usu�rio deseja incluir um l�xico ainda n�o cadastrado, alterar e/ou 
//              excluir um cen�rio/l�xico previamente cadastrados.
//              Pr�-Condi��o: Login
//Atores:	Usu�rio, Gerente do projeto
//Recursos:	Sistema, menu superior, objeto a ser modificado
//Epis�dios:	O usu�rio clica no menu superior na op��o:
//                Se usu�rio clica em Incluir ent�o INCLUIR L�XICO

				             if (isset($id_project))
				             {
				             ?>
				                var url = 'add_lexico.php?id_projeto=' + '<?=$id_project?>';
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

function prjInfo(idprojeto) {
    top.frames['text'].location.replace('main.php?id_projeto=' + idprojeto);
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
   	
   	$ret = verificaGerente($id_user, $id_project);
   	  
        if ( $ret != 0 ){
	
	
                                        
?>
                                <font color="#FF0033">Administrador</font>
                            
                            
<?php
        }
        else{  
       
?>                               <font color="#FF0033">Usu�rio normal</font>
                                    

<?php
        }
     }   
     else{         
?>        
                                
<?php
    }     
?>      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Projeto:&nbsp;&nbsp;
                                
                                    <select name="id_projeto" size="1" onChange="atualizaMenu();">
                                        <option>-- Selecione um Projeto --</option>
                                                                                

<?php

// ** Cenario "Login" **
// O sistema d� ao usu�rio a op��o de cadastrar um novo projeto
// ou utilizar um projeto em que ele fa�a parte.

// conecta ao SGBD
$r = bd_connect() or die("Erro ao conectar ao SGBD");

// define a consulta
$q = "SELECT p.id_project, p.name, pa.manager
      FROM user u, participates pa, project p
      WHERE u.id_user = pa.id_user
      AND pa.id_project = p.id_project
      AND pa.id_user = " . $_SESSION["id_usuario_corrente"] . "
      ORDER BY p.name";

// executa a consulta
$qrr = mysql_query($q) or die("Erro ao executar query");

while ($result = mysql_fetch_array($qrr)) {    // enquanto houver projetos
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
if (isset($id_project)) {    // Se o usuario ja tiver escolhido um projeto,
                             // entao podemos mostrar os links de adicionar cen/lex
                             // e de informacoes (pagina principal) do projeto


// Cen�rio - Administrador escolhe Projeto

// Objetivo:  Permitir ao Administrador escolher um projeto.
// Contexto:  O Administrador deseja escolher um projeto.
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado.
// Atores:    Administrador
// Recursos:  Projetos doAdministrador
// Epis�dios: Aparecendo no menu as op��es de: 
//            -Adicionar Cen�rio (ver Adicionar Cen�rio); 
//            -Adicionar L�xico (ver Adicionar L�xico); 
//            -Info; 
//            -Adicionar Projeto; 
//            -Alterar Cadastro.


?> <a href="#" onClick="newScenario();">Adicionar Cen�rio</a>&nbsp;&nbsp;&nbsp; 
              <a href="#" onClick="novoLexico();">Adicionar S�mbolo</a>&nbsp;&nbsp;&nbsp; 
              <a href="#" title="Informa��es sobre o Projeto" onClick="prjInfo(<?=$id_project?>);">Info</a>&nbsp;&nbsp;&nbsp; 
              <?php
}
?> <?php

//Cen�rio  -  Cadastrar Novo Projeto 

//Objetivo:    Permitir ao usu�rio cadastrar um novo projeto
//Contexto:    Usu�rio deseja incluir um novo projeto na base de dados
//             Pr�-Condi��o: Login
//Atores:      Usu�rio
//Recursos:    Sistema, dados do projeto, base de dados
//Epis�dios:   O Usu�rio clica na op��o �adicionar projeto� encontrada no menu superior.

?> <a href="#" onClick="window.open('User/add_project.php', '_blank', 'dependent,height=313,width=550,resizable,scrollbars,titlebar');">Adicionar 
              Projeto</a>&nbsp;&nbsp;&nbsp; <?php


//Cen�rio  -   Remover Novo Projeto 

//Objetivo:    Permitir ao Administrador do projeto remover um projeto
//Contexto:    Um Administrador de projeto deseja remover um determinado projeto da base de dados
//             Pr�-Condi��o: Login, Ser administrador do projeto selecionado.
//Atores:      Administrador
//Recursos:    Sistema, dados do projeto, base de dados
//Epis�dios:   O Administrador clica na op��o �remover projeto� encontrada no menu superior.


 if (isset($id_project)){
   	
   	$id_user = $_SESSION['id_usuario_corrente'];
   	
   	$ret = verificaGerente($id_user, $id_project);
   	  
        if ( $ret != 0 ){
?> <a href="#" onClick="window.open('remove_projeto.php', '_blank', 'dependent,height=300,width=550,resizable,scrollbars,titlebar');">Remover 
              Projeto</a>&nbsp;&nbsp;&nbsp; <?php
        }
 }       

// Cen�rio - Logar no sistema

// Objetivo:  Permitir ao usu�rio entrar no sistema e escolher um projeto que ele esteja 
//              cadastrado, ou cadastrar novo projeto	
// Contexto:  Sistema est� aberto Usu�rio na tela de login do sistema. 
//            Usu�rio sabe a sua senha Usu�rio deseja entrar no sistema com seu perfil 
//            Pr�-Condi��o: Usu�rio ter acessado ao sistema	
// Atores:	  Usu�rio, Sistema	
// Recursos:  Banco de Dados	
// Epis�dios: O sistema d� ao usu�rio as op��es:
//             - ALTERAR CADASTRO, no qual o usu�rio ter� a possibilidade de realizar 
//               altera��es nos seus dados cadastrais


// Cen�rio - Alterar cadastro
//
//Objetivo:  Permitir ao usu�rio realizar altera��o nos seus dados cadastrais	
//Contexto:  Sistema aberto, Usu�rio ter acessado ao sistema e logado 
//           Usu�rio deseja alterar seus dados cadastrais 
//           Pr�-Condi��o: Usu�rio ter acessado ao sistema	
//Atores:    Usu�rio, Sistema.	
//Recursos:  Interface	
//Epis�dios: O usu�rio clica na op��o de alterar cadastro da interface

?> <a href="#" onClick="window.open('Call_UpdUser.php', '_blank', 'dependent,height=300,width=550,resizable,scrollbars,titlebar');">Alterar 
              Cadastro</a>&nbsp;&nbsp;&nbsp; 
              
              
              
<a href="mailto:per@les.inf.puc-rio.br">Fale Conosco&nbsp;&nbsp;&nbsp;</a>


              <?php


// Cen�rio - Logar no sistema

// Objetivo:  Permitir ao usu�rio entrar no sistema e escolher um projeto que ele esteja 
//              cadastrado, ou cadastrar novo projeto	
// Contexto:  Sistema est� aberto Usu�rio na tela de login do sistema. 
//            Usu�rio sabe a sua senha Usu�rio deseja entrar no sistema com seu perfil 
//            Pr�-Condi��o: Usu�rio ter acessado ao sistema	
// Atores:    Usu�rio, Sistema	
// Recursos:  Banco de Dados	
// Epis�dios: O sistema d� ao usu�rio as op��es:
//             - REALIZAR LOGOUT, no qual o usu�rio ter� a possibilidade de sair da 
//               sess�o e se logar novamente


// Cen�rio - Realizar logout

// Objetivo:  Permitir ao usu�rio realizar o logout, mantendo a integridade do que foi 
//            realizado,  e retorna a tela de login	
// Contexto:  Sistema aberto. Usu�rio ter acessado ao sistema. 
//            Usu�rio deseja sair da aplica��o e manter a integridade do que foi 
//            realizado 
//            Pr�-Condi��o: Usu�rio ter acessado ao sistema	
// Atores:	  Usu�rio, Sistema.	
// Recursos:  Interface	
// Epis�dios: O usu�rio clica na op��o de logout

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
