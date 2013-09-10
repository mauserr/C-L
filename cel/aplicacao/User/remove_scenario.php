<?php

// remove_scenario.php: This script makes a request for deleting a project scenario.
//	Called by file: main.php

session_start();

include("funcoes_genericas.php");
include("httprequest.inc");
check_User("index.php");      

// Scenario -  Exclude Scenario

//Objective:	Allow to user to exclude an active scenario
//Contex:	User want to exclude a scenario
//Pre condition: Login, scenario registered in the system
//Actors:	User, System
//Resource:	Data reported
//Episode:	The system will provide a screen for the user justify the necessity of
//			the exclusion for the administrator to read and aproves or not the exclusion
//			This screen will contain the button of confirmation of the exclusion
//Restricion: After clicking on the button, the system verifys if all the fields were filled
	
//Exceção:	if all the fields weren't filled, return for the user the message
//			warning that all fields must been filled and one button to return to the previous page

insertRequestRemoveScenario($_SESSION['current_id_project'], $id_cenario, $_SESSION['current_id_user']);

?>

<script language="javascript1.3">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_projeto=<?=$_SESSION['id_projeto_corrente']?>');



</script>

<h4>Operação efetuada com sucesso!</h4>

<script language="javascript1.3">

self.close();

</script>
