<?php

require_once '/../bd.inc';
require_once'/../security.php';
/*************************************************************
 * File: /Functions/project_Functions.php
* purpose: Group the functions how has a relation with project
*
************************************************************/

###################################################################
# Insert a project in the data bank.
# Receive the name and description. (1.1)
# Verifies if user already has one project with the same name. (1.2)
# if not, insert the value in the table PROJECT. (1.3)
# Return id_cproject. (1.4)
###################################################################

if (!(function_exists("include_project")))
{
	function include_project($name, $description)
	{
		$connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
		$query_select_sql = "SELECT * FROM project WHERE name = '$name'";
		$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query de select<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		
		$resultArray = mysql_fetch_array($query_result_sql);


		if ( $resultArray != false )
		{
		
			$id_project_repetead = $resultArray['id_project'];

			$id_user_current = $_SESSION['current_id_user'];

			$query_select_repeated_sql = "SELECT * FROM participates WHERE id_project = '$id_projeto_repetido' AND id_user = '$id_usuario_corrente' ";

			$query_result_repeated_sql = mysql_query($query_select_repeated_sql) or die("Erro ao enviar a query de SELECT no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

			$resultArray = mysql_fetch_row($query_result_repeated_sql);

			if ($resultArray[0] != null )
			{
				return -1;
			}

		}

		$query_select_max_sql = "SELECT MAX(id_project) FROM project";
		$query_resutl_max_sql = mysql_query($query_select_max_sql) or die("Erro ao enviar a query de MAX ID<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
		$result = mysql_fetch_row($query_resutl_max_sql);

		if ( $result[0] == false )
		{
			$result[0] = 1;
		}
		else
		{
			$result[0]++;
		}
		$date = date("Y-m-d");

		$query_insert_sql = "INSERT INTO project (id_project, name, date_creation, description)
		VALUES ($result[0],'".data_prepare($name)."','$date' , '".data_prepare($description)."')";

		mysql_query($query_insert_sql) or die("Erro ao enviar a query INSERT<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		return $result[0];
	}
}

// Retorna TRUE ssse $id_usuario tem permissao sobre $id_projeto
if (!(function_exists("check_project_permanent"))) {
	function check_project_permanent($id_user, $id_project)
	{
		$connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
		$query_select_sql = "SELECT *
		FROM participates
		WHERE id_user = $id_user
		AND id_project = $id_project";
		$query_result_sql = mysql_query($query_select_sql) or die("Erro ao enviar a query<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
		return (1 == mysql_num_rows($query_result_sql));
	}
}

###################################################################
# Remove um determinado projeto da base de dados
# Recebe o id do projeto. (1.1)
# Apaga os valores da tabela pedidocen que possuam o id do projeto enviado (1.2)
# Apaga os valores da tabela pedidolex que possuam o id do projeto enviado (1.3)
# Faz um SELECT para saber quais l�xico pertencem ao projeto de id_projeto (1.4)
# Apaga os valores da tabela lextolex que possuam possuam lexico do projeto (1.5)
# Apaga os valores da tabela centolex que possuam possuam lexico do projeto (1.6)
# Apaga os valores da tabela sinonimo que possuam possuam o id do projeto (1.7)
# Apaga os valores da tabela lexico que possuam o id do projeto enviado (1.8)
# Faz um SELECT para saber quais cenario pertencem ao projeto de id_projeto (1.9)
# Apaga os valores da tabela centocen que possuam possuam cenarios do projeto (2.0)
# Apaga os valores da tabela centolex que possuam possuam cenarios do projeto (2.1)
# Apaga os valores da tabela cenario que possuam o id do projeto enviado (2.2)
# Apaga os valores da tabela participa que possuam o id do projeto enviado (2.3)
# Apaga os valores da tabela publicacao que possuam o id do projeto enviado (2.4)
# Apaga os valores da tabela projeto que possuam o id do projeto enviado (2.5)
#
###################################################################

function removeProject($id_project)
{

	$connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	//Remove os pedidos de cenario
	$qv = "Delete FROM request_scenario WHERE id_project = '$id_project' ";
	$deletaPedidoCenario = mysql_query($qv) or die("Erro ao apagar pedidos de cenario<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	//Remove os pedidos de lexico
	$qv = "Delete FROM request_lexicon WHERE id_project = '$id_projectt' ";
	$deletaPedidoLexico = mysql_query($qv) or die("Erro ao apagar pedidos do lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	//Remove os lexicos //verificar lextolex!!!
	$qv = "SELECT * FROM lexicon WHERE id_project = '$id_project' ";
	$qvr = mysql_query($qv) or die("Erro ao enviar a query de select no lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	while ($result = mysql_fetch_array($qvr))
	{
		$id_lexico = $result['id_lexicon']; //seleciona um lexico

		$qv = "Delete FROM lextolex WHERE id_lexico_from = '$id_lexico' OR id_lexico_to = '$id_lexico' ";
		$deletaLextoLe = mysql_query($qv) or die("Erro ao apagar pedidos do lextolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		$qv = "Delete FROM centolex WHERE id_lexico = '$id_lexico'";
		$deletacentolex = mysql_query($qv) or die("Erro ao apagar pedidos do centolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		//$qv = "Delete FROM sinonimo WHERE id_lexico = '$id_lexico'";
		//$deletacentolex = mysql_query($qv) or die("Erro ao apagar sinonimo<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		$qv = "Delete FROM sinonimo WHERE id_projeto = '$id_projeto'";
		$deletacentolex = mysql_query($qv) or die("Erro ao apagar sinonimo<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	}

	$qv = "Delete FROM lexico WHERE id_projeto = '$id_projeto' ";
	$deletaLexico = mysql_query($qv) or die("Erro ao apagar pedidos do lexico<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	//remove os cenarios
	$qv = "SELECT * FROM cenario WHERE id_projeto = '$id_projeto' ";
	$qvr = mysql_query($qv) or die("Erro ao enviar a query de select no cenario<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
	$resultArrayCenario = mysql_fetch_array($qvr);

	while ($result = mysql_fetch_array($qvr))
	{
		$id_lexico = $result['id_cenario']; //seleciona um lexico

		$qv = "Delete FROM centocen WHERE id_cenario_from = '$id_cenario' OR id_cenario_to = '$id_cenario' ";
		$deletaCentoCen = mysql_query($qv) or die("Erro ao apagar pedidos do centocen<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		$qv = "Delete FROM centolex WHERE id_cenario = '$id_cenario'";
		$deletaLextoLe = mysql_query($qv) or die("Erro ao apagar pedidos do centolex<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	}

	$qv = "Delete FROM cenario WHERE id_projeto = '$id_projeto' ";
	$deletaLexico = mysql_query($qv) or die("Erro ao apagar pedidos do cenario<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	//remover participantes
	$qv = "Delete FROM participa WHERE id_projeto = '$id_projeto' ";
	$deletaParticipantes = mysql_query($qv) or die("Erro ao apagar no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	//remover publicacao
	$qv = "Delete FROM publicacao WHERE id_projeto = '$id_projeto' ";
	$deletaPublicacao = mysql_query($qv) or die("Erro ao apagar no publicacao<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

	//remover projeto
	$qv = "Delete FROM projeto WHERE id_projeto = '$id_projeto' ";
	$deletaProjeto= mysql_query($qv) or die("Erro ao apagar no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

}
?>