<?php

session_start();

/* vim: set expandtab tabstop=4 shiftwidth=4: */

// add_cenario.php: This script registers a new scenario of a project.
//                  is passed,through of URL, a variable $id_project,
//					That indicate the project a new scenario must be inserted.

include("funcoes_genericas.php");
include("httprequest.inc");
include_once("bd.inc");

// Scenario -  Insert Scenario

//Objective:    Allows the user	an insertion of a new scenario
//Context:      User want to include a new scenario.
//              Pre-Condition: Login, scenario not registered
//Actors:       User, System
//Resource:     Data to be registered
//Episodes:    The system will provide to an user one screen with the following text boxes:
//
//                - New Scenario
//                - Objective.  Restriction: Text Box with a minimun of 5 of visible written lines
//                - Context.    Restriction: Text Box with a minimun of 5 of visible written lines
//                - Actors.    Restriction: Text Box with a minimun of 5 of visible written lines
//                - Resource.  Restriction: Text Box with a minimun of 5 of visible written lines
//                - Exception.   Restriction: Text Box with a minimun of 5 of visible written lines
//                - Episodes. Restriction: Text Box with a minimun of 16 of visible written lines
//                - Button to confirm the insertion of a new scenario
//          	    Restriction: After clicking on the button of confirmation,
//							 the system verify if all the fields were filled
// 					Exception:	if all the fields weren't filled, return for an user a message warning 
//             				    that all fields must be completed and a button to return to the previous page.
check_User("index.php");

if ( !isset( $sucess ) )
{
	$sucess = "n" ;
}

$connect_db = bd_connect() or die("Erro ao conectar ao SGBD");

if (isset($submit)) {
	$ret = checkExistingScenario($_SESSION['current_id_project'],$title);
	?>
<!-- ADICIONEI ISTO PARA TESTES -->
<!--
           RET = <?=$ret?> => RET = <?PHP $ret? print("TRUE") : print("FALSE") ; ?><BR>
        $sucesso        = <?=$sucess?><BR>
       _GET["sucesso"] = <?=$_GET["sucesso"]?><BR>   
        -->
<?PHP
if ($ret == true)
{
	print("<!-- Tentando Inserir Cenario --><BR>");

	$title     = str_replace( ">" , " " , str_replace ( "<" , " " , $title     ) ) ;
	$objective   = str_replace( ">" , " " , str_replace ( "<" , " " , $objetive   ) ) ;
	$context   = str_replace( ">" , " " , str_replace ( "<" , " " , $context   ) ) ;
	$authors     = str_replace( ">" , " " , str_replace ( "<" , " " , $authors     ) ) ;
	$resource   = str_replace( ">" , " " , str_replace ( "<" , " " , $recourse   ) ) ;
	$exception    = str_replace( ">" , " " , str_replace ( "<" , " " , $exception    ) ) ;
	$episodes  = str_replace( ">" , " " , str_replace ( "<" , " " , $episodes  ) ) ;
	
	insertRequestAddScenario ($_SESSION['current_id_project'],
			$title,
			$objective,
			$context,
			$authors,
			$recourse,
			$exception,
			$episodes,
			$_SESSION['current_id_user']);
	print("<!-- Cenario Inserido Com Sucesso! --><BR>");
}
else
{
	?>
<html>
<head>
<title>Projeto</title>
</head>
<body bgcolor="#FFFFFF">
	<p style="color: red; font-weight: bold; text-align: center">Este
		cenário já existe!</p>
	<br>
	<br>
	<center>
		<a href="JavaScript:window.history.go(-1)">Voltar</a>
	</center>
</body>
</html>
<?php
return;
}
?>

<script language="javascript1.2">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_projeto=<?=$_SESSION['id_projeto_corrente']?>');
//self.close();
//location.href = "http://<?php print( CELConfig_ReadVar("HTTPD_ip") . "/" . CELConfig_ReadVar("CEL_dir_relativo") ); ?>add_cenario.php?id_projeto=<?=$id_projeto?>&sucesso=s" ;


location.href = "add_cenario.php?id_projeto=<?=$id_projeto?>&sucesso=s";

</script>

<?php

} else {    // Script chamado atraves do menu superior
	$project_name = simple_query("name", "project", "id_project = " . $_SESSION['current_id_projeto']);
	?>

<html>
<head>
<title>Adicionar Cenário</title>
</head>
<body>
	<script language="JavaScript">
<!--
function TestEmpty(form)
{
title     = form.title.value;
objective   = form.objective.value;
context   = form.context.value;

  if ((title == ""))
    { 
		alert ("Por favor, digite o titulo do cenário.")
		form.title.focus()
		return false;
	}else{
		pattern = /[\\\/\?<>:|]/";
		OK = padrao.exec(title);
		if (OK)
		{
			window.alert ("O título do cenário não pode conter nenhum dos seguintes caracteres:   / \\ : ? \" < > |");
			form.title.focus();
			return false;
		} 
	}
      
  if ((objective == ""))
    { alert ("Por favor, digite o objetivo do cenário.")
      form.objective.focus()
      return false;}    
      
   if ((context == ""))
    { alert ("Por favor, digite o contexto do cenário.")
      form.context.focus()
      return false;}        
}
//-->

<?php

?>

</SCRIPT>

	<h4>Adicionar Cenário</h4>
	<br>
	<?php
	if ( $sucess == "s" )
	{
		?>
	<p style="color: blue; font-weight: bold; text-align: center">Cenário
		inserido com sucesso!</p>
	<?php    
	}
	?>
	<form action="" method="post">
		<table>
			<tr>
				<td>Projeto:</td>
				<td><input disabled size="51" type="text" value="<?=$project_name?>">
				</td>
			</tr>
			<td>Título:</td>
			<td><input size="51" name="titulo" type="text" value=""></td>
			<tr>
				<td>Objetivo:</td>
				<td><textarea cols="51" name="objetivo" rows="3" WRAP="SOFT"></textarea>
				</td>
			</tr>
			<tr>
				<td>Contexto:</td>
				<td><textarea cols="51" name="contexto" rows="3" WRAP="SOFT"></textarea>
				</td>
			</tr>
			<tr>
				<td>Atores:</td>
				<td><textarea cols="51" name="atores" rows="3" WRAP="SOFT"></textarea>
				</td>
			</tr>
			<tr>
				<td>Recursos:</td>
				<td><textarea cols="51" name="recursos" rows="3" WRAP="SOFT"></textarea>
				</td>
			</tr>
			<tr>
				<td>Exceção:</td>
				<td><textarea cols="51" name="excecao" rows="3" WRAP="SOFT"></textarea>
				</td>
			</tr>
			<tr>
				<td>Episódios:</td>
				<td><textarea cols="51" name="episodios" rows="5" WRAP="SOFT"></textarea>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2" height="60"><input name="submit"
					type="submit" onClick="return TestarBranco(this.form);"
					value="Adicionar Cenário"></td>
			</tr>
		</table>
	</form>
	<center>
		<a href="javascript:self.close();">Fechar</a>
	</center>
	<br>
	<i><a href="showSource.php?file=add_cenario.php">Veja o código fonte!</a>
	</i>
</body>
</html>

<?php
}
?>
