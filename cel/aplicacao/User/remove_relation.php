<?php

// remove_relacao.php: This script make a request to remove a relation


session_start();

include("funcoes_genericas.php");
include("httprequest.inc");
check_User("index.php");        

insertRequestRemoveRelation($_SESSION['id_projeto_corrente'], $id_relacao, $_SESSION['id_usuario_corrente']);

?>  

<script language="javascript1.3">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_projeto=<?=$_SESSION['id_projeto_corrente']?>');

<?php

// Scenario - Remove Relation

//Objective: Allows a user to remove a relation 
//Contexto:	Usuário deseja excluir um conceito
//              Pré-Condição: Login, cenário cadastrado no sistema
//Atores:	Usuário, Sistema
//Exceção:	Se todos os campos não foram preenchidos, retorna para o usuário uma mensagem
//              avisando que todos os campos devem ser preenchidos e um botão de voltar para a pagina anterior.

?>

</script>

<h4>Operação efetuada com sucesso!</h4>

<script language="javascript1.3">

self.close();

</script>
