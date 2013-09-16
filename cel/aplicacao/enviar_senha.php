<?php
include("bd.inc");
include("httprequest.inc");

// Scenario - remenber password
//Objetivo:	Allows an registered user, how forgot the password, receive that for email
//Contexto:	 The system is open, the user forgot his password, user clicks on the button of forgot password  
//Actors:	 User, system
     
 
$connect = bd_connect() or die("Erro ao conectar ao SGBD");

$query_select_sql = "SELECT * FROM user WHERE login='$login'";

$query_result_sql = mysql_query($query_select_sql) or die("Erro ao executar a query");


?>

<html>
<head>
<title>Enviar senha</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<?php
if (!mysql_num_rows($query_result_sql) )
{

?>
<p style="color: red; font-weight: bold; text-align: center">Login inexistente!</p>
<center><a href="JavaScript:window.history.go(-1)">Voltar</a></center>
<?php
}
else
{
   $row = mysql_fetch_row($query_result_sql);
   $name  = $row[1];
   $mail  = $row[2];
   $login = $row[3];
   $password = $row[4];


	function createrandonstring($n)
	{	
		$string = "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz0123456789";
		$cod = "";
		for($a = 0;$a < $n;$a++)
		{		
			$rand = rand(0,61);
			$cod .= substr($str,$rand,1);
		}	
		return $cod;
	}
	
   $new_password = createrandonstring(6);
   
   $new_password_cript = md5($new_password);
   
   
   $query_update_sql = "update user set password = '$new_password_cript' where login = '$login'";
   $query_resutl_upadate_sql = mysql_query($qUp) or die("Erro ao executar a query de update na tabela usuario");
   
   $body_email = "Caro $name,\n Como solicitado, estamos enviando sua nova senha para acesso ao sistema C&L.\n\n login: $login \n senha: $new_password \n\n Para evitar futuros transtornos altere sua senha o mais breve possível. \n Obrigado! \n Equipe de Suporte do C&L.";
   $headers = "";
   if(mail("$mail", "Nova senha do C&L" , "$body_email" , $headers))
   { 	
   ?>
		<p style="color: red; font-weight: bold; text-align: center">Uma nova senha foi criada e enviada para seu e-mail cadastrado.</p>
	    <center><a href="JavaScript:window.history.go(-2)">Voltar</a></center>
   <?php
   }else{
	?>
		<p style="color: red; font-weight: bold; text-align: center">Ocorreu um erro durante o envio do e-mail!</p>
		<center><a href="JavaScript:window.history.go(-2)">Voltar</a></center>
	<?php
   
   }

}
?>


</body>
</html>
