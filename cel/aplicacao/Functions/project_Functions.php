<?php
/*************************************************************
 * File: /Functions/project_Functions.php
* purpose: Group the functions how has a relation with project
*
************************************************************/

###################################################################
# Insere um projeto no banco de dados.
# Recebe o nome e descricao. (1.1)
# Verifica se este usuario ja possui um projeto com esse nome. (1.2)
# Caso nao possua, insere os valores na tabela PROJETO. (1.3)
# Devolve o id_cprojeto. (1.4)
###################################################################

if (!(function_exists("include_project")))
{
	function include_project($name, $description)
	{
		$connect = bd_connect() or die("Erro ao conectar ao SGBD<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
		//verifica se usuario ja existe
		$qv = "SELECT * FROM project WHERE name = '$name'";
		$qvr = mysql_query($qv) or die("Erro ao enviar a query de select<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		//$result = mysql_fetch_row($qvr);
		$resultArray = mysql_fetch_array($qvr);


		if ( $resultArray != false )
		{
			//verifica se o nome existente corresponde a um projeto que este usuario participa
			$id_projeto_repetido = $resultArray['id_project'];

			$id_usuario_corrente = $_SESSION['id_usuario_corrente'];

			$qvu = "SELECT * FROM participates WHERE id_project = '$id_projeto_repetido' AND id_user = '$id_usuario_corrente' ";

			$qvuv = mysql_query($qvu) or die("Erro ao enviar a query de SELECT no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

			$resultArray = mysql_fetch_row($qvuv);

			if ($resultArray[0] != null )
			{
				return -1;
			}

		}

		$q = "SELECT MAX(id_project) FROM project";
		$qrr = mysql_query($q) or die("Erro ao enviar a query de MAX ID<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
		$result = mysql_fetch_row($qrr);

		if ( $result[0] == false )
		{
			$result[0] = 1;
		}
		else
		{
			$result[0]++;
		}
		$date = date("Y-m-d");

		$qr = "INSERT INTO project (id_project, name, date_creation, description)
		VALUES ($result[0],'".data_prepare($name)."','$date' , '".data_prepare($description)."')";

		mysql_query($qr) or die("Erro ao enviar a query INSERT<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);

		return $result[0];
	}
}
?>