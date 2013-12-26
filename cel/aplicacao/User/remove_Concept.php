<?php
//**********************************************************************
// remove_conceito.php: This script make a request to remove a concept 
//***********************************************************************

session_start();

include("funcoes_genericas.php");
require_once '/Functions/check_User.php';
include("httprequest.inc");
check_User("index.php");        

insertRequestRemoveConcept($_SESSION['current_id_project'], 
        $id_conceito = '', 
        $_SESSION['id_usuario_corrente']);

?>  

<script language="javascript1.3">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_projeto=<?=$_SESSION['current_id_project']?>');

<?php

// Scenario - Remove concept

//Objetivo:	Allows a user to remove one active concept
//Contexto:	User wants to remove a concept
//Atores:	User, System
//Exce��o:  if all the fields weren't filled, return for the user a message
//			alerting that all the fields must been filled and a button to return to the previous page


?>

</script>

<h4>Opera��o efetuada com sucesso!</h4>

<script language="javascript1.3">

self.close();

</script>
