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

			$id_user_current = $_SESSION['id_usuario_corrente'];

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
?>