<?php
//***************************************************************************
// remove_lexico.php: This script make a request to remove a current lexicon
//					  remove the current lexicon
//****************************************************************************

session_start();

include("funcoes_genericas.php");
require_once '/Functions/lexicon_Functions';
require_once '/Functions/check_User.php';
include("httprequest.inc");
check_User("index.php");      

//Scenarios -  Remove Lexicon

//Objetivo:	Allow the user to remove a ative word or lexicon
//Contexto:	User wants to remove a word from the lexicon
//              Pre-condiction: Login, registered word on the lexicon 
//Actors:	User, System
//Exce��o:  If all the fields were not filled, return to user a message alerting

//			that all the fields must be filled and a button to return to the previous page

insertRequestRemoveLexicon(
        $id_project = '',
        $id_lexicon = '', 
        $_SESSION['current_id_user']);
?>

<script language="javascript1.3">

opener.parent.frames['code'].location.reload();
opener.parent.frames['text'].location.replace('main.php?id_projeto=<?=$_SESSION['current_id_project']?>');

</script>

<h4>Operacao efetuada com sucesso!</h4>

<script language="javascript1.3">

self.close();

</script>
