<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

// alt_cenario.php: This script makes a request of alterarion of one scenario of project.
//  The user receives a form of current scenario (with empty text box).
//	and will do alteration in all text boxes(without the title). At the end of the main screen
//	return for the top screen and close the tree. The form is altered and closed.
//	Called by file: main.php

session_start();
include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
include("httprequest.inc");
include_once("bd.inc");

check_User("index.php");

//Scenario -    Change scenario

//Objective:	Allows a user to change a scenario
//Context:      The user want to alter a scenario registered previously
//              Pre condition: Login, Scnenario registered in the system
//Atores:	    User
//Recursos:	    System, registered data
//Exception:    The scenario name is altered to a existing scenario name
//Episodes:		The system will provide for an user the same screen of INCLUDE SCENARIO
//				however with the following data scenario to be altered and
//              filled your fields: Objective, Contex, Actors, Resource and Episodes.
//				The fields of Project and Title can't be edited. 
//				A Fild of justification will be shown for the user to write a justification of the alteration.

$connect_bd = bd_connect() or die("Erro ao conectar ao SGBD");

if (isset($submit)) {
	insertRequestAddScenario($_SESSION['current_id_project'],
			$id_scenario = '',
			$title = '',
			$objective = '',
			$contex ='',
			$actors = '',
			$resource = '',
			$exception = '',
			$episodes = '',
			$justification = '',
			$_SESSION['current_id_user']);
	?>

<script language="javascript1.3">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_projeto=<?=$_SESSION['id_projeto_corrente']?>');

</script>

<h4>Opera��o efetuada com sucesso!</h4>

<script language="javascript1.3">

self.close();

</script>

<?php
} else { // Script chamado atraves do link no cenario corrente

	$project_name = simple_query("nome", "projeto", "id_projeto = " . $_SESSION['id_projeto_corrente']);

	$query_sql = "SELECT * FROM cenario WHERE id_cenario = $id_scenario";
	$query_confirm_sql = mysql_query($query_sql) or die(" Erro ao executar a consulta");
	$result = mysql_fetch_array($query_confirm_sql);

	?>

<html>
<head>
<title>Alterar Cen�rio</title>
</head>
<body>
	<h4>Alterar Cen�rio</h4>
	<br>
	<form action="?id_projeto=<?=$id_project?>" method="post">
		<table>
			<tr>
				<td>Projeto:</td>
				<td><input disabled size="48" type="text" value="<?=$project_name?>">
				</td>
			</tr>
			<input type="hidden" name="id_cenario"
				value="<?=$result['id_cenario']?>">
			<td>T�tulo:</td>
			<? $result['titulo'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['titulo']); ?>
			<input type="hidden" name="titulo" value="<?=$result['titulo']?>">
			<td><input disabled maxlength="128" name="titulo2" size="48"
				type="text" value="<?=$result['titulo']?>"></td>
			<tr>
				<td>Objetivo:</td>
				<? $result['objetivo'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['objetivo']); ?>

				<td><textarea name="objetivo" cols="48" rows="3">
						<?=$result['objetivo']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Contexto:</td>
				<? $result['contexto'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['contexto']); ?>
				<td><textarea name="contexto" cols="48" rows="3">
						<?=$result['contexto']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Atores:</td>
				<? $result['atores'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['atores']); ?>

				<td><textarea name="atores" cols="48" rows="3">
						<?=$result['atores']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Recursos:</td>
				<? $result['recursos'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['recursos']); ?>

				<td><textarea name="recursos" cols="48" rows="3">
						<?=$result['recursos']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Exce��o:</td>
				<? $result['excecao'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['excecao']); ?>

				<td><textarea name="excecao" cols="48" rows="3">
						<?=$result['excecao']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Epis�dios:</td>
				<? $result['episodios'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['episodios']); ?>
				<td><textarea cols="48" name="episodios" rows="5">
						<?=$result['episodios']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Justificativa para a altera&ccedil;&atilde;o:</td>
				<td><textarea name="justificativa" cols="48" rows="2"></textarea></td>
			</tr>

			<tr>
				<td colspan="2"><b><small>Essa justificativa � necess�ria apenas
							para aqueles usu�rios que n�o s�o administradores.</small> </b></td>
			</tr>

			<tr>
				<td align="center" colspan="2" height="60"><input name="submit"
					type="submit" value="Alterar Cen�rio" onClick="updateOpener()"></td>
			</tr>
		</table>
	</form>
	<center>
		<a href="javascript:self.close();">Fechar</a>
	</center>
	<br>
	<i><a href="showSource.php?file=alt_scenario.php">Veja o c�digo fonte!</a>
	</i>
</body>
</html>

<?php
}
?>
