<?php
/***************************************
 * File: main.php
 * Purpose: general functionalities and home site structure
 * 
 */

session_start();
include_once("CELConfig/CELConfig.inc");
require_once'/Functions/project_Functions.php';
include("Functions/reload_Page.php");
require_once '/User/code.php';


/* URL do diretorio contendo os arquivos de DAML */
$_SESSION['site'] = "http://" . CELConfig_ReadVar("HTTPD_ip") . "/" . CELConfig_ReadVar("CEL_dir_relativo") . CELConfig_ReadVar("DAML_dir_relativo_ao_CEL") ;

     
/* Caminho relativo ao CEL do diretorio contendo os arquivos de DAML */
$_SESSION['diretorio'] = CELConfig_ReadVar("DAML_dir_relativo_ao_CEL") ;

include("funcoes_genericas.php");    
require_once '/Functions/check_User.php';
include("httprequest.inc");
include_once("puts_Links.php");


// Checa se o usu�rio foi autenticado
check_User("index.php");   

//Recebe parametro da heading.php. Sem isso vai travar ja que a variavel nao foi inicializada 
if( isset( $_GET['id_project']))    
{    
    $id_project = $_GET['id_project'];    
}    
else    
{    
  // $id_project = ""; 
}    

if (!isset  ( $_SESSION['current_id_project'] ))    
{    

   $_SESSION['current_id_project'] = "";    
}    


?>    

<html> 



    <head> 
        <LINK rel="stylesheet" type="text/css" href="style.css"> 
        <script language="javascript1.3"> 
 

<?php    

// Cen�rio - Atualizar Cen�rio 

//Objetivo:    Permitir Inclus�o, Altera��o e Exclus�o de um Cen�rio por um usu�rio 
//Contexto:    Usu�rio deseja incluir um cen�rio ainda n�o cadastrado, alterar e/ou excluir 
//              um cen�rio previamente cadastrados. 
//              Pr�-Condi��o: Login 
//Atores:    Usu�rio, Gerente do projeto 
//Recursos:    Sistema, menu superior, objeto a ser modificado 
//Epis�dios:    O usu�rio clica no menu superior na op��o: 
//                Se usu�rio clica em Alterar ent�o ALTERAR CEN�RIO 

?>    

        function altCenario(cenario) { 
            var url = 'alter_Scenario.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_scenario=' + cenario; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=660,width=550,resizable,scrollbars,titlebar';
            open(url, where, window_spec); 
        } 

<?php    

// Cen�rio - Atualizar Cen�rio 

//Objetivo:    Permitir Inclus�o, Altera��o e Exclus�o de um Cen�rio por um usu�rio 
//Contexto:    Usu�rio deseja incluir um cen�rio ainda n�o cadastrado, alterar e/ou excluir 
//              um cen�rio previamente cadastrados. 
//              Pr�-Condi��o: Login 
//Atores:    Usu�rio, Gerente do projeto 
//Recursos:    Sistema, menu superior, objeto a ser modificado 
//Epis�dios:    O usu�rio clica no menu superior na op��o: 
//                Se usu�rio clica em Excluir ent�o EXCLUIR CEN�RIO 

?>    

        function rmvCenario(scenario) { 
            var url = '../User/remove_Scenario.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_scenario=' + scenario; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php    

//Cen�rios -  Atualizar L�xico 

//Objetivo:    Permitir Inclus�o, Altera��o e Exclus�o de um L�xico por um usu�rio 
//Contexto:    Usu�rio deseja incluir um lexico ainda n�o cadastrado, alterar e/ou 
//              excluir um cen�rio/l�xico previamente cadastrados. 
//              Pr�-Condi��o: Login 
//Atores:    Usu�rio, Gerente do projeto 
//Recursos:    Sistema, menu superior, objeto a ser modificado 
//Epis�dios:    O usu�rio clica no menu superior na op��o: 
//                Se usu�rio clica em Alterar ent�o ALTERAR L�XICO 

?>    

        function altLexico(lexico) { 
            var url = 'alter_Lexicon.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_lexico=' + lexico; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=573,width=570,resizable,scrollbars,titlebar';
            open(url, where, window_spec); 
        } 

<?php    

//Cen�rios -  Atualizar L�xico 

//Objetivo:    Permitir Inclus�o, Altera��o e Exclus�o de um L�xico por um usu�rio 
//Contexto:    Usu�rio deseja incluir um lexico ainda n�o cadastrado, alterar e/ou 
//              excluir um cen�rio/l�xico previamente cadastrados. 
//              Pr�-Condi��o: Login 
//Atores:    Usu�rio, Gerente do projeto 
//Recursos:    Sistema, menu superior, objeto a ser modificado 
//Epis�dios:    O usu�rio clica no menu superior na op��o: 
//                Se usu�rio clica em Excluir ent�o EXCLUIR L�XICO 

?>    

        function rmvLexico(lexico) { 
            var url = 'rmv_lexico.php?id_projeto=' + '<?=$_SESSION['current_id_project']?>' + '&id_lexico=' + lexico; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

        // Funcoes que serao usadas quando o script 
        // for chamado atraves da heading.php 

<?php    

// Cen�rio - Atualizar Cen�rio 

//Objetivo:    Permitir Inclus�o, Altera��o e Exclus�o de um Cen�rio por um usu�rio 
//Contexto:    Usu�rio deseja incluir um cen�rio ainda n�o cadastrado, alterar e/ou excluir 
//              um cen�rio previamente cadastrados. 
//              Pr�-Condi��o: Login 
//Atores:    Usu�rio, Gerente do projeto 
//Recursos:    Sistema, menu superior, objeto a ser modificado 
//Epis�dios:    O usu�rio clica no menu superior na op��o: 
//                Se usu�rio clica em Alterar ent�o ALTERAR CEN�RIO 

?>    

        function altConceito(conceito) { 
            var url = 'alte_Concept.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_concept=' + conceito; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php    

// Cen�rio - Atualizar Conceito

//Objetivo:    Permitir Inclus�o, Altera��o e Exclus�o de um Cen�rio por um usu�rio 
//Contexto:    Usu�rio deseja incluir um cen�rio ainda n�o cadastrado, alterar e/ou excluir 
//              um cen�rio previamente cadastrados. 
//              Pr�-Condi��o: Login 
//Atores:    Usu�rio, Gerente do projeto 
//Recursos:    Sistema, menu superior, objeto a ser modificado 
//Epis�dios:    O usu�rio clica no menu superior na op��o: 
//                Se usu�rio clica em Excluir ent�o EXCLUIR CEN�RIO 

?>    

        function rmvConceito(conceito) { 
            var url = 'remove_Concept.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_concept=' + conceito; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 
        
        function rmvRelacao(relacao) { 
            
            var url = 'remove_relation.php?id_project=' + '<?=$_SESSION['current_id_project']?>' + '&id_relation=' + relacao; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        }

<?php    


// Cen�rio - Administrador escolhe Projeto 

// Objetivo:  Permitir ao Administrador escolher um projeto. 
// Contexto:  O Administrador deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado. 
// Atores:    Administrador 
// Recursos:  Projetos doAdministrador 
// Epis�dios: O Administrador seleciona da lista de projetos um projeto da qual ele seja 
//            administrador. 
//            Aparecendo na tela as op��es de: 
//            -Verificar pedidos de altera��o de cen�rio (ver Verificar pedidos de altera��o 
//            de cen�rio); 

?>    

        function pedidoCenario() { 
            <?php    
             if (isset($id_project))    
             {    
             ?>    
				var url = 'see_Scenario_Request.php?id_project=' + '<?=$id_project?>'; 
             <?php    
             }    
             else    
             {    
             ?>    
				var url = 'see_Scenario_Request.php'; 
             <?php    
             }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php    

// Cen�rio - Administrador escolhe Projeto 

// Objetivo:  Permitir ao Administrador escolher um projeto. 
// Contexto:  O Administrador deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado. 
// Atores:    Administrador 
// Recursos:  Projetos doAdministrador 
// Epis�dios: O Administrador seleciona da lista de projetos um projeto da qual ele seja 
//            administrador. 
//            Aparecendo na tela as op��es de: 
//            - Verificar pedidos de altera��o de termos do l�xico 
//            ( ver Verificar pedidos de altera��o de termos do l�xico); 

?>    

        function pedidoLexico() { 

         <?php    
                     if (isset($id_project))    
                     {    
                     ?>    
						var url = 'see_Lexicon_Request.php?id_project=' + '<?=$id_project?>'; 
                     <?php    
                     }    
                     else    
                     {    
                     ?>    
						var url = 'see_Lexicon_Request.php?' 
                     <?php    
                     }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php    

// Cen�rio - Administrador escolhe Projeto 

// Objetivo:  Permitir ao Administrador escolher um projeto. 
// Contexto:  O Administrador deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado. 
// Atores:    Administrador 
// Recursos:  Projetos doAdministrador 
// Epis�dios: O Administrador seleciona da lista de projetos um projeto da qual ele seja 
//            administrador. 
//            Aparecendo na tela as op��es de: 
//            - Verificar pedidos de altera��o de termos do l�xico 
//            ( ver Verificar pedidos de altera��o de termos do l�xico); 

?>    

        function pedidoConceito() { 

         <?php    
                     if (isset($id_project))    
                     {    
                     ?>    
						var url = 'see_Concept_Request.php?id_project=' + '<?=$id_project?>'; 
                     <?php    
                     }    
                     else    
                     {    
                     ?>    
						var url = 'see_Concept_Request.php?' 
                     <?php    
                     }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 
        
        function pedidoRelacao() { 

         <?php    
                     if (isset($id_project))    
                     {    
                     ?>    
						var url = 'see_Relation_Request.php?id_project=' + '<?=$id_project?>'; 
                     <?php    
                     }    
                     else    
                     {    
                     ?>    
						var url = 'see_Relation_Request.php?' 
                     <?php    
                     }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=300,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php   

// Cen�rio - Administrador escolhe Projeto 

// Objetivo:  Permitir ao Administrador escolher um projeto. 
// Contexto:  O Administrador deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado. 
// Atores:    Administrador 
// Recursos:  Projetos doAdministrador 
// Epis�dios: O Administrador seleciona da lista de projetos um projeto da qual ele seja 
//            administrador. 
//            Aparecendo na tela as op��es de: 
//            -Adicionar usu�rio (n�o existente) neste projeto (ver Adicionar Usu�rio); 

?>    

        function addUsuario() { 
            var url = 'add_usuario.php'; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=320,width=490,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php    

// Cen�rio - Administrador escolhe Projeto 

// Objetivo:  Permitir ao Administrador escolher um projeto. 
// Contexto:  O Administrador deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado. 
// Atores:    Administrador 
// Recursos:  Projetos doAdministrador 
// Epis�dios: O Administrador seleciona da lista de projetos um projeto da qual ele seja 
//            administrador. 
//            Aparecendo na tela as op��es de: 
//            -Relacionar usu�rios j� existentes com este projeto 
//            (ver Relacionar usu�rios com projetos); 

?>    

        function relUsuario() { 
            var url = 'relation_User.php'; 
            var where = '_blank'; 
            var window_spec = 'dependent,height=380,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php    

// Cen�rio - Administrador escolhe Projeto 

// Objetivo:  Permitir ao Administrador escolher um projeto. 
// Contexto:  O Administrador deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado. 
// Atores:    Administrador 
// Recursos:  Projetos doAdministrador 
// Epis�dios: O Administrador seleciona da lista de projetos um projeto da qual ele seja 
//            administrador. 
//            Aparecendo na tela as op��es de: 
//            -Gerar xml deste projeto (ver Gerar relat�rios XML); 

?>    

        function geraXML() 
        { 

        <?php    
                             if (isset($id_project))    
                             {    
                             ?>    
								var url = 'form_xml.php?id_project=' + '<?=$id_project?>'; 
                             <?php    
                             }    
                             else    
                             {    
                             ?>    
								var url = 'form_xml.php?' 
                             <?php    
                             }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=330,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

        function recuperaXML() 
                { 

        <?php    
                             if (isset($id_project))    
                             {    
                             ?>    
								var url = 'recuperarXML.php?id_projeto=' + '<?=$id_project?>'; 
                             <?php    
                             }    
                             else    
                             {    
                             ?>    
								var url = 'recuperarXML.php?' 
                             <?php    
                             }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=330,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 
		
		function geraGrafo() 
        { 

        <?php    
			if (isset($id_project))    
            {    
                ?>    
					var url = 'gerarGrafo.php?id_projeto=' + '<?=$id_project?>'; 
                <?php    
            }else    
            {    
                ?>    
					var url = 'gerarGrafo.php?' 
                <?php    
            }    

        ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=330,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

		
		<?php   

		// Ontologia 

		// Objetivo:  Gerar ontologia do projeto 
		
		?>    
        function geraOntologia() 
        { 

        <?php    
                             if (isset($id_project))    
                             {    
                             ?>    
								var url = 'inicio.php?id_projeto=' + '<?=$id_project?>'; 
                             <?php    
                             }    
                             else    
                             {    
                             ?>    
								var url = 'inicio.php?' 
                             <?php    
                             }    

            ?>    

            var where = '_blank'; 
            var window_spec = ""; 
            open(url, where, window_spec); 
        } 

<?php   

// Ontologia - DAML 

// Objetivo:  Gerar daml deste da ontologia do projeto 
?>    
        function geraDAML() 
        { 

        <?php    
                             if (isset($id_project))    
                             {    
                             ?>    
								var url = 'form_daml.php?id_projeto=' + '<?=$id_project?>'; 
                             <?php    
                             }    
                             else    
                             {    
                             ?>    
								var url = 'form_daml.php?' 
                             <?php    
                             }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=375,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 

<?php   

// Objetivo: Recuperar hist�rico da ontologia em DAML 
?>    
        function recuperaDAML() 
        { 

        <?php    
                             if (isset($id_project))    
                             {    
                             ?>    
								var url = 'recuperaDAML.php?id_projeto=' + '<?=$id_project?>'; 
                             <?php    
                             }    
                             else    
                             {    
                             ?>    
								var url = 'recuperaDAML.php?' 
                             <?php    
                             }    

            ?>    

            var where = '_blank'; 
            var window_spec = 'dependent,height=330,width=550,resizable,scrollbars,titlebar'; 
            open(url, where, window_spec); 
        } 


        </script> 
        <script type="text/javascript" src="mtmtrack.js"> 
        </script> 
    </head> 
    <body> 

<!--                     PRIMEIRA PARTE                                     --> 

<?php    

include("frame_inferior.php");    


if (isset($id) && isset($t)) {      // SCRIPT CHAMADO PELO PROPRIO MAIN.PHP (OU PELA ARVORE) 
    $vetorVazio = array();
    if ($t == "c")        { print "<h3>Informa��es sobre o cen�rio</h3>";   

    } elseif ($t == "l")  { print "<h3>Informa��es sobre o s�mbolo</h3>";   

    } elseif ($t == "oc") { print "<h3>Informa��es sobre o conceito</h3>";    

    } elseif ($t == "or") { print "<h3>Informa��es sobre a rela�o</h3>";   

    } elseif ($t == "oa") { print "<h3>Informa��es sobre o axioma</h3>";   

    }    

?>    
        <table> 




<!--                     SEGUNDA PARTE                                     --> 


<?php    
    $c = bd_connect() or die("Erro ao conectar ao SGBD");    
?>   



<!-- CEN�RIO --> 

<?php   
    
	if ($t == "c") {        // se for cenario 
        
		$q = "SELECT id_scenario, title, objetive, context, autor, resourses, exception, episodes, id_project    
              FROM scenario    
              WHERE id_scenario = $id";    
        
		$qrr = mysql_query($q) or die("Erro ao enviar a query de selecao !!". mysql_error());    
        $result = mysql_fetch_array($qrr);  
        
		$c_id_projeto = $result['id_project'];
		
		$vetorDeCenarios = carrega_vetor_cenario( $c_id_project, $id, true ); // carrega vetor de cenario
        quicksort( $vetorDeCenarios, 0, count($vetorDeCenarios)-1,'scenario' );
      
	    $vetorDeLexicos = carrega_vetor_lexicos( $c_id_project, 0, false ); // carrega vetor de l�xicos 
        quicksort( $vetorDeLexicos, 0, count($vetorDeLexicos)-1,'lexicon' );
    		
?>    

            <tr> 
                <th>Titulo:</th><td CLASS="Estilo">
        <?php echo nl2br(monta_links( $result['title'], $vetorDeLexicos, $vetorVazio)) ;?>
                </td> 

            </tr> 
            <tr> 
                <th>Objetivo:</th><td CLASS="Estilo">
		<?php echo nl2br(monta_links( $result['objective'], $vetorDeLexicos, $vetorVazio )) ; ?>
				</td> 
            </tr> 
            <tr> 
                <th>Contexto:</th><td CLASS="Estilo">
		<?php
    	    echo nl2br(monta_links( $result['context'], $vetorDeLexicos, $vetorDeCenarios ) ); ?>		 
				</td> 
            </tr> 
            <tr> 
                <th>Atores:</th><td CLASS="Estilo">
		<?php echo nl2br(monta_links( $result['actors'], $vetorDeLexicos, $vetorVazio) ) ; ?>
                </td>  
            </tr> 
            <tr> 
                <th>Recursos:</th><td CLASS="Estilo">
		<?php echo nl2br(monta_links( $result['resource'], $vetorDeLexicos, $vetorVazio ) ) ; ?>
                </td> 
            </tr> 
            <tr> 
                <th>Exce��o:</th><td CLASS="Estilo">
		<?php echo nl2br(monta_links( $result['exception'], $vetorDeLexicos, $vetorVazio) ) ; ?>
                </td> 
            </tr> 
            <tr> 
                <th>Epis�dios:</th><td CLASS="Estilo">
		<?php 
	  		echo nl2br(monta_links( $result['episodes'], $vetorDeLexicos, $vetorDeCenarios ) ); ?>
	  	
                </td> 
            </tr> 
        </TABLE> 
        <BR> 
        <TABLE> 
            <tr> 
                 <td CLASS="Estilo" height="40" valign=MIDDLE> 
                    <a href="#" onClick="alter_Scenario(<?=$result['id_scenario']?>);">Alterar Cen�rio</a> 
                </th> 
                <td CLASS="Estilo"  valign=MIDDLE> 
                    <a href="#" onClick="remove_Scenario(<?=$result['id_scenario']?>);">Remover Cen�rio</a> 
                </th> 
            </tr> 


<!-- L�XICO --> 

<?php    
    } elseif ($t == "l") {
              
        $q = "SELECT id_lexicon, name, notion, impact, type, id_project   
              FROM lexicon    
              WHERE id_lexicon = $id";    
      
		$qrr = mysql_query($q) or die("Erro ao enviar a query de selecao !!". mysql_error());    
        $result = mysql_fetch_array($qrr);
        
        $l_id_project = $result['id_project'];
        
        $vetorDeLexicos = carrega_vetor_lexicos( $l_id_project, $id, true );  
		
        quicksort( $vetorDeLexicos, 0, count( $vetorDeLexicos )-1,'lexicon' );
 
?>    
            <tr> 
                <th>Nome:</th><td CLASS="Estilo"><?php echo $result['name']; ?>
				</td> 
            </tr> 
            <tr> 
                <th>No��oo:</th><td CLASS="Estilo"><?php echo nl2br( monta_links( $result['notion'], $vetorDeLexicos, $vetorVazio ) ); ?>
				</td> 
            </tr> 
            <tr> 
                <th>Classifica��o:</th><td CLASS="Estilo"><?=nl2br( $result['type'] ) ?>
				</td> 
            </tr> 
            <tr> 
                <th>Impacto(s):</th><td CLASS="Estilo"><?php echo nl2br( monta_links( $result['impact'], $vetorDeLexicos, $vetorVazio ) ); ?> 
				</td>
            </tr> 
            <tr> 
            <th>Sin�nimo(s):</th> 

			<?php //sinonimos 
                 $id_project = $_SESSION['current_id_project'];    
                 $qSinonimo = "SELECT * FROM synonym WHERE id_lexicon = $id";    
                 $qrr = mysql_query($qSinonimo) or die("Erro ao enviar a query de Sinonimos". mysql_error());    

                 $tempS = array();
                 
                 while ($resultSinonimo = mysql_fetch_array($qrr))    
                 {    
                      $tempS[] = $resultSinonimo['name'];    
                 }    

			?>    
               
			   <td CLASS="Estilo">
			
			<?php                    
                $count = count($tempS);
                
                 for ($i = 0; $i < $count; $i++)    
                 {    
                      if ($i == $count-1)
                      {    
                          echo $tempS[$i].".";
                      }
                      else
                      {
                      	  echo $tempS[$i].", ";
                      }
                 }    

			 ?>    

            </td> 

            </tr> 
        </TABLE> 
        <BR> 
        <TABLE> 
            <tr> 
                <td CLASS="Estilo" height="40" valign="middle"> 
                    <a href="#" onClick="altLexico(<?=$result['id_lexicon']?>);">Alterar S�mbolo</a> 
                </th> 
                <td CLASS="Estilo" valign="middle"> 
                    <a href="#" onClick="rmvLexico(<?=$result['id_lexicon']?>);">Remover S�mbolo</a> 
                </th> 
            </tr> 


<!-- ONTOLOGIA - CONCEITO --> 

<?php    
    } elseif ($t == "oc") {        // se for cenario 
        
		$q = "SELECT id_conceito, nome, descricao   
              FROM   conceito   
              WHERE  id_conceito = $id";    
        
		$qrr = mysql_query($q) or die("Erro ao enviar a query de selecao !!". mysql_error());    
        $result = mysql_fetch_array($qrr);    
?>    

            <tr> 
                <th>Nome:</th><td CLASS="Estilo"><?=$result['name']?></td> 
            </tr> 
            <tr> 
                <th>Descri��oo:</th><td CLASS="Estilo"><?=nl2br($result['descricao'])?></td> 
            </tr> 
        </TABLE> 
        <BR> 
        <TABLE> 
            <tr> 
                <td CLASS="Estilo" height="40" valign=MIDDLE>                     
                </th> 
                <td CLASS="Estilo"  valign=MIDDLE> 
                    <a href="#" onClick="rmvConceito(<?=$result['id_conceito']?>);">Remover Conceito</a> 
                </th> 
            </tr> 




<!-- ONTOLOGIA - RELA��ES --> 

<?php    
    } elseif ($t == "or") {        // se for cenario 
        $q = "SELECT id_relation, name   
              FROM relation   
              WHERE id_relation = $id";    
        $qrr = mysql_query($q) or die("Erro ao enviar a query de selecao !!". mysql_error());    
        $result = mysql_fetch_array($qrr);    
?>    

            <tr> 
                <th>Nome:</th><td CLASS="Estilo"><?=$result['name']?></td> 
            </tr> 

        </TABLE> 
        <BR> 
        <TABLE> 
            <tr> 
                 <td CLASS="Estilo" height="40" valign=MIDDLE>                   
                </th>
                <td CLASS="Estilo"  valign=MIDDLE> 
                    <a href="#" onClick="rmvRelacao(<?=$result['id_relation']?>);">Remover Rela��oo</a> 
                </th> 
            </tr> 




<?php    
    }    
?>   

        </table> 
        <br> 


<!--                     TERCEIRA PARTE                                     --> 


<?php    
    if ($t == "c")       { print "<h3>Cen�rios que referenciam este cen�rio</h3>";   

    } elseif ($t == "l") { print "<h3>Cen�rios e termos do l�xico que referenciam este termo</h3>";   

    } elseif ($t == "oc") { print "<h3>Rela��es do conceito</h3>";   

    } elseif ($t == "or") { print "<h3>Conceitos referentes � rela��o</h3>";   

    } elseif ($t == "oa") { print "<h3>Axioma</h3>";   

    }    
?>   





<!--                     QUARTA PARTE                                     --> 


<?php   

    frame_inferior($c, $t, $id);    

} elseif (isset($id_project)) {         // SCRIPT CHAMADO PELO HEADING.PHP 

    // Foi passada uma variavel $id_project. Esta variavel deve conter o id de um 
    // projeto que o usuario esteja cadastrado. Entretanto, como a passagem eh 
    // feita usando JavaScript (no heading.php), devemos checar se este id realmente 
    // corresponde a um projeto que o usuario tenha acesso (seguranca). 
    check_project_permanent($_SESSION['current_id_user'], $id_project) or die("Permissao negada");    

    // Seta uma variavel de sessao correspondente ao projeto atual 
    $_SESSION['current_id_project'] = $id_project;    
?>    

        <table ALIGN=CENTER> 
            <tr> 
                <th>Projeto:</th> 
                <td CLASS="Estilo"><?=simple_query("name", "project", "id_project = $id_project")?></td> 
            </tr> 
            <tr> 
                <th>Data de cria��o:</th> 
                <?php    
                    $data = simple_query("date_creation", "project", "id_project = $id_project");    
                ?>    

        <td CLASS="Estilo"><?=formataData($data)?></td> 

            </tr> 
            <tr> 
                <th>Descri��o:</th> 
                <td CLASS="Estilo"><?=nl2br(simple_query("description", "project", "id_project = $id_project"))?></td> 
            </tr> 
        </table> 

<?php    

// Cen�rio - Escolher Projeto 

// Objetivo:  Permitir ao Administrador/Usu�rio escolher um projeto. 
// Contexto:  O Administrador/Usu�rio deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser Administrador 
// Atores:    Administrador, Usu�rio 
// Recursos:  Usu�rios cadastrados 
// Epis�dios: Caso o Usuario selecione da lista de projetos um projeto da qual ele seja 
//            administrador, ver Administrador escolhe Projeto. 
//            Caso contr�rio, ver Usu�rio escolhe Projeto. 

    // Verifica se o usuario eh administrador deste projeto 
    if (is_admin($_SESSION['current_id_user'], $id_project)) {    
?>    

        <br> 
        <table ALIGN=CENTER> 
            <tr> 
                <th>Voc� � um administrador deste projeto:</th> 

<?php    

// Cen�rio - Administrador escolhe Projeto 

// Objetivo:  Permitir ao Administrador escolher um projeto. 
// Contexto:  O Administrador deseja escolher um projeto. 
//            Pr�-Condi��es: Login, Ser administrador do projeto selecionado. 
// Atores:    Administrador 
// Recursos:  Projetos doAdministrador 
// Epis�dios: O Administrador seleciona da lista de projetos um projeto da qual ele seja 
//            administrador. 
//            Aparecendo na tela as op��es de: 
//            -Verificar pedidos de altera��o de cen�rio (ver Verificar pedidos de altera��o 
//            de cen�rio); 
//            - Verificar pedidos de altera��o de termos do l�xico 
//            ( ver Verificar pedidos de altera��o de termos do l�xico); 
//            -Adicionar usu�rio (n�o existente) neste projeto (ver Adicionar Usu�rio); 
//            -Relacionar usu�rios j� existentes com este projeto 
//            (ver Relacionar usu�rios com projetos); 
//            -Gerar xml deste projeto (ver Gerar relat�rios XML); 

?>    
            </TR>
            
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="add_usuario.php();">Adicionar usu�rio (n�o cadastrado) neste projeto</a></td> 
            </TR> 
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="relation_User_Project();">Adicionar usu�rios j� existentes neste projeto</a></td> 
            </TR>   
            
            <TR> 
                <td CLASS="Estilo">&nbsp;</td> 
            </TR> 
            
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="request_Scenario();">Verificar pedidos de altera��o de Cen�rios</a></td> 
            </TR> 
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="request_Lexicon();">Verificar pedidos de altera�aode termos do L�xico</a></td> 
            </TR>
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="request_Concept();">Verificar pedidos de altera��o de Conceitos</a></td> 
            </TR> 
            
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="request_Relation();">Verificar pedidos de altera��o de Rela��es</a></td> 
            </TR>
   
       
            <TR> 
                <td CLASS="Estilo">&nbsp;</td> 
            </TR> 
			<TR> 
                <td CLASS="Estilo"><a href="#" onClick="geraGrafo();" >Gerar grafo deste projeto</a></td>
		    </TR>       
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="geraXML();">Gerar XML deste projeto</a></td> 
            </TR> 
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="recuperaXML();">Recuperar XML deste projeto</a></td> 
            </TR> 
            
            <TR> 
                <td CLASS="Estilo">&nbsp;</td> 
            </TR> 
            
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="geraOntologia();">Gerar ontologia deste projeto</a></td> 
            </TR>            
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="geraDAML();">Gerar DAML da ontologia do projeto</a></td> 
            </TR> 
            <TR> 
                <td CLASS="Estilo"><a href="#" onClick="recuperaDAML();">Hist�rico em DAML da ontologia do projeto</a></td> 
            </TR>           
            <TR> 
                <td CLASS="Estilo"><a href="http://www.daml.org/validator/" target="new">*Validador de Ontologias na Web</a></td> 
            </TR>
            <TR> 
                <td CLASS="Estilo"><a href="http://www.daml.org/2001/03/dumpont/" target="new">*Visualizador de Ontologias na Web</a></td> 
            </TR>
             <TR> 
                <td CLASS="Estilo">&nbsp;</td> 
            </TR>
            <TR> 
                <td CLASS="Estilo"><font size="1">*Para usar Ontologias Geradas pelo C&L: </font></td>               
            </TR>
            <TR> 
                <td CLASS="Estilo">   <font size="1">Hist�rico em DAML da ontologia do projeto -> Botao Direito do Mouse -> Copiar Atalho</font></td>             
            </TR>
		</table>


<?php    
    }   else
	{
?>	
	<br>
	<table ALIGN=CENTER> 
            <tr> 
                <th>Voc� n�o � um administrador deste projeto:</th> 	
			</tr>	
			<tr> 
                <td CLASS="Estilo"><a href="#" onClick="geraGrafo();" >Gerar grafo deste projeto</a></td>
		    </tr>  
	</table>			
<?php
	}
} else {        // SCRIPT CHAMADO PELO INDEX.PHP 
?>    

        <p>Selecione um projeto acima, ou crie um novo projeto.</p> 

<?php    
}    
?>    
<i><a href="showSource.php?file=main.php">Veja o c�digo fonte!</a></i> 
    </body> 

</html> 

