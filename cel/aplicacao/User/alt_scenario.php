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

if (isset($_POST['submit'])) {
        $id_scenario = $_POST['id_scenario'];
	insertRequestAddScenario($_SESSION['current_id_project'],
			$_POST['id_scenario'],
			$_POST['title'],
			$_POST['objective'],
			$_POST['contex'],
			$_POST['actors'],
			$_POST['resource'],
			$_POST['exception'],
			$_POST['episodes'],
			$_POST['justification'],
			$_SESSION['current_id_user']);
	?>

<script language="javascript1.3">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_project=<?=$_SESSION['id_projeto_corrente']?>');

</script>

<h4>Opera��o efetuada com sucesso!</h4>

<script language="javascript1.3">

self.close();

</script>

<?php
} else { // Script chamado atraves do link no scenario corrente

	$project_name = simple_query("name", "project", "id_project = " . $_SESSION['id_projeto_corrente']);

	$query_sql = "SELECT * FROM scenario WHERE id_scenario = $id_scenario";
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
	<form action="?id_project=<?=$id_project?>" method="post">
		<table>
			<tr>
				<td>Projeto:</td>
				<td><input disabled size="48" type="text" value="<?=$project_name?>">
				</td>
			</tr>
			<input type="hidden" name="id_scenario"
				value="<?=$result['id_scenario']?>">
			<td>T�tulo:</td>
			<? $result['title'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['title']); ?>
			<input type="hidden" name="title" value="<?=$result['title']?>">
			<td><input disabled maxlength="128" name="titulo2" size="48"
				type="text" value="<?=$result['title']?>"></td>
			<tr>
				<td>Objetivo:</td>
				<? $result['objective'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['objective']); ?>

				<td><textarea name="objective" cols="48" rows="3">
						<?=$result['objective']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Contexto:</td>
				<? $result['context'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['context']); ?>
				<td><textarea name="context" cols="48" rows="3">
						<?=$result['context']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Atores:</td>
				<? $result['actors'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['actors']); ?>

				<td><textarea name="actors" cols="48" rows="3">
						<?=$result['actors']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Recursos:</td>
				<? $result['resources'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['resources']); ?>

				<td><textarea name="resources" cols="48" rows="3">
						<?=$result['resources']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Exce��o:</td>
				<? $result['exception'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['exception']); ?>

				<td><textarea name="exception" cols="48" rows="3">
						<?=$result['exception']?>
					</textarea></td>
			</tr>
			<tr>
				<td>Epis�dios:</td>
				<? $result['episodes'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $result['episodes']); ?>
				<td><textarea cols="48" name="episodes" rows="5">
						<?=$result['episodes']?>
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
					type="submit" value="Alterar Cen�rio" onClick="updateOpener();"></td>
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
