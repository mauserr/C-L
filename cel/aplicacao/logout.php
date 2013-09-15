<?php
include("bd.inc");
include_once("CELConfig/CELConfig.inc");

session_start();


// Scenario - Do logout

// Objective: Allows the user to do a logout, and return to the login screen
// Context:   System opened. User has acess to the system.
//            User wants to leave the aplication and keep the integrity
// Atores:	  User, System.


session_destroy();
session_unset();
$ipValor = CELConfig_ReadVar("HTTPD_ip") ;
?>

<html>
<script language="javascript1.3">


document.writeln('<p style="color: blue; font-weight: bold; text-align: center">A aplicação teminou escolha uma das opções abaixo:</p>');
document.writeln('<p align="center"><a href="javascript:logoff();">Entrar novamente</a></p>');
document.writeln('<p align="center"><a href="http://<?php print( CELConfig_ReadVar("HTTPD_ip") . "/" . CELConfig_ReadVar("CEL_dir_relativo") . "../" ); ?>">Página inicial</a></p>');
document.writeln('<p align="center"><a href="javascript:self.close();">Fechar</a></p>');

function logoff()
{
   location.href = "http://<?php print( CELConfig_ReadVar("HTTPD_ip") . "/" . CELConfig_ReadVar("CEL_dir_relativo") ); ?>index.php";
}


//window.close();
//location.href = "http://<?php print( CELConfig_ReadVar("HTTPD_ip") . "/" . CELConfig_ReadVar("CEL_dir_relativo") ); ?>index.php";
</script>
</html>

