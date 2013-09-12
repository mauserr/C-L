<?php
session_start();

include("funcoes_genericas.php");
include("httprequest.inc");
include_once("bd.inc");

$id_user = $_SESSION['id_usuario_corrente'];

$connectDB = bd_connect() or die("Erro ao conectar ao SGBD");


?>

<html>
    <head>
        <title>Alterar dados de Usu�rio</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>


   <body>

<?php

// Scenario - Alter registration
//
//Objective: Allows the user to modify his registration data	
//Context:   System opened, User must have accessed the system and be logged 
//           User wants to modify his registered data 
//Preconditions: User must have accessed the system	
//Actors:    User, System.	
//Resources:  Interface	
//Episodes:  The user modify the wanted data
// 	     User clicks on the button 'Atualizar'

$encrypt_password = md5($senha);

$update_query = "UPDATE usuario SET  nome ='$name' , login = '$login' , email = '$email' , senha = '$encrypt_password' WHERE  id_usuario='$id_user'";

mysql_query($update_query) or die("<p style='color: red; font-weight: bold; text-align: center'>Erro!Login ja existente!</p><br><br><center><a href='JavaScript:window.history.go(-1)'>Voltar</a></center>");

?>

<center><b>Cadastro atualizado com sucesso!</b></center>
<center><button onClick="javascript:window.close();">Fechar</button></center>

         
  </body>
</html>