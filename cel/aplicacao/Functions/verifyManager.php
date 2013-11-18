<?php
###################################################################
# Verifica se um determinado usuario e gerente de um determinado
# projeto
# Recebe o id do projeto. (1.1)
# Faz um select para pegar o resultArray da tabela Participa.(1.2)
# Se o resultArray for nao nulo: devolvemos TRUE(1);(1.3)
# Se o resultArray for nulo: devolvemos False(0);(1.4)
###################################################################

function verificaGerente($id_user, $id_project)
{
	assert($id_user !=NULL);
	assert($id_project !=NULL);


	$return_value = 0;

	$q = "SELECT * FROM participates WHERE manager = 1 AND id_user = $id_user AND id_project = $id_project";
	$qr = mysql_query($q) or die("Erro ao enviar a query de select no participa<br>" . mysql_error() . "<br>" . __FILE__ . __LINE__);
	$resultArray = mysql_fetch_array($qr);

	if ( $resultArray != false ){

		$retern_value = 1;
	}
	return $return_value;
}
?>